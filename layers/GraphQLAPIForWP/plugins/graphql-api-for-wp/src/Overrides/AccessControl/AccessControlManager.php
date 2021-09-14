<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Overrides\AccessControl;

use GraphQLByPoP\GraphQLServer\TypeResolvers\ObjectType\MutationRootObjectTypeResolver;
use GraphQLByPoP\GraphQLServer\TypeResolvers\ObjectType\QueryRootObjectTypeResolver;
use PoP\AccessControl\Services\AccessControlManager as UpstreamAccessControlManager;

class AccessControlManager extends UpstreamAccessControlManager
{
    /**
     * Add an additional entry: whenever QueryRoot and MutationRoot is used,
     * duplicate it also as Root, so that the user needs to set
     * the configuration only once
     */
    public function addEntriesForFields(string $group, array $fieldEntries): void
    {
        parent::addEntriesForFields($group, $fieldEntries);

        // Find all entries set to QueryRoot or MutationRoot
        $rootFieldEntries = array_filter(
            $fieldEntries,
            fn (array $fieldEntry) => in_array($fieldEntry[0], [
                QueryRootObjectTypeResolver::class,
                MutationRootObjectTypeResolver::class,
            ])
        );

        // Replace the type to Root
        $rootFieldEntries = array_map(
            function (array $rootFieldEntry): array {
                $rootFieldEntry[0] = RootObjectTypeResolver::class;
                return $rootFieldEntry;
            },
            $rootFieldEntries
        );

        // Add the entries
        $this->fieldEntries[$group] = array_merge(
            $this->fieldEntries[$group] ?? [],
            $rootFieldEntries
        );
    }
}
