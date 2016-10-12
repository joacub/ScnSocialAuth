<?php
namespace ScnSocialAuth\HybridAuth\Provider;

include_once realpath(__DIR__ . '/../../../../../../hybridauth/hybridauth/additional-providers/hybridauth-tumblr/Providers/Tumblr.php');
/**
 * This is simply to trigger autoloading as a hack for poor design in HybridAuth.
 */
class Tumblr extends \Hybrid_Providers_Tumblr {}
