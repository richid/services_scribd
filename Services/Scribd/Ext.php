<?php

require_once 'Services/Scribd/Common.php';

class Services_Scribd_Ext extends Services_Scribd_Common
{
    protected $validEndpoints = array(
        'lookup',
        'set'
    );

    //TODO: doesnt work?
    public function lookup($externalId)
    {
        $this->arguments['ext_id'] = $externalId;

        $response = $this->sendRequest(
            'ext.lookup'
        );

        return (string) $response->url;
    }

    /**
     * set
     *
     * This method associates the current Scribd user with his account ID on
     * your website.
     *
     * @param integer $externalId The external ID associated
     *
     * @return true
     */
    public function set($externalId)
    {
        $this->arguments['ext_id'] = $externalId;

        $response = $this->sendRequest(
            'ext.set'
        );

        return true;
    }
}

?>
