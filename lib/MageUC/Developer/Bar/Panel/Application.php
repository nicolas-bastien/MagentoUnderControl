<?php
/**
 * @category   MageUC
 * @package    Developer
 * @author     Nicolas Bastien
 */

/**
 * Panel for Application
 *
 * @category   MageUC
 * @package    Developer
 * @author     Nicolas Bastien
 */
class MageUC_Developer_Bar_Panel_Application extends MageUC_Developer_Bar_Panel implements MageUC_Developer_Bar_Panel_Interface
{
    /**
     * @see MageUC_Developer_Bar_Panel::$_panelName
     */
    static $_panelName = 'application';
    
    /**
     * @see MageUC_Developer_Bar_Panel::_getItemMenuString()
     */
    protected function _getItemMenuString()
    {
        return 
            strtoupper(Mage::app()->getRequest()->getControllerModule())
            . ' | ' . Mage::app()->getRequest()->getControllerName()
            . ' | ' . Mage::app()->getRequest()->getActionName()
            . ' (' . Mage::app()->getResponse()->getHttpResponseCode() . ')'
        ;
    }
}