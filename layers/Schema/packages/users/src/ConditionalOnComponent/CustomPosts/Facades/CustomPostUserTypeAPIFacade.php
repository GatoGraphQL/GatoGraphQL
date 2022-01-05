<?php

declare(strict_types=1);

namespace PoPSchema\Users\ConditionalOnComponent\CustomPosts\Facades;

use PoP\Root\Container\ContainerBuilderFactory;
use PoPSchema\Users\ConditionalOnComponent\CustomPosts\TypeAPIs\CustomPostUserTypeAPIInterface;

class CustomPostUserTypeAPIFacade
{
    public static function getInstance(): CustomPostUserTypeAPIInterface
    {
        /**
         * @var CustomPostUserTypeAPIInterface
         */
        $service = \PoP\Engine\App::getContainerBuilderFactory()->getInstance()->get(CustomPostUserTypeAPIInterface::class);
        return $service;
    }
}
