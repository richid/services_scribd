<?php

require_once 'CommonTest.php';
require_once 'HTTP/Request2/Exception.php';

class Services_Scribd_DummyTest extends Services_Scribd_CommonTest
{
    public function testInvalidHTTPMethod()
    {
        $this->setExpectedException('Services_Scribd_Exception',
                                    'Invalid HTTP method: HEAD');

        $this->scribd->sendHeadRequest();
    }

    public function testAlreadySetSignature()
    {
        $this->setExpectedException('Services_Scribd_Exception',
                                    'Invalid response returned from server');

        $this->scribd->setAPISignatureManually();
    }

    public function testSendRequestException()
    {
        $this->setExceptionResponse('Nope');
        $this->setExpectedException('Services_Scribd_Exception', 'Nope');
        $this->scribd->sendRequestThrowsException();
    }
}

?>
