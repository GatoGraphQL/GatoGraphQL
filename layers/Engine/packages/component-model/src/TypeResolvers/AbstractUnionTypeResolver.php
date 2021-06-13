<?php

declare(strict_types=1);

namespace PoP\ComponentModel\TypeResolvers;

use PoP\ComponentModel\AttachableExtensions\AttachableExtensionGroups;
use Exception;
use PoP\ComponentModel\ErrorHandling\Error;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\TypeResolvers\UnionTypeHelpers;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoP\ComponentModel\Facades\Schema\SchemaDefinitionServiceFacade;
use PoP\ComponentModel\TypeResolverPickers\TypeResolverPickerInterface;
use PoP\ComponentModel\Facades\AttachableExtensions\AttachableExtensionManagerFacade;

abstract class AbstractUnionTypeResolver extends AbstractTypeResolver implements UnionTypeResolverInterface
{
    /**
     * @var TypeResolverPickerInterface[]
     */
    protected ?array $typeResolverPickers = null;

    final public function getTypeOutputName(): string
    {
        return UnionTypeHelpers::getUnionTypeCollectionName(parent::getTypeOutputName());
    }

    public function getSchemaTypeInterfaceClass(): ?string
    {
        return null;
    }

    /**
     * Remove the type from the ID to resolve the objects through `getObjects` (check parent class)
     *
     * @return mixed[]
     */
    protected function getIDsToQuery(array $ids_data_fields): array
    {
        $ids = parent::getIDsToQuery($ids_data_fields);

        // Each ID contains the type (added in function `getID`). Remove it
        return array_map(
            [UnionTypeHelpers::class, 'extractDBObjectID'],
            $ids
        );
    }

    /**
     * @param $dbObjectIDOrIDs string|int|array<string|int>
     * @return string|int|array<string|int>
     */
    public function getQualifiedDBObjectIDOrIDs(string | int | array $dbObjectIDOrIDs): string | int | array
    {
        $dbObjectIDs = is_array($dbObjectIDOrIDs) ? $dbObjectIDOrIDs : [$dbObjectIDOrIDs];
        $resultItemIDTargetTypeResolvers = $this->getResultItemIDTargetTypeResolvers($dbObjectIDs);
        $typeDBObjectIDOrIDs = [];
        foreach ($dbObjectIDs as $resultItemID) {
            // Make sure there is a resolver for this resultItem. If there is none, return the same ID
            $targetTypeResolver = $resultItemIDTargetTypeResolvers[$resultItemID];
            if (!is_null($targetTypeResolver)) {
                $typeDBObjectIDOrIDs[] = UnionTypeHelpers::getDBObjectComposedTypeAndID(
                    $targetTypeResolver,
                    $resultItemID
                );
            } else {
                $typeDBObjectIDOrIDs[] = $resultItemID;
            }
        }
        if (!is_array($dbObjectIDOrIDs)) {
            return $typeDBObjectIDOrIDs[0];
        }
        return $typeDBObjectIDOrIDs;
    }

    public function qualifyDBObjectIDsToRemoveFromErrors(): bool
    {
        return true;
    }

    public function getResultItemIDTargetTypeResolvers(array $ids): array
    {
        return $this->recursiveGetResultItemIDTargetTypeResolvers($this, $ids);
    }

