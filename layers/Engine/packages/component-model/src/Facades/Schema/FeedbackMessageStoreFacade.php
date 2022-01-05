<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Facades\Schema;

use PoP\ComponentModel\Schema\FeedbackMessageStoreInterface;
use PoP\Root\Container\ContainerBuilderFactory;

class FeedbackMessageStoreFacade
{
    public static function getInstance(): FeedbackMessageStoreInterface
    {
        /**
         * @var FeedbackMessageStoreInterface
         */
        $service = \PoP\Root\App::getContainerBuilderFactory()->getInstance()->get(FeedbackMessageStoreInterface::class);
        return $service;
    }
}
