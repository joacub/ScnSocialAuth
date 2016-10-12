<?php
/**
 * ScnSocialAuth Module
 *
 * @category   ScnSocialAuth
 * @package    ScnSocialAuth_Service
 */

namespace ScnSocialAuth\Service;

use Interop\Container\ContainerInterface;
use Interop\Container\Exception\ContainerException;
use ScnSocialAuth\Controller\HybridAuthController;
use Zend\ServiceManager\Exception\ServiceNotCreatedException;
use Zend\ServiceManager\Exception\ServiceNotFoundException;
use Zend\ServiceManager\Factory\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * @category   ScnSocialAuth
 * @package    ScnSocialAuth_Service
 */
class HybridAuthControllerFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $controllerManager)
    {
        // Just making sure to instantiate and configure
        // It's not actually needed in HybridAuthController
        $hybridAuth = $controllerManager->get('HybridAuth');

        $controller = new HybridAuthController();

        return $controller;
    }

    /**
     * @inheritDoc
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return $this->createService($container);
    }


}
