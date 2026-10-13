<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Schema;

use PoP\ComponentModel\Registries\TypeRegistryInterface;
use PoP\ComponentModel\TypeResolvers\InterfaceType\InterfaceTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;
use PoP\Root\Services\AbstractBasicService;

use function array_map;
use function array_merge;
use function in_array;
use function str_contains;

class TypeConditionService extends AbstractBasicService implements TypeConditionServiceInterface
{
    private ?TypeRegistryInterface $typeRegistry = null;

    final protected function getTypeRegistry(): TypeRegistryInterface
    {
        if ($this->typeRegistry === null) {
            /** @var TypeRegistryInterface */
            $typeRegistry = $this->instanceManager->getInstance(TypeRegistryInterface::class);
            $this->typeRegistry = $typeRegistry;
        }
        return $this->typeRegistry;
    }

    public function isObjectType(
        ObjectTypeResolverInterface $objectTypeResolver,
        string $typeName,
    ): bool {
        // If the provided typeName contains the namespace separator, then compare by qualifiedType
        if (str_contains($typeName, SchemaDefinitionTokens::NAMESPACE_SEPARATOR)) {
            /**
             * @todo Replace the code below with:
             *
             *     return $typeName === $objectTypeResolver->getNamespacedTypeName();
             *
             * Currently, because the GraphQL spec doesn't support namespaces,
             * we are using "_" as the namespace separator, instead of "/".
             * But this character can also be part of the Type name!
             * So only temporarily, compare both the namespaced and the
             * normal type name, until can use "/".
             *
             * @see https://github.com/graphql/graphql-spec/issues/163
             */
            return
                $typeName === $objectTypeResolver->getNamespacedTypeName()
                || $typeName === $objectTypeResolver->getTypeName();
        }
        return $typeName === $objectTypeResolver->getTypeName();
    }

    public function implementsInterface(
        ObjectTypeResolverInterface $objectTypeResolver,
        string $interfaceName,
    ): bool {
        $implementedInterfaceTypeResolvers = $objectTypeResolver->getImplementedInterfaceTypeResolvers();
        // If the provided interface contains the namespace separator, then compare by qualifiedInterface
        $useNamespaced = str_contains($interfaceName, SchemaDefinitionTokens::NAMESPACE_SEPARATOR);
        $implementedInterfaceNames = array_map(
            function (InterfaceTypeResolverInterface $interfaceTypeResolver) use ($useNamespaced): string {
                if ($useNamespaced) {
                    return $interfaceTypeResolver->getNamespacedTypeName();
                }
                return $interfaceTypeResolver->getTypeName();
            },
            $implementedInterfaceTypeResolvers
        );
        /**
         * @todo Remove the block of code below.
         *
         * Currently, because the GraphQL spec doesn't support namespaces,
         * we are using "_" as the namespace separator, instead of "/".
         * But this character can also be part of the Interface name!
         * So only temporarily, also add the interface names to the
         * array to compare, until can use "/".
         *
         * @see https://github.com/graphql/graphql-spec/issues/163
         *
         * -- Begin code --
         */
        if ($useNamespaced) {
            $implementedInterfaceNames = array_merge(
                $implementedInterfaceNames,
                array_map(
                    function (InterfaceTypeResolverInterface $interfaceTypeResolver): string {
                        return $interfaceTypeResolver->getTypeName();
                    },
                    $implementedInterfaceTypeResolvers
                )
            );
        }
        /**
         * -- End code --
         */
        return in_array($interfaceName, $implementedInterfaceNames);
    }

    public function getUnionTypeResolver(string $unionTypeName): ?UnionTypeResolverInterface
    {
        /**
         * If the provided unionTypeName contains the namespace separator, then compare by qualifiedType
         * @see https://github.com/graphql/graphql-spec/issues/163
         */
        $isNamespacedUnionTypeName = str_contains($unionTypeName, SchemaDefinitionTokens::NAMESPACE_SEPARATOR);
        foreach ($this->getTypeRegistry()->getUnionTypeResolvers() as $unionTypeResolver) {
            if (
                $unionTypeName === $unionTypeResolver->getTypeName()
                || ($isNamespacedUnionTypeName && $unionTypeName === $unionTypeResolver->getNamespacedTypeName())
            ) {
                return $unionTypeResolver;
            }
        }
        return null;
    }

    public function isTypeOrImplements(
        ObjectTypeResolverInterface $objectTypeResolver,
        string $typeOrInterface,
    ): bool {
        if ($this->isObjectType($objectTypeResolver, $typeOrInterface)) {
            return true;
        }
        if ($this->implementsInterface($objectTypeResolver, $typeOrInterface)) {
            return true;
        }
        $unionTypeResolver = $this->getUnionTypeResolver($typeOrInterface);
        if ($unionTypeResolver === null) {
            return false;
        }
        return in_array($objectTypeResolver, $unionTypeResolver->getTargetObjectTypeResolvers(), true);
    }
}
