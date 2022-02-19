<?php

declare(strict_types=1);

namespace PoP\ComponentModel\TypeResolvers\UnionType;

use PoP\ComponentModel\AttachableExtensions\AttachableExtensionGroups;
use PoP\ComponentModel\Component;
use PoP\ComponentModel\ComponentConfiguration;
use PoP\ComponentModel\Error\Error;
use PoP\ComponentModel\Exception\SchemaReferenceException;
use PoP\ComponentModel\Feedback\FeedbackItemResolution;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedback;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\FeedbackItemProviders\FeedbackItemProvider;
use PoP\ComponentModel\ObjectTypeResolverPickers\ObjectTypeResolverPickerInterface;
use PoP\ComponentModel\TypeResolvers\AbstractRelationalTypeResolver;
use PoP\ComponentModel\TypeResolvers\InterfaceType\InterfaceTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoP\GraphQLParser\StaticHelpers\LocationHelper;
use PoP\Root\App;

abstract class AbstractUnionTypeResolver extends AbstractRelationalTypeResolver implements UnionTypeResolverInterface
{
    /**
     * @var ObjectTypeResolverPickerInterface[]
     */
    protected ?array $objectTypeResolverPickers = null;

    public function getUnionTypeInterfaceTypeResolvers(): array
    {
        return [];
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
     * @param string|int|array<string|int> $dbObjectIDOrIDs
     * @return string|int|array<string|int>
     */
    public function getQualifiedDBObjectIDOrIDs(string | int | array $dbObjectIDOrIDs): string | int | array
    {
        $objectIDs = is_array($dbObjectIDOrIDs) ? $dbObjectIDOrIDs : [$dbObjectIDOrIDs];
        $objectIDTargetObjectTypeResolvers = $this->getObjectIDTargetTypeResolvers($objectIDs);
        $typeDBObjectIDOrIDs = [];
        foreach ($objectIDs as $objectID) {
            // Make sure there is a resolver for this object. If there is none, return the same ID
            $targetObjectTypeResolver = $objectIDTargetObjectTypeResolvers[$objectID] ?? null;
            if ($targetObjectTypeResolver !== null) {
                $typeDBObjectIDOrIDs[] = UnionTypeHelpers::getObjectComposedTypeAndID(
                    $targetObjectTypeResolver,
                    $objectID
                );
            } else {
                $typeDBObjectIDOrIDs[] = $objectID;
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

    /**
     * @param array<string|int> $ids
     * @return array<string|int,ObjectTypeResolverInterface|null>
     */
    public function getObjectIDTargetTypeResolvers(array $ids): array
    {
        return $this->recursiveGetObjectIDTargetTypeResolvers($this, $ids);
    }

    /**
     * @param array<string|int> $ids
     * @return array<string,ObjectTypeResolverInterface|null>
     */
    private function recursiveGetObjectIDTargetTypeResolvers(RelationalTypeResolverInterface $relationalTypeResolver, array $ids): array
    {
        if (!$ids) {
            return [];
        }

        $objectIDTargetTypeResolvers = [];
        $isUnionTypeResolver = $relationalTypeResolver instanceof UnionTypeResolverInterface;
        if ($isUnionTypeResolver) {
            /** @var UnionTypeResolverInterface $relationalTypeResolver */
            $targetTypeResolverNameDataItems = [];
            foreach ($ids as $objectID) {
                if ($targetObjectTypeResolver = $relationalTypeResolver->getObjectTypeResolverForObject($objectID)) {
                    $targetObjectTypeName = $targetObjectTypeResolver->getNamespacedTypeName();
                    $targetTypeResolverNameDataItems[$targetObjectTypeName] ??= [
                        'targetObjectTypeResolver' => $targetObjectTypeResolver,
                        'objectIDs' => [],
                    ];
                    $targetTypeResolverNameDataItems[$targetObjectTypeName]['objectIDs'][] = $objectID;
                } else {
                    $objectIDTargetTypeResolvers[(string)$objectID] = null;
                }
            }
            foreach ($targetTypeResolverNameDataItems as $targetObjectTypeName => $targetTypeResolverDataItems) {
                $targetObjectTypeResolver = $targetTypeResolverDataItems['targetObjectTypeResolver'];
                $objectIDs = $targetTypeResolverDataItems['objectIDs'];
                $targetObjectIDTargetTypeResolvers = $this->recursiveGetObjectIDTargetTypeResolvers(
                    $targetObjectTypeResolver,
                    $objectIDs
                );
                foreach ($targetObjectIDTargetTypeResolvers as $targetObjectID => $targetObjectTypeResolver) {
                    $objectIDTargetTypeResolvers[(string)$targetObjectID] = $targetObjectTypeResolver;
                }
            }
        } else {
            /** @var ObjectTypeResolverInterface */
            $objectTypeResolver = $relationalTypeResolver;
            foreach ($ids as $objectID) {
                $objectIDTargetTypeResolvers[(string)$objectID] = $objectTypeResolver;
            }
        }
        return $objectIDTargetTypeResolvers;
    }

    /**
     * Watch out! This function overrides the implementation from the for the AbstractRelationalTypeResolver
     *
     * Collect all directives for all fields, and then build a single directive pipeline for all fields,
     * including all directives, even if they don't apply to all fields
     * Eg: id|title<skip>|excerpt<translate> will produce a pipeline [Skip, Translate] where they apply
     * to different fields. After producing the pipeline, add the mandatory items
     */
    final public function enqueueFillingObjectsFromIDs(array $ids_data_fields): void
    {
        /**
         * This section is different from parent's implementation
         */
        // Watch out! The UnionType must obtain the mandatoryDirectivesForFields
        // from each of its target types!
        // This is mandatory, because the UnionType doesn't have fields by itself.
        // Otherwise, RelationalTypeResolverDecorators can't have their defined ACL rules
        // work when querying a union type (eg: "customPosts")
        $targetObjectTypeResolverClassMandatoryDirectivesForFields = [];
        $targetObjectTypeResolvers = $this->getTargetObjectTypeResolvers();
        foreach ($targetObjectTypeResolvers as $targetObjectTypeResolver) {
            $targetObjectTypeResolverClass = get_class($targetObjectTypeResolver);
            $targetObjectTypeResolverClassMandatoryDirectivesForFields[$targetObjectTypeResolverClass] = $targetObjectTypeResolver->getAllMandatoryDirectivesForFields();
        }
        // If the type data resolver is union, the dbKey where the value is stored
        // is contained in the ID itself, with format dbKey/ID.
        // Remove this information, and get purely the ID
        $objectIDs = array_map(
            function ($composedID) {
                list(
                    $dbKey,
                    $id
                ) = UnionTypeHelpers::extractDBObjectTypeAndID($composedID);
                return $id;
            },
            array_keys($ids_data_fields)
        );
        $objectIDTargetTypeResolvers = $this->getObjectIDTargetTypeResolvers($objectIDs);

        $mandatorySystemDirectives = $this->getMandatoryDirectives();
        foreach ($ids_data_fields as $id => $data_fields) {
            $fields = $this->getFieldsToEnqueueFillingObjectsFromIDs($data_fields);

            /**
             * This section is different from parent's implementation
             */
            list(
                $dbKey,
                $objectID
            ) = UnionTypeHelpers::extractDBObjectTypeAndID($id);
            $objectIDTargetTypeResolver = $objectIDTargetTypeResolvers[$objectID];
            $mandatoryDirectivesForFields = $targetObjectTypeResolverClassMandatoryDirectivesForFields[get_class($objectIDTargetTypeResolver)];

            $this->doEnqueueFillingObjectsFromIDs($fields, $mandatoryDirectivesForFields, $mandatorySystemDirectives, $id, $data_fields);
        }
    }

    /**
     * In order to enable elements from different types (such as posts and users) to have same ID,
     * add the type to the ID.
     *
     * @return string|int|null the ID of the passed object, or `null` if there is no resolver to handle it
     */
    public function getID(object $object): string | int | null
    {
        $targetObjectTypeResolver = $this->getTargetObjectTypeResolver($object);
        if ($targetObjectTypeResolver === null) {
            return null;
        }

        // Add the type to the ID, so that elements of different types can live side by side
        // The type will be removed again in `getIDsToQuery`
        return UnionTypeHelpers::getObjectComposedTypeAndID(
            $targetObjectTypeResolver,
            $targetObjectTypeResolver->getID($object)
        );
    }

    public function getTargetObjectTypeResolvers(): array
    {
        $objectTypeResolverPickers = $this->getObjectTypeResolverPickers();
        return $this->getObjectTypeResolversFromPickers($objectTypeResolverPickers);
    }

    protected function getObjectTypeResolversFromPickers(array $objectTypeResolverPickers): array
    {
        return array_map(
            fn (ObjectTypeResolverPickerInterface $typeResolverPicker) => $typeResolverPicker->getObjectTypeResolver(),
            $objectTypeResolverPickers
        );
    }

    /**
     * @return ObjectTypeResolverPickerInterface[]
     */
    public function getObjectTypeResolverPickers(): array
    {
        if (is_null($this->objectTypeResolverPickers)) {
            $this->objectTypeResolverPickers = $this->calculateTypeResolverPickers();
        }
        return $this->objectTypeResolverPickers;
    }

    protected function calculateTypeResolverPickers()
    {
        // Iterate classes from the current class towards the parent classes until finding typeResolver that satisfies processing this field
        $class = get_called_class();
        $objectTypeResolverPickers = [];
        do {
            // All the pickers and their priorities for this class level
            // Important: do array_reverse to enable more specific hooks, which are initialized later on in the project, to be the chosen ones (if their priority is the same)
            /** @var ObjectTypeResolverPickerInterface[] */
            $attachedTypeResolverPickers = array_reverse($this->getAttachableExtensionManager()->getAttachedExtensions($class, AttachableExtensionGroups::OBJECT_TYPE_RESOLVER_PICKERS));
            // Order them by priority: higher priority are evaluated first
            $extensionPriorities = array_map(
                fn (ObjectTypeResolverPickerInterface $typeResolverPicker) => $typeResolverPicker->getPriorityToAttachToClasses(),
                $attachedTypeResolverPickers
            );

            // Sort the found pickers by their priority, and then add to the stack of all pickers, for all classes
            // Higher priority means they execute first!
            array_multisort($extensionPriorities, SORT_DESC, SORT_NUMERIC, $attachedTypeResolverPickers);
            $objectTypeResolverPickers = array_merge(
                $objectTypeResolverPickers,
                $attachedTypeResolverPickers
            );
            // Continue iterating for the class parents
        } while ($class = get_parent_class($class));

        /**
         * Support Union Type implementing an Interface Type?
         * This functionality is not supported by the GraphQL spec.
         *
         * @see https://github.com/graphql/graphql-spec/issues/518
         *
         * It is disabled by default in this GraphQL server, because it can produce a runtime exception
         * when creating an Access Control List:
         *
         * - CustomPostUnionTypeResolver is set to implement IsCustomPostInterfaceType
         * - CustomPostUnionTypeResolver contains types PostObjectTypeResolver and PageObjectTypeResolver
         * - Via ACL in a private schema, we disable access to field "Post.author"
         * - Because IsCustomPostInterfaceType contains field "author", then Post suddenly
         *   does not satisfy this interface anymore
         * - But Post is still part of CustomPostUnion, then the code below will throw an exception
         *   stating that the member Post type does not implement the IsCustomPost interface!
         */
        /** @var ComponentConfiguration */
        $componentConfiguration = App::getComponent(Component::class)->getConfiguration();
        if ($componentConfiguration->enableUnionTypeImplementingInterfaceType()) {
            // Validate that all typeResolvers implement the required interface
            if ($interfaceTypeResolvers = $this->getUnionTypeInterfaceTypeResolvers()) {
                $notImplementingInterfaceTypeResolvers = [];
                foreach ($interfaceTypeResolvers as $interfaceTypeResolver) {
                    $objectTypeResolvers = $this->getObjectTypeResolversFromPickers($objectTypeResolverPickers);
                    $interfaceTypeResolverClass = get_class($interfaceTypeResolver);
                    $notImplementingInterfaceTypeResolvers = array_merge(
                        $notImplementingInterfaceTypeResolvers,
                        array_filter(
                            $objectTypeResolvers,
                            fn (ObjectTypeResolverInterface $objectTypeResolver) => !in_array(
                                $interfaceTypeResolverClass,
                                array_map(
                                    'get_class',
                                    $objectTypeResolver->getImplementedInterfaceTypeResolvers()
                                )
                            )
                        )
                    );
                }
                if ($notImplementingInterfaceTypeResolvers) {
                    throw new SchemaReferenceException(
                        sprintf(
                            $this->__('Union Type \'%s\' is defined to implement interface \'%s\', hence its Type members must also satisfy this interface, but the following ones do not: \'%s\'', 'component-model'),
                            $this->getMaybeNamespacedTypeName(),
                            $interfaceTypeResolver->getMaybeNamespacedTypeName(),
                            implode(
                                $this->__('\', \''),
                                array_map(
                                    fn (ObjectTypeResolverInterface $objectTypeResolver) => $objectTypeResolver->getMaybeNamespacedTypeName(),
                                    $notImplementingInterfaceTypeResolvers
                                )
                            )
                        )
                    );
                }
            }
        }

        // Return all the pickers
        return $objectTypeResolverPickers;
    }

    public function getObjectTypeResolverForObject(string | int $objectID): ?ObjectTypeResolverInterface
    {
        // Among all registered fieldresolvers, check if any is able to process the object, through function `process`
        // Important: iterate from back to front, because more general components (eg: Users) are defined first,
        // and dependent components (eg: Communities, Organizations) are defined later
        // Then, more specific implementations (eg: Organizations) must be queried before more general ones (eg: Communities)
        // This is not a problem by making the corresponding field processors inherit from each other, so that the more specific object also handles
        // the fields for the more general ones (eg: TypeResolver_OrganizationUsers extends TypeResolver_CommunityUsers, and TypeResolver_CommunityUsers extends UserObjectTypeResolver)
        foreach ($this->getObjectTypeResolverPickers() as $maybePicker) {
            if ($maybePicker->isIDOfType($objectID)) {
                // Found it!
                $typeResolverPicker = $maybePicker;
                return $typeResolverPicker->getObjectTypeResolver();
            }
        }

        return null;
    }

    public function getTargetObjectTypeResolverPicker(object $object): ?ObjectTypeResolverPickerInterface
    {
        // Among all registered fieldresolvers, check if any is able to process the object, through function `process`
        // Important: iterate from back to front, because more general components (eg: Users) are defined first,
        // and dependent components (eg: Communities, Organizations) are defined later
        // Then, more specific implementations (eg: Organizations) must be queried before more general ones (eg: Communities)
        // This is not a problem by making the corresponding field processors inherit from each other, so that the more specific object also handles
        // the fields for the more general ones (eg: TypeResolver_OrganizationUsers extends TypeResolver_CommunityUsers, and TypeResolver_CommunityUsers extends UserObjectTypeResolver)
        foreach ($this->getObjectTypeResolverPickers() as $maybePicker) {
            if ($maybePicker->isInstanceOfType($object)) {
                // Found it!
                return $maybePicker;
            }
        }
        return null;
    }

    public function getTargetObjectTypeResolver(object $object): ?ObjectTypeResolverInterface
    {
        if ($targetObjectTypeResolverPicker = $this->getTargetObjectTypeResolverPicker($object)) {
            return $targetObjectTypeResolverPicker->getObjectTypeResolver();
        }
        return null;
    }

    protected function getUnresolvedObjectIDError(string | int $objectID)
    {
        return new Error(
            'unresolved-resultitem-id',
            sprintf(
                $this->__('Either the DataLoader can\'t load data, or no TypeResolver resolves, object with ID \'%s\'', 'component-model'),
                (string) $objectID
            )
        );
    }

    /**
     * @param array<string, mixed> $variables
     * @param array<string, mixed> $expressions
     * @param array<string, mixed> $options
     */
    public function resolveValue(
        object $object,
        string $field,
        array $variables,
        array $expressions,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
        array $options = []
    ): mixed {
        // Check that a typeResolver from this Union can process this object, or return an arror
        $targetObjectTypeResolver = $this->getTargetObjectTypeResolver($object);
        if ($targetObjectTypeResolver === null) {
            $objectTypeFieldResolutionFeedbackStore->addError(
                new ObjectTypeFieldResolutionFeedback(
                    new FeedbackItemResolution(
                        FeedbackItemProvider::class,
                        FeedbackItemProvider::E8,
                        [
                            json_encode($object),
                        ]
                    ),
                    LocationHelper::getNonSpecificLocation(),
                    $this,
                )
            );
            return null;
        }

        // Delegate to that typeResolver to obtain the value
        // Because the schema validation cannot be performed through the UnionTypeResolver, since it depends on each dbObject, indicate that it must be done in resolveValue
        $options[self::OPTION_VALIDATE_SCHEMA_ON_RESULT_ITEM] = true;
        return $targetObjectTypeResolver->resolveValue(
            $object,
            $field,
            $variables,
            $expressions,
            $objectTypeFieldResolutionFeedbackStore,
            $options
        );
    }

    /**
     * The UnionTypeResolver itself does not implement interfaces.
     * @see https://github.com/graphql/graphql-spec/issues/518
     *
     * @return InterfaceTypeResolverInterface[]
     */
    public function getImplementedInterfaceTypeResolvers(): array
    {
        return [];
    }
}
