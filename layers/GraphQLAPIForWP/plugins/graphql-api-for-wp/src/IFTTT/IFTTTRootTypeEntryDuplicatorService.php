<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\IFTTT;

use GraphQLByPoP\GraphQLServer\TypeResolvers\ObjectType\MutationRootObjectTypeResolver;
use GraphQLByPoP\GraphQLServer\TypeResolvers\ObjectType\QueryRootObjectTypeResolver;
use PoP\ComponentModel\Instances\InstanceManagerInterface;
use PoP\ComponentModel\State\ApplicationState;
use PoP\Engine\TypeResolvers\ObjectType\RootObjectTypeResolver;

class IFTTTRootTypeEntryDuplicatorService implements IFTTTRootTypeEntryDuplicatorServiceInterface
{
    public function __construct(
        protected InstanceManagerInterface $instanceManager,
    ) {
    }

    /**
     * For each of the entries assigned to Root (RootObjectTypeResolver::class),
     * add an additional QueryRoot and/or MutationRoot.
     * 
     * Fields "id", "self" and "__typename" can belong to both types.
     * Otherwise, the field is added to MutationRoot if it has a MutationResolver,
     * or to QueryRoot otherwise.
     * 
     * The duplicated entry is duplicated as is, just changing what class it applies to.
     * Then it can be an entry for anything: Access Control, Cache Control, or any other.
     */


    /**
     * For each of the entries assigned to Root (RootObjectTypeResolver::class),
     * add an additional QueryRoot and/or MutationRoot.
     * 
     * Fields "id", "self" and "__typename" can belong to both types.
     * Otherwise, the field is added to MutationRoot if it has a MutationResolver,
     * or to QueryRoot otherwise.
     * 
     * The duplicated entry is duplicated as is, just changing what class it applies to.
     * Then it can be an entry for anything: Access Control, Cache Control, or any other.
     */
    public function appendAdditionalRootEntriesForFields(array $fieldEntries): array
    {
        // With Nested Mutations there's no need to duplicate Root entries
        $vars = ApplicationState::getVars();
        if ($vars['nested-mutations-enabled']) {
            return $fieldEntries;
        }

        // Duplicate the Root entries into QueryRoot and/or MutationRoot
        return array_merge(
            $fieldEntries,
            $this->getAdditionalRootEntriesForFields($fieldEntries)
        );
    }

    protected function getAdditionalRootEntriesForFields(array $fieldEntries): array
    {
        // Get the entries assigned to Root
        $rootFieldEntries = $this->filterRootEntriesForFields($fieldEntries);
        if ($rootFieldEntries === []) {
            return [];
        }
        
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
            $isFieldAMutation = $rootObjectTypeResolver->isFieldAMutation($fieldName);
            // Make sure the field has a FieldResolver. If not, ignore
            if ($isFieldAMutation === null) {
                continue;
            }
            if ($isFieldAMutation) {
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

    /**
     * Filter the entries set to Root
     */
    protected function filterRootEntriesForFields(array $fieldEntries): array
    {
        return array_filter(
            $fieldEntries,
            fn (array $fieldEntry) => $fieldEntry[0] === RootObjectTypeResolver::class
        );
    }
}
