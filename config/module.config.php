<?php

return array(
    'service_manager' => array(
        'factories' => array(
            'FwsMailchimp\Members' => 'FwsMailchimp\Service\MembersFactory',
            'FwsMailchimp\Interests' => 'FwsMailchimp\Service\InterestsFactory',
        ),
        'invokables' => array(
            'FwsMailchimp\Client\Mailchimp' => 'FwsMailchimp\Client\Mailchimp',
        ),
    ),
);
