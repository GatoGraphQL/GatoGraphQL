<?php

declare(strict_types=1);

namespace PoPCMSSchema\TagMetaMutations\Facades;

use PoP\Root\App;
use PoPCMSSchema\TagMetaMutations\TypeAPIs\TagMetaTypeMutationAPIInterface;

class TagMetaTypeMutationAPIFacade
{
    public static function getInstance(): TagMetaTypeMutationAPIInterface
    {
        /**
         * @var TagMetaTypeMutationAPIInterface
         */
        $service = App::getContainer()->get(TagMetaTypeMutationAPIInterface::class);
        return $service;
    }
}
