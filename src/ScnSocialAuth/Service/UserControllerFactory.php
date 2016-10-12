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
use ScnSocialAuth\Controller\UserController;
use Tracy\Debugger;
use Zend\ServiceManager\Exception\ServiceNotCreatedException;
use Zend\ServiceManager\Exception\ServiceNotFoundException;
use Zend\ServiceManager\Factory\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * @category   ScnSocialAuth
 * @package    ScnSocialAuth_Service
 */
class UserControllerFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $controllerManager)
    {
        $mapper = $controllerManager->get('ScnSocialAuth-UserProviderMapper');
        $moduleOptions = $controllerManager->get('ScnSocialAuth-ModuleOptions');
        $redirectCallback = $controllerManager->get('zfcuser_redirect_callback');
        $zfcuserModuleOptions = $controllerManager->get('zfcuser_module_options');

        $controller = new UserController($redirectCallback);
        $controller->setMapper($mapper);
        $controller->setOptions($moduleOptions);
        $controller->setZfcModuleOptions($zfcuserModuleOptions);

        try {
          $hybridAuth = $controllerManager->get('HybridAuth');
          $controller->setHybridAuth($hybridAuth);
        } catch (\Zend\ServiceManager\Exception\ServiceNotCreatedException $e) {
          // This is likely the user cancelling login...
        }

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
