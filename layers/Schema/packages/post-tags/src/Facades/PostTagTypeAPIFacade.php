<?php

declare(strict_types=1);

namespace PoPSchema\PostTags\Facades;

use PoP\Engine\App;
use PoPSchema\PostTags\TypeAPIs\PostTagTypeAPIInterface;

class PostTagTypeAPIFacade
{
    public static function getInstance(): PostTagTypeAPIInterface
    {
        /**
         * @var PostTagTypeAPIInterface
         */
        $service = App::getContainerBuilderFactory()->getInstance()->get(PostTagTypeAPIInterface::class);
        return $service;
    }
}
