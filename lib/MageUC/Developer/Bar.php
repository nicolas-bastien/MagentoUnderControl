<?php
/**
 * @category   MageUC
 * @author     Nicolas Bastien
 */

/**
 * MageUC Developer Bar manage all specific panels and render
 * 
 * @category   MageUC
 * @author     Nicolas Bastien
 */
class MageUC_Developer_Bar
{
    /**
     * Event which trigger the bar rendering
     */
    const EVENT_POST_DISPATCH = 'controller_action_postdispatch';

    /**
     * Stock all the bar panels
     * @var array
     */
    protected $_panels;

    /**
     * Flag to disable bar rendering
     * @var boolean
     */
    protected static $_enabled = true;


    public function  __construct()
    {
        //Load Bar panel
        $this->_panels = array();
    }

    /**
     * Returns all Bar panels
     *
     * @return array
     */
    public function getPanels()
    {
        return $this->_panels;
    }

    /**
     * Returns all Bar panels name
     *
     * @return array
     */
    public function getPanelNames()
    {
        return array_keys($this->_panels);
    }

    /**
     * Disable bar rendering
     *
     * @return void
     */
    public static function disable()
    {
        self::$_enabled = false;
    }
    
    /**
     * Returns view path for module
     *
     * @return string
     */
    public static function getViewPath()
    {
        return 'lib' . DS . 'MageUC' . DS . 'Developer' . DS . 'Bar' . DS . 'Panel' . DS . 'views' . DS;
    }
    
    /**
     * Handle events
     *
     * @param  Varien_Event $event
     * @return void
     */
    public function update(Varien_Event $event)
    {
        if (!self::$_enabled) {
            return;
        }
        foreach ($this->_panels as $panel) {
            $panel->update($event);
        }
        
        if ($event->getName() == 'core_block_abstract_prepare_layout_after' 
          && Mage::app()->getLayout()->getBlock('head') != null
          && Mage::app()->getWebsite()->getId() != 0) {
            Mage::app()->getLayout()->getBlock('head')->addCss('css/mageuc-developer-bar.css');
        }
        
        //Render the bar at the end
        if ($event->getName() == self::EVENT_POST_DISPATCH
        && !Mage::app()->getRequest()->getQuery('isAjax')
        && !Mage::app()->getRequest()->isXmlHttpRequest()
        && !Mage::app()->getRequest()->getParam('isAjax')
        && Mage::app()->getWebsite()->getId() != 0) {
            Mage::app()->getResponse()->appendBody($this->render(), 'mageuc_developer');
        }
    }

    /**
     * Render the bar for display
     *
     * @return string HTML code
     */
    public function render()
    {
        ob_start();
        require($this->getViewPath() . 'bar.phtml');
        return ob_get_clean();
    }
}