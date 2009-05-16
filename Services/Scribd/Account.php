<?php
/**
 * Defines values that are tied to a specific Scribd account.
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

/**
 * Basic value object that contains information for a Scribd account.  Can be
 * passed into {@link Services_Scribd::__construct()} and
 * {@link Services_Scribd_Common::__construct()} to make requests.
 *
 * @category  Services
 * @package   Services_Scribd
 * @author    Rich Schumacher <rich.schu@gmail.com>
 * @copyright 2009 Rich Schumacher <rich.schu@gmail.com>
 * @license   http://www.opensource.org/licenses/bsd-license.php New BSD License
 * @link      http://www.scribd.com/publisher/api
 * @link      http://www.scribd.com/developers/api?method_name=Authentication
 */
class Services_Scribd_Account
{
    /**
     * API key for this account
     *
     * @var string
     */
    private $_apiKey = null;

    /**
     * API secret for this account
     *
     * @var string
     */
    private $_apiSecret = null;

    /**
     * The custom user id to use
     *
     * @var string
     */
    private $_myUserId = null;

    /**
     * The session identifier to use for this account
     *
     * @see Services_Scribd_User::login()
     * @var string
     */
    private $_apiSessionKey = null;

    /**
     * Sets up the API key and secret if passed in
     *
     * @param string $apiKey    The API key
     * @param string $apiSecret The API secret
     *
     * @return void
     */
    public function __construct($apiKey, $apiSecret = null)
    {
        $this->_apiKey    = $apiKey;
        $this->_apiSecret = $apiSecret;
    }

    /**
     * Gets a property of the class
     *
     * @param string $name The property to retrieve
     *
     * @return string|null
     */
    public function __get($name)
    {
        if (property_exists($this, '_' . $name)) {
            return $this->{'_' . $name};
        }
    }

    /**
     * Sets a property of the class
     * 
     * @param string $name  The property to set
     * @param string $value The value to use
     * 
     * @return void
     */
    public function __set($name, $value)
    {
        if (property_exists($this, '_' . $name)) {
            $this->{'_' . $name} = $value;
        }
    }
}

?>
