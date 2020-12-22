<?php

declare(strict_types=1);

namespace PoP\CacheControl\Managers;

interface CacheControlManagerInterface
{
    public function getEntriesForFields(): array;
    public function getEntriesForDirectives(): array;
    public function addEntriesForFields(array $fieldEntries): void;
    public function addEntriesForDirectives(array $directiveEntries): void;
}
