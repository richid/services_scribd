<?php

require_once 'PHPUnit/Framework/TestCase.php';

class Services_Scribd_ScribdTest extends PHPUnit_Framework_TestCase
{
    protected $scribd = null;

    public function setUp()
    {
        $this->scribd = new Services_Scribd('key', 'secret');
    }

    public function testConstructor()
    {
        $this->assertType('Services_Scribd', $this->scribd);
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

    public function testInvalidEndpoint()
    {
        $this->setExpectedException('Services_Scribd_Exception',
                                    'Invalid endpoint requested: invalidEndpoint');

        $this->scribd->docs->invalidEndpoint();
    }

    public function tearDown()
    {
        unset($this->scribd);
    }
}

?>
