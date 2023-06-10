<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Feedback;

use PoP\Root\Feedback\FeedbackItemResolution as UpstreamFeedbackItemResolution;

class FeedbackItemResolution extends UpstreamFeedbackItemResolution
{
    /**
     * @phpstan-param class-string<FeedbackItemProviderInterface> $feedbackProviderServiceClass
     * @param array<string|int|float|bool> $messageParams
     * @param array<FeedbackItemResolution> $causes
     */
    public function __construct(
        string $feedbackProviderServiceClass,
        string $code,
        /** @var array<string|int|float|bool> */
        array $messageParams = [],
        /**
         * @see https://github.com/graphql/graphql-spec/issues/893
         */
        protected array $causes = [],
    ) {
        parent::__construct(
            $feedbackProviderServiceClass,
            $code,
            $messageParams,
        );
    }

    /**
     * @return FeedbackItemResolution[]
     */
    public function getCauses(): array
    {
        return $this->causes;
    }
}
