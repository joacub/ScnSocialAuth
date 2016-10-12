<?php
namespace ScnSocialAuth\HybridAuth\Provider;

include_once realpath(__DIR__ . '/../../../../../../hybridauth/hybridauth/additional-providers/hybridauth-instagram/Providers/Instagram.php');

/**
 * This is simply to trigger autoloading as a hack for poor design in HybridAuth.
 */
class Instagram extends \Hybrid_Providers_Instagram {}
