<?php

require_once 'PHPUnit/Framework/TestCase.php';
require_once 'Services/Scribd/Account.php';

class Services_Scribd_AccountTest extends PHPUnit_Framework_TestCase
{
    protected $account = null;

    public function setUp()
    {
        $account                = new Services_Scribd_Account('key', 'secret');
        $account->myUserId      = 12345;
        $account->apiSessionKey = 'sess_key';
        
        $this->account = $account;
    }

    public function testValues()
    {
        $this->assertEquals('key', $this->account->apiKey);
        $this->assertEquals('secret', $this->account->apiSecret);
        $this->assertEquals(12345, $this->account->myUserId);
        $this->assertEquals('sess_key', $this->account->apiSessionKey);
    }

    public function testInvalidValueIsNull()
    {
        $this->assertEquals(null, $this->account->wrong);
    }

    public function tearDown()
    {
        unset($this->account);
    }
}

?>
