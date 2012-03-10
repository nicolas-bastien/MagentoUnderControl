<?php
/**
 * @category   MageUC
 * @package    Developer
 * @author     Nicolas Bastien
 */

/**
 * Panel for Memory
 *
 * @category   MageUC
 * @package    Developer
 * @author     Nicolas Bastien
 */
class MageUC_Developer_Bar_Panel_Memory extends MageUC_Developer_Bar_Panel implements MageUC_Developer_Bar_Panel_Interface
{
    /**
     * @see MageUC_Developer_Bar_Panel::$_panelName
     */
    static $_panelName = 'memory';

    /**
     * @see MageUC_Developer_Bar_Panel::_getItemMenuString()
     */
    protected function _getItemMenuString()
    {
        return sprintf('%.1f', (memory_get_peak_usage(true) / 1024)) . ' KB';
    }

    /**
     * @see MageUC_Developer_Bar_Panel::renderContent()
     */
    public function renderContent()
    {
        return '';
    }
}
