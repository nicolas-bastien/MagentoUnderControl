<?php
/**
 * @category   MageUC
 * @package    Developer
 * @author     Nicolas Bastien
 */

/**
 * Interface for bar panels
 *
 * @category   MageUC
 * @package    Developer
 * @author     Nicolas Bastien
 */
interface MageUC_Developer_Bar_Panel_Interface
{
    /**
     * Update panel data
     *
     * @param Varien_Event $event
     * @return void
     */
    public function update(Varien_Event $event);

    /**
     * Render for item menu in Main bar
     * 
     * @return string HTML code
     */
    public function renderMenuItem();

    /**
     * Render panel content
     *
     * @return string HTML code
     */
    public function renderContent();
}