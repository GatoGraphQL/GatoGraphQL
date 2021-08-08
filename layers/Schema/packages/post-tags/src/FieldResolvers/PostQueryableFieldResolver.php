<?php

declare(strict_types=1);

namespace PoPSchema\PostTags\FieldResolvers;

use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoPSchema\Posts\TypeResolvers\PostTypeResolver;
use PoPSchema\PostTags\ComponentContracts\PostTagAPISatisfiedContractTrait;
use PoPSchema\Tags\FieldResolvers\AbstractCustomPostQueryableFieldResolver;
use PoPSchema\Tags\ModuleProcessors\FilterInnerModuleProcessor;

class PostQueryableFieldResolver extends AbstractCustomPostQueryableFieldResolver
{
    use PostTagAPISatisfiedContractTrait;

    public function getClassesToAttachTo(): array
    {
        return [
            PostTypeResolver::class,
        ];
    }

    public function getSchemaFieldDescription(TypeResolverInterface $typeResolver, string $fieldName): ?string
    {
        $descriptions = [
            'tags' => $this->translationAPI->__('Tags added to this post', 'pop-post-tags'),
            'tagCount' => $this->translationAPI->__('Number of tags added to this post', 'pop-post-tags'),
            'tagNames' => $this->translationAPI->__('Names of the tags added to this post', 'pop-post-tags'),
        ];
        return $descriptions[$fieldName] ?? parent::getSchemaFieldDescription($typeResolver, $fieldName);
    }

    protected function getFieldDataFilteringModule(TypeResolverInterface $typeResolver, string $fieldName, array $fieldArgs = []): ?array
    {
        return match ($fieldName) {
            'tags' => [FilterInnerModuleProcessor::class, FilterInnerModuleProcessor::MODULE_FILTERINNER_TAGS],
            'tagCount' => [FilterInnerModuleProcessor::class, FilterInnerModuleProcessor::MODULE_FILTERINNER_TAGCOUNT],
            'tagNames' => [FilterInnerModuleProcessor::class, FilterInnerModuleProcessor::MODULE_FILTERINNER_TAGS],
            default => parent::getFieldDataFilteringModule($typeResolver, $fieldName, $fieldArgs),
        };
    }
}
