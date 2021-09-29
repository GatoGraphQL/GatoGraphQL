<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\TypeResolvers\ObjectType;

use Symfony\Contracts\Service\Attribute\Required;
use PoP\ComponentModel\FieldResolvers\InterfaceType\InterfaceTypeFieldResolverInterface;
use PoP\ComponentModel\FieldResolvers\ObjectType\ObjectTypeFieldResolverInterface;
use PoP\ComponentModel\TypeResolvers\InterfaceType\InterfaceTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\AbstractObjectTypeResolver;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoP\Engine\TypeResolvers\ObjectType\RootObjectTypeResolver;

abstract class AbstractUseRootAsSourceForSchemaObjectTypeResolver extends AbstractObjectTypeResolver implements UseRootAsSourceForSchemaObjectTypeResolverInterface
{
    protected RootObjectTypeResolver $rootObjectTypeResolver;

    #[Required]
    public function autowireAbstractUseRootAsSourceForSchemaObjectTypeResolver(
        RootObjectTypeResolver $rootObjectTypeResolver,
    ): void {
        $this->rootObjectTypeResolver = $rootObjectTypeResolver;
    }

    protected function getTypeResolverToCalculateSchema(): RelationalTypeResolverInterface
    {
        return $this->rootObjectTypeResolver;
    }

    protected function isFieldNameResolvedByObjectTypeFieldResolver(
        ObjectTypeResolverInterface | InterfaceTypeResolverInterface $objectTypeOrInterfaceTypeResolver,
        ObjectTypeFieldResolverInterface | InterfaceTypeFieldResolverInterface $objectTypeOrInterfaceTypeFieldResolver,
        string $fieldName
    ): bool {
        if (
            $objectTypeOrInterfaceTypeFieldResolver instanceof ObjectTypeFieldResolverInterface
            && !$this->isFieldNameConditionSatisfiedForSchema($objectTypeOrInterfaceTypeFieldResolver, $fieldName)
        ) {
            return false;
        }
        return parent::isFieldNameResolvedByObjectTypeFieldResolver(
            $objectTypeOrInterfaceTypeResolver,
            $objectTypeOrInterfaceTypeFieldResolver,
            $fieldName
        );
    }
}
