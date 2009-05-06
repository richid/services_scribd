<?php
/**
 * Interface for Scribd's "user" API endpoint.
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

require_once 'Services/Scribd/Common.php';

/**
 * The interface for the "user" API endpoint.  Allows the user to create an
 * account and login to different accounts.
 * 
 * @category  Services
 * @package   Services_Scribd
 * @author    Rich Schumacher <rich.schu@gmail.com>
 * @copyright 2009 Rich Schumacher <rich.schu@gmail.com>
 * @license   http://www.opensource.org/licenses/bsd-license.php New BSD License
 * @link      http://www.scribd.com/publisher/api
 */
class Services_Scribd_User extends Services_Scribd_Common
{
    /**
     * Array of API endpoints that are supported
     *
     * @var array
     */
    public $validEndpoints = array(
        'getAutoSigninUrl',
        'login',
        'signup'
    );

    /**
     * Returns a URL that, when visited, will automatically log a user in and
     * then redirect to the URL provided
     *
     * @param string $redirectUrl The URL to redirect to after logging in
     *
     * @link http://www.scribd.com/publisher/api?method_name=user.login
     * @return string
     */
    public function getAutoSigninUrl($redirectUrl = '/')
    {
        $this->arguments['next_url'] = $redirectUrl;

        $response = $this->call('user.getAutoSigninUrl');

        return trim((string) $response->url);
    }

    /**
     * Signs in as an existing Scribd user and executes methods as that user
     *
     * @param string $username The username or email address to login with
     * @param string $password Password of the account
     *
     * @link http://www.scribd.com/publisher/api?method_name=user.login
     * @return SimpleXMLElement
     */
    public function login($username, $password)
    {
        $this->arguments['username'] = $username;
        $this->arguments['password'] = $password;

        $response = $this->call('user.login', HTTP_Request2::METHOD_POST);

        unset($response['stat']);

        return $response;
    }

    /**
     * Creates a new Scribd account
     *
     * @param string $username The username to create
     * @param string $password Password to set
     * @param string $email    Email to use for this account
     * @param string $name     The user's name
     *
     * @return SimpleXMLElement
     */
    public function signup($username, $password, $email = null, $name = null)
    {
        $this->arguments['username'] = $username;
        $this->arguments['password'] = $password;
        $this->arguments['email']    = $email;
        $this->arguments['name']     = $name;

        $response = $this->call('user.signup', HTTP_Request2::METHOD_POST);

        unset($response['stat']);

        return $response;
    }
}

?>
