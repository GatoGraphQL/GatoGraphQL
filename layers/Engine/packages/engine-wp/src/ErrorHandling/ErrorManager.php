<?php

declare(strict_types=1);

namespace PoP\EngineWP\ErrorHandling;

use PoP\Engine\ErrorHandling\AbstractErrorManager;
use PoP\ComponentModel\ErrorHandling\Error;
use \WP_Error;

class ErrorManager extends AbstractErrorManager
{
    public function convertFromCMSToPoPError(object $cmsError): Error
    {
        $error = new Error();
        /** @var WP_Error */
        $cmsError = $cmsError;
        foreach ($cmsError->get_error_codes() as $code) {
            $error->add($code, $cmsError->get_error_message($code), $cmsError->get_error_data($code));
        }
        return $error;
    }

    public function isCMSError(object $object): bool
    {
        return \is_wp_error($object);
    }
}
