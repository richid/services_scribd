<?php
/**
 * Dummy class that is only used for testing purposes.
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
 * Contains two simple methods that are only used to satisfy unit testing.
 *
 * @category  Services
 * @package   Services_Scribd
 * @author    Rich Schumacher <rich.schu@gmail.com>
 * @copyright 2009 Rich Schumacher <rich.schu@gmail.com>
 * @license   http://www.opensource.org/licenses/bsd-license.php New BSD License
 * @link      http://www.scribd.com/publisher/api
 */
class Services_Scribd_Dummy extends Services_Scribd_Common
{
    /**
     * Attempts to send a request with an unspported HTTP method
     *
     * @throws Services_Scribd_Exception
     * @return void
     */
    public function sendHeadRequest()
    {
        return $this->call('dummy.no', HTTP_Request2::METHOD_HEAD);
    }

    /**
     * Manually sets the api_sig value for the request
     *
     * @throws Services_Scribd_Exception
     * @return void
     */
    public function setAPISignatureManually()
    {
        $this->arguments['api_sig'] = 'omg';
        return $this->call('dummy.no');
    }
}

?>
