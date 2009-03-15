<?php
/**
 * Interface for Scribd's "security" API endpoint.
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
 * The interface for the "security" API endpoint.  Provides all interaction that
 * is associated with account and document security and access restrictions.
 * 
 * @category  Services
 * @package   Services_Scribd
 * @author    Rich Schumacher <rich.schu@gmail.com>
 * @copyright 2009 Rich Schumacher <rich.schu@gmail.com>
 * @license   http://www.opensource.org/licenses/bsd-license.php New BSD License
 * @version   @package_version@
 * @link      http://www.scribd.com/publisher/api
 */
class Services_Scribd_Security extends Services_Scribd_Common
{
    /**
     * validEndpoints 
     *
     * Array of API endpoints that are supported.
     *
     * @var array
     */
    protected $validEndpoints = array(
        'getDocumentAccessList',
        'getUserAccessList',
        'setAccess'
    );

    /**
     * getDocumentAccessList
     *
     * Retreive the list of user identifiers currently authorized to view the
     * given document.
     *
     * @param integer $docId The id of the document we're interested in
     *
     * @todo Contact Scribd, keep getting Insufficient permissions to access
     * this document"
     *
     * @link http://www.scribd.com/publisher/api?method_name=security.getDocumentAccessList
     * @return SimpleXMLElement
     */
    public function getDocumentAccessList($docId)
    {
        $this->arguments['doc_id'] = $docId;

        $respones = $this->sendRequest('security.getDocumentAccessList');

        return $response->resultset;
    }

    /**
     * getUserAccessList
     *
     * Retrieve the list of secure documents that the given user identifier is
     * is currently allowed to access.
     *
     * @param integer $userId The user identifier
     *
     * @todo Contact Scribd, keep getting an empty result
     *
     * @link http://www.scribd.com/publisher/api?method_name=security.getUserAccessList
     * @return SimpleXMLElement
     */
    public function getUserAccessList($userId)
    {
        $this->arguments['user_identifier'] = $userId;

        $response = $this->sendRequest('security.getUserAccessList');

        return $response->resultset;
    }

    /**
     * setAccess
     *
     * Disable or enable a user's acces to a secure document.  Always returns
     * true since any problems will be reported by the sendRequest() method.
     *
     * @param integer $userId The user identifier
     * @param integer $access {0,1}
     * @param integer $docId  The use
     *
     * @todo Contact Scribd, not entirely sure how this works
     *
     * @link http://www.scribd.com/publisher/api?method_name=security.getUserAccessList
     * @see Services_Scribd_Common::sendRequest()
     * @throws Services_Scribd_Exception
     * @return void
     */
    public function setAccess($userId, $access, $docId = null)
    {
        if ($access !== 0 AND $access !== 1) {
            throw new Services_Scribd_Exception(
                'Invalid access level prodvided: ' . $access
            );
        }

        $this->arguments['user_identifier'] = $userId;
        $this->arguments['allowed']         = $access;
        $this->arguments['doc_id']          = $docId;

        $this->sendRequest('security.setAccess',
                           Services_Scribd::HTTP_METHOD_POST);
    
        return true;
    }
}

?>
