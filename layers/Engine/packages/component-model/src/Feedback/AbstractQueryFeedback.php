<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Feedback;

use PoP\GraphQLParser\Spec\Parser\Location;

class AbstractQueryFeedback extends AbstractFeedback implements QueryFeedbackInterface
{
    public function __construct(
        ?string $message = null,
        ?string $code = null,
        ?Location $location = null,
        ?array $data = null,
        ?array $extensions = null,
    ) {
        parent::__construct(
            $message,
            $code,
            $location,
            $data,
        );
    }

    /**
     * @return array|null
     */
    public function getExtensions(): ?array
    {
        return $this->extensions;
    }
}
