<?php
/**
 * @category   MageUC
 * @package    Developer
 * @author     Nicolas Bastien
 */

/**
 * Panel for Database queries
 *
 * @category   MageUC
 * @package    Developer
 * @author     Nicolas Bastien
 */
class MageUC_Developer_Bar_Panel_Database extends MageUC_Developer_Bar_Panel implements MageUC_Developer_Bar_Panel_Interface
{
    /**
     * @see MageUC_Developer_Bar_Panel::$_panelName
     */
    static $_panelName = 'database';

    /**
     * @var Zend_Db_Profiler
     */
    protected $_profiler;

    /**
     * @var boolean
     */
    protected $_profilerEnabled = false;

    /**
     * List of query key words to format
     * @var array
     */
    protected $_arQueryKeyWords = array(
        'set character set', 'select', 'update', 'insert', 'and', 'or', 'in', 'not in', 'on', 'date_format', 'asc', 'desc'
    );

    /**
     * List of query key words to format with a line break
     * @var array
     */
    protected $_arQueryKeyWordsWithBr = array ('from', 'where', 'inner join', 'left join', 'right join', 'order by', 'limit');

    /**
     * @see MageUC_Developer_Bar_Panel_Interface::update(Varien_Event $event)
     */
    public function update(Varien_Event $event)
    {
        if (!$this->_profilerEnabled) {
            Mage::getSingleton('core/resource')->getConnection('core_write')
                ->getProfiler()->setEnabled(true);
            $this->_profilerEnabled = true;
        }
        if ($event->getName() == MageUC_Developer_Bar::EVENT_POST_DISPATCH) {
            $conn = Mage::getSingleton('core/resource')->getConnection('core_write');
            $this->_profiler = $conn->getProfiler();
        }
        return;
    }

    /**
     * @see MageUC_Developer_Bar_Panel::_getItemMenuString()
     */
    protected function _getItemMenuString()
    {
        return $this->getTotalQuery();
    }

    /**
     * Returns total time spend handling db queries
     *
     * @return float
     */
    public function getTotalTime()
    {
        return $this->_profiler->getTotalElapsedSecs();
    }

    /**
     * Returns total number of db query
     *
     * @return int
     */
    public function getTotalQuery()
    {
        return $this->_profiler->getTotalNumQueries();
    }

    /**
     * Returns all the db queries made
     *
     * @return array
     */
    public function getQueries()
    {
        if ($this->_profiler->getQueryProfiles() != null) {
            return $this->_profiler->getQueryProfiles();
        }
        return array();
    }

    /**
	 * Returns query formatted for rendering
	 *
	 * @param  array $query returned by self::getQuerys
	 * @return array
	 */
	protected function _formatQuery($query)
    {
        foreach ($this->_arQueryKeyWords as $keyWord) {
            $query = str_ireplace($keyWord . ' ', '<span class="query_key_word">&nbsp;' . $keyWord . '&nbsp;</span>', $query);
        }
        foreach ($this->_arQueryKeyWordsWithBr as $keyWord) {
            $query = str_ireplace(' ' . $keyWord . ' ', '<br /><span class="query_key_word">&nbsp;' . $keyWord . '&nbsp;</span>', $query);
        }

        $query = str_ireplace(',', ',<br/><div class="small-space">&nbsp;</div>', $query);
        return $query;
	}
}