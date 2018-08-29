FwsMailchimp
============

Docs now available [online](https://www.freedomwebservices.net/zend-framework/fws-mailchimp).

ZF3 Mailchimp module

This module integrates with the mailchimp api.

Installation
------------

### Main Setup

#### With composer

1. Add this project in your composer.json:

    ```json
    "require": {
        "krytenuk/mailchimp": "^0.2"
    }
    ```

2. Now tell composer to download FwsMailchimp by running the command:

    ```bash
    $ php composer.phar update
    ```





#### Post installation

1. Enabling it in your `application.config.php` file.

    ```php
    <?php
    return array(
        'modules' => array(
            // ...
            'FwsMailchimp',
        ),
        // ...
    );
    ```

2. Copy `./vendor/krytenuk/mailchimp/config/fwsmailchimp.local.php.dist` to `./config/autoload/fwsmailchimp.local.php`.

3. In your fwsmailchimp.local.php fill in the following:
	<?php
	return array(
		'fwsMailchimp' => array(
			'apikey' => 'your mailchimp api key here',
			'listId' => 'your mailchimp list id here',
		),
	);

	Your Mailchimp api key can be created/found in your Mailchimp admin area, to login goto https://login.mailchimp.com/
	Your list id can be found in your members list.  Goto Lists and select the list you wish to use then goto Settings -> List name and defaults.



### Usage

Docs now available [online](https://www.freedomwebservices.net/zend-framework/fws-mailchimp).