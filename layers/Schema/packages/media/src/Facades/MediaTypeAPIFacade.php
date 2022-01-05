<?php

declare(strict_types=1);

namespace PoPSchema\Media\Facades;

use PoP\Engine\App;
use PoPSchema\Media\TypeAPIs\MediaTypeAPIInterface;

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
