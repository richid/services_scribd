<?php

require_once 'PHPUnit/Framework/TestCase.php';
require_once 'Services/Scribd.php';
require_once 'HTTP/Request2/Adapter/Mock.php';
require_once 'HTTP/Request2/Response.php';

abstract class Services_Scribd_CommonTest extends PHPUnit_Framework_TestCase
{
    protected $scribd = null;
    protected $adapter = null;

    public function setUp()
    {
        $class = str_replace('Test', '', get_class($this));
        include_once str_replace('_', '/', $class . '.php');

        $account                = new Services_Scribd_Account('key', 'secret');
        $account->myUserId      = '12345';
        $account->apiSessionKey = 'sess_key';

        $this->adapter = new HTTP_Request2_Adapter_Mock();

        $this->scribd = new $class($account);
        $this->scribd->setRequestAdapter($this->adapter);
    }

    public function setHTTPResponse($expectedResponse, $status = 'HTTP/1.1 200 OK')
    {
        $response = new HTTP_Request2_Response($status);
        $response->appendBody($expectedResponse);
        $this->adapter->addResponse($response);
    }

    public function setExceptionResponse($message = null)
    {
        $response = new HTTP_Request2_Exception($message);
        $this->adapter->addResponse($response);
    }

    public function tearDown()
    {
        unset($this->scribd);
        unset($this->adapter);
    }
}

?>
