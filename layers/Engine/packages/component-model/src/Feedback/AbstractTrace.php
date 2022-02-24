<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Feedback;

abstract class AbstractTrace implements TraceInterface
{
    public function __construct(
        protected string|int $id,
        /** @var array<string,mixed> */
        protected array $data = [],
    ) {
    }

    public function getID(): string|int
    {
        return $this->id;
    }

    /**
     * @return array<string,mixed>
     */
    public function getData(): array
    {
        return $this->data;
    }
}