    private function recursiveGetResultItemIDTargetTypeResolvers(TypeResolverInterface $typeResolver, array $ids): array
    {
        if (!$ids) {
            return [];
        }

        $resultItemIDTargetTypeResolvers = [];
        $isUnionTypeResolver = $typeResolver instanceof UnionTypeResolverInterface;
        if ($isUnionTypeResolver) {
            /** @var UnionTypeResolverInterface */
            $typeResolver = $typeResolver;
            $targetTypeResolverClassDataItems = [];
            foreach ($ids as $resultItemID) {
                if ($targetTypeResolverClass = $typeResolver->getTypeResolverClassForResultItem($resultItemID)) {
                    $targetTypeResolverClassDataItems[$targetTypeResolverClass][] = $resultItemID;
                } else {
                    $resultItemIDTargetTypeResolvers[(string)$resultItemID] = null;
                }
            }
            foreach ($targetTypeResolverClassDataItems as $targetTypeResolverClass => $resultItemIDs) {
                $targetTypeResolver = $this->instanceManager->getInstance($targetTypeResolverClass);
                $targetResultItemIDTargetTypeResolvers = $this->recursiveGetResultItemIDTargetTypeResolvers(
                    $targetTypeResolver,
                    $resultItemIDs
                );
                foreach ($targetResultItemIDTargetTypeResolvers as $targetResultItemID => $targetTypeResolver) {
                    $resultItemIDTargetTypeResolvers[(string)$targetResultItemID] = $targetTypeResolver;
                }
            }
        } else {
            foreach ($ids as $resultItemID) {
                $resultItemIDTargetTypeResolvers[(string)$resultItemID] = $typeResolver;
            }
        }
        return $resultItemIDTargetTypeResolvers;
    }

    // /**
    //  * Add the type to the ID
    //  */
    // public function addTypeToID(string | int $resultItemID): string
    // {
    //     if ($resultItemTypeResolverClass = $this->getTypeResolverClassForResultItem($resultItemID)) {
    //         $resultItemTypeResolver = $this->instanceManager->getInstance($resultItemTypeResolverClass);
    //         return UnionTypeHelpers::getDBObjectComposedTypeAndID(
    //             $resultItemTypeResolver,
    //             $resultItemID
    //         );
    //     }
    //     return (string)$resultItemID;
    // }

    /**
     * Watch out! This function overrides the implementation from the for the AbstractTypeResolver
     *
     * Collect all directives for all fields, and then build a single directive pipeline for all fields,
     * including all directives, even if they don't apply to all fields
     * Eg: id|title<skip>|excerpt<translate> will produce a pipeline [Skip, Translate] where they apply
     * to different fields. After producing the pipeline, add the mandatory items
     */
    final public function enqueueFillingResultItemsFromIDs(array $ids_data_fields): void
    {
        /**
         * This section is different from parent's implementation
         */
        // Watch out! The UnionType must obtain the mandatoryDirectivesForFields
        // from each of its target types!
        // This is mandatory, because the UnionType doesn't have fields by itself.
        // Otherwise, TypeResolverDecorators can't have their defined ACL rules
        // work when querying a union type (eg: "customPosts")
        $targetTypeResolverClassMandatoryDirectivesForFields = [];
        $targetTypeResolverClasses = $this->getTargetTypeResolverClasses();
        foreach ($targetTypeResolverClasses as $targetTypeResolverClass) {
            $targetTypeResolver = $this->instanceManager->getInstance($targetTypeResolverClass);
            $targetTypeResolverClassMandatoryDirectivesForFields[$targetTypeResolverClass] = $targetTypeResolver->getAllMandatoryDirectivesForFields();
        }
        // If the type data resolver is union, the dbKey where the value is stored
        // is contained in the ID itself, with format dbKey/ID.
        // Remove this information, and get purely the ID
        $resultItemIDs = array_map(
            function ($composedID) {
                list(
                    $dbKey,
                    $id
                ) = UnionTypeHelpers::extractDBObjectTypeAndID($composedID);
                return $id;
            },
            array_keys($ids_data_fields)
        );
        $resultItemIDTargetTypeResolvers = $this->getResultItemIDTargetTypeResolvers($resultItemIDs);

        $mandatorySystemDirectives = $this->getMandatoryDirectives();
        foreach ($ids_data_fields as $id => $data_fields) {
            $fields = $this->getFieldsToEnqueueFillingResultItemsFromIDs($data_fields);

            /**
             * This section is different from parent's implementation
             */
            list(
                $dbKey,
                $resultItemID
            ) = UnionTypeHelpers::extractDBObjectTypeAndID($id);
            $resultItemIDTargetTypeResolver = $resultItemIDTargetTypeResolvers[$resultItemID];
            $mandatoryDirectivesForFields = $targetTypeResolverClassMandatoryDirectivesForFields[get_class($resultItemIDTargetTypeResolver)];

            $this->doEnqueueFillingResultItemsFromIDs($fields, $mandatoryDirectivesForFields, $mandatorySystemDirectives, $id, $data_fields);
        }
    }

