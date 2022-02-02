<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Feedback;

use PoP\GraphQLParser\Spec\Parser\Location;

class AbstractQueryFeedback extends AbstractFeedback implements QueryFeedbackInterface
{
    public function __construct(
        string $message,
        string $code,
        Location $location,
        array $data = [],
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
     * @return array
     */
    public function getExtensions(): array
    {
        return $this->extensions;
    }
}
