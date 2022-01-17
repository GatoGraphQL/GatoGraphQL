<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPosts\Facades;

use PoP\Root\App;
use PoPCMSSchema\CustomPosts\TypeAPIs\CustomPostTypeAPIInterface;

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
