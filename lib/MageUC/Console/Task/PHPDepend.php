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
class MageUC_Console_Task_PHPDepend extends MageUC_Console_Task
{
    /**
     * @var string
     */
    public $description = 'Run PHP Depend';

    /**
     * @var array
     */
    public $requiredArguments = array('to_check' => 'Folder to check');

    /**
     * (non-PHPdoc)
     * @see MageUC_Console_Task::validate()
     */
    public function validate ()
    {
        if (!@include_once('PHP/Depend/TextUI/Command.php')) {
            throw new MageUC_Console_Exception('PHP Depend is required.');
        }
        return parent::validate();
    }

    /**
     * Returns the path to the checkstyle report file
     *
     * @return string
     */
    public static function getReportFileName($filename)
    {
        return Mage::getBaseDir('var') . DS . 'mageuc' . DS . 'phpdepend' . DS . 'report' . DS . $filename;
    }

    /**
     * (non-PHPdoc)
     * @see MageUC_Console_Task::execute()
     */
    public function execute()
    {
        print('[pdepend] Lancement de PHP Depend : ' . $this->getArgument('to_check') . PHP_EOL);

        $this->_runner = new PHP_Depend_TextUI_Runner();
        $this->_runner->addProcessListener(new PHP_Depend_TextUI_ResultPrinter());
        $this->_runner->addLogger('summary-xml', self::getReportFileName('summary.xml'));
        $this->_runner->addLogger('jdepend-chart', self::getReportFileName('jdepend.svg'));
        $this->_runner->addLogger('jdepend-xml', self::getReportFileName('jdepend.xml'));
        $this->_runner->addLogger('overview-pyramid', self::getReportFileName('pyramid.svg'));

        $arSource = $this->_getFilesToCheck($this->getArgument('to_check'));
        if (!is_array($arSource)) {
            $arSource = array($arSource);
        }
        $this->_runner->setSourceArguments($arSource);
        
        $configurationFactory = new PHP_Depend_Util_Configuration_Factory();
        $configuration = $configurationFactory->createDefault();
        // Store in config registry
        PHP_Depend_Util_ConfigurationInstance::set($configuration);

        $this->_runner->setConfiguration($configuration);
        $this->_runner->run();
        
        print('[pdepend] done' . PHP_EOL);
    }
}