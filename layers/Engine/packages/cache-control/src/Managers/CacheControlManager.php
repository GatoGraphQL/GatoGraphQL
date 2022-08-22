<?php

declare(strict_types=1);

namespace PoP\CacheControl\Managers;

class CacheControlManager implements CacheControlManagerInterface
{
    /**
     * @var array<mixed[]>
     */
    protected array $fieldEntries = [];
    /**
     * @var array<mixed[]>
     */
    protected array $directiveEntries = [];

    /**
     * @return array<mixed[]>
     */
    public function getEntriesForFields(): array
    {
        return $this->fieldEntries ?? [];
    }

    /**
     * @return array<mixed[]>
     */
    public function getEntriesForDirectives(): array
    {
        return $this->directiveEntries ?? [];
    }

    /**
     * @param array<mixed[]> $fieldEntries
     */
    public function addEntriesForFields(array $fieldEntries): void
    {
        $this->fieldEntries = array_merge(
            $this->fieldEntries ?? [],
            $fieldEntries
        );
    }
    /**
     * @param array<mixed[]> $directiveEntries
     */
    public function addEntriesForDirectives(array $directiveEntries): void
    {
        $this->directiveEntries = array_merge(
            $this->directiveEntries ?? [],
            $directiveEntries
        );
    }
}
