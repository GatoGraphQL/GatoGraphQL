<?php

declare(strict_types=1);

namespace PoPSchema\CustomPosts\TypeResolvers\ObjectType;

use Symfony\Contracts\Service\Attribute\Required;
use PoP\ComponentModel\AttachableExtensions\AttachableExtensionManagerInterface;
use PoP\ComponentModel\DirectivePipeline\DirectivePipelineServiceInterface;
use PoP\ComponentModel\Engine\DataloadingEngineInterface;
use PoP\ComponentModel\ErrorHandling\ErrorProviderInterface;
use PoP\ComponentModel\Instances\InstanceManagerInterface;
use PoP\ComponentModel\Schema\FeedbackMessageStoreInterface;
use PoP\ComponentModel\Schema\FieldQueryInterpreterInterface;
use PoP\ComponentModel\Schema\SchemaDefinitionServiceInterface;
use PoP\ComponentModel\Schema\SchemaNamespacingServiceInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\AbstractObjectTypeResolver;
use PoP\Hooks\HooksAPIInterface;
use PoP\Translation\TranslationAPIInterface;
use PoPSchema\CustomPosts\TypeAPIs\CustomPostTypeAPIInterface;

abstract class AbstractCustomPostObjectTypeResolver extends AbstractObjectTypeResolver implements CustomPostObjectTypeResolverInterface
{
    protected CustomPostTypeAPIInterface $customPostTypeAPI;

    #[Required]
    public function autowireAbstractCustomPostObjectTypeResolver(
        CustomPostTypeAPIInterface $customPostTypeAPI,
    ): void {
        $this->customPostTypeAPI = $customPostTypeAPI;
    }

    public function getSchemaTypeDescription(): ?string
    {
        return $this->translationAPI->__('Representation of a custom post', 'customposts');
    }

    public function getID(object $object): string | int | null
    {
        return $this->customPostTypeAPI->getID($object);
    }
}
