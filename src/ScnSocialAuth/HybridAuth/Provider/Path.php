<?php
namespace ScnSocialAuth\HybridAuth\Provider;

use Tracy\Debugger;

/**
 * This is simply to trigger autoloading as a hack for poor design in HybridAuth.
 */
class Path extends \Hybrid_Provider_Model_OAuth2
{

    /**
     * {@inheritdoc}
     */
    function initialize() {
        parent::initialize();

        // Provider api end-points
        $this->api->authorize_url = 'https://partner.path.com/oauth2/authenticate';
        $this->api->token_url = 'https://partner.path.com/oauth2/access_token';

        // Google POST methods require an access_token in the header
        $this->api->curl_header = array("Authorization: OAuth " . $this->api->access_token);

        // Override the redirect uri when it's set in the config parameters. This way we prevent
        // redirect uri mismatches when authenticating with Google.
        if (isset($this->config['redirect_uri']) && !empty($this->config['redirect_uri'])) {
            $this->api->redirect_uri = $this->config['redirect_uri'];
        }
    }

    /**
     * {@inheritdoc}
     */
    function loginBegin() {
        \Hybrid_Auth::redirect($this->api->authorizeUrl());
    }

    /**
     * {@inheritdoc}
     */
    function getUserProfile() {
        // refresh tokens if needed
        //$this->refreshToken();

        // ask google api for user infos


        $response = $this->api->api('https://partner.path.com/1/user/self');

        if (!isset($response->user->id) || isset($response->error)) {
            throw new \Exception("User profile request failed! {$this->providerId} returned an invalid response:" . \Hybrid_Logger::dumpData( $response ), 6);
        }

        $this->user->profile->identifier = ((property_exists($response->user, 'id')) ? $response->user->id : "");
        $this->user->profile->firstName = "";
        $this->user->profile->lastName = "";
        $this->user->profile->displayName = (property_exists($response->user, 'name')) ? $response->user->name : "";
        $this->user->profile->photoURL = (property_exists($response->user, 'photo')) ? (string) $response->user->photo : "";
        $this->user->profile->profileURL = "";
        $this->user->profile->description = "";
        $this->user->profile->gender ="";
        $this->user->profile->language = "";
        $this->user->profile->email = (property_exists($response->user, 'email') ? $response->user->email : "");
        $this->user->profile->emailVerified = "";

        return $this->user->profile;
    }

    /**
     * Add query parameters to the $url
     *
     * @param string $url    URL
     * @param array  $params Parameters to add
     * @return string
     */
    function addUrlParam($url, array $params) {
        $query = parse_url($url, PHP_URL_QUERY);

        // Returns the URL string with new parameters
        if ($query) {
            $url .= '&' . http_build_query($params);
        } else {
            $url .= '?' . http_build_query($params);
        }
        return $url;
    }

}
