<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Feedback;

use PoP\Root\Feedback\FeedbackCategories as UpstreamFeedbackCategories;

class FeedbackCategories extends UpstreamFeedbackCategories
{
    public const NOTICE = 'notice';
    public const WARNING = 'warning';
    public const SUGGESTION = 'suggestion';
}
