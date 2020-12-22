<?php

declare(strict_types=1);

namespace PoP\AccessControl\Services;

interface AccessControlManagerInterface
{
    public function getEntriesForFields(string $group): array;
    public function getEntriesForDirectives(string $group): array;
    public function addEntriesForFields(string $group, array $fieldEntries): void;
    public function addEntriesForDirectives(string $group, array $directiveEntries): void;
}
