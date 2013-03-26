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
        $account = $this->scribd->getAccount();

        $this->assertInstanceOf('Services_Scribd', $this->scribd);
        $this->assertEquals('key', $account->apiKey);
        $this->assertEquals('secret', $account->apiSecret);
    }

    public function testConstructorWithAccountObject()
    {
        $account = new Services_Scribd_Account('key', 'secret');
        $scribd  = new Services_Scribd($account);
        $account = $scribd->getAccount();

        $this->assertInstanceOf('Services_Scribd', $scribd);
        $this->assertEquals('key', $account->apiKey);
        $this->assertEquals('secret', $account->apiSecret);
    }

    public function testValidDriver()
    {
        $this->assertInstanceOf('Services_Scribd', $this->scribd->docs);
        $this->assertInstanceOf('Services_Scribd_Common', $this->scribd->docs);
        $this->assertInstanceOf('Services_Scribd_Docs', $this->scribd->docs);
    }

    public function testInvalidDriver()
    {
        $this->setExpectedException('Services_Scribd_Exception',
                                    'Invalid driver provided: invalid');

        $this->scribd->invalid;
    }

    public function testInvalidClassInDriver()
    {
        $this->setExpectedException('Services_Scribd_Exception',
                                    'Unable to load driver: Empty');

        $this->scribd->empty;
    }

    public function testInvalidEndpoint()
    {
        $this->setExpectedException('Services_Scribd_Exception',
                                    'Invalid endpoint requested: invalidEndpoint');

        $this->scribd->docs->invalidEndpoint();
    }

    public function testSetAndGetAccount()
    {
        $account = new Services_Scribd_Account('newKey', 'newSecret');

        $this->scribd->setAccount($account);

        $account = $this->scribd->getAccount();

        $this->assertEquals('newKey', $account->apiKey);
        $this->assertEquals('newSecret', $account->apiSecret);
    }

    public function tearDown()
    {
        unset($this->scribd);
    }
}

?>
