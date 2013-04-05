<?php

require_once 'CommonTest.php';

class Services_Scribd_CollectionsTest extends Services_Scribd_CommonTest
{
    public function testGetAvailableEndpoints()
    {
        $endpoints = $this->scribd->getAvailableEndpoints();

        $this->assertInternalType('array', $endpoints);
        $this->assertEquals(2, count($endpoints));
        $this->assertTrue(in_array('addDoc', $endpoints));
        $this->assertTrue(in_array('create', $endpoints));
    }

    public function testAddDoc()
    {
        $expectedResponse = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<rsp stat="ok"></rsp>
XML;

        $this->setHTTPResponse($expectedResponse);
        $response = $this->scribd->addDoc(123, 345);

        $this->assertInternalType('bool', $response);
        $this->assertTrue($response);
    }


    public function testCreate()
    {
        $expectedResponse = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<rsp stat="ok">
    <collection_id>4214</collection_id>
    <privacy_type>public</privacy_type>
</rsp>
XML;

        $this->setHTTPResponse($expectedResponse);
        $response = $this->scribd->create("my_coll", "desc", "public");

        $this->assertInternalType('int', $response);
        $this->assertEquals(4214, $response);
    }
}