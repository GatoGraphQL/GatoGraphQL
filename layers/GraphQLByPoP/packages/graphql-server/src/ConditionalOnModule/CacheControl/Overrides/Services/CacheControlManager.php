<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\ConditionalOnModule\CacheControl\Overrides\Services;

use GraphQLByPoP\GraphQLServer\IFTTT\MandatoryDirectivesForFieldsRootTypeEntryDuplicatorInterface;
use PoP\CacheControl\Managers\CacheControlManager as UpstreamCacheControlManager;
use PoP\Root\Services\BasicServiceTrait;

class CacheControlManager extends UpstreamCacheControlManager
{
    use BasicServiceTrait;

    /**
     * @var array<mixed[]>|null
     */
    protected ?array $overriddenFieldEntries = null;

    private ?MandatoryDirectivesForFieldsRootTypeEntryDuplicatorInterface $mandatoryDirectivesForFieldsRootTypeEntryDuplicator = null;

    final public function setMandatoryDirectivesForFieldsRootTypeEntryDuplicator(MandatoryDirectivesForFieldsRootTypeEntryDuplicatorInterface $mandatoryDirectivesForFieldsRootTypeEntryDuplicator): void
    {
        $this->mandatoryDirectivesForFieldsRootTypeEntryDuplicator = $mandatoryDirectivesForFieldsRootTypeEntryDuplicator;
    }
    final protected function getMandatoryDirectivesForFieldsRootTypeEntryDuplicator(): MandatoryDirectivesForFieldsRootTypeEntryDuplicatorInterface
    {
        if ($this->mandatoryDirectivesForFieldsRootTypeEntryDuplicator === null) {
            /** @var MandatoryDirectivesForFieldsRootTypeEntryDuplicatorInterface */
            $mandatoryDirectivesForFieldsRootTypeEntryDuplicator = $this->instanceManager->getInstance(MandatoryDirectivesForFieldsRootTypeEntryDuplicatorInterface::class);
            $this->mandatoryDirectivesForFieldsRootTypeEntryDuplicator = $mandatoryDirectivesForFieldsRootTypeEntryDuplicator;
        }
        return $this->mandatoryDirectivesForFieldsRootTypeEntryDuplicator;
    }

    /**
     * @param array<mixed[]> $fieldEntries
     */
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
     *
     * @return array<mixed[]>
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
