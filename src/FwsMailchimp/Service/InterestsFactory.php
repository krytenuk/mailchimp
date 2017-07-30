<?php

namespace FwsMailchimp\Service;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
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
     * Create Interests service
     * @param ServiceLocatorInterface $serviceLocator
     * @return Members
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new Interests($serviceLocator->get(Mailchimp::class), $serviceLocator->get('config'));
    }

}
