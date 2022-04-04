<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Feedback;

use PoP\Root\Feedback\FeedbackCategories as UpstreamFeedbackCategories;

class FeedbackCategories extends UpstreamFeedbackCategories
{
    public final const NOTICE = 'notice';
    public final const WARNING = 'warning';
    public final const SUGGESTION = 'suggestion';
    public final const TRACE = 'trace';
}
