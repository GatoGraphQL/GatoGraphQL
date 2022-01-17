<?php

declare(strict_types=1);

namespace PoPSchema\CustomPosts\Facades;

use PoP\Root\App;
use PoPSchema\CustomPosts\TypeAPIs\CustomPostTypeAPIInterface;

class CustomPostTypeAPIFacade
{
    public static function getInstance(): CustomPostTypeAPIInterface
    {
        /**
         * @var CustomPostTypeAPIInterface
         */
        $service = App::getContainer()->get(CustomPostTypeAPIInterface::class);
        return $service;
    }
}
