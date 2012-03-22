<?php
/**
 * @category   MageUC
 * @package    MageUC_Console
 * @author     Nicolas Bastien
 */

/**
 * MageUC CheckStyle Task to run PHP_CodeSniffer
 *
 * @category   MageUC
 * @package    MageUC_Console
 * @author     Nicolas Bastien
 */
class MageUC_Console_Task_CheckStyle extends MageUC_Console_Task
{
    /**
     * @var string
     */
    public $description = 'Run PHP_CodeSniffer CheckStyle';
    
    /**
     * @var array
     */
    public $requiredArguments = array('to_check' => 'Folder to check');
    
    /**
     * @var array
     */
    public $optionalArguments = array('report_format' => 'Report mode : --report phpcs option (by default xml)');
    
    /**
     * (non-PHPdoc)
     * @see MageUC_Console_Task::validate()
     */
    public function validate ()
    {
        if (!@include_once('PHP/CodeSniffer.php')) {
            throw new MageUC_Console_Exception('PHP_CodeSniffer is required.');
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
        return Mage::getBaseDir('var') . DS . 'mageuc' . DS . 'codesniffer' . DS . 'report' . DS . 'checkstyle.xml';
    }
    
    /**
     * (non-PHPdoc)
     * @see MageUC_Console_Task::execute()
     */
    public function execute()
    {
        print('[checkstyle] Lancement du checkstyle : ' . $this->getArgument('to_check') . PHP_EOL);

        require_once('PHP' . DS . 'CodeSniffer.php');
        $phpcs = new PHP_CodeSniffer_CLI();
        $phpcs->checkRequirements();
        $phpcs->process($this->_getOptions());
        
        print('[checkstyle] done' . PHP_EOL);
    }
    
    /**
     * Options for PHP_CodeSniffer_CLI same phpcs command line
     *
     * @see PHP_CodeSniffer_CLI::getDefaults() for more informations
     *
     * @return array
     */
    protected function _getOptions()
    {
        $options = array();

        // The default values for config settings.
        $options['files']       = $this->_getFilesToCheck($this->getArgument('to_check'));
        $options['standard']    = Mage::getBaseDir('lib') . DS . 'MageUC' . DS . 'Console' . DS .'Task' . DS . 'CheckStyle' . DS  .'ruleset.xml';
        $options['verbosity']   = 1;
        $options['interactive'] = false;
        $options['local']       = false;
        $options['showSources'] = false;
        $options['extensions']  = array();
        $options['sniffs']      = array();
        $options['ignored']     = array();
        if (!is_null($this->getArgument('report_format'))) {
            $options['reports'] = array($this->getArgument('report_format') => null);
        } else {
            $options['reports'] = array();
        }
        $options['reportFile']  = self::getReportFileName();
        $options['reportWidth'] = 80;
        $options['generator']   = '';
        $options['tabWidth'] = 4;
        $options['encoding'] = 'utf-8';
        $options['errorSeverity']   = 1;
        $options['warningSeverity'] = 1;
        $options['default_standard'] = 'Zend'; //http://framework.zend.com/manual/fr/coding-standard.coding-style.html
        $options['showProgress'] = false;

        return $options;
    }
}