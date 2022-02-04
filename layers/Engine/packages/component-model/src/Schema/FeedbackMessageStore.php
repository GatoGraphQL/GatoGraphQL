<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Schema;

use PoP\ComponentModel\Feedback\Tokens;
use PoP\FieldQuery\FeedbackMessageStore as UpstreamFeedbackMessageStore;

class FeedbackMessageStore extends UpstreamFeedbackMessageStore implements FeedbackMessageStoreInterface
{
}
