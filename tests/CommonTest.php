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

    public function testConstructor()
    {
        $scribd = new Services_Scribd('key', 'secret');

        $this->assertType('Services_Scribd', $scribd);
        $this->assertEquals('key', Services_Scribd::$apiKey);
        $this->assertEquals('secret', Services_Scribd::$apiSecret);
    }

    public function testValidDriver()
    {
        $this->assertType('Services_Scribd', $this->scribd->docs);
        $this->assertType('Services_Scribd_Common', $this->scribd->docs);
        $this->assertType('Services_Scribd_Docs', $this->scribd->docs);
    }

    public function testInvalidDriver()
    {
        $this->setExpectedException('Services_Scribd_Exception',
                                    'Invalid driver provided: invalid');

        $this->scribd->invalid;
    }

    public function testInvalidClassName()
    {
        $this->setExpectedException('Services_Scribd_Exception',
                                    'Unable to load driver: invalidClass');

        $this->scribd->invalidClass;
    }

    public function testInvalidEndpoint()
    {
        $this->setExpectedException('Services_Scribd_Exception',
                                    'Invalid endpoint requested: invalidEndpoint');

        $this->scribd->docs->invalidEndpoint();
    }

    public function testInvalidHTTPMethod()
    {
        $this->markTestIncomplete();
        $this->setExpectedException('Services_Scribd_Exception',
                                    'Invalid HTTP method: invalid');

        $scribd = new Services_Scribd('key', 'secret');

        $scribd->call('docs.getList', 'PUT');
    }

    protected function tearDown()
    {
        unset($this->scribd);
    }
}

?>
