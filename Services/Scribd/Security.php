<?php

require_once 'Services/Scribd/Common.php';

class Services_Scribd_Security extends Services_Scribd_Common
{
    protected $validEndpoints = array(
        'getDocumentAccessList',
        'getUserAccessList',
        'setAccess'
    );

    public function getDocumentAccessList($docId)
    {
        $this->arguments['doc_id'] = $docId;

        $respones = $this->sendRequest(
            'security.getDocumentAccessList'
        );

        return $response;
    }
}

?>
