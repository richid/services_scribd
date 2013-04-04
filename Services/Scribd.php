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
 * @version   Release: @package-version@
 * @link      http://pear.php.net/package/Services_Scribd
 */

require_once 'Services/Scribd/Account.php';
require_once 'Services/Scribd/Exception.php';

/**
 * The base class for the Scribd API interface.  Takes care of defining common
 * variables and loading the individual drivers.
 *
 * <code>
 * <?php
 * require_once 'Services/Scribd.php';
 *
 * $apiKey    = 'myAPIKey';
 * $apiSecret = 'myAPISecret';
 *
 * $scribd = new Services_Scribd($apiKey, $apiSecret);
 *
 * try {
 *     $result = $scribd->docs->search('vim');
 *     var_dump($result);
 * } catch (Services_Scribd_Exception $e) {
 *     var_dump($e);
 * }
 * ?>
 * </code>
 *
 * @category  Services
 * @package   Services_Scribd
 * @author    Rich Schumacher <rich.schu@gmail.com>
 * @copyright 2009 Rich Schumacher <rich.schu@gmail.com>
 * @license   http://www.opensource.org/licenses/bsd-license.php New BSD License
 * @link      http://www.scribd.com/developers/platform
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
     * Timeout to use when making the request
     *
     * @var integer
     */
    public $timeout = 10;

    /**
     * The Scribd account to use for requests
     *
     * @var Services_Scribd_Account
     */
    protected $account = null;

    /**
     * An array that contains instances of the individual drivers
     *
     * @var array
     */
    private $_drivers = array();

    /**
     * An array of supported drivers
     *
     * @var array
     */
    private $_validDrivers = array(
        'collections',
        'docs',
        'thumbnail',
        'user',
        'empty'
    );

    /**
     * Sets the API key and optional API secret
     *
     * @param string|Services_Scribd_Account $spec      The API key or an
     * existing account object
     * @param string                         $apiSecret The API secret
     *
     * @return void
     */
    public function __construct($spec, $apiSecret = null)
    {
        if ($spec instanceof Services_Scribd_Account) {
            $this->account = $spec;
        } else {
            $this->account = new Services_Scribd_Account($spec, $apiSecret);
        }
    }

    /**
     * Loads individual endpoint drivers
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
     * Sets the Scribd account to use
     *
     * @param Services_Scribd_Account $account The account to set
     *
     * @return void
     */
    public function setAccount(Services_Scribd_Account $account)
    {
        $this->account  = $account;
        $this->_drivers = array();
    }

    /**
     * Returns the current account instance
     *
     * @return Services_Scribd_Account
     */
    public function getAccount()
    {
        return $this->account;
    }

    /**
     * Churns out individual API endpoint drivers
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

        return new $class($this->account);
    }
}

?>
