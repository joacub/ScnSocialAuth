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
use ScnSocialAuth\Controller\RedirectCallback;
use Zend\ServiceManager\Exception\ServiceNotCreatedException;
use Zend\ServiceManager\Exception\ServiceNotFoundException;
use Zend\ServiceManager\Factory\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * @category   ScnSocialAuth
 * @package    ScnSocialAuth_Service
 */
class RedirectCallbackFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $router = $serviceLocator->get('Router');
        $application = $serviceLocator->get('Application');
        $options = $serviceLocator->get('zfcuser_module_options');

        return new RedirectCallback($application, $router, $options);
    }

    /**
     * @inheritDoc
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return $this->createService($container);
    }


}
