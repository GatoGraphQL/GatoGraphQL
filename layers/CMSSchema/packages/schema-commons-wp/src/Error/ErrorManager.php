<?php

declare(strict_types=1);

namespace PoPCMSSchema\SchemaCommonsWP\Error;

use PoP\ComponentModel\Error\Error;
use PoP\Root\Services\BasicServiceTrait;
use PoPCMSSchema\SchemaCommons\Error\AbstractErrorManager;
use WP_Error;

class ErrorManager extends AbstractErrorManager
{
    use BasicServiceTrait;

    public function convertFromCMSToPoPError(object $cmsError): Error
    {
        /** @var WP_Error */
        $cmsError = $cmsError;
        $cmsErrorCodes = $cmsError->get_error_codes();
        if (count($cmsErrorCodes) === 1) {
            $cmsErrorCode = $cmsErrorCodes[0];
            return new Error(
                $cmsErrorCode,
                $cmsError->get_error_message($cmsErrorCode)
            );
        }
        $errorMessages = [];
        foreach ($cmsErrorCodes as $cmsErrorCode) {
            if ($errorMessage = $cmsError->get_error_message($cmsErrorCode)) {
                $errorMessages[] = sprintf(
                    $this->__('[%s] %s', 'engine-wp'),
                    $cmsErrorCode,
                    $errorMessage
                );
            } else {
                $errorMessages[] = sprintf(
                    $this->__('Error code: %s', 'engine-wp'),
                    $cmsErrorCode
                );
            }
        }
        return new Error(
            'cms-error',
            sprintf(
                $this->__('CMS errors: \'%s\'', 'engine-wp'),
                implode($this->__('\', \'', 'engine-wp'), $errorMessages)
            )
        );
    }

    public function isCMSError(mixed $thing): bool
    {
        return \is_wp_error($thing);
    }
}
