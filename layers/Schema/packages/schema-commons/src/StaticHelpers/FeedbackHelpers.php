<?php

declare(strict_types=1);

namespace PoPSchema\SchemaCommons\StaticHelpers;

use PoPSchema\SchemaCommons\FeedbackItemProviders\ErrorFeedbackItemProvider;
use PoP\ComponentModel\Feedback\FeedbackItemResolution;

use function preg_last_error_msg;

class FeedbackHelpers
{
    public static function getLastPregReplaceErrorFeedbackItemResolution(): FeedbackItemResolution
    {
        return new FeedbackItemResolution(
            ErrorFeedbackItemProvider::class,
            ErrorFeedbackItemProvider::E1,
            [
                preg_last_error_msg(),
            ]
        );
    }
}
