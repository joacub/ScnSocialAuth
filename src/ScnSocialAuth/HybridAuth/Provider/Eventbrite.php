<?php
namespace ScnSocialAuth\HybridAuth\Provider;

use Tracy\Debugger;

/**
 * This is simply to trigger autoloading as a hack for poor design in HybridAuth.
 */
class Eventbrite extends \Hybrid_Provider_Model_OAuth2
{

    /**
     * {@inheritdoc}
     */
    function initialize() {
        parent::initialize();

        // Provider api end-points
        $this->api->authorize_url = 'https://www.eventbrite.com/oauth/authorize';
        $this->api->token_url = 'https://www.eventbrite.com/oauth/token';

        // Google POST methods require an access_token in the header
        $this->api->curl_header = array("Authorization: Bearer " . $this->api->access_token);

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

        $response = $this->api->api('https://www.eventbriteapi.com/v3/users/me/');

        if (!isset($response->id) || isset($response->error)) {
            throw new \Exception("User profile request failed! {$this->providerId} returned an invalid response:" . \Hybrid_Logger::dumpData( $response ), 6);
        }

        $this->user->profile->identifier = ((property_exists($response, 'id')) ? $response->id : "");
        $this->user->profile->firstName = (string) (property_exists($response, 'first_name')) ? $response->first_name : "";;
        $this->user->profile->lastName = (string) (property_exists($response, 'last_name')) ? $response->last_name : "";;
        $this->user->profile->displayName = (string) (property_exists($response, 'name')) ? $response->name : "";
        $this->user->profile->photoURL = (string) (property_exists($response, 'image_id')) ? (string) $response->image_id : "";
        $this->user->profile->profileURL = "";
        $this->user->profile->description = "";
        $this->user->profile->gender ="";
        $this->user->profile->language = "";
        $this->user->profile->email = (property_exists($response, 'email') ? $response->email : "");
        $this->user->profile->emailVerified = "";

        if (property_exists($response, 'emails')) {
            if (count($response->emails) == 1) {
                $this->user->profile->email = $response->emails[0]->email;
                if($response->emails[0]->verified) {
                    $this->user->profile->emailVerified = $response->emails[0]->email;
                }
            } else {
                foreach ($response->emails as $email) {
                    if ($email->primary) {
                        $this->user->profile->email = $email->email;
                        break;
                    }

                    if($email->verified) {
                        $this->user->profile->emailVerified = $email->email;
                    }
                }
            }
        }

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