    /**
     * In order to enable elements from different types (such as posts and users) to have same ID,
     * add the type to the ID
     */
    public function getID(object $resultItem): string | int
    {
        $targetTypeResolver = $this->getTargetTypeResolver($resultItem);
        if (is_null($targetTypeResolver)) {
            return null;
        }

        // Add the type to the ID, so that elements of different types can live side by side
        // The type will be removed again in `getIDsToQuery`
        return UnionTypeHelpers::getDBObjectComposedTypeAndID(
            $targetTypeResolver,
            $targetTypeResolver->getID($resultItem)
        );
    }

    public function getTargetTypeResolverClasses(): array
    {
        $typeResolverPickers = $this->getTypeResolverPickers();
        return $this->getTypeResolverClassesFromPickers($typeResolverPickers);
    }

    protected function getTypeResolverClassesFromPickers(array $typeResolverPickers): array
    {
        return array_map(
            function ($typeResolverPicker) {
                return $typeResolverPicker->getTypeResolverClass();
            },
            $typeResolverPickers
        );
    }

    public function getTypeResolverPickers(): array
    {
        if (is_null($this->typeResolverPickers)) {
            $this->typeResolverPickers = $this->calculateTypeResolverPickers();
        }
        return $this->typeResolverPickers;
    }

    protected function calculateTypeResolverPickers()
    {
        $attachableExtensionManager = AttachableExtensionManagerFacade::getInstance();
        // Iterate classes from the current class towards the parent classes until finding typeResolver that satisfies processing this field
        $class = get_called_class();
        $typeResolverPickers = [];
        do {
            // All the pickers and their priorities for this class level
            // Important: do array_reverse to enable more specific hooks, which are initialized later on in the project, to be the chosen ones (if their priority is the same)
            /** @var TypeResolverPickerInterface[] */
            $attachedTypeResolverPickers = array_reverse($attachableExtensionManager->getAttachedExtensions($class, AttachableExtensionGroups::TYPERESOLVERPICKERS));
            // Order them by priority: higher priority are evaluated first
            $extensionPriorities = array_map(
                fn (TypeResolverPickerInterface $typeResolverPicker) => $typeResolverPicker->getPriorityToAttachToClasses(),
                $attachedTypeResolverPickers
            );

            // Sort the found pickers by their priority, and then add to the stack of all pickers, for all classes
            // Higher priority means they execute first!
            array_multisort($extensionPriorities, SORT_DESC, SORT_NUMERIC, $attachedTypeResolverPickers);
            $typeResolverPickers = array_merge(
                $typeResolverPickers,
                $attachedTypeResolverPickers
            );
            // Continue iterating for the class parents
        } while ($class = get_parent_class($class));

        // Validate that all typeResolvers implement the required interface
        if ($typeInterfaceClass = $this->getSchemaTypeInterfaceClass()) {
            $typeResolverClasses = $this->getTypeResolverClassesFromPickers($typeResolverPickers);
            $notImplementingInterfaceTypeResolverClasses = array_filter(
                $typeResolverClasses,
                function ($typeResolverClass) use ($typeInterfaceClass) {
                    /**
                     * @var TypeResolverInterface
                     */
                    $typeResolver = $this->instanceManager->getInstance($typeResolverClass);
                    return !in_array($typeInterfaceClass, $typeResolver->getAllImplementedInterfaceClasses());
                }
            );
            if ($notImplementingInterfaceTypeResolverClasses) {
                $typeInterfaceResolver = $this->instanceManager->getInstance($typeInterfaceClass);
                throw new Exception(
                    sprintf(
                        $this->translationAPI->__('UnionTypeResolver \'%s\' (\'%s\') must return results implementing interface \'%s\' (\'%s\'), however its following member TypeResolvers do not: \'%s\'', 'component-model'),
                        $this->getMaybeNamespacedTypeName(),
                        get_called_class(),
                        $typeInterfaceResolver->getMaybeNamespacedInterfaceName(),
                        $typeInterfaceClass,
                        implode(
                            $this->translationAPI->__('\', \''),
                            array_map(
                                function ($typeResolverClass) {
                                    /**
                                     * @var TypeResolverInterface
                                     */
                                    $typeResolver = $this->instanceManager->getInstance($typeResolverClass);
                                    return sprintf(
                                        $this->translationAPI->__('%s (%s)'),
                                        $typeResolver->getMaybeNamespacedTypeName(),
                                        $typeResolverClass
                                    );
                                },
                                $notImplementingInterfaceTypeResolverClasses
                            )
                        )
                    )
                );
            }
        }

        // Return all the pickers
        return $typeResolverPickers;
    }

