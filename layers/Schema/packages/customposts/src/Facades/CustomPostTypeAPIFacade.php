<?php

declare(strict_types=1);

namespace PoPSchema\CustomPosts\Facades;

use PoP\Engine\App;
use PoPSchema\CustomPosts\TypeAPIs\CustomPostTypeAPIInterface;

class CustomPostTypeAPIFacade
{
    public static function getInstance(): CustomPostTypeAPIInterface
    {
        /**
         * @var CustomPostTypeAPIInterface
         */
        $service = App::getContainerBuilderFactory()->getInstance()->get(CustomPostTypeAPIInterface::class);
        return $service;
    }
}
