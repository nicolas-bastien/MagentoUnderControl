===========================
    MagentoUnderControl
===========================

A collection of tool for Magento developer


# Magento Developer Bar
===========================

#Installation :

Put the lib/MageUC folder and the css stylesheet in your project.

Modify your Mage.php file to instanciate MageUC_App instead of Mage_Core_Model_App.

Add a dispatchEvent at the beginning of the Mage::log() method like this :

Mage::dispatchEvent('mage_log', array('message' => $message, 'level' => $level, 'file' => $file));
Your developer bar is ready to run.
-> More informations : http://nicolas-bastien.com/en/magento/magento-under-control/developper-bar


# MageUC Console > Command line tool
=====================================

Add mageuc and mageuc.bat for Windows user

>> mageuc help

-> More informations : http://nicolas-bastien.com/en/magento/magento-under-control/console



... and more comming...


[![Bitdeli Badge](https://d2weczhvl823v0.cloudfront.net/nicolas-bastien/magentoundercontrol/trend.png)](https://bitdeli.com/free "Bitdeli Badge")

