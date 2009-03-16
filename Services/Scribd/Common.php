<?php
/**
 * Common logic for all of Scribd's API endpoints.
 *
 * PHP version 5.2.0+
 *
 * LICENSE: This source file is subject to the New BSD license that is 
 * available through the world-wide-web at the following URI:
 * http://www.opensource.org/licenses/bsd-license.php. If you did not receive  
 * a copy of the New BSD License and are unable to obtain it through the web, 
 * please send a note to license@php.net so we can mail you a copy immediately. 
 *
 * @category  Services
 * @package   Services_Scribd
 * @author    Rich Schumacher <rich.schu@gmail.com>
 * @copyright 2009 Rich Schumacher <rich.schu@gmail.com>
 * @license   http://www.opensource.org/licenses/bsd-license.php New BSD License
 * @version   @package_version@
 * @link      http://pear.php.net/package/Services_Scribd
 */

require_once 'Services/Scribd/Common.php';

/**
 * This class contains common logic needed for all the API endpoints.  Handles
 * tasks such as sending requests, signing the requests, etc.
 * 
 * @category  Services
 * @package   Services_Scribd
 * @author    Rich Schumacher <rich.schu@gmail.com>
 * @copyright 2009 Rich Schumacher <rich.schu@gmail.com>
 * @license   http://www.opensource.org/licenses/bsd-license.php New BSD License
 * @version   @package_version@
 * @link      http://www.scribd.com/publisher/api
 */
class Services_Scribd_Common extends Services_Scribd
{
    /**
     * An array of arguments to send to the API
     *
     * @var array
     */
    protected $arguments = array();

    /**
     * _skipSignatureArguments 
     *
     * An array of arguments that we must skip when calculating the API
     * signature.
     *
     * @link http://www.scribd.com/publisher/api?method_name=Signing
     * @var array
     */
    private $_skipSignatureArguments = array(
        'file'
    );

    /**
     * __construct
     *
     * This is only defined so that the call doesn't bubble up to
     * Serices_Scribd::_construct()
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * getAvailableEndpoints
     *
     * Return an array of endpoints for this driver.
     *
     * @return array
     */
    public function getAvailableEndpoints()
    {
        return $this->validEndpoints;
    }

    /**
     * __call
     *
     * Trap any requests to endpoints we have not defined.
     *
     * @param string $endpoint The invalid endpoint requested
     * @param array  $params   Array of params for this endpoint
     *
     * @throws Services_Scribd_Exception
     * @return null
     */
    public function __call($endpoint, array $params)
    {
        throw new Services_Scribd_Exception(
            'Invalid endpoint requested: ' . $endpoint
        );
    }

    /**
     * sendRequest
     *
     * Using curl, actually send the request to the Scribd API.  Delegates to
     * helper methods to format the arguments, response, etc.
     *
     * @param string $endpoint The requested endpoint
     * @param string $method   The HTTP method to use, defaults to GET
     *
     * @throws Services_Scribd_Exception
     * @return mixed
     */
    protected function sendRequest($endpoint,
                                   $method = Services_Scribd::HTTP_METHOD_GET)
    {
        if ($method !== Services_Scribd::HTTP_METHOD_GET
            && $method !== Services_Scribd::HTTP_METHOD_POST) {
            throw new Services_Scribd_Exception('Invalid HTTP method: ' . $method);
        }

        $uri = $this->_buildRequestURI($endpoint, $method);

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $uri);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_TIMEOUT, Services_Scribd::$timeout);
        
        if ($method === Services_Scribd::HTTP_METHOD_POST) {
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $this->arguments);
        }

        $response = curl_exec($curl);

        if (curl_errno($curl)) {
            throw new Services_Scribd_Exception();
        }

        curl_close($curl);

        $this->_reset();
        
        return $this->_formatResponse($response);
    }

    /**
     * _buildRequestURI
     *
     * Method to build the API request URI.  Delegates the merging and request
     * signing to more specific methods.
     *
     * @param string $endpoint The requested endpoint
     * @param string $method   The HTTP method to use, defaults to GET
     *
     * @return string
     */
    private function _buildRequestURI($endpoint, $method)
    {
        $this->_mergeRequestArguments($endpoint);

        $this->_signRequest();

        if ($method === Services_Scribd::HTTP_METHOD_POST) {
            return Services_Scribd::API;
        }

        $queryString = http_build_query($this->arguments);

        return Services_Scribd::API . '?' . $queryString;
    }

    /**
     * _mergeRequestArguments
     *
     * Merge some required API arguments with those specific to this request.
     *
     * @param string $endpoint The requested endpoint
     *
     * @return void
     */
    private function _mergeRequestArguments($endpoint)
    {
        $requiredArguments = array(
            'method'  => $endpoint,
            'api_key' => Services_Scribd::$apiKey
        );

        if (Services_Scribd::$apiSessionKey !== null) {
            $this->arguments['session_key'] = Services_Scribd::$apiSessionKey;
        }

        // Get rid of any nulls
        $this->arguments = array_diff($this->arguments, array(null));

        // ...and merge them with the required arguments
        $this->arguments = array_merge($requiredArguments, $this->arguments);
    }

    /**
     * _signRequest
     *
     * If the Services_Scribd::$apiSecret variable has been set calculate a
     * signature to help protect against evesdropping attacks.
     *
     * @link http://www.scribd.com/publisher/api?method_name=Signing
     * @see Services_Scribd::$apiSecret
     * @return void
     */
    private function _signRequest()
    {
        if (Services_Scribd::$apiSecret === null) {
            return;
        }

        if (!empty($this->arguments['api_sig'])) {
            unset($this->arguments['api_sig']);
        }

        ksort($this->arguments);

        $apiSig = null;

        foreach ($this->arguments as $key => $value) {
            if (!in_array($key, $this->_skipSignatureArguments)) {
                $apiSig .= $key . $value;
            }
        }

        $this->arguments['api_sig'] = md5(Services_Scribd::$apiSecret . $apiSig);
    }

    /**
     * _formatResponse
     *
     * Create and return a SimpleXMLElement element given the raw XML response
     * return from the API.
     *
     * @param string $response The XML response from the API
     *
     * @throws Services_Scribd_Exception
     * @return SimpleXMLElement
     */
    private function _formatResponse($response)
    {
        $xml = simplexml_load_string($response);
        if (!$xml instanceof SimpleXmlElement) {
            throw new Services_Scribd_Exception(
                'Could not parse XML response'
            );
        }
        
        if ( (string) $xml['stat'] !== 'ok') {
            $code    = (int) $xml->error['code'];
            $message = (string) $xml->error['message'];
            throw new Services_Scribd_Exception($message, $code);
        }

        return $xml;
    }

    /**
     * _reset
     *
     * Perform any cleanup after the request has been sent.
     *
     * @return void
     */
    private function _reset()
    {
        $this->arguments = array();
    }
}

?>
