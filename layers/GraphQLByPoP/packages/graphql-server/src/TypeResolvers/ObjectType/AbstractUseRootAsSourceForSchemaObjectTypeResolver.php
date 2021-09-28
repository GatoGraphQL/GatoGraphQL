<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\TypeResolvers\ObjectType;

use Symfony\Contracts\Service\Attribute\Required;
use PoP\ComponentModel\Engine\DataloadingEngineInterface;
use PoP\ComponentModel\AttachableExtensions\AttachableExtensionManagerInterface;
use PoP\ComponentModel\DirectivePipeline\DirectivePipelineServiceInterface;
use PoP\ComponentModel\ErrorHandling\ErrorProviderInterface;
use PoP\ComponentModel\FieldResolvers\InterfaceType\InterfaceTypeFieldResolverInterface;
use PoP\ComponentModel\FieldResolvers\ObjectType\ObjectTypeFieldResolverInterface;
use PoP\ComponentModel\Instances\InstanceManagerInterface;
use PoP\ComponentModel\Schema\FeedbackMessageStoreInterface;
use PoP\ComponentModel\Schema\FieldQueryInterpreterInterface;
use PoP\ComponentModel\Schema\SchemaDefinitionServiceInterface;
use PoP\ComponentModel\Schema\SchemaNamespacingServiceInterface;
use PoP\ComponentModel\TypeResolvers\InterfaceType\InterfaceTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\AbstractObjectTypeResolver;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoP\Engine\TypeResolvers\ObjectType\RootObjectTypeResolver;
use PoP\Hooks\HooksAPIInterface;
use PoP\Translation\TranslationAPIInterface;

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
