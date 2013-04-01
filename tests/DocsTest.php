<?php

require_once 'CommonTest.php';

class Services_Scribd_DocsTest extends Services_Scribd_CommonTest
{
    public function testGetAvailableEndpoints()
    {
        $endpoints = $this->scribd->getAvailableEndpoints();

        $this->assertInternalType('array', $endpoints);
        $this->assertEquals(13, count($endpoints));
        $this->assertTrue(in_array('browse', $endpoints));
        $this->assertTrue(in_array('changeSettings', $endpoints));
        $this->assertTrue(in_array('delete', $endpoints));
        $this->assertTrue(in_array('featured', $endpoints));
        $this->assertTrue(in_array('getCategories', $endpoints));
        $this->assertTrue(in_array('getConversionStatus', $endpoints));
        $this->assertTrue(in_array('getDownloadUrl', $endpoints));
        $this->assertTrue(in_array('getList', $endpoints));
        $this->assertTrue(in_array('getSettings', $endpoints));
        $this->assertTrue(in_array('search', $endpoints));
        $this->assertTrue(in_array('upload', $endpoints));
        $this->assertTrue(in_array('uploadFromUrl', $endpoints));
    }

    public function testBrowse()
    {
        $expectedResponse = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<rsp stat="ok">
    <result_set totalResultsAvailable="9109" totalResultsReturned="1" firstResultPosition="1" list="true">
        <result>
            <doc_id>112033847</doc_id>
            <access_key>key-1dii4ec212x6x5sqqswn</access_key>
            <title><![CDATA[50 things they never told you about being a chef]]></title>
            <description><![CDATA[Uploaded from Google Docs]]></description>
            <tags><![CDATA[]]></tags>
            <license>by-nc</license>
            <thumbnail_url>http://imgv2-3.scribdassets.com/img/word_document/112033847/111x142/42383c3de0/1351981884</thumbnail_url>
            <page_count>3</page_count>
            <download_formats>pdf,docx,txt</download_formats>
            <reads>129907</reads>
            <uploaded_by><![CDATA[Kloiii]]></uploaded_by>
            <uploader_id>93600737</uploader_id>
            <when_uploaded>2012-11-03T22:27:14+00:00</when_uploaded>
            <when_updated>2013-03-29T21:53:13+00:00</when_updated>
        </result>
    </result_set>
</rsp>
XML;

        $this->setHTTPResponse($expectedResponse);
        $response = $this->scribd->browse(1, 1, 86);

        $this->assertInstanceOf('SimpleXMLElement', $response);
        $this->assertEquals(112033847, (int) $response->result->doc_id);
        $this->assertEquals('50 things they never told you about being a chef', (string) $response->result->title);
        $this->assertEquals('by-nc', $response->result->license);
        $this->assertEquals(129907, (int) $response->result->reads);
        $this->assertEquals('2012-11-03T22:27:14+00:00', $response->result->when_uploaded);
    }

