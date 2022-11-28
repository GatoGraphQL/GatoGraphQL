<?php

declare(strict_types=1);

namespace PoPCMSSchema\SchemaCommonsWP\TypeAPIs;

use stdClass;
use WP_Error;

trait TypeMutationAPITrait
{
    protected function getWPErrorData(WP_Error $wpError): ?stdClass
    {
        if (!$wpError->get_error_data()) {
            return null;
        }
        if ($wpError->get_error_data() instanceof stdClass) {
            return $wpError->get_error_data();
        }
        if (is_array($wpError->get_error_data())) {
            return (object) $wpError->get_error_data();
        }
        $errorData = new stdClass();
        $key = $wpError->get_error_code() ? (string) $wpError->get_error_code() : 'data';
        $errorData->$key = $wpError->get_error_data();
        return $errorData;
    }
}
