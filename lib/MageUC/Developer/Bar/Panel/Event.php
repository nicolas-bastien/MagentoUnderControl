<?php
/**
 * @category   MageUC
 * @package    Developer
 * @author     Nicolas Bastien
 */

/**
 * Panel for Event
 *
 * @category   MageUC
 * @package    Developer
 * @author     Nicolas Bastien
 */
class MageUC_Developer_Bar_Panel_Event extends MageUC_Developer_Bar_Panel implements MageUC_Developer_Bar_Panel_Interface
{
    /**
     * @see MageUC_Developer_Bar_Panel::$_panelName
     */
    static $_panelName = 'event';
    
    /**
     * @var array
     */
    protected $_arEvents = array();
    
    /**
     * @var integer
     */
    protected $_totalEventDispatched = 0;

    /**
     * @see MageUC_Developer_Bar_Panel::_getItemMenuString()
     */
    protected function _getItemMenuString()
    {
        return $this->getTotalEvents();
    }

    /**
     * Return total of event
     * 
     * @return string
     */
    public function getTotalEvents()
    {
        return count($this->_arEvents) . ' >> ' . $this->_totalEventDispatched;
    }

    /**
     * Return all events
     * 
     * @return array
     */
    public function getEvents()
    {
        arsort($this->_arEvents);
        return $this->_arEvents;
    }
    
    /**
     * @see MageUC_Developer_Bar_Panel_Interface::update(Varien_Event $event)
     */
    public function update(Varien_Event $event)
    {
        if (!isset($this->_arEvents[$event->getName()])) {
            $this->_arEvents[$event->getName()] = 0;
        }
        $this->_arEvents[$event->getName()] += 1;
        $this->_totalEventDispatched++;
    }
}
