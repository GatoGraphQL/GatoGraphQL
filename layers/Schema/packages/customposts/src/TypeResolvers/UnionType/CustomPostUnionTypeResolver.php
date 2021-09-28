<?php

declare(strict_types=1);

namespace PoPSchema\CustomPosts\TypeResolvers\UnionType;

use PoP\ComponentModel\Engine\DataloadingEngineInterface;
use PoP\ComponentModel\AttachableExtensions\AttachableExtensionManagerInterface;
use PoP\ComponentModel\DirectivePipeline\DirectivePipelineServiceInterface;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;
use PoP\ComponentModel\ErrorHandling\ErrorProviderInterface;
use PoP\ComponentModel\Instances\InstanceManagerInterface;
use PoP\ComponentModel\Schema\FeedbackMessageStoreInterface;
use PoP\ComponentModel\Schema\FieldQueryInterpreterInterface;
use PoP\ComponentModel\Schema\SchemaDefinitionServiceInterface;
use PoP\ComponentModel\Schema\SchemaNamespacingServiceInterface;
use PoP\ComponentModel\TypeResolvers\InterfaceType\InterfaceTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\UnionType\AbstractUnionTypeResolver;
use PoP\Hooks\HooksAPIInterface;
use PoP\Translation\TranslationAPIInterface;
use PoPSchema\CustomPosts\RelationalTypeDataLoaders\UnionType\CustomPostUnionTypeDataLoader;

class CustomPostUnionTypeResolver extends AbstractUnionTypeResolver
{
    /**
     * Can't inject in constructor because of a circular reference
     */
    protected ?CustomPostUnionTypeDataLoader $customPostUnionTypeDataLoader = null;
    protected InterfaceTypeResolverInterface $interfaceTypeResolver;
    
    #[\Symfony\Contracts\Service\Attribute\Required]
    public function autowireCustomPostUnionTypeResolver(
        InterfaceTypeResolverInterface $interfaceTypeResolver,
    ) {
        $this->interfaceTypeResolver = $interfaceTypeResolver;
    }

    public function getTypeName(): string
    {
        return 'CustomPostUnion';
    }

    public function getSchemaTypeDescription(): ?string
    {
        return $this->translationAPI->__('Union of \'custom post\' type resolvers', 'customposts');
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        if ($this->customPostUnionTypeDataLoader === null) {
            $this->customPostUnionTypeDataLoader = $this->instanceManager->getInstance(CustomPostUnionTypeDataLoader::class);
        }
        return $this->customPostUnionTypeDataLoader;
    }

    public function getSchemaTypeInterfaceTypeResolver(): ?InterfaceTypeResolverInterface
    {
        return $this->interfaceTypeResolver;
    }
}
