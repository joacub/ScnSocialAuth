<?php
/**
 * ScnSocialAuth Module
 *
 * @category   ScnSocialAuth
 * @package    ScnSocialAuth_Service
 */

namespace ScnSocialAuth\Service;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * @category   SIgnCom
 * @package    SIgnCom_Controller
 * @copyright  Copyright (c) 2006-2011 IGN Entertainment, Inc. (http://corp.ign.com/)
 */
class HybridAuthFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $services)
    {
        $options = $services->get('ScnSocialAuth-ModuleOptions');

        $router = $services->get('Router');
        $baseUrl = $router->assemble(
            array(),
            array(
            	'name' => 'home',
                'force_canonical' => true,
            )
        );

        require_once $options->getHybridAuthPath()
            . '/Hybrid'
            . '/Auth.php';

        $hybridAuth = new \Hybrid_Auth(
            array(
                'base_url' => $baseUrl . 'scn-social-auth/hauth',
                'providers' => array(
                    'Facebook' => array(
                        'enabled' => $options->getFacebookEnabled(),
                        'keys' => array(
                            'id' => $options->getFacebookClientId(),
                            'secret' => $options->getFacebookSecret(),
                        ),
                        'scope' => $options->getFacebookScope(),
                        'display' => $options->getFacebookDisplay(),
                    ),
                ),
            )
        );

        return $hybridAuth;
    }
}
