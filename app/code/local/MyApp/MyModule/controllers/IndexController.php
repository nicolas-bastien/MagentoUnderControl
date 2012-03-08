<?php
/**
 * @category	MyApp
 * @package     MyApp_MyModule
 * @author      Nicolas Bastien
 */

/**
 * Simple controller example
 *
 * @category	MyApp
 * @package     MyApp_MyModule
 * @author      Nicolas Bastien
 */
class MyApp_MyModule_IndexController extends MageUC_Controller_Front
{
    /**
     * Default page
     */
    public function indexAction()
    {
        $this->loadLayout();
        $this->renderLayout();
    }
}