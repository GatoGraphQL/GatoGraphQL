<?php

declare(strict_types=1);

namespace PoP\AccessControl\Services;

use PoP\Root\Services\AbstractBasicService;

class AccessControlManager extends AbstractBasicService implements AccessControlManagerInterface
{
    /**
     * @var array<string,array<mixed[]>>
     */
    protected array $fieldEntries = [];
    /**
     * @var array<string,array<mixed[]>>
     */
    protected array $directiveEntries = [];

    /**
     * @return array<mixed[]>
     */
    public function getEntriesForFields(string $group): array
    {
        return $this->fieldEntries[$group] ?? [];
    }
    /**
     * @return array<mixed[]>
     */
    public function getEntriesForDirectives(string $group): array
    {
        return $this->directiveEntries[$group] ?? [];
    }

    /**
     * @return array<string,array<mixed[]>> Key: group, value: entries
     */
    public function getFieldEntries(): array
    {
        return $this->fieldEntries;
    }
    /**
     * @return array<string,array<mixed[]>> Key: group, value: entries
     */
    public function getDirectiveEntries(): array
    {
        return $this->directiveEntries;
    }

    /**
     * @param array<mixed[]> $fieldEntries
     */
    public function addEntriesForFields(string $group, array $fieldEntries): void
    {
        $this->fieldEntries[$group] = array_merge(
            $this->fieldEntries[$group] ?? [],
            $fieldEntries
        );
    }
    /**
     * @param array<mixed[]> $directiveEntries
     */
    public function addEntriesForDirectives(string $group, array $directiveEntries): void
    {
        $this->directiveEntries[$group] = array_merge(
            $this->directiveEntries[$group] ?? [],
            $directiveEntries
        );
    }
}
