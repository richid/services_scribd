<?php
/**
 * Interface for Scribd's API.
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

require_once 'Services/Scribd/Exception.php';

/**
 * The base class for the Scribd API interface.  Takes care of defining common
 * variables and loading the indidividual drivers.
 *
 * @category  Services
 * @package   Services_Scribd
 * @author    Rich Schumacher <rich.schu@gmail.com>
 * @copyright 2009 Rich Schumacher <rich.schu@gmail.com>
 * @license   http://www.opensource.org/licenses/bsd-license.php New BSD License
 * @version   @package_version@
 * @link      http://www.scribd.com/publisher/api
 */
class Services_Scribd
{
    /**
     * URI of the API
     *
     * @var string
     */
    const API = 'http://api.scribd.com/api';

    /**
     * HTTP GET method
     *
     * @var string
     */
    const HTTP_METHOD_GET = 'GET';

    /**
     * HTTP POST method
     *
     * @var string
     */
    const HTTP_METHOD_POST = 'POST';

    /**
     * API key
     *
     * @var string
     */
    static public $apiKey = null;

    /**
     * API secret
     *
     * @var string
     */
    static public $apiSecret = null;

    /**
     * Third party user id to associate with the requests
     *
     * @link http://www.scribd.com/publisher/api?method_name=Authentication
     * @var string
     */
    static public $myUserId = null;

    /**
     * Timeout to use when making the request
     *
     * @var integer
     */
    static public $timeout = 10;

    /**
     * The API session key
     *
     * @var string
     */
    static public $apiSessionKey = null;

    /**
     * An array that contains instances of the individual drivers
     *
     * @var array
     */
    private $_drivers = array();

    /**
     * An array of drivers that we support
     *
     * @var array
     */
    private $_validDrivers = array(
        'docs',
        'security',
        'user'
    );

    /**
     * __construct
     *
     * Construct and set the api key.
     *
     * @param string $apiKey    The API key
     * @param string $apiSecret The super secret API passphrase
     *
     * @return void
     */
    public function __construct($apiKey, $apiSecret = null)
    {
        self::$apiKey    = $apiKey;
        self::$apiSecret = $apiSecret;
    }

    /**
     * __get
     *
     * Magic method use to load individual drivers.
     *
     * @param string $driver The driver we want to load
     *
     * @throws Services_Scribd_Exception
     * @return Services_Scribd_Common
     */
    public function __get($driver)
    {
        if (!in_array($driver, $this->_validDrivers)) {
            throw new Services_Scribd_Exception(
                'Invalid driver provided: ' . $driver
            );
        }

        if (empty($this->_drivers[$driver])) {
            $this->_drivers[$driver] = $this->_factory($driver);
        }

        return $this->_drivers[$driver];
    }

    /**
     * _factory
     *
     * Churn out individual API drivers.
     *
     * @param string $driver The driver we want to load
     *
     * @throws Services_Scribd_Exception
     * @return Services_Scribd_Common
     */
    private function _factory($driver)
    {
        $driver = mb_convert_case($driver, MB_CASE_TITLE);
        $file   = 'Services/Scribd/' . $driver . '.php';
        $class  = 'Services_Scribd_' . $driver;

        include_once $file;

        if (!class_exists($class)) {
            throw new Services_Scribd_Exception(
                'Unable to load driver: ' . $driver
            );
        }

        return new $class();
    }
}

?>
