<?php
/**
 * @category   MageUC
 * @package    MageUC_Console
 * @author     Nicolas Bastien
 */

/**
 * MageUC PHPUnit Task to run test
 *
 * @category   MageUC
 * @package    MageUC_Console
 * @author     Nicolas Bastien
 */
class MageUC_Console_Task_PHPUnit extends MageUC_Console_Task
{
    /**
     * @var string
     */
    public $description = 'Run PHPUnit test';

    /**
     * @var array
     */
    public $requiredArguments = array('suite' => 'Specific suite name or "all" if you want to run all test');

    /**
     * @var array
     */
    public $optionalArguments = array('class' => 'Class name to run');

    /**
     * (non-PHPdoc)
     * @see MageUC_Console_Task::validate()
     */
    public function validate ()
    {
        if (!@include_once('PHPUnit/Framework.php')) {
            throw new MageUC_Console_Exception('PHPUnit is required.');
        }
        return parent::validate();
    }

    /**
     * Returns the path to the phpunit report file
     *
     * @return string
     */
    public static function getReportFileName()
    {
        return Mage::getBaseDir('var') . DS . 'mageuc' . DS . 'phpunit' . DS . 'report' . DS . 'logfile.xml';
    }

    /**
     * (non-PHPdoc)
     * @see MageUC_Console_Task::execute()
     */
    public function execute()
    {
        print('[test] start running PHPUnit test  : ' . $this->getArgument('suite')
            . ' ' . $this->getArgument('class'). PHP_EOL) ;

        require_once(Mage::getBaseDir('app') . DS . 'test' . DS .'bootstrap.php');

        //Create test suite to run
        $testSuite = $this->_getTestSuite();

        //Format arguments
        $arOptions = $this->_getOptions();

        require_once('PHPUnit/TextUI/TestRunner.php');
        $result = PHPUnit_TextUI_TestRunner::run($testSuite, $arOptions);

        print('[test] done' . PHP_EOL);
    }

    /**
     * Options for PHPUnit_TextUI_TestRunner same phpunit command line
     *
     * @see PHPUnit_TextUI_Command::handleArguments() for more informations
     *
     * @return array
     */
    private function _getOptions()
    {
        $arOptions = array();

        //--log-xml
        $arOptions['xmlLogfile']   = self::getReportFileName(); //old phpunit version
        $arOptions['junitLogfile'] = self::getReportFileName();

        $arOptions['logIncompleteSkipped'] = true;
        $arOptions['stopOnFailure'] = false;
        $arOptions['syntaxCheck'] = true;

        //Add filter to run one test only
        if (!is_null($this->getArgument('test_name'))) {
            $arOptions['filter'] = $this->getArgument('test_name');
        }

        if (extension_loaded('tokenizer') && extension_loaded('xdebug')) {
            //--log-pmd
            $arOptions['pmd-xml'] = Mage::getBaseDir() . DS . 'var' . DS . 'mageuc' . DS . 'phpunit' . DS . 'report' . DS . 'phpunit.pmd.xml';

            //--log-metrics
            $arOptions['log-metrics'] = Mage::getBaseDir() . DS . 'var' . DS . 'mageuc' . DS . 'phpunit' . DS . 'report' . DS . 'phpunit.metrics.xml';

            //--coverage-html
            $arOptions['reportDirectory'] = Mage::getBaseDir() . DS . 'var' . DS . 'mageuc' . DS . 'phpunit' . DS . 'coverage';
        }
        return $arOptions;
    }

    /**
     * Return the test suite to run depending of the arguments
     *
     * @return PHPUnit_Framework_TestSuite
     */
    private function _getTestSuite()
    {
        if ($this->getArgument('suite') == 'all') {
            //By default run all test directory
            return $this->_makeDirectorySuite();
        }

        if (!is_null($this->getArgument('class'))) {
            require_once(
                Mage::getBaseDir() . DS . 'app' . DS . 'test' . DS . $this->getArgument('suite') . DS
                . str_replace('_', DS, $this->getArgument('class')) . '.php'
            );
            //run all the test present in this class
            $classSuite = new PHPUnit_Framework_TestSuite ('Test : ' . $this->getArgument('suite') . ' / ' . $this->getArgument('class'));
            $classSuite->addTestSuite($this->getArgument('class'));
            return $classSuite;
        }
    }

    /**
     * Make test suite from a directory
     *
     * @param  mixed $directoryInfo
     * @return PHPUnit_Framework_TestSuite
     */
    protected function _makeDirectorySuite($directoryInfo = null)
    {
        if (!$directoryInfo instanceof SplFileInfo) {
            $directoryInfo = new SplFileInfo(Mage::getBaseDir('app') . DS . 'test' . DS . str_replace('_', DS, $directoryInfo));
        }
        $directorySuite = new PHPUnit_Framework_TestSuite ('Suite_' . $directoryInfo->getFilename());
        foreach (new DirectoryIterator($directoryInfo->getPathname()) as $fileInfo) {
            if ($fileInfo->isDot() || substr($fileInfo->getFilename(), 0, 1) == '.'
                ||  $fileInfo->getFilename() == 'CVS' || (!$fileInfo->isDir() && substr($fileInfo->getFilename(), -4) != '.php')
            ) {
                continue;
            }
            if ($fileInfo->isDir()) {
                $directorySuite->addTestSuite($this->_makeDirectorySuite($fileInfo));
            } else {
                $directorySuite->addTestFile($fileInfo->getPathname());
            }
        }
        return $directorySuite;
    }
}
