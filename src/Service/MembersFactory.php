<?php

namespace FwsMailchimp\Service;

use Laminas\ServiceManager\Factory\FactoryInterface;
use Interop\Container\ContainerInterface;
use FwsMailchimp\Members;
use FwsMailchimp\Client\Mailchimp;
use FwsMailchimp\Interests;

/**
 * Create Members class
 *
 * @author Garry Childs (Freedom Web Services)
 */
class MembersFactory implements FactoryInterface
{

    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array $options
     * @return Members
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new Members($container->get(Mailchimp::class), $container->get('config'), $container->get(Interests::class));
    }

}
