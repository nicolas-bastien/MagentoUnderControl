<?php
/**
 * @category   MageUC
 * @package    MageUC_Console
 * @author     Nicolas Bastien
 */

/**
 * MageUC PHPMD Task
 * 
 * @link http://phpmd.org/documentation/index.html
 *
 * @category   MageUC
 * @package    MageUC_Console
 * @author     Nicolas Bastien
 */
class MageUC_Console_Task_PHPMD extends MageUC_Console_Task
{
    /**
     * @var string
     */
    public $description = 'Run PHP Mess Detector';
    
    /**
     * @var array
     */
    public $requiredArguments = array('to_check' => 'Folder to check');
    
    /**
     * @var array
     */
    public $optionalArguments = array(
        'report_format' => 'report format : phpmd second argument (xml, text or html)',
        'ruleset'       => 'Ruleset : codesize, unusedcode, naming or design'
    );
    
    /**
     * (non-PHPdoc)
     * @see MageUC_Console_Task::validate()
     */
    public function validate ()
    {
        if (!@include_once('PHP/PMD/TextUI/Command.php')) {
            throw new MageUC_Console_Exception('PHP Mess Detector is required.');
        }
        return parent::validate();
    }
    
    /**
     * Returns the path to the checkstyle report file
     *
     * @return string
     */
    public static function getReportFileName()
    {
        return Mage::getBaseDir('var') . DS . 'mageuc' . DS . 'phpmd' . DS . 'report' . DS . 'phpmd.xml';
    }
    
    /**
     * (non-PHPdoc)
     * @see MageUC_Console_Task::execute()
     */
    public function execute()
    {
        print('[phpmd] Lancement de PHPMD : ' . $this->getArgument('to_check') . PHP_EOL);

        $options = new PHP_PMD_TextUI_CommandLineOptions($this->_getOptions());
        $command = new PHP_PMD_TextUI_Command();
        $command->run($options);
        
        print('[phpmd] done' . PHP_EOL);
    }
    
    /**
     * Options for phpmd command line
     *
     * @see http://phpmd.org/documentation/index.html
     *
     * @return array
     */
    protected function _getOptions()
    {
        $options = array(
            'phpmd',
            $this->_getFilesToCheck($this->getArgument('to_check')),
            $this->getArgument('report_format', 'text'),
            $this->getArgument('ruleset', 'codesize,unusedcode,naming,design')
        );
        if ($this->getArgument('report_format') == 'xml') {
            $options[] = '--reportfile';
            $options[] = $this->getReportFileName();
        }
        return $options;
    }
}