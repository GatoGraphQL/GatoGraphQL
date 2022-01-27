<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostTags\Facades;

use PoP\Root\App;
use PoPCMSSchema\PostTags\TypeAPIs\PostTagTypeAPIInterface;

class PostTagTypeAPIFacade
{
    public static function getInstance(): PostTagTypeAPIInterface
    {
        /**
         * @var PostTagTypeAPIInterface
         */
        $service = App::getContainer()->get(PostTagTypeAPIInterface::class);
        return $service;
    }
}
