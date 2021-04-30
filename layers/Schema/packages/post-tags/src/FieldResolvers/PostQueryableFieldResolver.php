<?php

declare(strict_types=1);

namespace PoPSchema\PostTags\FieldResolvers;

use PoPSchema\Posts\TypeResolvers\PostTypeResolver;
use PoP\Translation\Facades\TranslationAPIFacade;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoPSchema\Tags\FieldResolvers\AbstractCustomPostQueryableFieldResolver;
use PoPSchema\PostTags\ComponentContracts\PostTagAPISatisfiedContractTrait;

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
}
