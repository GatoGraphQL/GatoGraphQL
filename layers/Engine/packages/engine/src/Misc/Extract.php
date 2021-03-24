<?php

declare(strict_types=1);

namespace PoP\Engine\Misc;

use PoP\Engine\Misc\OperatorHelpers;
use Exception;
use PoP\ComponentModel\Facades\ErrorHandling\ErrorProviderFacade;

class Extract
{
    protected const ERRORCODE_PATHNOTREACHABLE = 'path-not-reachable';
    protected static function getDataFromPathError(string $fieldName, string $errorMessage)
    {
        $errorProvider = ErrorProviderFacade::getInstance();
        return $errorProvider->getError(
            $fieldName,
            self::ERRORCODE_PATHNOTREACHABLE,
            $errorMessage
        );
    }

    public static function getDataFromPath(string $fieldName, array $data, string $path)
    {
        try {
            $dataPointer = OperatorHelpers::getPointerToArrayItemUnderPath($data, $path);
        } catch (Exception $e) {
            return self::getDataFromPathError($fieldName, $e->getMessage());
        }
        return $dataPointer;
    }
}
