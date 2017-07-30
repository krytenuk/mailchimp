<?php

namespace FwsMailchimp\Service;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
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
     * Create Members service
     * @param ServiceLocatorInterface $serviceLocator
     * @return Members
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new Members($serviceLocator->get(Mailchimp::class), $serviceLocator->get('config'), $serviceLocator->get(Interests::class));
    }

}
