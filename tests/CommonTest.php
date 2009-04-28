<?php

require_once 'PHPUnit/Framework/TestCase.php';
require_once 'Services/Scribd.php';

abstract class Services_Scribd_CommonTest extends PHPUnit_Framework_TestCase
{
    protected $scribd = null;

    protected $mockMethods = array(
        'sendRequest'
    );

    public function setUp()
    {
        $class = str_replace('Test', '', get_class($this));
        include_once str_replace('_', '/', $class . '.php');

        Services_Scribd::$apiKey        = 'key';
        Services_Scribd::$apiSecret     = 'secret';
        Services_Scribd::$myUserId      = 12345;
        Services_Scribd::$apiSessionKey = 'sess_key';

        $this->scribd = $this->getMock($class, $this->mockMethods);
    }

    public function mockSendRequest($response)
    {   
        $this->scribd->expects($this->any())
                     ->method('sendRequest')
                     ->will($this->returnValue($response));
    }

    public function tearDown()
    {
        unset($this->scribd);
    }
}

?>
