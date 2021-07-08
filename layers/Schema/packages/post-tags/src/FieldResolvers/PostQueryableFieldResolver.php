<?php

declare(strict_types=1);

namespace PoPSchema\PostTags\FieldResolvers;

use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoPSchema\Posts\TypeResolvers\PostTypeResolver;
use PoPSchema\PostTags\ComponentContracts\PostTagAPISatisfiedContractTrait;
use PoPSchema\PostTags\ModuleProcessors\PostTagFieldDataloadModuleProcessor;
use PoPSchema\Tags\FieldResolvers\AbstractCustomPostQueryableFieldResolver;

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

    protected function getFieldDefaultFilterDataloadingModule(TypeResolverInterface $typeResolver, string $fieldName, array $fieldArgs = []): ?array
    {
        switch ($fieldName) {
            case 'tagCount':
                return [PostTagFieldDataloadModuleProcessor::class, PostTagFieldDataloadModuleProcessor::MODULE_DATALOAD_RELATIONALFIELDS_TAGCOUNT];
            case 'tagNames':
                return [PostTagFieldDataloadModuleProcessor::class, PostTagFieldDataloadModuleProcessor::MODULE_DATALOAD_RELATIONALFIELDS_TAGLIST];
        }
        return parent::getFieldDefaultFilterDataloadingModule($typeResolver, $fieldName, $fieldArgs);
    }
}
