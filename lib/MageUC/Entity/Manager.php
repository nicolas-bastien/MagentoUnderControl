<?php
/**
 * @category   MageUC
 * @author     Nicolas Bastien
 * @link       http://nicolas-bastien.com/magento/load-function
 */

/**
 * Entity Manager handles entity loading
 *
 * @category   MageUC
 * @author     Nicolas Bastien
 * @link       http://nicolas-bastien.com/magento/load-function
 */
class MageUC_Entity_Manager
{
    /**
     * Store every loaded entities
     * @var array
     */
    protected $_arEntities = array();

    /**
     * Check if data has been loaded for model and id
     *
     * @param  string $modelId
     * @param  string $entityCacheKey
     * @return boolean
     */
    public function has($modelId, $entityCacheKey)
    {
        return (isset($this->_arEntities[$modelId]) && isset($this->_arEntities[$modelId][$entityCacheKey]));
    }

    /**
     * Remove un object from internal cache
     *
     * @param  string $modelId
     * @param  string $entityCacheKey
     * @return boolean
     */
    public function remove($modelId, $entityCacheKey)
    {
        if (isset($this->_arEntities[$modelId]) && isset($this->_arEntities[$modelId][$entityCacheKey])) {
            unset($this->_arEntities[$modelId][$entityCacheKey]);
            return true;
        }
        return false;
    }

    /**
     * Allow us to register in the load data an object
     *
     * For example an entity comming from a collection (only way to get attributes)
     *
     * @param type $modelId
     * @param type $entityCacheKey
     * @param type $entity
     */
    public static function add($modelId, $entityCacheKey, $entity)
    {
        if (!isset($this->_arEntities[$modelId])) {
            $this->_arEntities[$modelId] = array();
        }
        $this->_arEntities[$modelId][$entityCacheKey] = $entity;
        return $entity;
    }

    /**
     * Function to use instead of simple Mage::getModel($modelId)->load($entityId, $field);
     *
     * use internal cache to reduce redundant queries
     * only store if object exist
     *
     * @param  string $modelId
     * @param  string $entityId
     * @param  string $field [optional]
     * @return mixed Varien_Object if exist or false
     */
    public function load($modelId, $entityId, $field = null)
    {
        $entityCacheKey = $entityId;
        if ($field != null) {
            $entityCacheKey .= '-' . $field;
        }
        if ($this->has($modelId, $entityCacheKey)) {
            //Case data already present in cache
            return $this->_arEntities[$modelId][$entityCacheKey];
        }

        //check if model exist first
        $model = Mage::getModel($modelId);
        if ($model == false) {
            return false;
        }
        //Check if object exist
        $object = $model->load($entityId, $field);
        if ($object->getId() == null) {
            return false;
        }
        //Only store valide object
        $this->add($modelId, $entityCacheKey, $object);
        return $object;
    }
}