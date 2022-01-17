<?php

declare(strict_types=1);

namespace PoPSchema\CustomPostMutations\Facades;

use PoP\Root\App;
use PoPSchema\CustomPostMutations\TypeAPIs\CustomPostTypeMutationAPIInterface;

class CustomPostTypeMutationAPIFacade
{
    public static function getInstance(): CustomPostTypeMutationAPIInterface
    {
        /**
         * @var CustomPostTypeMutationAPIInterface
         */
        $service = App::getContainer()->get(CustomPostTypeMutationAPIInterface::class);
        return $service;
    }
}
