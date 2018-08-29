<?php

namespace FwsMailchimp\Service;

use Zend\ServiceManager\Factory\FactoryInterface;
use Interop\Container\ContainerInterface;
use FwsMailchimp\Interests;
use FwsMailchimp\Client\Mailchimp;

/**
 * Create Interests class
 *
 * @author Garry Childs (Freedom Web Services)
 */
class InterestsFactory implements FactoryInterface
{

    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array $options
     * @return Interests
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new Interests($container->get(Mailchimp::class), $container->get('config'));
    }

}
