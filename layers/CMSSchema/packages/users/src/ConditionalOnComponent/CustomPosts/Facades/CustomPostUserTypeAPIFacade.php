<?php

declare(strict_types=1);

namespace PoPCMSSchema\Users\ConditionalOnComponent\CustomPosts\Facades;

use PoP\Root\App;
use PoPCMSSchema\Users\ConditionalOnComponent\CustomPosts\TypeAPIs\CustomPostUserTypeAPIInterface;

class CustomPostUserTypeAPIFacade
{
    public static function getInstance(): CustomPostUserTypeAPIInterface
    {
        /**
         * @var CustomPostUserTypeAPIInterface
         */
        $service = App::getContainer()->get(CustomPostUserTypeAPIInterface::class);
        return $service;
    }
}
