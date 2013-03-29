<?php
/**
 * Interface for Scribd's "docs" API endpoint.
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
 * The interface for the "docs" API endpoint.  Provides all interaction that
 * is associated with a specific document or several documents, such as
 * uploading, editing, etc.
 *
 * @category  Services
 * @package   Services_Scribd
 * @author    Rich Schumacher <rich.schu@gmail.com>
 * @copyright 2009 Rich Schumacher <rich.schu@gmail.com>
 * @license   http://www.opensource.org/licenses/bsd-license.php New BSD License
 * @link      http://www.scribd.com/developers/platform
 */
class Services_Scribd_Docs extends Services_Scribd_Common
{
    /**
     * Array of API endpoints that are supported
     *
     * @var array
     */
    protected $validEndpoints = array(
        'browse',
        'changeSettings',
        'delete',
        'getCategories',
        'getConversionStatus',
        'getDownloadUrl',
        'getList',
        'getSettings',
        'search',
        'upload',
        'uploadFromUrl',
        'uploadThumb',
    );

    /**
     * Supported document formats for uploading
     *
     * @link http://www.scribd.com/developers/platform/api/docs_upload
     * @var array
     */
    private $_validUploadDocTypes = array(
        'pdf', 'txt', 'ps', 'rtf', 'epub', 'odt',
        'odp', 'ods', 'odg', 'odf', 'sxw', 'sxc',
        'sxi', 'sxd', 'doc', 'ppt', 'pps', 'xls',
        'docx', 'pptx', 'ppsx', 'xlsx', 'tif', 'tiff'
    );

    /**
     * Supported document formats for downloading
     *
     * @link http://www.scribd.com/developers/platform/api/docs_getdownloadurl
     * @var array
     */
    private $_validDownloadDocTypes = array('pdf', 'txt', 'original');

    /**
     * Returns a list of documents that meet filter criteria
     *
     * @param integer $limit      Number of results to return
     * @param integer $offset     Number to start at
     * @param integer $categoryId A category ID to search documents in
     * @param string  $sort       Sort order, options are popular, views, newest
     *
     * @link http://www.scribd.com/developers/platform/api/docs_browse
     * @return SimpleXMLElement
     */
    public function browse($limit = 20, $offset = 1, $categoryId = null,
        $sort = 'popular'
    ) {
        $this->arguments['offset'] = $offset;
        $this->arguments['limit']  = $limit;
        $this->arguments['sort']   = $sort;

        if ($categoryId !== null) {
            $this->arguments['category_id'] = $categoryId;
        }

        $response = $this->call('docs.browse', HTTP_Request2::METHOD_GET);

        return $response->result_set;
    }

    /**
     * Changes metadata for one or many documents
     *
     * @param array $docIds   Array of document ids to modify
     * @param array $settings Associative array of values to use
     *
     * @link http://www.scribd.com/developers/platform/api/docs_changesettings
     * @return true
     */
    public function changeSettings(array $docIds, array $settings)
    {
        $validSettings = array(
            'title',
            'description',
            'access',
            'license',
            'show_ads',
            'link_back_url',
            'tags',
            'author',
            'pubisher',
            'when_published',
            'edition'
        );

        $docIds = implode(',', $docIds);

        foreach ($settings as $key => $value) {
            if (!in_array($key, $validSettings)) {
                unset($settings[$key]);
            }
        }

        $this->arguments            = $settings;
        $this->arguments['doc_ids'] = $docIds;

        $response = $this->call('docs.changeSettings', HTTP_Request2::METHOD_POST);

        return true;
    }

    /**
     * Deletes a document
     *
     * @param integer $docId The id of the document to delete
     *
     * @link http://www.scribd.com/developers/platform/api/docs_delete
     * @return true
     */
    public function delete($docId)
    {
        $this->arguments['doc_id'] = $docId;

        $this->call('docs.delete', HTTP_Request2::METHOD_POST);

        return true;
    }

    /**
     * Fetch a list of categories
     *
     * @param integer $categoryId        The ID of category to retrieve children
     * for. If none - all root categories will be returned.
     * @param boolean $withSubcategories Include subcategories in results
     *
     * @link http://www.scribd.com/developers/platform/api/docs_getcategories
     * @return SimpleXMLElement
     */
    public function getCategories($categoryId = null, $withSubcategories = true)
    {
        $this->arguments['with_subcategories'] = $withSubcategories;

        if ($categoryId !== null) {
            $this->arguments['category_id'] = $categoryId;
        }

        $response = $this->call('docs.getCategories', HTTP_Request2::METHOD_GET);

        return $response->result_set;
    }

    /**
     * Retrieves the conversion status of a document
     *
     * @param integer $docId The id of document to check
     *
     * @link http://www.scribd.com/developers/platform/api/docs_getconversionstatus
     * @return string
     */
    public function getConversionStatus($docId)
    {
        $this->arguments['doc_id'] = $docId;

        $response = $this->call('docs.getConversionStatus');

        return trim((string) $response->conversion_status);
    }

    /**
     * Gets a download URL for a particular document
     *
     * @param integer $docId   The id of the document
     * @param string  $docType The format of the document
     *
     * @link http://www.scribd.com/developers/platform/api/docs_getdownloadurl
     * @throws Services_Scribd_Exception
     * @return string
     */
    public function getDownloadUrl($docId, $docType = 'original')
    {
        if (!in_array($docType, $this->_validDownloadDocTypes)) {
            throw new Services_Scribd_Exception(
                'Invalid document type requested: ' . $docType
            );
        }

        $this->arguments['doc_id']   = $docId;
        $this->arguments['doc_type'] = $docType;

        $response = $this->call('docs.getDownloadUrl');

        return trim((string) $response->download_link);
    }

