<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\IFTTT;

interface MandatoryDirectivesForFieldsRootTypeEntryDuplicatorInterface
{
    /**
     * This function appends entries only when Nested Mutations is disabled,
     * so that we have a QueryRoot and MutationRoot types.
     *
     * For each of the entries assigned to Root (RootObjectTypeResolver::class),
     * add a corresponding additional entry for QueryRoot and/or MutationRoot.
     *
     * Fields "id", "globalID", "self" and "__typename" can belong to both types.
     * Otherwise, the field is added to MutationRoot if it has a MutationResolver,
     * or to QueryRoot otherwise.
     *
     * The duplicated entry is duplicated as is, just changing what class it applies to.
     * Then it can be an entry for anything: Access Control, Cache Control, or any other.
     *
     * @param boolean $forceBothTypes Define if to always add it to both QueryRoot and MutationRoot, without checking if the field belongs to one or the other
     *                                This is needed when calling this function before the Schema has been configured, i.e. before finding FieldResolvers for each Type
     *
     * @return array<mixed[]> The same array $fieldEntries + appended entries for QueryRoot and MutationRoot
     * @param array<mixed[]> $fieldEntries
     */
    public function maybeAppendAdditionalRootEntriesForFields(array $fieldEntries, bool $forceBothTypes = false): array;
}
