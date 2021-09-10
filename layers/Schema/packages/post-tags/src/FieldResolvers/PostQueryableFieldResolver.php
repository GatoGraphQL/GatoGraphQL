<?php

declare(strict_types=1);

namespace PoPSchema\PostTags\FieldResolvers;

use PoP\ComponentModel\TypeResolvers\Object\ObjectTypeResolverInterface;
use PoPSchema\Posts\TypeResolvers\Object\PostTypeResolver;
use PoPSchema\PostTags\ComponentContracts\PostTagAPISatisfiedContractTrait;
use PoPSchema\Tags\FieldResolvers\AbstractCustomPostQueryableFieldResolver;
use PoPSchema\PostTags\ModuleProcessors\PostTagFilterInputContainerModuleProcessor;

class PostQueryableFieldResolver extends AbstractCustomPostQueryableFieldResolver
{
    use PostTagAPISatisfiedContractTrait;

    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            PostTypeResolver::class,
        ];
    }

    public function getSchemaFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        $descriptions = [
            'tags' => $this->translationAPI->__('Tags added to this post', 'pop-post-tags'),
            'tagCount' => $this->translationAPI->__('Number of tags added to this post', 'pop-post-tags'),
            'tagNames' => $this->translationAPI->__('Names of the tags added to this post', 'pop-post-tags'),
        ];
        return $descriptions[$fieldName] ?? parent::getSchemaFieldDescription($objectTypeResolver, $fieldName);
    }

    public function getFieldDataFilteringModule(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?array
    {
        return match ($fieldName) {
            'tags' => [PostTagFilterInputContainerModuleProcessor::class, PostTagFilterInputContainerModuleProcessor::MODULE_FILTERINPUTCONTAINER_TAGS],
            'tagCount' => [PostTagFilterInputContainerModuleProcessor::class, PostTagFilterInputContainerModuleProcessor::MODULE_FILTERINPUTCONTAINER_TAGCOUNT],
            'tagNames' => [PostTagFilterInputContainerModuleProcessor::class, PostTagFilterInputContainerModuleProcessor::MODULE_FILTERINPUTCONTAINER_TAGS],
            default => parent::getFieldDataFilteringModule($objectTypeResolver, $fieldName),
        };
    }
}
