<?php
/**
 * @category   MageUC
 * @package    MageUC_Console
 * @author     Nicolas Bastien
 */

/**
 * MageUC Console Task
 *
 * @category   MageUC
 * @package    MageUC_Console
 * @author     Nicolas Bastien
 */
abstract class MageUC_Console_Task
{
    public  $taskName             =   null,
            $description          =   null,
            $arguments            =   array(),
            $requiredArguments    =   array(),
            $optionalArguments    =   array();

    
    public function __construct()
    {
        $this->taskName = str_replace('MageUC_Console_Task_', '', get_class($this));
    }

    /**
     * Execute task
     *
     * Override with each task class
     *
     * @return void
     * @abstract
     */
    abstract function execute();

    /**
     * Validates that all required fields are present
     *
     * @return bool true
     */
    public function validate()
    {
        $requiredArguments = $this->getRequiredArguments();
        foreach ($requiredArguments as $arg) {
            if (!isset($this->arguments[$arg])) {
                return false;
            }
        }
        return true;
    }

    /**
     * Add Argument
     *
     * @param  string $name
     * @param  string $value
     * @return void
     */
    public function addArgument($name, $value)
    {
        $this->arguments[$name] = $value;
    }

    /**
     * Return argument identified by $name, $default by default
     *
     * @param  string $name
     * @param  string $default
     * @return mixed
     */
    public function getArgument($name, $default = null)
    {
        if (isset($this->arguments[$name]) && $this->arguments[$name] !== null) {
            return $this->arguments[$name];
        } else {
            return $default;
        }
    }

    /**
     * Return all arguments
     *
     * @return array
     */
    public function getArguments()
    {
        return $this->arguments;
    }

    /**
     * Set a list of argument
     *
     * @param  array $args
     * @return void
     */
    public function setArguments(array $args)
    {
        $this->arguments = $args;
    }

    /**
     * Return task name
     *
     * @return string $taskName
     */
    public function getTaskName()
    {
        return $this->taskName;
    }

    /**
     * Return task Description
     *
     * @return string $description
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Return task Required Arguments
     *
     * @return array $requiredArguments
     */
    public function getRequiredArguments()
    {
        return array_keys($this->requiredArguments);
    }

    /**
     * Return task Optional Arguments
     *
     * @return array $optionalArguments
     */
    public function getOptionalArguments()
    {
        return array_keys($this->optionalArguments);
    }

    /**
     * Return task Required Arguments Descriptions
     *
     * @return array $requiredArgumentsDescriptions
     */
    public function getRequiredArgumentsDescriptions()
    {
        return $this->requiredArguments;
    }

    /**
     * Return task Optional Arguments Descriptions
     *
     * @return array $optionalArgumentsDescriptions
     */
    public function getOptionalArgumentsDescriptions()
    {
        return $this->optionalArguments;
    }

    /**
     * Prints task description and arguments
     *
     * @return void
     */
    public function printHelp()
    {
        return '@todo';
    }

    /**
     * Return path to folder to check
     *
     * @param string $toCheck command line params
     * @return void
     */
    protected function _getFilesToCheck($toCheck)
    {
        switch($toCheck) {
            case 'all':
                //Check all modules and libraries
                return array(Mage::getBaseDir('lib') . DS . 'MageUC', Mage::getBaseDir('app') . DS . 'code' . DS . 'local');
            case 'lib':
                //Check all libraries : add an array is you have more than one
                return Mage::getBaseDir('lib') . DS . 'MageUC';
            case 'module':
                //Check all local modules
                return Mage::getBaseDir('app') . DS . 'code' . DS . 'local';
            default:
                if (strpos($toCheck, '#') === 0) {
                    //Case of a specific file, just prefix the path by #
                    return $toCheck;
                }
                //Check a specific module package
                return Mage::getBaseDir('app') . DS . 'code' . DS . 'local' . DS . str_replace('_', DS, $toCheck);
        }
    }
}