<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Facades\HelperServices;

use PoP\Root\App;
use PoP\ComponentModel\Feedback\FeedbackEntryServiceInterface;

class FeedbackEntryServiceFacade
{
    public static function getInstance(): FeedbackEntryServiceInterface
    {
        /**
         * @var FeedbackEntryServiceInterface
         */
        $service = App::getContainer()->get(FeedbackEntryServiceInterface::class);
        return $service;
    }
}