    public function getTypeResolverClassForResultItem(string | int $resultItemID)
    {
        // Among all registered fieldresolvers, check if any is able to process the object, through function `process`
        // Important: iterate from back to front, because more general components (eg: Users) are defined first,
        // and dependent components (eg: Communities, Organizations) are defined later
        // Then, more specific implementations (eg: Organizations) must be queried before more general ones (eg: Communities)
        // This is not a problem by making the corresponding field processors inherit from each other, so that the more specific object also handles
        // the fields for the more general ones (eg: TypeResolver_OrganizationUsers extends TypeResolver_CommunityUsers, and TypeResolver_CommunityUsers extends UserTypeResolver)
        foreach ($this->getTypeResolverPickers() as $maybePicker) {
            if ($maybePicker->isIDOfType($resultItemID)) {
                // Found it!
                $typeResolverPicker = $maybePicker;
                return $typeResolverPicker->getTypeResolverClass();
            }
        }

        return null;
    }

    public function getTargetTypeResolverPicker(object $resultItem): ?TypeResolverPickerInterface
    {
        // Among all registered fieldresolvers, check if any is able to process the object, through function `process`
        // Important: iterate from back to front, because more general components (eg: Users) are defined first,
        // and dependent components (eg: Communities, Organizations) are defined later
        // Then, more specific implementations (eg: Organizations) must be queried before more general ones (eg: Communities)
        // This is not a problem by making the corresponding field processors inherit from each other, so that the more specific object also handles
        // the fields for the more general ones (eg: TypeResolver_OrganizationUsers extends TypeResolver_CommunityUsers, and TypeResolver_CommunityUsers extends UserTypeResolver)
        foreach ($this->getTypeResolverPickers() as $maybePicker) {
            if ($maybePicker->isInstanceOfType($resultItem)) {
                // Found it!
                return $maybePicker;
            }
        }
        return null;
    }

    public function getTargetTypeResolver(object $resultItem): ?TypeResolverInterface
    {
        if ($typeResolverPicker = $this->getTargetTypeResolverPicker($resultItem)) {
            $typeResolverClass = $typeResolverPicker->getTypeResolverClass();
            /**
             * @var TypeResolverInterface
             */
            $typeResolver = $this->instanceManager->getInstance($typeResolverClass);
            return $typeResolver;
        }
        return null;
    }

    protected function getUnresolvedResultItemIDError(string | int $resultItemID)
    {
        return new Error(
            'unresolved-resultitem-id',
            sprintf(
                $this->translationAPI->__('Either the DataLoader can\'t load data, or no TypeResolver resolves, object with ID \'%s\'', 'pop-component-model'),
                (string) $resultItemID
            )
        );
    }

    protected function getUnresolvedResultItemError(object $resultItem): Error
    {
        return new Error(
            'unresolved-resultitem',
            sprintf(
                $this->translationAPI->__('No TypeResolver resolves object \'%s\'', 'pop-component-model'),
                json_encode($resultItem)
            )
        );
    }