    /**
     * Revtrieves a list of documents owned by a user
     *
     * @param integer $limit         Max amount of results to return
     * @param integer $offset        Offset into the list of documents
     * @param boolean $useAPIAccount Show documents associated with the API
     * account, rather the Scribd user account
     *
     * @link http://www.scribd.com/developers/platform/api/docs_getlist
     * @return array
     */
    public function getList($limit = 10, $offset = 0, $useAPIAccount = false)
    {
        $this->arguments['limit']           = $limit;
        $this->arguments['offset']          = $offset;
        $this->arguments['use_api_account'] = $useAPIAccount;

        $response = $this->call('docs.getList');
        $response = (array) $response->resultset;

        return $response['result'];
    }

    /**
     * Gets metadata about a particular document
     *
     * @param integer $docId The id of the document
     *
     * @link http://www.scribd.com/developers/platform/api/docs_getsettings
     * @return SimpleXMLElement
     */
    public function getSettings($docId)
    {
        $this->arguments['doc_id'] = $docId;

        return $this->call('docs.getSettings');
    }

    /**
     * Searches for the text string within Scribd documents
     *
     * @param string  $query  The text to search for
     * @param string  $scope  Whether to search all of Scribd or just the
     * users documents
     * @param integer $limit  The max number of results to return
     * @param integer $offset The number to start at
     *
     * @link http://www.scribd.com/developers/platform/api/docs_search
     * @throws Services_Scribd_Exception
     * @return array
     */
    public function search($query, $scope = 'user', $limit = 10, $offset = 1)
    {
        $validScope = array(
            'all',
            'user',
            'account'
        );

        if (!in_array($scope, $validScope)) {
            throw new Services_Scribd_Exception(
                'Invalid scope requested: ' . $scope
            );
        }

        $this->arguments['query']       = $query;
        $this->arguments['scope']       = $scope;
        $this->arguments['num_results'] = $limit;
        $this->arguments['num_start']   = $offset;

        $rawResponse = $this->call('docs.search');

        $response['results'] = array();

        foreach ($rawResponse->result_set[0]->attributes() as $key => $value) {
            $response[$key] = (string) $value;
        }

        foreach ($rawResponse->result_set->result as $result) {
            array_push($response['results'], $result);
        }

        return $response;
    }

    /**
     * Uploads and publishes a document from the filesystem
     *
     * @param string  $filepath    A path to the file we want to upload
     * @param string  $docType     The type of document
     * @param string  $access      Document Access {public, private}
     * @param integer $paidContent Is this paid content? {0,1}
     * @param integer $revisionId  The document id we are revising
     *
     * @link http://www.scribd.com/developers/platform/api/docs_upload
     * @throws Services_Scribd_Exception
     * @return SimpleXMLElement
     */
    public function upload($filepath, $docType, $access = 'public',
        $paidContent = 0, $revisionId = null
    ) {
        if (!in_array($docType, $this->_validUploadDocTypes)) {
            throw new Services_Scribd_Exception(
                'Invalid document type requested: ' . $docType
            );
        }

        if (!file_exists($filepath)) {
            throw new Services_Scribd_Exception(
                'The selected file was not found'
            );
        }

        $this->arguments['file']         = $filepath;
        $this->arguments['doc_type']     = $docType;
        $this->arguments['access']       = $access;
        $this->arguments['paid_content'] = $paidContent;
        $this->arguments['rev_id']       = $revisionId;

        $response = $this->call('docs.upload', HTTP_Request2::METHOD_POST);

        unset($response['stat']);

        return $response;
    }

    /**
     * Uploads and publishes a document from a URL
     *
     * @param string  $url         The URL where the file is located
     * @param string  $docType     The type of document
     * @param string  $access      Document Access {public, private}
     * @param integer $paidContent Is this paid content? {0,1}
     * @param integer $revisionId  The document id we are revising
     *
     * @link http://www.scribd.com/developers/platform/api/docs_uploadfromurl
     * @throws Services_Scribd_Exception
     * @return SimpleXMLElement
     */
    public function uploadFromUrl($url, $docType, $access = 'public',
        $paidContent = 0, $revisionId = null
    ) {
        if (!in_array($docType, $this->_validUploadDocTypes)) {
            throw new Services_Scribd_Exception(
                'Invalid document type requested: ' . $docType
            );
        }

        $this->arguments['url']          = $url;
        $this->arguments['doc_type']     = $docType;
        $this->arguments['access']       = $access;
        $this->arguments['paid_content'] = $paidContent;
        $this->arguments['rev_id']       = $revisionId;

        $response = $this->call('docs.uploadFromUrl', HTTP_Request2::METHOD_POST);
    
        unset($response['stat']);

        return $response;
    }

    /**
     * XXX: This doesn't appear to be working; throwing 500's consistently.
     *
     * Uploads a thumbnail for a document
     *
     * @param string $filepath A path to the thumbnail we want to upload
     * @param string $docId    The id of the document
     *
     * @link http://www.scribd.com/developers/platform/api/docs_uploadthumb
     * @throws Services_Scribd_Exception
     * @return boolean
     */
    public function uploadThumb($filepath, $docId)
    {
        if (!file_exists($filepath)) {
            throw new Services_Scribd_Exception(
                'The selected file was not found'
            );
        }

        $this->arguments['file']   = $filepath;
        $this->arguments['doc_id'] = $docId;

        $response = $this->call('docs.uploadThumb', HTTP_Request2::METHOD_POST);

        return (string) $response['stat'] == "ok";
    }
}

?>
