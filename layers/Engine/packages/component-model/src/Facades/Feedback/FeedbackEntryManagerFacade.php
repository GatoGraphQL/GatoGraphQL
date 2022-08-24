<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Facades\Feedback;

use PoP\Root\App;
use PoP\ComponentModel\Feedback\FeedbackEntryManagerInterface;

class FeedbackEntryManagerFacade
{
    public static function getInstance(): FeedbackEntryManagerInterface
    {
        /**
         * @var FeedbackEntryManagerInterface
         */
        $service = App::getContainer()->get(FeedbackEntryManagerInterface::class);
        return $service;
    }
}
