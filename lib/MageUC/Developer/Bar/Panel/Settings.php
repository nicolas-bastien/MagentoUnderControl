<?php
/**
 * @category   MageUC
 * @package    Developer
 * @author     Nicolas Bastien
 */

/**
 * Panel for Settings
 *
 * @category   MageUC
 * @package    Developer
 * @author     Nicolas Bastien
 */
class MageUC_Developer_Bar_Panel_Settings extends MageUC_Developer_Bar_Panel implements MageUC_Developer_Bar_Panel_Interface
{
    /**
     * @see MageUC_Developer_Bar_Panel::$_panelName
     */
    static $_panelName = 'settings';

    /**
     * @see MageUC_Developer_Bar_Panel::renderMenuItem()
     */
    protected function _getItemMenuString()
    {
        return 'Settings';
    }

    /**
     * @see MageUC_Developer_Bar_Panel::renderContent()
     */
    public function renderContent()
    {
        ob_start();
        $arPhpInfo = self::phpInfoAsArray();
        require(MageUC_Developer_Bar::getViewPath() . DS . 'settings.phtml');
        return ob_get_clean();
    }

   /**
    * Returns PHP information as an array.
    *
    * @return array An array of php information
    */
    public static function phpInfoAsArray()
    {
        $values = array(
            'php'        => phpversion(),
            'os'         => php_uname(),
            'extensions' => get_loaded_extensions(),
        );

        natcasesort($values['extensions']);

        // assign extension version
        if ($values['extensions']) {
            foreach($values['extensions'] as $key => $extension) {
                $values['extensions'][$key] = phpversion($extension) ? sprintf('%s (%s)', $extension, phpversion($extension)) : $extension;
            }
        }

        return $values;
    }
}
