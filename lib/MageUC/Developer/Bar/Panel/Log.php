<?php
/**
 * @category   MageUC
 * @package    Developer
 * @author     Nicolas Bastien
 */

/**
 * Panel for Log
 *
 * (!) If you want to see logs in this panel you need to add
 * 
 * Mage::dispatchEvent('mage_log', array('message' => $message, 'level' => $level, 'file' => $file));
 * in Mage::log function
 * 
 * @category   MageUC
 * @package    Developer
 * @author     Nicolas Bastien
 */
class MageUC_Developer_Bar_Panel_Log extends MageUC_Developer_Bar_Panel implements MageUC_Developer_Bar_Panel_Interface
{
    /**
     * Log event name
     */
    const EVENT_MAGE_LOG = 'mage_log';
    
    /**
     * Data to log
     * @var array
     */
    protected static $_arData = array();
    
    /**
     * @see MageUC_Developer_Bar_Panel::$_panelName
     */
    static $_panelName = 'log';

    /**
     * @see MageUC_Developer_Bar_Panel::_getItemMenuString()
     */
    protected function _getItemMenuString()
    {
        return count(self::$_arData);
    }

    /**
     * @see MageUC_Developer_Bar_Panel_Interface::update(Varien_Event $event)
     */
    public function update(Varien_Event $event)
    {
        if ($event->getName() == self::EVENT_MAGE_LOG) {
            self::$_arData[] = array(
                'message' => $event->getMessage(),
                'level'   => $event->getLevel(),
                'file'    => $event->getFile()
            );
        }
        return;
    }
    
    /**
     * Returns data to dump
     * 
     * @return array
     */
    public function getData()
    {
        return self::$_arData;
    }
}
