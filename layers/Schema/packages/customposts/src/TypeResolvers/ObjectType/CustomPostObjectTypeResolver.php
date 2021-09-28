<?php

declare(strict_types=1);

namespace PoPSchema\CustomPosts\TypeResolvers\ObjectType;

use Symfony\Contracts\Service\Attribute\Required;
use PoP\ComponentModel\AttachableExtensions\AttachableExtensionManagerInterface;
use PoP\ComponentModel\DirectivePipeline\DirectivePipelineServiceInterface;
use PoP\ComponentModel\Engine\DataloadingEngineInterface;
use PoP\ComponentModel\ErrorHandling\ErrorProviderInterface;
use PoP\ComponentModel\Instances\InstanceManagerInterface;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;
use PoP\ComponentModel\Schema\FeedbackMessageStoreInterface;
use PoP\ComponentModel\Schema\FieldQueryInterpreterInterface;
use PoP\ComponentModel\Schema\SchemaDefinitionServiceInterface;
use PoP\ComponentModel\Schema\SchemaNamespacingServiceInterface;
use PoP\Hooks\HooksAPIInterface;
use PoP\Translation\TranslationAPIInterface;
use PoPSchema\CustomPosts\RelationalTypeDataLoaders\ObjectType\CustomPostTypeDataLoader;
use PoPSchema\CustomPosts\TypeAPIs\CustomPostTypeAPIInterface;
use PoPSchema\CustomPosts\TypeResolvers\ObjectType\AbstractCustomPostObjectTypeResolver;

/**
 * Class to be used only when a generic CustomPost type is good enough.
 * Otherwise, a specific type for the entity should be employed.
 *
 * @author Leonardo Losoviz <leo@getpop.org>
 */
class CustomPostObjectTypeResolver extends AbstractCustomPostObjectTypeResolver
{
    protected CustomPostTypeDataLoader $customPostTypeDataLoader;

    #[Required]
    public function autowireCustomPostObjectTypeResolver(
        CustomPostTypeDataLoader $customPostTypeDataLoader,
    ) {
        $this->customPostTypeDataLoader = $customPostTypeDataLoader;
    }

    public function getTypeName(): string
    {
        return 'CustomPost';
    }

    public function getSchemaTypeDescription(): ?string
    {
        return $this->translationAPI->__('Representation of a custom post', 'customposts');
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->customPostTypeDataLoader;
    }
}
