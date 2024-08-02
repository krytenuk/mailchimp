<?php

use FwsMailchimp\Client\Mailchimp;
use FwsMailchimp\Interests;
use FwsMailchimp\Members;
use Laminas\ServiceManager\AbstractFactory\ConfigAbstractFactory;
use Laminas\ServiceManager\Factory\InvokableFactory;

return [
    'service_manager' => [
        'abstract_factories' => [
            ConfigAbstractFactory::class,
        ],
        'factories' => [
            Members::class => ConfigAbstractFactory::class,
            Interests::class => ConfigAbstractFactory::class,
            Mailchimp::class => InvokableFactory::class
        ],
    ],
    ConfigAbstractFactory::class => [
        Members::class => [
            Mailchimp::class,
            'config',
            Interests::class,
        ],
        Interests::class => [
            Mailchimp::class,
            'config',
        ],
    ],
];
