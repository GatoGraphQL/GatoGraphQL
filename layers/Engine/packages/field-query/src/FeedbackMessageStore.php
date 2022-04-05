<?php

declare(strict_types=1);

namespace PoP\FieldQuery;

class FeedbackMessageStore implements FeedbackMessageStoreInterface
{
    /**
     * @var array<string, array>
     */
    protected array $queryErrors = [];

    /**
     * @var array<string, array>
     */
    protected array $queryWarnings = [];

    public function clearAll(): void
    {
        $this->queryErrors = [];
        $this->queryWarnings = [];
    }

    /**
     * $extensions is optional. It is used by GraphQL to pass the location with "line" and "column" (as a string)
     *
     * @param mixed[] $extensions Adding extra information (eg: location error for GraphQL)
     */
    public function addQueryError(string $error, array $extensions = []): void
    {
        $this->queryErrors[$error] = $extensions;
    }
    /**
     * @return array<string, array>
     */
    public function getQueryErrors(): array
    {
        // return array_unique($this->queryErrors);
        return $this->queryErrors;
    }

    /**
     * $extensions is optional
     *
     * @param mixed[] $extensions Adding extra information
     */
    public function addQueryWarning(string $warning, array $extensions = []): void
    {
        $this->queryWarnings[$warning] = $extensions;
    }
    /**
     * @return array<string, array>
     */
    public function getQueryWarnings(): array
    {
        return $this->queryWarnings;
    }
}
