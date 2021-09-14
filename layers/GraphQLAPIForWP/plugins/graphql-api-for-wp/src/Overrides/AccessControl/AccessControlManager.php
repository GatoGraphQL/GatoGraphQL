<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Overrides\AccessControl;

use GraphQLByPoP\GraphQLServer\TypeResolvers\ObjectType\MutationRootObjectTypeResolver;
use GraphQLByPoP\GraphQLServer\TypeResolvers\ObjectType\QueryRootObjectTypeResolver;
use PoP\AccessControl\Services\AccessControlManager as UpstreamAccessControlManager;
use PoP\ComponentModel\Instances\InstanceManagerInterface;

class AccessControlManager extends UpstreamAccessControlManager
{
    public function __construct(
        protected InstanceManagerInterface $instanceManager,
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
     * the container is compiled and we can access the TypeResolver
     * services. In contrast, `addEntriesForFields` can be called
     * with a CompilerPass, so this logic wouldn't work.
     */
    public function getEntriesForFields(string $group): array
    {
        if (isset($this->overriddenFieldEntries[$group])) {
            return $this->overriddenFieldEntries[$group];
        }
        $fieldEntries = parent::getEntriesForFields($group);

        // Find all entries set to Root
        if ($rootFieldEntries = array_filter(
            $fieldEntries,
            fn (array $fieldEntry) => $fieldEntry[0] === RootObjectTypeResolver::class
        )) {
            $fieldEntries = array_merge(
                $fieldEntries,
                $this->getAdditionalRootEntriesForFields($rootFieldEntries)
            );
        }

        $this->overriddenFieldEntries[$group] = $fieldEntries;
        return $this->overriddenFieldEntries[$group];
    }

    /**
     * For each of the rootFieldEntries, add an additional QueryRoot and/or MutationRoot.
     * Fields "id", "self" and "__typename" can belong to both types.
     * Otherwise, the field is added to MutationRoot if it has a MutationResolver,
     * or to QueryRoot otherwise.
     * 
     * @param array $rootFieldEntries Entries where the class is set to Root
     */
    protected function getAdditionalRootEntriesForFields(array $rootFieldEntries): array
    {
        $additionalFieldEntries = [];

        /** @var RootObjectTypeResolver */
        $rootObjectTypeResolver = $this->instanceManager->getInstance(RootObjectTypeResolver::class);

        foreach ($rootFieldEntries as $rootFieldEntry) {
            $fieldName = $rootFieldEntry[1];
            if (in_array($fieldName, ['id', 'self', '__typename'])) {
                $rootFieldEntry[0] = QueryRootObjectTypeResolver::class;
                $additionalFieldEntries[] = $rootFieldEntry;
                $rootFieldEntry[0] = MutationRootObjectTypeResolver::class;
                $additionalFieldEntries[] = $rootFieldEntry;
                continue;
            }
            // If it has a MutationResolver for that field then add entry for MutationRoot
            if ($rootObjectTypeResolver->getFieldMutationResolverClass($fieldName) !== null) {
                $rootFieldEntry[0] = MutationRootObjectTypeResolver::class;
                $additionalFieldEntries[] = $rootFieldEntry;
                continue;
            }
            // It's a field for QueryRoot
            $rootFieldEntry[0] = QueryRootObjectTypeResolver::class;
            $additionalFieldEntries[] = $rootFieldEntry;
        }

        return $additionalFieldEntries;
    }
}
