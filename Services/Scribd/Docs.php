<?php

require_once 'Services/Scribd/Common.php';

class Services_Scribd_Docs extends Services_Scribd_Common
{
    protected $validEndpoints = array(
        'changeSettings',
        'delete',
        'getConversionStatus',
        'getDownloadUrl',
        'getList',
        'getSettings',
        'search',
        'upload',
        'uploadFromUrl'
    );

    // check
    public function changeSettings()
    {
        $this->arguments[] = array();

        $response = $this->sendRequest(
            'docs.changeSettings'
        );
    }

    // check
    public function delete($docId)
    {
        $this->arguments['doc_id'] = $docId;

        return $this->sendRequest(
            'docs.delete',
            'POST'
        );
    }

    /**
     * getConversionStatus
     *
     * Retrieve the conversion status of a document.
     *
     * @param integer $docId The id of document to check
     *
     * @return string
     */
    public function getConversionStatus($docId)
    {
        $this->arguments['doc_id'] = $docId;

        $response = $this->sendRequest(
            'docs.getConversionStatus'
        );

        return trim((string) $response->conversion_status);
    }

    /**
     * getDownloadUrl
     *
     * Get a download URL for a particular document.
     *
     * @param mixed $docId    The id of the document
     * @param string $docType The format of the document
     *
     * @return string
     */
    public function getDownloadUrl($docId, $docType = 'original')
    {
        $validDocTypes = array(
            'original',
            'pdf',
            'txt'
        );

        if (!in_array($docType, $validDocTypes)) {
            throw new Services_Scribd_Exception(
                'Invalid document type requested'
            );
        }

        $this->arguments['doc_id']   = $docId;
        $this->arguments['doc_type'] = $docType;

        $response = $this->sendRequest(
            'docs.getDownloadUrl'
        );

        return trim((string) $response->download_link);
    }

    /**
     * getList
     *
     * Revtrieve a list of documents owned by a give user.
     *
     * @return array
     */
    public function getList()
    {
        $response = $this->sendRequest(
            'docs.getList'
        );

        $response = (array) $response->resultset;

        return $response['result'];
    }

    /**
     * getSettings
     *
     * Get metadata about a particular document.
     *
     * @param integer $docId The id of document to get settings for
     *
     * @return SimpleXMLElement
     */
    public function getSettings($docId)
    {
        $this->arguments['doc_id'] = $docId;

        return $this->sendRequest(
            'docs.getSettings'
        );
    }

    /**
     * TODO: Why do we have to use result_set here?
     * search
     *
     * Search for the text string within the Scribd documents.
     *
     * @param string  $query  The text to search for
     * @param string  $scope  Whether to search all of Scribd or just the
     * users documents
     * @param integer $limit  The max number of results to return
     * @param integer $offset The number to start at
     *
     * @return void
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

        $response = $this->sendRequest(
            'docs.search'
        );

        return $response->result_set;
    }
}

?>