    /**
     * @param array<string, mixed>|null $variables
     * @param array<string, mixed>|null $expressions
     * @param array<string, mixed> $options
     */
    public function resolveValue(
        object $resultItem,
        string $field,
        ?array $variables = null,
        ?array $expressions = null,
        array $options = []
    ): mixed {
        // Check that a typeResolver from this Union can process this resultItem, or return an arror
        $targetTypeResolver = $this->getTargetTypeResolver($resultItem);
        if (is_null($targetTypeResolver)) {
            return $this->getUnresolvedResultItemError($resultItem);
        }
        // Delegate to that typeResolver to obtain the value
        // Because the schema validation cannot be performed through the UnionTypeResolver, since it depends on each dbObject, indicate that it must be done in resolveValue
        $options[self::OPTION_VALIDATE_SCHEMA_ON_RESULT_ITEM] = true;
        return $targetTypeResolver->resolveValue($resultItem, $field, $variables, $expressions, $options);
    }

    protected function addSchemaDefinition(array $stackMessages, array &$generalMessages, array $options = [])
    {
        $schemaDefinitionService = SchemaDefinitionServiceFacade::getInstance();
        $typeSchemaKey = $schemaDefinitionService->getTypeSchemaKey($this);

        // Properties
        $this->schemaDefinition[$typeSchemaKey][SchemaDefinition::ARGNAME_NAME] = $this->getMaybeNamespacedTypeName();
        $this->schemaDefinition[$typeSchemaKey][SchemaDefinition::ARGNAME_NAMESPACED_NAME] = $this->getNamespacedTypeName();
        $this->schemaDefinition[$typeSchemaKey][SchemaDefinition::ARGNAME_ELEMENT_NAME] = $this->getTypeName();
        if ($description = $this->getSchemaTypeDescription()) {
            $this->schemaDefinition[$typeSchemaKey][SchemaDefinition::ARGNAME_DESCRIPTION] = $description;
        }
        $this->schemaDefinition[$typeSchemaKey][SchemaDefinition::ARGNAME_IS_UNION] = true;

        // If it returns an interface as type, add it to the schemaDefinition
        if ($typeInterfaceClass = $this->getSchemaTypeInterfaceClass()) {
            $typeInterfaceResolver = $this->instanceManager->getInstance($typeInterfaceClass);
            $this->schemaDefinition[$typeSchemaKey][SchemaDefinition::ARGNAME_RESULTS_IMPLEMENT_INTERFACE] = $typeInterfaceResolver->getMaybeNamespacedInterfaceName();
        }

        // Iterate through the typeResolvers from all the pickers and get their schema definitions
        foreach ($this->getTypeResolverPickers() as $picker) {
            $pickerTypeResolver = $this->instanceManager->getInstance($picker->getTypeResolverClass());
            $pickerTypeSchemaDefinition = $pickerTypeResolver->getSchemaDefinition($stackMessages, $generalMessages, $options);
            $pickerTypeName = $pickerTypeResolver->getMaybeNamespacedTypeName();
            $this->schemaDefinition[$typeSchemaKey][SchemaDefinition::ARGNAME_POSSIBLE_TYPES][$pickerTypeName] = $pickerTypeSchemaDefinition[$pickerTypeName];
        }
    }

    protected function processFlatShapeSchemaDefinition(array $options = [])
    {
        parent::processFlatShapeSchemaDefinition($options);

        $schemaDefinitionService = SchemaDefinitionServiceFacade::getInstance();
        $typeSchemaKey = $schemaDefinitionService->getTypeSchemaKey($this);

        // Replace the UnionTypeResolver's types with their typeNames
        $this->schemaDefinition[$typeSchemaKey][SchemaDefinition::ARGNAME_POSSIBLE_TYPES] = array_keys(
            $this->schemaDefinition[$typeSchemaKey][SchemaDefinition::ARGNAME_POSSIBLE_TYPES]
        );
    }

    /**
     * Because the UnionTypeResolver doesn't know yet which TypeResolver will be used (that depends on each resultItem), it can't resolve error validation
     */
    public function resolveSchemaValidationErrorDescriptions(string $field, array &$variables = null): array
    {
        return [];
    }
}
