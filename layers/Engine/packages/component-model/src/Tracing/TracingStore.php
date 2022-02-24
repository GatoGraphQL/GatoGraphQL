<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Tracing;

class TracingStore
{
    /** @var TraceInterface[] */
    private array $traces = [];

    /**
     * @return TraceInterface[]
     */
    public function getTraces(): array
    {
        return $this->traces;
    }

    public function addTrace(TraceInterface $trace): void
    {
        $this->traces[] = $trace;
    }

    /**
     * @param TraceInterface[] $traces
     */
    public function setTraces(array $traces): void
    {
        $this->traces = $traces;
    }
}
