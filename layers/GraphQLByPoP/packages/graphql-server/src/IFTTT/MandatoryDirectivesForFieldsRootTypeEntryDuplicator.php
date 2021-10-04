<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\IFTTT;

use GraphQLByPoP\GraphQLServer\Helpers\TypeResolverHelperInterface;
use GraphQLByPoP\GraphQLServer\TypeResolvers\ObjectType\MutationRootObjectTypeResolver;
use GraphQLByPoP\GraphQLServer\TypeResolvers\ObjectType\QueryRootObjectTypeResolver;
use PoP\ComponentModel\Instances\InstanceManagerInterface;
use PoP\ComponentModel\State\ApplicationState;
use PoP\Engine\TypeResolvers\ObjectType\RootObjectTypeResolver;
use Symfony\Contracts\Service\Attribute\Required;

class MandatoryDirectivesForFieldsRootTypeEntryDuplicator implements MandatoryDirectivesForFieldsRootTypeEntryDuplicatorInterface
{
    /** @var string[] */
    protected array $objectTypeResolverMandatoryFields;
    protected InstanceManagerInterface $instanceManager;
    protected RootObjectTypeResolver $rootObjectTypeResolver;

    #[Required]
    final public function autowireMandatoryDirectivesForFieldsRootTypeEntryDuplicator(
        InstanceManagerInterface $instanceManager,
        RootObjectTypeResolver $rootObjectTypeResolver,
        TypeResolverHelperInterface $typeResolverHelper
    ): void {
        $this->instanceManager = $instanceManager;
        $this->rootObjectTypeResolver = $rootObjectTypeResolver;
        /** Fields "id", "self" and "__typename" belong to both QueryRoot and MutationRoot */
        $this->objectTypeResolverMandatoryFields = $typeResolverHelper->getObjectTypeResolverMandatoryFields();
    }

    /**
     * This function appends entries only when Nested Mutations is disabled,
     * so that we have a QueryRoot and MutationRoot types.
     *
     * For each of the entries assigned to Root (RootObjectTypeResolver::class),
     * add a corresponding additional entry for QueryRoot and/or MutationRoot.
     *
     * Fields "id", "self" and "__typename" can belong to both types.
     * Otherwise, the field is added to MutationRoot if it has a MutationResolver,
     * or to QueryRoot otherwise.
     *
     * The duplicated entry is duplicated as is, just changing what class it applies to.
     * Then it can be an entry for anything: Access Control, Cache Control, or any other.
     *
     * @param boolean $forceBothTypes Define if to always add it to both QueryRoot and MutationRoot, without checking if the field belongs to one or the other
     *                                This is needed when calling this function before the Schema has been configured, i.e. before finding FieldResolvers for each Type
     *
     * @return array The same array $fieldEntries + appended entries for QueryRoot and MutationRoot
     */
    public function maybeAppendAdditionalRootEntriesForFields(array $fieldEntries, bool $forceBothTypes = false): array
    {
        // With Nested Mutations there's no need to duplicate Root entries
        $vars = ApplicationState::getVars();
        if ($vars['nested-mutations-enabled']) {
            return $fieldEntries;
        }

        // Duplicate the Root entries into QueryRoot and/or MutationRoot
        return array_merge(
            $fieldEntries,
            $this->getAdditionalRootEntriesForFields($fieldEntries, $forceBothTypes)
        );
    }

    protected function getAdditionalRootEntriesForFields(array $fieldEntries, bool $forceBothTypes): array
    {
        // Get the entries assigned to Root
        $rootFieldEntries = $this->filterRootEntriesForFields($fieldEntries);
        if ($rootFieldEntries === []) {
            return [];
        }

        $additionalFieldEntries = [];

        foreach ($rootFieldEntries as $rootFieldEntry) {
            $fieldName = $rootFieldEntry[1];
            if ($forceBothTypes || in_array($fieldName, $this->objectTypeResolverMandatoryFields)) {
                $rootFieldEntry[0] = QueryRootObjectTypeResolver::class;
                $additionalFieldEntries[] = $rootFieldEntry;
                $rootFieldEntry[0] = MutationRootObjectTypeResolver::class;
                $additionalFieldEntries[] = $rootFieldEntry;
                continue;
            }
            // If it has a MutationResolver for that field then add entry for MutationRoot
            $isFieldAMutation = $this->rootObjectTypeResolver->isFieldAMutation($fieldName);
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
