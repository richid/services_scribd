<?php

require_once 'CommonTest.php';

class Services_Scribd_CollectionsTest extends Services_Scribd_CommonTest
{
    public function testGetAvailableEndpoints()
    {
        $endpoints = $this->scribd->getAvailableEndpoints();

        $this->assertInternalType('array', $endpoints);
        $this->assertEquals(7, count($endpoints));
        $this->assertTrue(in_array('addDoc', $endpoints));
        $this->assertTrue(in_array('create', $endpoints));
        $this->assertTrue(in_array('delete', $endpoints));
        $this->assertTrue(in_array('getList', $endpoints));
        $this->assertTrue(in_array('removeDoc', $endpoints));
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

    public function testGetList()
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

    public function testListDocs()
    {
        $expectedResponse = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<rsp stat="ok">
    <result_set firstResultPosition="1" totalResultsAvailable="2" list="true" totalResultsReturned="2">
        <result>
            <doc_id>1234</doc_id>
            <access_key>key-1234</access_key>
            <title><![CDATA[my_doc_1]]></title>
            <description><![CDATA[my_desc_1]]></description>
            <tags><![CDATA[my_tag_1]]></tags>
            <license>by-nc</license>
            <thumbnail_url>http://imgv2-3.scribdassets.com/img/word_document/1234</thumbnail_url>
            <page_count>26</page_count>
            <download_formats>pdf,txt</download_formats>
            <reads>289</reads>
            <uploaded_by><![CDATA[user_1]]></uploaded_by>
            <uploader_id>1</uploader_id>
            <when_uploaded>2010-01-05T09:00:04+00:00</when_uploaded>
            <when_updated>2010-01-04T21:00:18+00:00</when_updated>
        </result>
        <result>
            <doc_id>5678</doc_id>
            <access_key>key-5678</access_key>
            <title><![CDATA[my_doc_2]]></title>
            <description><![CDATA[my_desc_2]]></description>
            <tags><![CDATA[my_tag_2]]></tags>
            <license>by-nc</license>
            <thumbnail_url>http://imgv2-3.scribdassets.com/img/word_document/5678></thumbnail_url>
            <page_count>7</page_count>
            <download_formats>pdf,txt</download_formats>
            <reads>2</reads>
            <uploaded_by><![CDATA[user_2]]></uploaded_by>
            <uploader_id>2</uploader_id>
            <when_uploaded>2013-03-26T03:38:41+00:00</when_uploaded>
            <when_updated>2013-03-26T03:38:48+00:00</when_updated>
        </result>
    </result_set>
</rsp>
XML;

        $this->setHTTPResponse($expectedResponse);
        $response = $this->scribd->listDocs(1234);

        $this->assertInstanceOf('SimpleXMLElement', $response);
        $this->assertEquals(1234, (int) $response->result->doc_id);
        $this->assertEquals('my_doc_1', (string) $response->result->title);
        $this->assertEquals('my_desc_1', $response->result->description);
        $this->assertEquals('my_tag_1', $response->result->tags);
        $this->assertEquals(289, (int) $response->result->reads);
        $this->assertEquals(5678, (int) $response->result[1]->doc_id);
        $this->assertEquals('my_doc_2', (string) $response->result[1]->title);
        $this->assertEquals('my_desc_2', $response->result[1]->description);
        $this->assertEquals('my_tag_2', $response->result[1]->tags);
        $this->assertEquals(2, (int) $response->result[1]->reads);
    }

    public function testRemoveDoc()
    {
        $expectedResponse = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<rsp stat="ok"></rsp>
XML;

        $this->setHTTPResponse($expectedResponse);
        $response = $this->scribd->removeDoc(123, 345);

        $this->assertInternalType('bool', $response);
        $this->assertTrue($response);
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
