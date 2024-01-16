<?php

declare(strict_types=1);

namespace PoPCMSSchema\MediaMutations\Facades;

use PoP\Root\App;
use PoPCMSSchema\MediaMutations\TypeAPIs\MediaTypeMutationAPIInterface;

class MediaTypeMutationAPIFacade
{
    public static function getInstance(): MediaTypeMutationAPIInterface
    {
        /**
         * @var MediaTypeMutationAPIInterface
         */
        $service = App::getContainer()->get(MediaTypeMutationAPIInterface::class);
        return $service;
    }
}
