<?php

declare(strict_types=1);

namespace PoP\FieldQuery;

interface FeedbackMessageStoreInterface
{
    public function clearAll(): void;
    /**
     * $extensions is optional. It is used by GraphQL to pass the location with "line" and "column" (as a string)
     *
     * @param mixed[] $extensions Adding extra information (eg: location error for GraphQL)
     */
    public function addQueryError(string $error, array $extensions = []): void;
    /**
     * @return array<string, array>
     */
    public function getQueryErrors(): array;
    /**
     * $extensions is optional
     *
     * @param mixed[] $extensions Adding extra information
     */
    public function addQueryWarning(string $warning, array $extensions = []): void;
    /**
     * @return array<string, array>
     */
    public function getQueryWarnings(): array;
}
