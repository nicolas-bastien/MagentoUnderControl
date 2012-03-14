<?php
/**
 * @category   MageUC
 * @package    Developer
 * @author     Nicolas Bastien
 */

/**
 * Abstract class for bar panels
 *
 * @category   MageUC
 * @package    Developer
 * @author     Nicolas Bastien
 */
abstract class MageUC_Developer_Bar_Panel
{
    /**
     * @var string
     */
    static $_panelName;

    /**
     * Returns string to display in bar menu
     *
     * @return string
     */
    abstract protected function _getItemMenuString();
    
    /**
     * @see MageUC_Developer_Bar_Panel_Interface::update(Varien_Event $event)
     */
    public function update(Varien_Event $event)
    {
        return;
    }

    /**
     * @see MageUC_Developer_Bar_Panel_Interface::renderMenuItem()
     */
    public function renderMenuItem()
    {
        return '<li onclick="MageUCDeveloperBar.showPanel(\'mageuc-developer-bar-' . static::$_panelName . '\');" class="' . static::$_panelName . '">'
            . $this->_getItemMenuString()
            . '</li>';
    }

    /**
     * @see MageUC_Developer_Bar_Panel_Interface::renderContent()
     */
    public function renderContent()
    {
        ob_start();
        require(MageUC_Developer_Bar::getViewPath() . DS . static::$_panelName . '.phtml');
        return ob_get_clean();
    }
}