    public function testFeatured()
    {
        $expectedResponse = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<rsp stat="ok">
    <result_set totalResultsAvailable="11233" totalResultsReturned="20" firstResultPosition="1" list="true">
        <result>
            <doc_id>127126759</doc_id>
            <access_key>key-1cv4k40hbx4irwxyhoo2</access_key>
            <title><![CDATA[The Invention of World Religions: Or, How European Universalism Was Preserved in the Language of Pluralism]]></title>
            <description><![CDATA[]]></description>
            <tags><![CDATA[religion.,religions.,europe  religion.,universalism.,europe  religion  history.]]></tags>
            <license>c</license>
            <thumbnail_url>http://imgv2-2.scribdassets.com/img/word_document/127126759/111x142/ff642c988d/1361781233</thumbnail_url>
            <page_count>377</page_count>
            <download_formats></download_formats>
            <price>25.0</price>
            <reads>1614</reads>
            <uploaded_by><![CDATA[UChicagoPress]]></uploaded_by>
            <uploader_id>11693806</uploader_id>
            <when_uploaded>2013-02-25T08:18:02+00:00</when_uploaded>
            <when_updated>2013-03-29T22:09:55+00:00</when_updated>
        </result>
    </result_set>
</rsp>
XML;

        $this->setHTTPResponse($expectedResponse);
        $response = $this->scribd->featured();

        $this->assertInstanceOf('SimpleXMLElement', $response);
        $this->assertEquals(127126759, (int) $response->result->doc_id);
        $this->assertEquals('c', $response->result->license);
        $this->assertEquals(1614, (int) $response->result->reads);
        $this->assertEquals('2013-02-25T08:18:02+00:00', $response->result->when_uploaded);
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

    public function testGetCategoriesSpecificCategory()
    {
        $expectedResponse = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<rsp stat="ok">
    <result_set>
        <result>
            <id>241</id>
            <name><![CDATA[Maps]]></name>
            <subcategories/>
        </result>
        <result>
            <id>251</id>
            <name><![CDATA[Sheet Music]]></name>
            <subcategories/>
        </result>
        <result>
            <id>231</id>
            <name><![CDATA[Origami]]></name>
            <subcategories/>
        </result>
    </result_set>
</rsp>
XML;
        $this->setHTTPResponse($expectedResponse);
        $response = $this->scribd->getCategories(86);

        $this->assertInstanceOf('SimpleXMLElement', $response);
        $this->assertEquals(3, count($response->result));
        $this->assertEquals(241, (int) $response->result[0]->id);
        $this->assertEquals('Maps', $response->result[0]->name);
        $this->assertEquals(251, (int) $response->result[1]->id);
        $this->assertEquals('Sheet Music', $response->result[1]->name);
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
    <result_set totalResultsAvailable="324805" totalResultsReturned="2" firstResultPosition="2" list="true">
        <result>
            <doc_id>86589367</doc_id>
            <access_key>key-1zh4pue8w56cpghsl2ca</access_key>
            <title><![CDATA[beer]]></title>
            <description><![CDATA[beer]]></description>
            <tags><![CDATA[Beer,Ale]]></tags>
            <license>by-nc</license>
            <thumbnail_url>http://imgv2-1.scribdassets.com/img/word_document/86589367/111x142/71127fd85d/1355848230</thumbnail_url>
            <page_count>6</page_count>
            <download_formats>pdf,txt</download_formats>
            <reads>66</reads>
            <uploaded_by><![CDATA[matthew_priest_5]]></uploaded_by>
            <uploader_id>131333044</uploader_id>
            <when_uploaded>2012-03-24T19:15:17+00:00</when_uploaded>
            <when_updated>2012-03-24T19:15:23+00:00</when_updated>
        </result>
        <result>
            <doc_id>30088273</doc_id>
            <access_key>key-rt3kk85xb0wixfob9wl</access_key>
            <title><![CDATA[Beer]]></title>
            <description><![CDATA[Beer ]]></description>
            <tags><![CDATA[Beer,wine,wrapareceipe]]></tags>
            <license>c</license>
            <thumbnail_url>http://imgv2-2.scribdassets.com/img/word_document/30088273/111x142/4a7d70cbf3/1342028251</thumbnail_url>
            <page_count>44</page_count>
            <download_formats>ppt</download_formats>
            <reads>1304</reads>
            <uploaded_by><![CDATA[Hotelierstudy]]></uploaded_by>
            <uploader_id>26011936</uploader_id>
            <when_uploaded>2010-04-17T20:50:46+00:00</when_uploaded>
            <when_updated>2013-03-30T14:50:35+00:00</when_updated>
        </result>
    </result_set>
</rsp>
XML;

        $this->setHTTPResponse($expectedResponse);
        $response = $this->scribd->search('beer', 2, 0, 2);

        $this->assertInstanceOf('SimpleXMLElement', $response);
        $this->assertEquals(86589367, (int) $response->result->doc_id);
        $this->assertEquals('beer', $response->result->title);
        $this->assertEquals('by-nc', $response->result->license);
        $this->assertEquals(66, (int) $response->result->reads);
        $this->assertEquals('2012-03-24T19:15:17+00:00', $response->result->when_uploaded);
        $this->assertEquals(30088273, (int) $response->result[1]->doc_id);
        $this->assertEquals('Beer', $response->result[1]->title);
        $this->assertEquals('c', $response->result[1]->license);
        $this->assertEquals(1304, (int) $response->result[1]->reads);
        $this->assertEquals('2010-04-17T20:50:46+00:00', $response->result[1]->when_uploaded);

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
