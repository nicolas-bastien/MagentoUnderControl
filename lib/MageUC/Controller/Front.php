<?php
/**
 * @category   MageUC
 * @package    MageUC_Controller
 * @author     Nicolas Bastien
 */

/**
 * MageUC Controller for Front
 *
 * extends Magento front controller to add Zend Framework structure and bypass default Magento Layout
 *
 * -> manage view directly in module directory with standard name
 *
 * @category   MageUC
 * @package    MageUC_Controller
 * @author     Nicolas Bastien
 */
class MageUC_Controller_Front extends Mage_Core_Controller_Front_Action
{
    /**
     * @see Mage_Core_Controller_Front_Action::renderLayout($output)
     */
    public function renderLayout($output = '')
    {
        //Add main template to to content block
        $mainViewBlock = $this->getLayout()->addBlock(new MageUC_Layout_Controller_Block(), 'main-view');
        $mainViewBlock->assign(get_object_vars($this));
        $this->getLayout()->getBlock('content')->append($mainViewBlock);

        return parent::renderLayout($output);
    }
}