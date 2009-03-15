<?php

require_once 'Services/Scribd.php';
require_once 'Services/Scribd/Exception.php';

class Services_Scribd_Common extends Services_Scribd
{
    /**
     * An array of arguments to send to the API
     *
     * @var array
     */
    protected $arguments = array();

    public function __construct()
    {
        self::$apiKey = parent::$apiKey;
    }

    public function getAvailableEndpoints()
    {
        return $this->validEndpoints;
    }

    public function signRequest()
    {
        if ($this->apiSignature === null) {
            return;
        }

        $sortedArguments = sort($this->arguments);

        $sig = $this->apiSignature . implode('', $sortedArguments);

        $this->arguments['api_sig'] = $sig;
    }

    protected function buildRequestURI($endpoint)
    {
        $requiredArguments = array(
            'method'  => $endpoint,
            'api_key' => Services_Scribd::$apiKey
        );

        $this->arguments = array_merge($requiredArguments, $this->arguments);

        $qsa = http_build_query($this->arguments);

        return Services_Scribd::API . '?' . $qsa;
    }

    //TODO: See about validating endpoints using the validEndpoints array
    protected function sendRequest($endpoint, $method = 'GET')
    {
        $uri = $this->buildRequestURI($endpoint);
var_dump($uri);
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $uri);
        //curl_setopt($curl, CURLOPT_POST, false);
        //curl_setopt($curl, CURLOPT_POSTFIELDS, $args);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_TIMEOUT, Services_Scribd::$timeout);
        $response = curl_exec($curl);

        if (curl_errno($curl)) {
            throw new Services_Scribd_Exception();
        }

        curl_close($curl);

        $this->reset();
        
        return $this->formatResponse($response);
    }

    protected function formatResponse($response)
    {
        $xml = simplexml_load_string($response);
        if (!$xml instanceof SimpleXmlElement) {
            throw new Services_Scribd_Exception(
                'Could not parse XML response'
            );
        }
        
        if ( (string) $xml['stat'] != 'ok') {
            $code    = (int) $xml->error['code'];
            $message = (string) $xml->error['message'];
            throw new Services_Scribd_Exception($message, $code);
        }

        return $xml;
    }

    /**
     * __call
     *
     * Trap any requests to endpoints we have not defined.
     *
     * @param string $method
     * @param array $params
     * @return void
     */
    public function __call($method, array $params)
    {
        throw new Services_Scribd_Exception('Invalid endpoint requested');
    }

    /**
     * reset
     *
     * Perform any cleanup after the request has been sent.
     *
     * @return void
     */
    private function reset()
    {
        $this->arguments = array();
    }
}
?>
