<?php

declare(strict_types=1);

namespace PoP\CacheControl\Managers;

interface CacheControlManagerInterface
{
    /**
     * @return array<mixed[]>
     */
    public function getEntriesForFields(): array;
    /**
     * @return array<mixed[]>
     */
    public function getEntriesForDirectives(): array;
    /**
     * @param array<mixed[]> $fieldEntries
     */
    public function addEntriesForFields(array $fieldEntries): void;
    /**
     * @param array<mixed[]> $directiveEntries
     */
    public function addEntriesForDirectives(array $directiveEntries): void;
}
