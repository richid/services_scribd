<?php

require_once 'CommonTest.php';

class Services_Scribd_CollectionsTest extends Services_Scribd_CommonTest
{
    public function testGetAvailableEndpoints()
    {
        $endpoints = $this->scribd->getAvailableEndpoints();

        $this->assertInternalType('array', $endpoints);
        $this->assertEquals(5, count($endpoints));
        $this->assertTrue(in_array('addDoc', $endpoints));
        $this->assertTrue(in_array('create', $endpoints));
        $this->assertTrue(in_array('delete', $endpoints));
        $this->assertTrue(in_array('getList', $endpoints));
        $this->assertTrue(in_array('update', $endpoints));
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
        $response = $this->scribd->create('my_coll', 'desc', 'public');

        $this->assertInternalType('int', $response);
        $this->assertEquals(4214, $response);
    }

    public function testDelete()
    {
        $expectedResponse = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<rsp stat="ok"></rsp>
XML;

        $this->setHTTPResponse($expectedResponse);
        $response = $this->scribd->delete(1234);

        $this->assertInternalType('bool', $response);
        $this->assertTrue($response);
    }

    public function getGetList()
    {
        $expectedResponse = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<rsp stat="ok">
    <resultset list="true">
        <result>
            <collection_id>1234</collection_id>
            <collection_name>my_coll_1</collection_name>
            <description><![CDATA[my_desc_1]]></description>
            <doc_count>0</doc_count>
        </result>
        <result>
            <collection_id>5678</collection_id>
            <collection_name>my_coll_2</collection_name>
            <description><![CDATA[my_desc_2]]></description>
            <doc_count>1</doc_count>
        </result>
    </resultset>
</rsp>
XML;

        $this->setHTTPResponse($expectedResponse);
        $response = $this->scribd->getList();

        $this->assertInstanceOf('SimpleXMLElement', $response);
        $this->assertEquals(1234, (int) $response->result->collection_id);
        $this->assertEquals('my_coll_1', (string) $response->result->collection_name);
        $this->assertEquals('my_desc_1', $response->result->description);
        $this->assertEquals(0, (int) $response->result->doc_count);
        $this->assertEquals(5678, (int) $response->result[1]->collection_id);
        $this->assertEquals('my_coll_2', (string) $response->result[1]->collection_name);
        $this->assertEquals('my_desc_2', $response->result[1]->description);
        $this->assertEquals(1, (int) $response->result[1]->doc_count);
    }

    public function testUpdate()
    {
        $expectedResponse = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<rsp stat="ok">
    <collection_id>1234</collection_id>
    <changed>
        <description>my_desc</description>
        <privacy_type>public</privacy_type>
    </changed>
</rsp>
XML;

        $this->setHTTPResponse($expectedResponse);
        $response = $this->scribd->update(1234, null, 'my_desc', 'public');

        $this->assertInternalType('bool', $response);
        $this->assertTrue($response);
    }
}
