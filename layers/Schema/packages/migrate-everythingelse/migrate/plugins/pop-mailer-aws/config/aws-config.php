<?php
return array(
    // Bootstrap the configuration file with AWS specific features
    'includes' => array('_aws'),
    'services' => array(
        // All AWS clients extend from 'default_settings'. Here we are
        // overriding 'default_settings' with our default credentials and
        // providing a default region setting.
        'default_settings' => array(
            'params' => array(
                'credentials' => array(
                    'key'    => POP_MAILER_AWS_ACCESS_KEY_ID,
                    'secret' => POP_MAILER_AWS_SECRET_ACCESS_KEY,
                ),
                'region' => POP_MAILER_AWS_REGION
            )
        )
    )
);
