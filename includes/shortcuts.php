<?php
/**
 * includes/shortcuts.php
 * Include some functions for developper
 *
 * @author Nicolas Bastien
 * @link http://nicolas-bastien.com/en/magento/shortcut-functions
 */

/**
 * Advanced var_dump() method for Varien Objects
 *
 * @param mixed $var
 * @return void
 */
function _d($var)
{
    if ($var instanceof Varien_Object) {
        var_dump(extractVarienData($var->getData()));
        return;
    }
    if ($var instanceof Varien_Data_Collection) {
        var_dump(extractVarienData($var->getItems()));
        return;
    }
    var_dump($var);
}
/**
 * Extract Varien Obejct form an array
 *
 * @param  array $arData
 * @return array
 */
function extractVarienData($arData)
{
    foreach ($arData as $key => $data) {
        if ($data instanceof  Varien_Object) {
            $arData[$key] = extractVarienData($data->getData());
        }
    }
    return $arData;
}
/**
 * Shortcut for _d(); die;
 *
 * @param mixed $var
 * @return void
 */
function _dd($var)
{
    _d($var);die;
}