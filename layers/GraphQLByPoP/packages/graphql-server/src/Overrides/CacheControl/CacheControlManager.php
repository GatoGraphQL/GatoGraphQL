<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\Overrides\CacheControl;

use GraphQLByPoP\GraphQLServer\IFTTT\MandatoryDirectivesForFieldsRootTypeEntryDuplicatorInterface;
use PoP\CacheControl\Managers\CacheControlManager as UpstreamCacheControlManager;
use Symfony\Contracts\Service\Attribute\Required;

class CacheControlManager extends UpstreamCacheControlManager
{
    protected ?array $overriddenFieldEntries = null;
    protected MandatoryDirectivesForFieldsRootTypeEntryDuplicatorInterface $mandatoryDirectivesForFieldsRootTypeEntryDuplicator;

    #[Required]
    final public function autowireGraphQLServerCacheControlManager(MandatoryDirectivesForFieldsRootTypeEntryDuplicatorInterface $mandatoryDirectivesForFieldsRootTypeEntryDuplicator): void
    {
        $this->mandatoryDirectivesForFieldsRootTypeEntryDuplicator = $mandatoryDirectivesForFieldsRootTypeEntryDuplicator;
    }

    public function addEntriesForFields(array $fieldEntries): void
    {
        parent::addEntriesForFields($fieldEntries);

        // Make sure to reset getting the entries
        $this->overriddenFieldEntries = null;
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
    public function getEntriesForFields(): array
    {
        if ($this->overriddenFieldEntries !== null) {
            return $this->overriddenFieldEntries;
        }

        $this->overriddenFieldEntries = $this->mandatoryDirectivesForFieldsRootTypeEntryDuplicator->maybeAppendAdditionalRootEntriesForFields(
            parent::getEntriesForFields()
        );

        return $this->overriddenFieldEntries;
    }
}
