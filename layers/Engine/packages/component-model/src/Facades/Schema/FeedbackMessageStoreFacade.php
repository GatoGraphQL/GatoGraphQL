<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Facades\Schema;

use PoP\Root\App;
use PoP\ComponentModel\Schema\FeedbackMessageStoreInterface;

class FeedbackMessageStoreFacade
{
    public static function getInstance(): FeedbackMessageStoreInterface
    {
        /**
         * @var FeedbackMessageStoreInterface
         */
        $service = App::getContainerBuilderFactory()->getInstance()->get(FeedbackMessageStoreInterface::class);
        return $service;
    }
}
