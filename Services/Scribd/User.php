<?php

require_once 'Services/Scribd/Common.php';

class Services_Scribd_User extends Services_Scribd_Common
{
    public $validEndpoints = array(
        'getAutoSigninUrl',
        'login',
        'signup'
    );

    /**
     * TODO: This doesn't seem to work!
     * getAutoSigninUrl
     *
     * Get a URL that, when visited, will automatically log the user in and
     * then redirect to the URL you provide.
     *
     * @param string $redirectUrl The URL to redirect to after logging in
     *
     * @return string
     */
    public function getAutoSigninUrl($redirectUrl = '')
    {
        $this->arguments['next_url'] = $redirectUrl;

        $response = $this->sendRequest(
            'user.getAutoSigninUrl'
        );

        return (string) $response->url;
    }

    /**
     * login
     *
     * Sign in as an existing Scribd user and execute methods as that user.
     *
     * @param string $username The username or email address to login with
     * @param string $password Password of the account
     *
     * @return SimpleXMLElement
     */
    public function login($username, $password)
    {
        $this->arguments['username'] = $username;
        $this->arguments['password'] = $password;

        return $this->sendRequest(
            'user.login'
        );
    }

    /**
     * signup
     *
     * Create a new Scribd account.
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

        return $this->sendRequest(
            'user.signup'
        );
    }
}

?>
