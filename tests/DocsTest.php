<?php

require_once 'CommonTest.php';

class Services_Scribd_DocsTest extends Services_Scribd_CommonTest
{
    public function testGetAvailableEndpoints()
    {
        $endpoints = $this->scribd->getAvailableEndpoints();

        $this->assertInternalType('array', $endpoints);
        $this->assertEquals(12, count($endpoints));
        $this->assertEquals($endpoints[0], 'browse');
        $this->assertEquals($endpoints[1], 'changeSettings');
        $this->assertEquals($endpoints[2], 'delete');
        $this->assertEquals($endpoints[3], 'getCategories');
        $this->assertEquals($endpoints[4], 'getConversionStatus');
        $this->assertEquals($endpoints[5], 'getDownloadUrl');
        $this->assertEquals($endpoints[6], 'getList');
        $this->assertEquals($endpoints[7], 'getSettings');
        $this->assertEquals($endpoints[8], 'search');
        $this->assertEquals($endpoints[9], 'upload');
        $this->assertEquals($endpoints[10], 'uploadFromUrl');
    }

    public function testGetCategories()
    {
        $expectedResponse = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<rsp stat="ok">
    <result_set>
        <result>
            <id>86</id>
            <name><![CDATA[Art & Design]]></name>
            <subcategories>
                <subcategory>
                    <id>241</id>
                    <name><![CDATA[Maps]]></name>
                </subcategory>
                <subcategory>
                    <id>231</id>
                    <name><![CDATA[Origami]]></name>
                </subcategory>
            </subcategories>
        </result>
        <result>
            <id>242</id>
            <name><![CDATA[Comics]]></name>
            <subcategories/>
        </result>
        <result>
            <id>243</id>
            <name><![CDATA[Reviews]]></name>
            <subcategories>
                <subcategory>
                    <id>246</id>
                    <name><![CDATA[Art]]></name>
                </subcategory>
            </subcategories>
        </result>
    </result_set>
</rsp>
XML;
        $this->setHTTPResponse($expectedResponse);
        $response = $this->scribd->getCategories();

        $this->assertInstanceOf('SimpleXMLElement', $response);
        $this->assertEquals(3, count($response->result));
        $this->assertEquals(86, (int) $response->result[0]->id);
        $this->assertEquals('Art & Design', (string) $response->result[0]->name);
        $this->assertEquals(2, count($response->result[0]->subcategories->subcategory));
        $this->assertEquals(241, (int) $response->result[0]->subcategories->subcategory[0]->id);
        $this->assertEquals('Maps', $response->result[0]->subcategories->subcategory[0]->name);
        $this->assertEquals(242, (int) $response->result[1]->id);
        $this->assertEquals('Comics', (string) $response->result[1]->name);
        $this->assertEquals(0, count($response->result[1]->subcategories->subcategory));
    }

    public function testGetCategoriesWithoutSubcategories()
    {
        $expectedResponse = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<rsp stat="ok">
    <result_set>
        <result>
            <id>86</id>
            <name><![CDATA[Art & Design]]></name>
        </result>
        <result>
            <id>242</id>
            <name><![CDATA[Comics]]></name>
        </result>
    </result_set>
</rsp>
XML;
        $this->setHTTPResponse($expectedResponse);
        $response = $this->scribd->getCategories(null, false);

        $this->assertInstanceOf('SimpleXMLElement', $response);
        $this->assertEquals(2, count($response->result));
        $this->assertEquals(86, (int) $response->result[0]->id);
        $this->assertEquals('Art & Design', (string) $response->result[0]->name);
        $this->assertEquals(242, (int) $response->result[1]->id);
    }

    public function testChangeSettings()
    {
        $expectedResponse = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<rsp stat="ok">
</rsp>
XML;

        $ids = array(
            1234
        );

        $settings = array(
            'title'       => 'test-title',
            'description' => 'test-desc',
            'invalid'     => true
        );

        $this->setHTTPResponse($expectedResponse);
        $response = $this->scribd->changeSettings($ids, $settings);

        $this->assertInternalType('bool', $response);
        $this->assertEquals(true, $response);
    }

