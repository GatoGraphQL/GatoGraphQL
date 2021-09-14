<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Overrides\AccessControl;

use GraphQLAPI\GraphQLAPI\IFTTT\IFTTTRootTypeEntryDuplicatorServiceInterface;
use PoP\AccessControl\Services\AccessControlManager as UpstreamAccessControlManager;

class AccessControlManager extends UpstreamAccessControlManager
{
    public function __construct(
        protected IFTTTRootTypeEntryDuplicatorServiceInterface $iftttRootTypeEntryDuplicatorService,
    ) {
    }

    /**
     * @var array<string, array>
     */
    protected array $overriddenFieldEntries = [];
    
    public function addEntriesForFields(string $group, array $fieldEntries): void
    {
        parent::addEntriesForFields($group, $fieldEntries);

        // Make sure to reset getting the entries
        unset($this->overriddenFieldEntries[$group]);
    }

    /**
     * Add additional entries: whenever Root is used,
     * duplicate it also for both QueryRoot and MutationRoot,
     * so that the user needs to set the configuration only once.
     * 
     * Add this logic when retrieving the entries because by then
     * the container is compiled and we can access the RootObjectTypeResolver
     * instance. In contrast, `addEntriesForFields` can be called
     * within a CompilerPass, so the instances would not yet be available.
     */
    public function getEntriesForFields(string $group): array
    {
        if (isset($this->overriddenFieldEntries[$group])) {
            return $this->overriddenFieldEntries[$group];
        }

        $fieldEntries = parent::getEntriesForFields($group);
        $fieldEntries = $this->iftttRootTypeEntryDuplicatorService->maybeAppendAdditionalRootEntriesForFields($fieldEntries);

        $this->overriddenFieldEntries[$group] = $fieldEntries;
        return $this->overriddenFieldEntries[$group];
    }
}
