<?php
/**
 * Interface for Scribd's "ext" API endpoint.
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
 * The interface for the "ext" API endpoint.  This endpoint handles everything
 * related to external accounts and settings.
 * 
 * @category  Services
 * @package   Services_Scribd
 * @author    Rich Schumacher <rich.schu@gmail.com>
 * @copyright 2009 Rich Schumacher <rich.schu@gmail.com>
 * @license   http://www.opensource.org/licenses/bsd-license.php New BSD License
 * @version   @package_version@
 * @link      http://www.scribd.com/publisher/api
 */
class Services_Scribd_Ext extends Services_Scribd_Common
{
    /**
     * validEndpoints 
     *
     * Array of API endpoints that are supported.
     *
     * @var array
     */
    protected $validEndpoints = array(
        'lookup',
        'set'
    );

    /**
     * lookup
     *
     * Look up the Scribd ID of a user who has been associated with an external
     * account ID.  The external account ID is set in the set() method below.
     *
     * @param integer $externalId The external ID associated with Scribd
     *
     * @todo Contact Scribd, keep getting "Required parameter missing" error
     *
     * @link http://www.scribd.com/publisher/api?method_name=ext.lookup
     * @see Services_Scribd_Ext::set()
     * @return string
     */
    public function lookup($externalId)
    {
        $this->arguments['ext_id'] = $externalId;

        $response = $this->sendRequest('ext.lookup');

        return (string) $response->url;
    }

    /**
     * set
     *
     * This method associates the current Scribd user with his account ID on
     * your website.  Always returns true since any problems will be reported
     * by the sendRequest() method.
     *
     * @param integer $externalId The external ID associated with Scribd
     *
     * @link http://www.scribd.com/publisher/api?method_name=ext.set
     * @see Services_Scribd_Common::sendRequest()
     * @return true
     */
    public function set($externalId)
    {
        $this->arguments['ext_id'] = $externalId;

        $response = $this->sendRequest('ext.set',
                                       Services_Scribd::HTTP_METHOD_POST);

        return true;
    }
}

?>
