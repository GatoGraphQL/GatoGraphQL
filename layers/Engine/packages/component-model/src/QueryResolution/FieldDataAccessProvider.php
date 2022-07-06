<?php

declare(strict_types=1);

namespace PoP\ComponentModel\QueryResolution;

use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
use PoP\Root\Exception\ShouldNotHappenException;
use PoP\Root\Services\StandaloneServiceTrait;
use SplObjectStorage;

/**
 * Class to retrieve the values contained in Field Arguments.
 *
 * It transforms the FieldArgs passed in the GraphQL query, into the "normalized"
 * values that will be used when resolving the query. These include:
 *
 * - The default values that were not provided in the GraphQL query
 * - Coercing values according to the schema definition
 * - Potential custom additions of values into the FieldArgs, such as the
 *   logged-in user's name and email when adding comments
 *
 * There are 3 possibilities for hosting Field Argument values:
 *
 * 1. Data from a Field in an ObjectTypeResolver: a single instance of the
 *    FieldArgs will satisfy all queried objects, since the same schema applies
 *    to all of them.
 *
 * 2. Data from a Field in an UnionTypeResolver: the union field does not have
 *    the schema information, but only the corresponding ObjectTypeResolver
 *    that will resolve the entity does.
 *    For instance, when querying 'customPosts { dateStr }', field `dateStr`
 *    could be evaluated against a Post or Page types, and they could have
 *    different definitions of the `dateStr` field, such as making argument
 *    `$format` mandatory or not. Then, there will be a different FieldArgs
 *    produced for each targetObjectTypeResolver in the UnionTypeResolver
 *
 * 3. Data for a specific object: When executing nested mutations, the FieldArgs
 *    for each object will be different, as it will contain implicit information
 *    belonging to the object.
 *    For instance, when querying `mutation { posts { update(title: "New title") { id } } }`,
 *    the value for the `$postID` is injected into the FieldArgs for each object,
 *    and the validation of the FieldArgs must also be executed for each object.
 *
 * To satisfy all 3 cases with a single implementation, all data is stored
 * in a chained SplObjectStorage that organizes data by:
 *
 * 1. The Field
 * 2. The ObjectTypeResolver: directly the same ObjectTypeResolver when coming from there,
 *    or each of the targetObjectTypeResolvers when coming from an UnionTypeResolver
 * 3. The object, or a special "wildcard object" to signify "all objects"
 */
class FieldDataAccessProvider implements FieldDataAccessProviderInterface
{
    use StandaloneServiceTrait;

    /**
     * @param SplObjectStorage<FieldInterface,SplObjectStorage<ObjectTypeResolverInterface,SplObjectStorage<object,array<string,mixed>>>> $fieldObjectTypeResolverObjectFieldData
     */
    public function __construct(
        protected SplObjectStorage $fieldObjectTypeResolverObjectFieldData,
    ) {
    }

    /**
     * @return array<string,mixed>
     * @throws ShouldNotHappenException If accessing a non-set Field/ObjectTypeResolver/object
     */
    public function getFieldData(
        FieldInterface $field,
        ?ObjectTypeResolverInterface $objectTypeResolver = null,
        ?object $object = null,
    ): array {
        if (!$this->fieldObjectTypeResolverObjectFieldData->contains($field)) {
            throw new ShouldNotHappenException(
                sprintf(
                    $this->__('Field \'%s\' is not contained in the FieldDataAccessProvider'),
                    $field->getName()
                )
            );
        }
        /** @var SplObjectStorage<ObjectTypeResolverInterface,SplObjectStorage<object,array<string,mixed>>> */
        $objectTypeResolverObjectFieldData = $this->fieldObjectTypeResolverObjectFieldData[$field];
        if ($objectTypeResolverObjectFieldData->count() === 0) {
            throw new ShouldNotHappenException(
                sprintf(
                    $this->__('No ObjectTypeResolvers were set under Field \'%s\''),
                    $field->getName()
                )
            );
        }
        /**
         * If not passing the ObjectTypeResolver, then it's the only one that's been set
         * (only the UnionTypeResolver will add data for more than one ObjectTypeResolver)
         */
        if ($objectTypeResolver === null) {
            $objectTypeResolvers = \iterator_to_array($objectTypeResolverObjectFieldData);
            $objectTypeResolverCount = count($objectTypeResolvers);
            if ($objectTypeResolverCount > 1) {
                throw new ShouldNotHappenException(
                    sprintf(
                        $this->__('When not specifying the ObjectTypeResolver, the FieldDataAccessProvider can contain only 1, but %s were set'),
                        $objectTypeResolverCount
                    )
                );
            }
            /** @var ObjectTypeResolverInterface */
            $objectTypeResolver = $objectTypeResolvers[0];
        } elseif (!$objectTypeResolverObjectFieldData->contains($objectTypeResolver)) {
            throw new ShouldNotHappenException(
               sprintf(
                    $this->__('In the FieldDataAccessProvider, no data has been set for field \'%s\' and ObjectTypeResolver \'%s\''),
                    $field->getName(),
                    $objectTypeResolver->getMaybeNamespacedTypeName()
                )
            );
        }

        /** @var SplObjectStorage<object,array<string,mixed>> */
        $objectFieldData = $objectTypeResolverObjectFieldData[$objectTypeResolver];
        /**
         * If not passing the object, then the data must be under the '*' object,
         * which contains data for "all objects"
         */
        $isNullObject = $object === null;
        if ($isNullObject || !$objectFieldData->contains($object)) {
            $object = FieldDataAccessWildcardObjectFactory::getWildcardObject();
            if (!$objectFieldData->contains($object)) {
                throw new ShouldNotHappenException(
                    $isNullObject
                        ? sprintf(
                            $this->__('In the FieldDataAccessProvider, no data for "all objects" has been set for field \'%s\' and ObjectTypeResolver \'%s\''),
                            $field->getName(),
                            $objectTypeResolver->getMaybeNamespacedTypeName()
                        )
                        : sprintf(
                            $this->__('In the FieldDataAccessProvider, no data for object with ID \'%s\' has been set for field \'%s\' and ObjectTypeResolver \'%s\''),
                            $objectTypeResolver->getID($object),
                            $field->getName(),
                            $objectTypeResolver->getMaybeNamespacedTypeName()
                        )
                );
            }
        }
        /** @var array<string,mixed> */
        $fieldData = $objectFieldData[$object];
        return $fieldData;
    }

    /**
     * Used by the nested directive resolver
     */
    public function duplicateFieldData(
        FieldInterface $fromField,
        FieldInterface $toField,
    ): void {
        if (!$this->fieldObjectTypeResolverObjectFieldData->contains($fromField)) {
            throw new ShouldNotHappenException(
                sprintf(
                    $this->__('Field \'%s\' is not contained in the FieldDataAccessProvider'),
                    $fromField->getName()
                )
            );
        }
        $this->fieldObjectTypeResolverObjectFieldData[$toField] = $this->fieldObjectTypeResolverObjectFieldData[$fromField];
    }
}
