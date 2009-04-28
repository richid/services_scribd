<?php

require_once 'PHPUnit/Framework/TestSuite.php';
require_once 'Services/Scribd.php';
require_once 'CommonTest.php';
require_once 'DocsTest.php';
require_once 'UserTest.php';
require_once 'ScribdTest.php';

class Services_Scribd_AllTests extends PHPUnit_Framework_TestSuite
{
    public static function suite()
    {
        $suite = new PHPUnit_Framework_TestSuite('Services_Scribd Unit Test Suite');
        $suite->addTestSuite('Services_Scribd_CommonTest');
        $suite->addTestSuite('Services_Scribd_DocsTest');
        $suite->addTestSuite('Services_Scribd_UserTest');
        $suite->addTestSuite('Services_Scribd_ScribdTest');

        return $suite;
    }
}

?>
