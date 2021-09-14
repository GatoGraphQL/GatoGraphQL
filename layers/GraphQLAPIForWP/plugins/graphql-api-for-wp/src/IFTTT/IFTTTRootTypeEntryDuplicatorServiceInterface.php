<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\IFTTT;

interface IFTTTRootTypeEntryDuplicatorServiceInterface
{
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
    public function getAdditionalRootEntriesForFields(array $fieldEntries): array;
}
