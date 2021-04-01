<?php

declare(strict_types=1);

namespace PoPSchema\PostCategories\FieldResolvers;

use PoPSchema\Posts\TypeResolvers\PostTypeResolver;
use PoP\Translation\Facades\TranslationAPIFacade;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoPSchema\Categories\FieldResolvers\AbstractCustomPostQueryableFieldResolver;
use PoPSchema\PostCategories\ComponentContracts\PostCategoryAPISatisfiedContractTrait;

class PostQueryableFieldResolver extends AbstractCustomPostQueryableFieldResolver
{
    use PostCategoryAPISatisfiedContractTrait;

    public function getClassesToAttachTo(): array
    {
        return [
            PostTypeResolver::class,
        ];
    }

    public function getSchemaFieldDescription(TypeResolverInterface $typeResolver, string $fieldName): ?string
    {
        $translationAPI = TranslationAPIFacade::getInstance();
        $descriptions = [
            'categories' => $translationAPI->__('Categories added to this post', 'post-categories'),
            'categoryCount' => $translationAPI->__('Number of categories added to this post', 'post-categories'),
        ];
        return $descriptions[$fieldName] ?? parent::getSchemaFieldDescription($typeResolver, $fieldName);
    }
}
