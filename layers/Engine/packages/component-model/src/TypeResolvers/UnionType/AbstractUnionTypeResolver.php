<?php

declare(strict_types=1);

namespace PoP\ComponentModel\TypeResolvers\UnionType;

use PoP\ComponentModel\AttachableExtensions\AttachableExtensionGroups;
use PoP\ComponentModel\Engine\EngineIterationFieldSet;
use PoP\ComponentModel\Exception\SchemaReferenceException;
use PoP\ComponentModel\FeedbackItemProviders\ErrorFeedbackItemProvider;
use PoP\ComponentModel\Feedback\EngineIterationFeedbackStore;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedback;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\Feedback\SchemaFeedback;
use PoP\ComponentModel\Module;
use PoP\ComponentModel\ModuleConfiguration;
use PoP\ComponentModel\ObjectTypeResolverPickers\ObjectTypeResolverPickerInterface;
use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\ComponentModel\TypeResolvers\AbstractRelationalTypeResolver;
use PoP\ComponentModel\TypeResolvers\InterfaceType\InterfaceTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\Directive;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
use PoP\Root\App;
use PoP\ComponentModel\Feedback\FeedbackItemResolution;
use SplObjectStorage;

abstract class AbstractUnionTypeResolver extends AbstractRelationalTypeResolver implements UnionTypeResolverInterface
{
    /**
     * @var ObjectTypeResolverPickerInterface[]|null
     */
    protected ?array $objectTypeResolverPickers = null;

    /**
     * @var SplObjectStorage<ObjectTypeResolverInterface,SplObjectStorage<FieldInterface,SplObjectStorage<ObjectTypeResolverInterface,SplObjectStorage<object,array<string,mixed>>>|null>>
     */
    protected SplObjectStorage $fieldObjectTypeResolverObjectFieldDataCache;

    public function __construct()
    {
        $this->fieldObjectTypeResolverObjectFieldDataCache = new SplObjectStorage();
        parent::__construct();
    }

    /**
     * @return InterfaceTypeResolverInterface[]
     */
    public function getUnionTypeInterfaceTypeResolvers(): array
    {
        return [];
    }

    /**
     * Remove the type from the ID to resolve the objects through `getObjects` (check parent class)
     *
     * @param array<string|int,EngineIterationFieldSet> $idFieldSet
     * @return array<string|int>
     */
    protected function getIDsToQuery(array $idFieldSet): array
    {
        // Each ID contains the type (added in function `getID`). Remove it
        return array_map(
            UnionTypeHelpers::extractDBObjectID(...),
            parent::getIDsToQuery($idFieldSet)
        );
    }

    /**
     * @param array<string|int> $objectIDs
     * @return array<string|int>
     */
    protected function getResolvedObjectIDs(array $objectIDs): array
    {
        // Each ID contains the type (added in function `getID`). Remove it
        return array_map(
            UnionTypeHelpers::extractDBObjectID(...),
            parent::getResolvedObjectIDs($objectIDs)
        );
    }

