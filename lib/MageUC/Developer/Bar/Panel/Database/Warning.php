<?php
/**
 * @category   MageUC
 * @package    Developer
 * @author     Nicolas Bastien
 */

/**
 * Panel for Database Warning
 *
 * @category   MageUC
 * @package    Developer
 * @author     Nicolas Bastien
 */
class MageUC_Developer_Bar_Panel_Database_Warning extends MageUC_Developer_Bar_Panel_Database implements MageUC_Developer_Bar_Panel_Interface
{
    /**
     * @see MageUC_Developer_Bar_Panel::$_panelName
     */
    static $_panelName = 'database_warning';

    /**
     * Duplicated Queries
     * @var array
     */
    protected $_arDuplicatedQueries;

    /**
     * Duplicated Query number
     * @var int
     */
    protected $_nbDuplicatedQueries;

    /**
     * Duplicated Total Query number : means $_arDuplicatedQueries * each quantity
     * @var int
     */
    protected $_nbTotalDuplicatedQueries;

    /**
     * Compare function to sort duplicated queries
     * 
     * @param  array $a
     * @param  array $b
     * @return boolean
     */
    public static function compare($a, $b)
    {
        return (count($a) < count($b));
    }

    /**
     * @see MageUC_Developer_Bar_Panel::_getItemMenuString()
     */
    protected function _getItemMenuString()
    {
        if (!is_array($this->_arDuplicatedQueries)) {
            $this->_buildDuplicatedQueries();
        }
        return $this->_nbDuplicatedQueries . ' : ' . $this->_nbTotalDuplicatedQueries
                . '>&nbsp;' . round(($this->_nbTotalDuplicatedQueries / $this->getTotalQuery())*100, 2) . ' %';
    }

    /**
     * Returns string to display in panel title
     *
     * @return string
     */
    public function getPanelTitle()
    {
        return $this->_nbDuplicatedQueries . ' : ' . $this->_nbTotalDuplicatedQueries
                . '&nbsp;>&nbsp;' . round(($this->_nbTotalDuplicatedQueries / $this->getTotalQuery())*100, 2) . ' %';
    }

    /**
     * Build duplicated query tab
     */
    protected function _buildDuplicatedQueries()
    {
        $this->_arDuplicatedQueries = array();
        //First add each query with the query as key
        //so eahc key is an array of queries
        foreach($this->_profiler->getQueryProfiles() as $query) {
            if (!isset($this->_arDuplicatedQueries[$query->getQuery()])) {
                $this->_arDuplicatedQueries[$query->getQuery()] = array();
            }
            $this->_arDuplicatedQueries[$query->getQuery()][] = array(
                'query' => $query->getQuery(),
                'time' => $query->getElapsedSecs(),
                'params' => $query->getQueryParams()
            );
        }

        //unset un duplicated queries
        foreach ($this->_arDuplicatedQueries as $key => $arQueries) {
            if (count($arQueries) == 1) {
                unset($this->_arDuplicatedQueries[$key]);
            } else {
                $this->_nbTotalDuplicatedQueries += count($arQueries);
            }
        }
        //sort by criticity
        usort($this->_arDuplicatedQueries, array('MageUC_Developer_Bar_Panel_Database_Warning', 'compare'));
        $this->_nbDuplicatedQueries = count($this->_arDuplicatedQueries);
    }

    /**
     * Returns all the duplicated queries
     *
     * @return array
     */
    public function getDuplicatedQueries()
    {
        if (!is_array($this->_arDuplicatedQueries)) {
            $this->_buildDuplicatedQueries();
        }
        return $this->_arDuplicatedQueries;
    }
}