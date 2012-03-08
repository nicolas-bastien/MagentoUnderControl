<?php
/**
 * @category   MageUC
 * @author     Nicolas Bastien
 * @link       http://nicolas-bastien.com/en/magento/facade
 */

/**
 * Facade to provide an easier way to call useful functions
 *
 * @category   MageUC
 * @author     Nicolas Bastien
 * @link       http://nicolas-bastien.com/en/magento/facade
 */
class MageUC
{
    /**
     * @var MageUC_Entity_Manager
     */
    protected static $_entityManager = null;

    /**
     * Returns the entity manager
     *
     * @return MageUC_Entity_Manager
     */
    public static function getEntityManager()
    {
        if (self::$_entityManager == null) {
            self::$_entityManager = new MageUC_Entity_Manager();
        }
        return self::$_entityManager;
    }

    /**
     * Returns current website
     *
     * @return Mage_Core_Model_Website
     */
    public static function getWebsite()
    {
        return Mage::app()->getWebsite();
    }

    /**
     * Returns current store
     *
     * @return Mage_Core_Model_Store
     */
    public static function getStore()
    {
        return Mage::app()->getStore();
    }

    /**
     * Return current front customer
     *
     * @return Mage_Customer_Model_Customer
     */
    public static function getCustomer()
    {
        return Mage::getSingleton('customer/session')->getCustomer();
    }
}