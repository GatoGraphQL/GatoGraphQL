<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Feedback;

use PoP\GraphQLParser\Spec\Parser\Location;

class QueryFeedback extends AbstractFeedback implements QueryFeedbackInterface
{
    public function __construct(
        string $message,
        ?string $code,
        Location $location,
        /** @var array<string, mixed> */
        array $data = [],
        /** @var array<string, mixed> */
        protected array $extensions = [],
    ) {
        parent::__construct(
            $message,
            $code,
            $location,
            $data,
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function getExtensions(): array
    {
        return $this->extensions;
    }
}
