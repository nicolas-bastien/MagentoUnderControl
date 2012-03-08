<?php
/**
 * @category	MyApp
 * @author      Nicolas Bastien
 */

/**
 * MyApp facade : provide a easy way to call common method
 *
 * @category	MyApp
 * @author      Nicolas Bastien
 */
final class MyApp extends MageUC
{
    /**
     * Current Sales Organisation
     * @var MyApp_SalesOrganisation_Model_Sales_Organisation
     */
    protected static $_salesOrganisation = null;

    /**
     * Returns current Sales Organisation
     *
     * @return MyApp_SalesOrganisation_Model_Sales_Organisation
     */
    public static function getSalesOrganisation()
    {
        if (self::$_salesOrganisation == null) {
            self::$_salesOrganisation = Mage::getModel('myappsalesorganisation/salesorganisation')
                    ->load(Mage::app()->getWebsite()->getData('sales_organisation_id'));
        }
        return self::$_salesOrganisation;
    }
}