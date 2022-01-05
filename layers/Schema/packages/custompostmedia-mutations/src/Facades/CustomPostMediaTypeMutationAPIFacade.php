<?php

declare(strict_types=1);

namespace PoPSchema\CustomPostMediaMutations\Facades;

use PoP\Engine\App;
use PoPSchema\CustomPostMediaMutations\TypeAPIs\CustomPostMediaTypeMutationAPIInterface;

class CustomPostMediaTypeMutationAPIFacade
{
    public static function getInstance(): CustomPostMediaTypeMutationAPIInterface
    {
        /**
         * @var CustomPostMediaTypeMutationAPIInterface
         */
        $service = App::getContainer()->get(CustomPostMediaTypeMutationAPIInterface::class);
        return $service;
    }
}
