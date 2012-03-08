<?php
/**
 * @category   MageUC
 * @author     Nicolas Bastien
 */

/**
 * MageUC_App to call instead of Mage_Core_Model_App in Mage.php
 *
 * Allow us to catch every event dispatched
 *
 * @category   MageUC
 * @author     Nicolas Bastien
 */
class MageUC_App extends Mage_Core_Model_App
{
    /**
     * @var MageUC_Developer_Bar
     */
    private $_bar;
    
    public function __construct()
    {
        parent::__construct();
        $this->_bar = new MageUC_Developer_Bar();
    }

    /**
     * @see Mage_Core_Model_App::dispatchEvent($eventName, $args)
     */
    public function dispatchEvent($eventName, $args)
    {
        $event = new Varien_Event($args);
        $event->setName($eventName);
        $observer = new Varien_Event_Observer();
        $observer->setData(array('event' => $event));
        $observer->addData($args);
        
        $this->_bar->update($observer->getEvent());

        return parent::dispatchEvent($eventName, $args);
    }
}