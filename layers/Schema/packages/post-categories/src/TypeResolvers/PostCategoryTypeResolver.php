<?php

declare(strict_types=1);

namespace PoPSchema\PostCategories\TypeResolvers;

use PoPSchema\PostCategories\ComponentContracts\PostCategoryAPISatisfiedContractTrait;
use PoP\Translation\Facades\TranslationAPIFacade;
use PoPSchema\PostCategories\TypeDataLoaders\PostCategoryTypeDataLoader;
use PoPSchema\Categories\TypeResolvers\AbstractCategoryTypeResolver;

class PostCategoryTypeResolver extends AbstractCategoryTypeResolver
{
    use PostCategoryAPISatisfiedContractTrait;

    public function getTypeName(): string
    {
        return 'PostCategory';
    }

    public function getSchemaTypeDescription(): ?string
    {
        $translationAPI = TranslationAPIFacade::getInstance();
        return $translationAPI->__('Representation of a category, added to a post', 'post-categories');
    }

    public function getTypeDataLoaderClass(): string
    {
        return PostCategoryTypeDataLoader::class;
    }
}
