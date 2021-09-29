<?php

declare(strict_types=1);

namespace PoP\CacheControl\Managers;

class CacheControlManager implements CacheControlManagerInterface
{
    /**
     * @var array[]
     */
    protected array $fieldEntries = [];
    /**
     * @var array[]
     */
    protected array $directiveEntries = [];

    public function getEntriesForFields(): array
    {
        return $this->fieldEntries ?? [];
    }
    public function getEntriesForDirectives(): array
    {
        return $this->directiveEntries ?? [];
    }

    public function addEntriesForFields(array $fieldEntries): void
    {
        $this->fieldEntries = array_merge(
            $this->fieldEntries ?? [],
            $fieldEntries
        );
    }
    public function addEntriesForDirectives(array $directiveEntries): void
    {
        $this->directiveEntries = array_merge(
            $this->directiveEntries ?? [],
            $directiveEntries
        );
    }
}
