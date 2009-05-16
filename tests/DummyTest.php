<?php

require_once 'CommonTest.php';

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
}

?>
