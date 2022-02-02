<?php

declare(strict_types=1);

namespace PoP\ComponentModel;

use PoP\ComponentModel\Engine\EngineState;
use PoP\ComponentModel\Feedback\FeedbackMessageStore;
use PoP\ComponentModel\Stores\MutationResolutionStoreInterface;
use PoP\Root\AppInterface as UpstreamAppInterface;

interface AppInterface extends UpstreamAppInterface
{
    public static function getEngineState(): EngineState;

    public static function getFeedbackMessageStore(): FeedbackMessageStore;

    public static function getMutationResolutionStore(): MutationResolutionStoreInterface;

    public static function regenerateEngineState(): void;

    public static function regenerateFeedbackMessageStore(): void;

    public static function regenerateMutationResolutionStore(): void;
}
