<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostTagMutations\Facades;

use PoP\Root\App;
use PoPCMSSchema\CustomPostTagMutations\TypeAPIs\CustomPostTagTypeMutationAPIInterface;

class CustomPostTagTypeMutationAPIFacade
{
    public static function getInstance(): CustomPostTagTypeMutationAPIInterface
    {
        /**
         * @var CustomPostTagTypeMutationAPIInterface
         */
        $service = App::getContainer()->get(CustomPostTagTypeMutationAPIInterface::class);
        return $service;
    }
}
