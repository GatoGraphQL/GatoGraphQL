<?php

declare(strict_types=1);

namespace PoPSchema\CustomPostMutations\Facades;

use PoP\Engine\App;
use PoP\Root\Container\ContainerBuilderFactory;
use PoPSchema\CustomPostMutations\TypeAPIs\CustomPostTypeMutationAPIInterface;

class CustomPostTypeMutationAPIFacade
{
    public static function getInstance(): CustomPostTypeMutationAPIInterface
    {
        /**
         * @var CustomPostTypeMutationAPIInterface
         */
        $service = App::getContainerBuilderFactory()->getInstance()->get(CustomPostTypeMutationAPIInterface::class);
        return $service;
    }
}
