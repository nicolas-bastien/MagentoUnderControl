<?php
/**
 * @category	MageUC
 * @package		MageUC_Layout
 * @author		Nicolas Bastien
 */

/**
 * Block base class for controller default block
 *
 * extends Magento front controller to add Zend Framework structure and bypass default Magento Layout
 *
 * -> manage view directly in module directory with standard name
 *
 * @category	MageUC
 * @package		MageUC_Layout
 * @author		Nicolas Bastien
 */
class MageUC_Layout_Controller_Block extends Mage_Core_Block_Template
{
    /**
     * @see Mage_Core_Block_Template::getTemplate()
     */
    public function getTemplate()
    {
        return  str_replace('_', DS, Mage::app()->getRequest()->getControllerName())
            . DS .  Mage::app()->getRequest()->getActionName() . '.phtml';
    }
    
    /**
     * @see Mage_Core_Block_Template::renderView()
     */
    public function renderView()
    {
        $this->_viewDir = Mage::getModuleDir('views', Mage::app()->getRequest()->getControllerModule()) . DS . 'views';
        return $this->fetchView($this->getTemplate());
    }
}
