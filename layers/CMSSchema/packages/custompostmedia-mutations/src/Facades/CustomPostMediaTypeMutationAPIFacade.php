<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostMediaMutations\Facades;

use PoP\Root\App;
use PoPCMSSchema\CustomPostMediaMutations\TypeAPIs\CustomPostMediaTypeMutationAPIInterface;

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
