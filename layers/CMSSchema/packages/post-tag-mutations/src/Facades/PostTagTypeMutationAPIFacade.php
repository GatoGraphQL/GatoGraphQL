<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostTagMutations\Facades;

use PoP\Root\App;
use PoPCMSSchema\PostTagMutations\TypeAPIs\PostTagTypeMutationAPIInterface;

class PostTagTypeMutationAPIFacade
{
    public static function getInstance(): PostTagTypeMutationAPIInterface
    {
        /**
         * @var PostTagTypeMutationAPIInterface
         */
        $service = App::getContainer()->get(PostTagTypeMutationAPIInterface::class);
        return $service;
    }
}
