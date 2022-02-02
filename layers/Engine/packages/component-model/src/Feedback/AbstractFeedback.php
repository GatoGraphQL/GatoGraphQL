<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Feedback;

use PoP\GraphQLParser\Spec\Parser\Location;

class AbstractFeedback implements FeedbackInterface
{
    public function __construct(
        protected string $message,
        protected string $code,
        protected Location $location,
        protected array $data = [],
    ) {
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function getLocation(): Location
    {
        return $this->location;
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }
}
