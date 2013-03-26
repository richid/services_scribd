<?php

require_once 'CommonTest.php';

class Services_Scribd_ThumbnailTest extends Services_Scribd_CommonTest
{
    public function testGetAvailableEndpoints()
    {
        $endpoints = $this->scribd->getAvailableEndpoints();

        $this->assertInternalType('array', $endpoints);
        $this->assertArrayHasKey(0, $endpoints);
        $this->assertEquals($endpoints[0], 'get');
    }

    public function testChangeSettings()
    {
        $expectedResponse = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<rsp stat="ok">
    <thumbnail_url>http://imgv2-3.scribdassets.com/img/word_document/1234/64x64/b0b654c91f/1364265336</thumbnail_url>
</rsp>
XML;

        $this->setHTTPResponse($expectedResponse);
        $response = $this->scribd->get(1234, 64, 64);

        $this->assertInternalType('string', $response);
        $this->assertEquals('http://imgv2-3.scribdassets.com/img/word_document/1234/64x64/b0b654c91f/1364265336', $response);
    }
}
