<?php

declare(strict_types=1);

namespace PoPSchema\PostTags\FieldResolvers\ObjectType;

use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoPSchema\Posts\TypeResolvers\ObjectType\PostObjectTypeResolver;
use PoPSchema\PostTags\ComponentContracts\PostTagAPISatisfiedContractTrait;
use PoPSchema\PostTags\ModuleProcessors\PostTagFilterInputContainerModuleProcessor;
use PoPSchema\Tags\FieldResolvers\ObjectType\AbstractCustomPostQueryableObjectTypeFieldResolver;

class PostQueryableObjectTypeFieldResolver extends AbstractCustomPostQueryableObjectTypeFieldResolver
{
    use PostTagAPISatisfiedContractTrait;

    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            PostObjectTypeResolver::class,
        ];
    }

    public function getSchemaFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        return match ($fieldName) {
            'tags' => $this->translationAPI->__('Tags added to this post', 'pop-post-tags'),
            'tagCount' => $this->translationAPI->__('Number of tags added to this post', 'pop-post-tags'),
            'tagNames' => $this->translationAPI->__('Names of the tags added to this post', 'pop-post-tags'),
            default => parent::getSchemaFieldDescription($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldFilterInputContainerModule(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?array
    {
        return match ($fieldName) {
            'tags' => [PostTagFilterInputContainerModuleProcessor::class, PostTagFilterInputContainerModuleProcessor::MODULE_FILTERINPUTCONTAINER_TAGS],
            'tagCount' => [PostTagFilterInputContainerModuleProcessor::class, PostTagFilterInputContainerModuleProcessor::MODULE_FILTERINPUTCONTAINER_TAGCOUNT],
            'tagNames' => [PostTagFilterInputContainerModuleProcessor::class, PostTagFilterInputContainerModuleProcessor::MODULE_FILTERINPUTCONTAINER_TAGS],
            default => parent::getFieldFilterInputContainerModule($objectTypeResolver, $fieldName),
        };
    }
}
