<?php
/**
 * @category   MageUC
 * @package    Developer
 * @author     Nicolas Bastien
 */

/**
 * Panel for Debug variable
 *
 * @category   MageUC
 * @package    Developer
 * @author     Nicolas Bastien
 */
class MageUC_Developer_Bar_Panel_Debug extends MageUC_Developer_Bar_Panel implements MageUC_Developer_Bar_Panel_Interface
{
    /**
     * @see MageUC_Developer_Bar_Panel::$_panelName
     */
    static $_panelName = 'debug';

    /**
     * Data to dump
     * @var array
     */
    protected static $_arDebugVariables = array();
    
    /**
     * @see MageUC_Developer_Bar_Panel::_getItemMenuString()
     */
    protected function _getItemMenuString()
    {
        return count(self::$_arDebugVariables);
    }
    
    /**
     * Add data to dump
     *
     * @param  mixed $var
     * @param  string $varName
     * @return void
     */
    public static function addToDumpVariable($var, $varName = null)
    {
        if ($varName != null) {
            self::$_arDebugVariables[$varName] = $var;
            return;
        }
        self::$_arDebugVariables[] = $var;
    }

    /**
     * Returns data to dump
     * 
     * @return array
     */
    public function getData()
    {
        return self::$_arDebugVariables;
    }

    /**
     * Return variable as dump string
     *
     * @param  mixed $var
     * @return string
     */
    public function varDump($var)
    {
        if ($var instanceof Varien_Object) {
            $toReturn = var_dump($this->extractVarienData($var->getData()));
        } elseif ($var instanceof Varien_Data_Collection) {
            $toReturn = var_dump($this->extractVarienData($var->getItems()));
        } else {
            $toReturn = var_dump($var);
        }
        return $toReturn;
    }
    
    /**
     * Extract Varien Obejct form an array
     *
     * @param  array $arData
     * @return array
     */
    public function extractVarienData($arData)
    {
        foreach ($arData as $key => $data) {
            if ($data instanceof  Varien_Object) {
                $arData[$key] = extractVarienData($data->getData());
            }
        }
        return $arData;
    }
}
