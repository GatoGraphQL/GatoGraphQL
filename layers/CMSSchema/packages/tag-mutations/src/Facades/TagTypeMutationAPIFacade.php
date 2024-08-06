<?php

declare(strict_types=1);

namespace PoPCMSSchema\TagMutations\Facades;

use PoP\Root\App;
use PoPCMSSchema\TagMutations\TypeAPIs\TagTypeMutationAPIInterface;

class TagTypeMutationAPIFacade
{
    public static function getInstance(): TagTypeMutationAPIInterface
    {
        /**
         * @var TagTypeMutationAPIInterface
         */
        $service = App::getContainer()->get(TagTypeMutationAPIInterface::class);
        return $service;
    }
}
