<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\Overrides\CacheControl;

use GraphQLByPoP\GraphQLServer\IFTTT\MandatoryDirectivesForFieldsRootTypeEntryDuplicatorInterface;
use PoP\CacheControl\Managers\CacheControlManager as UpstreamCacheControlManager;
use PoP\BasicService\BasicServiceTrait;

class CacheControlManager extends UpstreamCacheControlManager
{
    use BasicServiceTrait;

    protected ?array $overriddenFieldEntries = null;

    private ?MandatoryDirectivesForFieldsRootTypeEntryDuplicatorInterface $mandatoryDirectivesForFieldsRootTypeEntryDuplicator = null;

    final public function setMandatoryDirectivesForFieldsRootTypeEntryDuplicator(MandatoryDirectivesForFieldsRootTypeEntryDuplicatorInterface $mandatoryDirectivesForFieldsRootTypeEntryDuplicator): void
    {
        $this->mandatoryDirectivesForFieldsRootTypeEntryDuplicator = $mandatoryDirectivesForFieldsRootTypeEntryDuplicator;
    }
    final protected function getMandatoryDirectivesForFieldsRootTypeEntryDuplicator(): MandatoryDirectivesForFieldsRootTypeEntryDuplicatorInterface
    {
        return $this->mandatoryDirectivesForFieldsRootTypeEntryDuplicator ??= $this->instanceManager->getInstance(MandatoryDirectivesForFieldsRootTypeEntryDuplicatorInterface::class);
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

        $this->overriddenFieldEntries = $this->getMandatoryDirectivesForFieldsRootTypeEntryDuplicator()->maybeAppendAdditionalRootEntriesForFields(
            parent::getEntriesForFields()
        );

        return $this->overriddenFieldEntries;
    }
}
