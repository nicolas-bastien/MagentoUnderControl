<?php
/**
 * @category   MageUC
 * @package    MageUC_Console
 * @author     Nicolas Bastien
 */

/**
 * MageUC ClearCache Task
 *
 * @category   MageUC
 * @package    MageUC_Console
 * @author     Nicolas Bastien
 */
class MageUC_Console_Task_ClearCache extends MageUC_Console_Task
{
    /**
     * @var string
     */
    public $description = 'Clear Magento Cache';
    
    /**
     * @var array
     */
    public $optionalArguments = array(
        'mode' => 'Cleanning mode @see Zend_Cache_Core::clean method',
        'tag'  => 'cache tag'
    );
    
    /**
     * (non-PHPdoc)
     * @see MageUC_Console_Task::execute()
     */
    public function execute()
    {
        print('[Clear-cache] Mode : ' . $this->getArgument('mode', Zend_Cache::CLEANING_MODE_ALL) . PHP_EOL);
        Mage::app()->getCache()->clean($this->getArgument('mode', Zend_Cache::CLEANING_MODE_ALL), $this->getArgument('tag', array()));
        print('[Clear-cache] Done' . PHP_EOL);
    }
}
