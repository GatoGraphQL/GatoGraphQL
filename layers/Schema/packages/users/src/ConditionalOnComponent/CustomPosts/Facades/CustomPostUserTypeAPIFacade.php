<?php

declare(strict_types=1);

namespace PoPSchema\Users\ConditionalOnComponent\CustomPosts\Facades;

use PoP\Engine\App;
use PoPSchema\Users\ConditionalOnComponent\CustomPosts\TypeAPIs\CustomPostUserTypeAPIInterface;

class CustomPostUserTypeAPIFacade
{
    public static function getInstance(): CustomPostUserTypeAPIInterface
    {
        /**
         * @var CustomPostUserTypeAPIInterface
         */
        $service = App::getContainerBuilderFactory()->getInstance()->get(CustomPostUserTypeAPIInterface::class);
        return $service;
    }
}
