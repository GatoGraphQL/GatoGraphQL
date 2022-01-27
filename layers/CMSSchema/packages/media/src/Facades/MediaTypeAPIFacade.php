<?php

declare(strict_types=1);

namespace PoPCMSSchema\Media\Facades;

use PoP\Root\App;
use PoPCMSSchema\Media\TypeAPIs\MediaTypeAPIInterface;

class MediaTypeAPIFacade
{
    public static function getInstance(): MediaTypeAPIInterface
    {
        /**
         * @var MediaTypeAPIInterface
         */
        $service = App::getContainer()->get(MediaTypeAPIInterface::class);
        return $service;
    }
}
