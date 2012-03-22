<?php
/**
 * @category   MageUC
 * @author     Nicolas Bastien
 */

/**
 * MageUC Date
 *
 * Include date formatting or special functions to handle working days
 *
 * @category   MageUC
 * @author     Nicolas Bastien
 */
class MageUC_Date extends Zend_Date
{
    /**
     * Set current store locale by default
     * @see parent::__construct()
     */
    public function __construct($date = null, $part = null, $locale = null)
    {
        if ($locale == null) {
            $locale = Mage::app()->getLocale()->getLocale();
        }
        return parent::__construct($date, $part, $locale);
    }

    /**
     * Go to next working day
     *
     * @return Amer_Date
     */
    public function goToNextWorkingDay()
    {
        if ($this->compareDay('Saturday', 'en') == 0) {
            $this->addDay(2);
        }
        if ($this->compareDay('Sunday', 'en') == 0) {
            $this->addDay(1);
        }
        return $this;
    }
}