    /**
     * @param string|int|array<string|int> $objectIDOrIDs
     * @return string|int|array<string|int>
     */
    public function getQualifiedDBObjectIDOrIDs(string|int|array $objectIDOrIDs): string|int|array
    {
        $objectIDs = is_array($objectIDOrIDs) ? $objectIDOrIDs : [$objectIDOrIDs];
        $objectIDTargetObjectTypeResolvers = $this->getObjectIDTargetTypeResolvers($objectIDs);
        $typeDBObjectIDOrIDs = [];
        foreach ($objectIDs as $objectID) {
            // Make sure there is a resolver for this object. If there is none, return the same ID
            $targetObjectTypeResolver = $objectIDTargetObjectTypeResolvers[$objectID];
            if ($targetObjectTypeResolver === null) {
                $typeDBObjectIDOrIDs[] = $objectID;
                continue;
            }
            $typeDBObjectIDOrIDs[] = UnionTypeHelpers::getObjectComposedTypeAndID(
                $targetObjectTypeResolver,
                $objectID
            );
        }
        if (!is_array($objectIDOrIDs)) {
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
     * @return array<string|int,ObjectTypeResolverInterface|null>
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
                $targetObjectTypeResolver = $relationalTypeResolver->getObjectTypeResolverForObject($objectID);
                if ($targetObjectTypeResolver === null) {
                    $objectIDTargetTypeResolvers[$objectID] = null;
                    continue;
                }
                $targetObjectTypeName = $targetObjectTypeResolver->getNamespacedTypeName();
                $targetTypeResolverNameDataItems[$targetObjectTypeName] ??= [
                    'targetObjectTypeResolver' => $targetObjectTypeResolver,
                    'objectIDs' => [],
                ];
                $targetTypeResolverNameDataItems[$targetObjectTypeName]['objectIDs'][] = $objectID;
            }
            foreach ($targetTypeResolverNameDataItems as $targetObjectTypeName => $targetTypeResolverDataItems) {
                $targetObjectTypeResolver = $targetTypeResolverDataItems['targetObjectTypeResolver'];
                $objectIDs = $targetTypeResolverDataItems['objectIDs'];
                $targetObjectIDTargetTypeResolvers = $this->recursiveGetObjectIDTargetTypeResolvers(
                    $targetObjectTypeResolver,
                    $objectIDs
                );
                /**
                 * For some weird reason, doing `array_merge([], [1 => ...])`
                 * produces `[0 => ...]` (i.e. ID "1" was changed to "0"),
                 * so then commented the code below, and instead iterate
                 * and explicitly set all key => values
                 */
                // $objectIDTargetTypeResolvers = array_merge(
                //     $objectIDTargetTypeResolvers,
                //     $targetObjectIDTargetTypeResolvers
                // );
                foreach ($targetObjectIDTargetTypeResolvers as $targetObjectID => $targetObjectTypeResolver) {
                    $objectIDTargetTypeResolvers[$targetObjectID] = $targetObjectTypeResolver;
                }
            }
            return $objectIDTargetTypeResolvers;
        }
        /** @var ObjectTypeResolverInterface */
        $objectTypeResolver = $relationalTypeResolver;
        foreach ($ids as $objectID) {
            $objectIDTargetTypeResolvers[$objectID] = $objectTypeResolver;
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
     *
     * @param array<string|int,EngineIterationFieldSet> $idFieldSet
     */
    final public function enqueueFillingObjectsFromIDs(array $idFieldSet): void
    {
        /**
         * This section is different from parent's implementation
         */
        // Watch out! The UnionType must obtain the mandatoryDirectivesForFields
        // from each of its target types!
        // This is mandatory, because the UnionType doesn't have fields by itself.
        // Otherwise, RelationalTypeResolverDecorators can't have their defined ACL rules
        // work when querying a union type (eg: "customPosts")
        /** @var array<string,array<string,Directive[]>> */
        $targetObjectTypeResolverClassMandatoryDirectivesForFields = [];
        $targetObjectTypeResolvers = $this->getTargetObjectTypeResolvers();
        foreach ($targetObjectTypeResolvers as $targetObjectTypeResolver) {
            $targetObjectTypeResolverClass = get_class($targetObjectTypeResolver);
            $targetObjectTypeResolverClassMandatoryDirectivesForFields[$targetObjectTypeResolverClass] = $targetObjectTypeResolver->getAllMandatoryDirectivesForFields();
        }
        /**
         * If the type data resolver is union, the typeOutputKey where the value is stored
         * is contained in the ID itself, with format typeOutputKey/ID.
         * Remove this information, and get purely the ID
         */
        $objectIDs = array_map(
            fn (string|int $maybeComposedID) => UnionTypeHelpers::extractObjectTypeAndID($maybeComposedID)[1],
            array_keys($idFieldSet)
        );
        $objectIDTargetTypeResolvers = $this->getObjectIDTargetTypeResolvers($objectIDs);

        $mandatorySystemDirectives = $this->getMandatoryDirectives();
        foreach ($idFieldSet as $id => $fieldSet) {
            $fields = $this->getFieldsToEnqueueFillingObjectsFromIDs($fieldSet);

            /**
             * This section is different from parent's implementation
             */
            list(
                $typeOutputKey,
                $objectID
            ) = UnionTypeHelpers::extractObjectTypeAndID($id);
            $objectIDTargetTypeResolver = $objectIDTargetTypeResolvers[$objectID];
            if ($objectIDTargetTypeResolver === null) {
                continue;
            }
            $mandatoryDirectivesForFields = $targetObjectTypeResolverClassMandatoryDirectivesForFields[get_class($objectIDTargetTypeResolver)];

            $this->doEnqueueFillingObjectsFromIDs($fields, $mandatoryDirectivesForFields, $mandatorySystemDirectives, $id, $fieldSet);
        }
    }

    /**
     * In order to enable elements from different types (such as posts and users) to have same ID,
     * add the type to the ID.
     *
     * @return string|int|null the ID of the passed object, or `null` if there is no resolver to handle it
     */
    public function getID(object $object): string|int|null
    {
        $targetObjectTypeResolver = $this->getTargetObjectTypeResolver($object);
        if ($targetObjectTypeResolver === null) {
            return null;
        }

        /** @var string|int */
        $objectID = $targetObjectTypeResolver->getID($object);

        // Add the type to the ID, so that elements of different types can live side by side
        // The type will be removed again in `getIDsToQuery`
        return UnionTypeHelpers::getObjectComposedTypeAndID(
            $targetObjectTypeResolver,
            $objectID
        );
    }

    /**
     * @return ObjectTypeResolverInterface[]
     */
    public function getTargetObjectTypeResolvers(): array
    {
        $objectTypeResolverPickers = $this->getObjectTypeResolverPickers();
        return $this->getObjectTypeResolversFromPickers($objectTypeResolverPickers);
    }

    /**
     * @return array<string,ObjectTypeResolverInterface> Key: TypeOutputKey, Value: ObjectTypeResolver
     */
    public function getTargetTypeOutputKeyObjectTypeResolvers(): array
    {
        $targetTypeOutputKeyObjectTypeResolvers = [];
        foreach ($this->getTargetObjectTypeResolvers() as $objectTypeResolver) {
            $targetTypeOutputKeyObjectTypeResolvers[$objectTypeResolver->getTypeOutputKey()] = $objectTypeResolver;
        }
        return $targetTypeOutputKeyObjectTypeResolvers;
    }

    /**
     * @return ObjectTypeResolverInterface[]
     * @param ObjectTypeResolverPickerInterface[] $objectTypeResolverPickers
     */
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

    /**
     * @return ObjectTypeResolverPickerInterface[]
     */
    protected function calculateTypeResolverPickers(): array
    {
        $attachableExtensionManager = $this->getAttachableExtensionManager();

        // Iterate classes from the current class towards the parent classes until finding typeResolver that satisfies processing this field
        $class = get_called_class();
        $objectTypeResolverPickers = [];
        do {
            // All the pickers and their priorities for this class level
            // Important: do array_reverse to enable more specific hooks, which are initialized later on in the project, to be the chosen ones (if their priority is the same)
            /** @var ObjectTypeResolverPickerInterface[] */
            $attachedTypeResolverPickers = array_reverse($attachableExtensionManager->getAttachedExtensions($class, AttachableExtensionGroups::OBJECT_TYPE_RESOLVER_PICKERS));
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
         * - CustomPostUnionTypeResolver is set to implement CustomPostInterfaceType
         * - CustomPostUnionTypeResolver contains types PostObjectTypeResolver and PageObjectTypeResolver
         * - Via ACL in a private schema, we disable access to field "Post.author"
         * - Because CustomPostInterfaceType contains field "author", then Post suddenly
         *   does not satisfy this interface anymore
         * - But Post is still part of CustomPostUnion, then the code below will throw an exception
         *   stating that the member Post type does not implement the CustomPost interface!
         */
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        if ($moduleConfiguration->enableUnionTypeImplementingInterfaceType()) {
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
                                    get_class(...),
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

    public function getObjectTypeResolverForObject(string|int $objectID): ?ObjectTypeResolverInterface
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

    protected function getUnresolvedObjectIDErrorFeedbackItemResolution(string|int $objectID): FeedbackItemResolution
    {
        return new FeedbackItemResolution(
            ErrorFeedbackItemProvider::class,
            ErrorFeedbackItemProvider::E10,
            [
                $this->getMaybeNamespacedTypeName(),
                $objectID
            ]
        );
    }

    public function resolveValue(
        object $object,
        FieldInterface|FieldDataAccessorInterface $fieldOrFieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): mixed {
        // Check that a typeResolver from this Union can process this object, or return an arror
        $targetObjectTypeResolver = $this->getTargetObjectTypeResolver($object);
        if ($targetObjectTypeResolver === null) {
            $field = $fieldOrFieldDataAccessor instanceof FieldInterface
                ? $fieldOrFieldDataAccessor
                : $fieldOrFieldDataAccessor->getField();
            $objectTypeFieldResolutionFeedbackStore->addError(
                new ObjectTypeFieldResolutionFeedback(
                    new FeedbackItemResolution(
                        ErrorFeedbackItemProvider::class,
                        ErrorFeedbackItemProvider::E8
                    ),
                    $field,
                )
            );
            return null;
        }

        return $targetObjectTypeResolver->resolveValue(
            $object,
            $fieldOrFieldDataAccessor,
            $objectTypeFieldResolutionFeedbackStore,
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

    /**
     * Convert the FieldArgs into its corresponding FieldDataAccessor, which integrates
     * within the default values and coerces them according to the schema.
     *
     * @see FieldDataAccessProvider
     *
     * @param array<string|int> $ids
     * @param array<string|int,object> $idObjects
     * @return SplObjectStorage<ObjectTypeResolverInterface,SplObjectStorage<object,array<string,mixed>>>|null
     */
    protected function doGetObjectTypeResolverObjectFieldData(
        FieldInterface $field,
        array $ids,
        array $idObjects,
        EngineIterationFeedbackStore $engineIterationFeedbackStore,
    ): ?SplObjectStorage {
        /**
         * Group the objects by ObjectTypeResolver
         *
         * @var SplObjectStorage<ObjectTypeResolverInterface,array<string|int>>
         */
        $objectTypeResolverObjectIDs = new SplObjectStorage();

        foreach ($ids as $id) {
            $object = $idObjects[$id];
            $targetObjectTypeResolver = $this->getTargetObjectTypeResolver($object);
            /**
             * If the object is not handled, then nothing to do
             */
            if ($targetObjectTypeResolver === null) {
                continue;
            }
            $objectIDs = $objectTypeResolverObjectIDs[$targetObjectTypeResolver] ?? [];
            $objectIDs[] = $id;
            $objectTypeResolverObjectIDs[$targetObjectTypeResolver] = $objectIDs;
        }

        /** @var SplObjectStorage<ObjectTypeResolverInterface,SplObjectStorage<object,array<string,mixed>>> */
        $objectTypeResolverObjectFieldData = new SplObjectStorage();
        $hasError = false;

        /**
         * Obtain the fieldArgs from each of the ObjectTypeResolvers,
         * for their corresponding objects
         */
        /** @var ObjectTypeResolverInterface $targetObjectTypeResolver */
        foreach ($objectTypeResolverObjectIDs as $targetObjectTypeResolver) {
            $executableObjectTypeFieldResolver = $targetObjectTypeResolver->getExecutableObjectTypeFieldResolverForField($field);
            /**
             * If the field does not exist, then nothing to do
             */
            if ($executableObjectTypeFieldResolver === null) {
                $hasError = true;
                /**
                 * If the field does not exist in the schema, then add an error the first
                 * time, and retrieve it from the cache from then on, so the error is
                 * not added more than once to the response.
                 *
                 * @var SplObjectStorage<FieldInterface,SplObjectStorage<ObjectTypeResolverInterface,SplObjectStorage<object,array<string,mixed>>>|null>
                 */
                $fieldObjectTypeResolverObjectFieldData = $this->fieldObjectTypeResolverObjectFieldDataCache[$targetObjectTypeResolver] ?? new SplObjectStorage();
                if (!$fieldObjectTypeResolverObjectFieldData->contains($field)) {
                    $fieldObjectTypeResolverObjectFieldData[$field] = null;
                    $engineIterationFeedbackStore->schemaFeedbackStore->addError(
                        new SchemaFeedback(
                            $targetObjectTypeResolver->getFieldNotResolvedByObjectTypeFeedbackItemResolution($field),
                            $field,
                            $this,
                            [$field],
                        )
                    );
                }
                $this->fieldObjectTypeResolverObjectFieldDataCache[$targetObjectTypeResolver] = $fieldObjectTypeResolverObjectFieldData;
                continue;
            }

            if (!$executableObjectTypeFieldResolver->validateMutationOnObject($targetObjectTypeResolver, $field->getName())) {
                /**
                 * Handle case:
                 *
                 * 2. Data from a Field in an UnionTypeResolver: the union field does not have
                 *    the schema information, but only the corresponding ObjectTypeResolver
                 *    that will resolve the entity does.
                 *    For instance, when querying 'customPosts { dateStr }', field `dateStr`
                 *    could be evaluated against a Post or Page types, and they could have
                 *    different definitions of the `dateStr` field, such as making argument
                 *    `$format` mandatory or not. Then, there will be a different FieldArgs
                 *    produced for each targetObjectTypeResolver in the UnionTypeResolver
                 */
                $targetObjectTypeResolverObjectFieldData = $targetObjectTypeResolver->getWildcardObjectTypeResolverObjectFieldData(
                    $field,
                    $engineIterationFeedbackStore,
                );
            } else {
                /** @var array<string|int> */
                $objectIDs = $objectTypeResolverObjectIDs[$targetObjectTypeResolver];
                $targetObjectTypeResolverObjectFieldData = $targetObjectTypeResolver->getIndependentObjectTypeResolverObjectFieldData(
                    $field,
                    $objectIDs,
                    $idObjects,
                    $engineIterationFeedbackStore,
                );
            }
            if ($targetObjectTypeResolverObjectFieldData === null) {
                $hasError = true;
                continue;
            }
            $objectTypeResolverObjectFieldData[$targetObjectTypeResolver] = $targetObjectTypeResolverObjectFieldData[$targetObjectTypeResolver];
        }
        if ($hasError) {
            return null;
        }
        return $objectTypeResolverObjectFieldData;
    }
}
