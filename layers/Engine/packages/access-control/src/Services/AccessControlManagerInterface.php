<?php

declare(strict_types=1);

namespace PoP\AccessControl\Services;

interface AccessControlManagerInterface
{
    /**
     * @return array<mixed[]>
     */
    public function getEntriesForFields(string $group): array;
    /**
     * @return array<mixed[]>
     */
    public function getEntriesForDirectives(string $group): array;
    /**
     * @return array<string,array<mixed[]>> Key: group, value: entries
     */
    public function getFieldEntries(): array;
    /**
     * @return array<string,array<mixed[]>> Key: group, value: entries
     */
    public function getDirectiveEntries(): array;
    /**
     * @param array<mixed[]> $fieldEntries
     */
    public function addEntriesForFields(string $group, array $fieldEntries): void;
    /**
     * @param array<mixed[]> $directiveEntries
     */
    public function addEntriesForDirectives(string $group, array $directiveEntries): void;
}
