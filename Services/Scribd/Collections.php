<?php
/**
 * Interface for Scribd's "collections" API endpoints.
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
 * @copyright 2013 Rich Schumacher <rich.schu@gmail.com>
 * @license   http://www.opensource.org/licenses/bsd-license.php New BSD License
 * @version   Release: @package-version@
 * @link      http://pear.php.net/package/Services_Scribd
 */

require_once 'Services/Scribd/Common.php';

/**
 * The interface for the "collections" API endpoints.  Provides all interaction
 * that is associated with a collection of documents, such as creating and 
 * deleting collections as well as adding and removing documents to those
 * collections.
 *
 * @category  Services
 * @package   Services_Scribd
 * @author    Rich Schumacher <rich.schu@gmail.com>
 * @copyright 2013 Rich Schumacher <rich.schu@gmail.com>
 * @license   http://www.opensource.org/licenses/bsd-license.php New BSD License
 * @link      http://www.scribd.com/developers/platform/api
 */
class Services_Scribd_Collections extends Services_Scribd_Common
{
    /**
     * Array of API endpoints that are supported
     *
     * @var array
     */
    protected $validEndpoints = array(
        'addDoc',
        'create'
    );

    /**
     * Adds a document to an existing collection.
     *
     * @param integer $docId        ID of the document to add
     * @param integer $collectionId ID of the colleciton to use
     *
     * @link http://www.scribd.com/developers/platform/api/collections_adddoc
     * @return boolean
     */
    public function addDoc($docId, $collectionId)
    {
        $this->arguments['doc_id']        = $docId;
        $this->arguments['collection_id'] = $collectionId;

        $response = $this->call('collections.addDoc', HTTP_Request2::METHOD_POST);

        return (string) $response['stat'] == 'ok';
    }

    /**
     * Creates a new collection.
     *
     * @param string $name        Name of the colleciton
     * @param string $description Description of the collection
     * @param string $privacyType Privacy setting, either 'public' or 'private'
     *
     * @link http://www.scribd.com/developers/platform/api/collections_create
     * @return integer The ID of the created collection
     */
    public function create($name, $description = null, $privacyType = 'public')
    {
        $this->arguments['name']         = $name;
        $this->arguments['description']  = $description;
        $this->arguments['privacy_type'] = $privacyType;

        $response = $this->call('collections.create', HTTP_Request2::METHOD_POST);

        return (int) $response->collection_id;
    }

    /**
     * Updates a new collection's name, description or privacy_type.
     *
     * @param integer $collectionId ID of the colleciton to use
     * @param string $name          Name of the colleciton
     * @param string $description   Description of the collection
     * @param string $privacyType   Privacy setting, either 'public' or 'private'
     *
     * @link http://www.scribd.com/developers/platform/api/collections_update
     * @return boolean
     */
    public function update($collectionId, $name = null, $description = null,
        $privacyType = null
    ) {
        $this->arguments['collection_id'] = $collectionId;
        $this->arguments['name']          = $name;
        $this->arguments['description']   = $description;
        $this->arguments['privacy_type']  = $privacyType;

        $response = $this->call('collections.update', HTTP_Request2::METHOD_POST);

        return (string) $response['stat'] == 'ok';
    }
}