    public function testDelete()
    {
        $expectedResponse = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<rsp stat="ok">
</rsp>
XML;

        $this->setHTTPResponse($expectedResponse);
        $response = $this->scribd->delete(1234);

        $this->assertInternalType('bool', $response);
        $this->assertEquals(true, $response);
    }

    public function testGetConversionStatus()
    {
        $expectedResponse = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<rsp stat="ok">
<conversion_status>DONE</conversion_status>
</rsp>
XML;

        $this->setHTTPResponse($expectedResponse);
        $response = $this->scribd->getConversionStatus(1234);

        $this->assertInternalType('string', $response);
        $this->assertEquals('DONE', $response);
    }

    public function testGetDownloadUrl()
    {
        $expectedResponse = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<rsp stat="ok">
  <download_link>
    <![CDATA[http://d.scribd.com/docs/57vnwv49p43zcz.pdf]]>
  </download_link>
</rsp>
XML;

        $this->setHTTPResponse($expectedResponse);
        $response = $this->scribd->getDownloadUrl(1234);

        $this->assertInternalType('string', $response);
        $this->assertEquals('http://d.scribd.com/docs/57vnwv49p43zcz.pdf',
                            $response);
    }

    public function testGetDownloadUrlInvalidDocType()
    {
        $this->setExpectedException('Services_Scribd_Exception',
                                    'Invalid document type requested: invalid');

        $this->scribd->getDownloadUrl(1234, 'invalid');
    }

    public function testGetList()
    {
        $expectedResponse = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<rsp stat="ok">
  <resultset>
    <result>
      <doc_id>11627281</doc_id>
      <access_key>key</access_key>
      <secret_password>secret</secret_password>
      <title>
        <![CDATA[test-title]]>
      </title>
      <description>
        <![CDATA[test-description]]>
      </description>
      <conversion_status>
        <![CDATA[DONE]]>
      </conversion_status>
      <page_count>26</page_count>
    </result>
  </resultset>
</rsp>
XML;

        $this->setHTTPResponse($expectedResponse);
        $response = $this->scribd->getList();

        $this->assertInstanceOf('SimpleXMLElement', $response);
        $this->assertEquals(11627281, (string) $response->doc_id);
        $this->assertEquals('key', (string) $response->access_key);
        $this->assertEquals('secret', (string) $response->secret_password);
        $this->assertEquals('test-title', trim((string) $response->title));
        $this->assertEquals('test-description',
                            trim( (string) $response->description));
        $this->assertEquals('DONE',
                            trim((string) $response->conversion_status));
        $this->assertEquals(26, (int) $response->page_count);
    }
    
    public function testGetSettings()
    {
        $expectedResponse = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<rsp stat="ok">
  <doc_id>1234</doc_id>
  <title>
    <![CDATA[title]]>
  </title>
  <description>
    <![CDATA[desc]]>
  </description>
  <access>private</access>
  <license>by-nc</license>
  <tags>
    <![CDATA[]]>
  </tags>
  <show_ads>default</show_ads>
  <access_key>key</access_key>
  <thumbnail_url>
    <![CDATA[http://s3.amazonaws.com/scribd_images/public/images/uploaded/8134706/15102631_thumbnail.jpg]]>
  </thumbnail_url>
  <secret_password>secret</secret_password>
  <page_count>360</page_count>
  <author></author>
  <publisher></publisher>
  <when_published></when_published>
  <edition></edition>
</rsp>
XML;

        $this->setHTTPResponse($expectedResponse);
        $response = $this->scribd->getSettings(1234);
    
        $this->assertInstanceOf('SimpleXMLElement', $response);
        $this->assertEquals(1234, (string) $response->doc_id);
        $this->assertEquals('title', trim((string) $response->title));
        $this->assertEquals('desc', trim((string) $response->description));
        $this->assertEquals('private', (string) $response->access);
        $this->assertEquals('by-nc', (string) $response->license);
        $this->assertEquals('', trim((string) $response->tags));
        $this->assertEquals('default', (string) $response->show_ads);
        $this->assertEquals('key', (string) $response->access_key);
        $this->assertEquals('http://s3.amazonaws.com/scribd_images/public/images/uploaded/8134706/15102631_thumbnail.jpg',
                            trim((string) $response->thumbnail_url));
        $this->assertEquals('secret', (string) $response->secret_password);
        $this->assertEquals(360, (string) $response->page_count);
        $this->assertEquals('', (string) $response->author);
        $this->assertEquals('', (string) $response->publisher);
        $this->assertEquals('', (string) $response->when_published);
        $this->assertEquals('', (string) $response->edition);
    }

    public function testSearch()
    {
        $expectedResponse = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<rsp stat="ok">
  <result_set totalResultsAvailable="3" totalResultsReturned="2" firstResultPosition="2" list="true">
    <result>
      <doc_id>1234</doc_id>
      <access_key>key1</access_key>
      <title>
        <![CDATA[title1]]>
      </title>
      <description>
        <![CDATA[desc1]]>
      </description>
      <tags>
        <![CDATA[]]>
      </tags>
      <license>by-nc</license>
      <thumbnail_url>http://i.scribd.com/public/images/uploaded/11573567/beiuWP1blotH41c_thumbnail.jpeg</thumbnail_url>
      <page_count>26</page_count>
    </result>
    <result>
      <doc_id>1235</doc_id>
      <access_key>key2</access_key>
      <title>
        <![CDATA[title2]]>
      </title>
      <description>
        <![CDATA[desc2]]>
      </description>
      <tags>
        <![CDATA[]]>
      </tags>
      <license>by-nc</license>
      <thumbnail_url>http://i.scribd.com/public/images/uploaded/11574926/xXwPoumfND_thumbnail.jpeg</thumbnail_url>
      <page_count>26</page_count>
    </result>
  </result_set>
</rsp>
XML;

        $this->setHTTPResponse($expectedResponse);
        $response = $this->scribd->search('test');

        $this->assertInternalType('array', $response);
        $this->assertArrayHasKey('totalResultsAvailable', $response);
        $this->assertArrayHasKey('totalResultsReturned', $response);
        $this->assertArrayHasKey('firstResultPosition', $response);
        $this->assertArrayHasKey('list', $response);
        $this->assertArrayHasKey('results', $response);
        $this->assertEquals(3, $response['totalResultsAvailable']);
        $this->assertEquals(2, $response['totalResultsReturned']);
        $this->assertEquals(2, $response['firstResultPosition']);
        $this->assertEquals('true', $response['list']);
        $this->assertArrayHasKey(0, $response['results']);
        $this->assertArrayHasKey(1, $response['results']);
        $this->assertInstanceOf('SimpleXMLElement', $response['results'][0]);
        $this->assertInstanceOf('SimpleXMLElement', $response['results'][1]);
        $this->assertEquals(1234, (string) $response['results'][0]->doc_id);
        $this->assertEquals('title1', trim((string) $response['results'][0]->title));
        $this->assertEquals('desc1', trim((string) $response['results'][0]->description));
        $this->assertEquals('by-nc', (string) $response['results'][0]->license);
        $this->assertEquals('', trim((string) $response['results'][0]->tags));
        $this->assertEquals('key1', (string) $response['results'][0]->access_key);
        $this->assertEquals('http://i.scribd.com/public/images/uploaded/11573567/beiuWP1blotH41c_thumbnail.jpeg',
                            trim((string) $response['results'][0]->thumbnail_url));
        $this->assertEquals(26, (string) $response['results'][0]->page_count);
        $this->assertEquals(1235, (string) $response['results'][1]->doc_id);
        $this->assertEquals('title2', trim((string) $response['results'][1]->title));
        $this->assertEquals('desc2', trim((string) $response['results'][1]->description));
        $this->assertEquals('by-nc', (string) $response['results'][1]->license);
        $this->assertEquals('', trim((string) $response['results'][1]->tags));
        $this->assertEquals('key2', (string) $response['results'][1]->access_key);
        $this->assertEquals('http://i.scribd.com/public/images/uploaded/11574926/xXwPoumfND_thumbnail.jpeg',
                            trim((string) $response['results'][1]->thumbnail_url));
        $this->assertEquals(26, (string) $response['results'][1]->page_count);
    }

    public function testSearchInvalidScope()
    {
        $this->setExpectedException('Services_Scribd_Exception',
                                    'Invalid scope requested: invalid');

        $this->scribd->search('test', 'invalid');
    }

    public function testUpload()
    {
        $expectedResponse = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<rsp stat="ok">
  <doc_id>1376</doc_id>
  <access_key>key1</access_key>
</rsp>
XML;

        $this->setHTTPResponse($expectedResponse);
        $response = $this->scribd->upload(__FILE__, 'txt');
        
        $this->assertInstanceOf('SimpleXMLElement', $response);
        $this->assertEquals('1376', (string) $response->doc_id);
        $this->assertEquals('key1', (string) $response->access_key);
    }

    public function testUploadInvalidDocType()
    {
        $this->setExpectedException('Services_Scribd_Exception',
                                    'Invalid document type requested: invalid');

        $this->scribd->upload(__FILE__, 'invalid');
    }

    public function testUploadInvalidFile()
    {
        $this->setExpectedException('Services_Scribd_Exception',
                                    'The selected file was not found');

        $this->scribd->upload('./file-that-does-not-exist', 'txt');
    }

    public function testUploadFromUrl()
    {
        $expectedResponse = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<rsp stat="ok">
  <doc_id>1223</doc_id>
  <access_key>key1</access_key>
</rsp>
XML;

        $this->setHTTPResponse($expectedResponse);
        $response = $this->scribd->uploadFromUrl('http://d.scribd.com/docs/5xttn3dmcm0gkxshomn.txt', 'txt');
        
        $this->assertInstanceOf('SimpleXMLElement', $response);
        $this->assertEquals('1223', (string) $response->doc_id);
        $this->assertEquals('key1', (string) $response->access_key);
    }

    public function testUploadFromUrlInvalidDocType()
    {
        $this->setExpectedException('Services_Scribd_Exception',
                                    'Invalid document type requested: invalid');

        $this->scribd->uploadFromUrl('http://d.scribd.com/docs/5xttn3dmcm0gkxshomn.txt',
                                     'invalid');
    }

    public function testUploadThumb()
    {
        $expectedResponse = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<rsp stat="ok">
</rsp>
XML;

        $this->setHTTPResponse($expectedResponse);
        $response = $this->scribd->uploadThumb(__FILE__, 1234);

        $this->assertInternalType('bool', $response);
        $this->assertTrue($response);
    }

    public function testUploadThumbInvalidFile()
    {
        $this->setExpectedException('Services_Scribd_Exception',
                                    'The selected file was not found');

        $this->scribd->uploadThumb('./file-that-does-not-exist', 'txt');
    }

    public function testMalformedXMLResponse()
    {
        $expectedResponse = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<rsp stat="ok">
  <doc_id>1223</invalid>
</rsp>
XML;

        $this->setExpectedException('Services_Scribd_Exception',
                                    'Could not parse XML response');

        $this->setHTTPResponse($expectedResponse);
        $this->scribd->getList();
    }

    public function testXMLErrorResponse()
    {
        $expectedResponse = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<rsp stat="fail">
  <error code="401" message="Unauthorized"/>
</rsp>
XML;

        $this->setExpectedException('Services_Scribd_Exception',
                                    'Unauthorized',
                                    401);

        $this->setHTTPResponse($expectedResponse);
        $this->scribd->getList();
    }

    public function testInvalidHTTPResponseCode()
    {
        $this->setExpectedException('Services_Scribd_Exception',
                                    'Invalid response returned from server',
                                    404);

        $this->setHTTPResponse('', 'HTTP/1.1 404 Not Found');
        $this->scribd->getList();
    }

    public function testUnsignedRequest()
    {
        $expectedResponse = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<rsp stat="ok">
</rsp>
XML;

        $account = new Services_Scribd_Account('key');
        $this->scribd->setAccount($account);

        $this->setHTTPResponse($expectedResponse);
        $response = $this->scribd->delete(1234);

        $this->assertInternalType('bool', $response);
        $this->assertEquals(true, $response);
    }
}

?>
