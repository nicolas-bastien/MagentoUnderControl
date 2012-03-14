<?php
/**
 * @category   MageUC
 * @author     Nicolas Bastien
 */

/**
 * MageUC Console called via command line
 *
 * @category   MageUC
 * @author     Nicolas Bastien
 */
class MageUC_Console
{
    /**
     * @var array
     */
    private $_tasks = array(
        'cc'   => 'MageUC_Console_Task_ClearCache',
    );

    /**
     * @var MageUC_Console_Task
     */
    private $_taskInstance = null;

    /**
     * Run the console
     *
     * @param array $args command line arguments
     * @return void
     */
    public function run ($args)
    {
        //Handle help and command line description
        $requestedTaskName = isset($args[1]) ? $args[1] : null;
        if (!$requestedTaskName || $requestedTaskName == 'help') {
            $this->printHelp();
            return;
        }

        try {
            $this->_run($args);
        } catch (MageUC_Console_Exception $e) {
            echo $e->getMessage();
            $this->printHelp();
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    /**
     * Run the console
     *
     * @param  array $args command line arguments
     * @return void
     */
    protected function _run($args)
    {
        $taskClass = $this->_getTaskClassFromArgs($args[1]);
        if (!class_exists($taskClass)) {
            throw new MageUC_Console_Exception('Unknown task : ' . $taskClass . PHP_EOL);
        }
        unset($args[0]);
        unset($args[1]);

        $this->_taskInstance = new $taskClass();
        $this->_taskInstance->setArguments($this->prepareArgs($args));

        if ($this->_taskInstance->validate()) {
            ob_start();
            $this->_taskInstance->execute();
            ob_flush();
        } else {
            throw new MageUC_Console_Exception('Invalide arguments for task : ' . $taskClass . PHP_EOL);
        }
    }

    /**
     * Get the name of the task class based on the first argument
     * which is always the task name. Do some inflection to determine the class name
     *
     * @param  array $args       Array of arguments from the cli
     * @return string $taskClass Task class name
     */
    protected function _getTaskClassFromArgs($task)
    {
        if (isset($this->_tasks[$task])) {
            return $this->_tasks[$task];
        }
        //Default MageUC_Console Task
        $taskName  = str_replace('-', ' ', $task);
        $taskName  = ucwords($taskName);
        $taskName  = str_replace(' ', '', $taskName);
        $taskClass = 'MageUC_Console_Task_' . $taskName;

        return $taskClass;
    }

    /**
     * Prepare the raw arguments for execution. Combines with the required and optional argument
     * list in order to determine a complete array of arguments for the task
     *
     * @see Doctrine_Cli::prepareArgs($args)
     *
     * @param  array $args      Array of raw arguments
     * @return array $prepared  Array of prepared arguments
     */
    protected function prepareArgs($args)
    {
        $taskInstance = $this->_taskInstance;

        $args = array_values($args);

        // First lets load populate an array with all the possible arguments. required and optional
        $prepared = array();

        $requiredArguments = $taskInstance->getRequiredArguments();
        foreach ($requiredArguments as $key => $arg) {
            $prepared[$arg] = null;
        }

        $optionalArguments = $taskInstance->getOptionalArguments();
        foreach ($optionalArguments as $key => $arg) {
            $prepared[$arg] = null;
        }

        // Now lets fill in the entered arguments to the prepared array
        $copy = $args;
        foreach ($prepared as $key => $value) {
            if ( ! $value && !empty($copy)) {
                $prepared[$key] = $copy[0];
                unset($copy[0]);
                $copy = array_values($copy);
            }
        }

        return $prepared;
    }

    /**
     * Prints an index of all the available tasks in the CLI instance
     *
     * @return void
     */
    public function printHelp()
    {
        print <<<EOT
---------------------------------
- MageUC command line interface -
---------------------------------

# Available tasks :

- cc : clear cache
EOT;
    }
}