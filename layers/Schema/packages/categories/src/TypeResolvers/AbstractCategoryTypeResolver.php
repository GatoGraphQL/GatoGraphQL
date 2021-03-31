<?php

declare(strict_types=1);

namespace PoPSchema\Categories\TypeResolvers;

use PoP\Translation\Facades\TranslationAPIFacade;
use PoP\ComponentModel\TypeResolvers\AbstractTypeResolver;
use PoPSchema\Categories\ComponentContracts\CategoryAPIRequestedContractTrait;

abstract class AbstractCategoryTypeResolver extends AbstractTypeResolver
{
    use CategoryAPIRequestedContractTrait;

    public function getSchemaTypeDescription(): ?string
    {
        $translationAPI = TranslationAPIFacade::getInstance();
        return $translationAPI->__('Representation of a category, added to a custom post', 'categories');
    }

    public function getID(object $resultItem): string | int
    {
        $cmscategoriesresolver = $this->getObjectPropertyAPI();
        $category = $resultItem;
        return $cmscategoriesresolver->getCategoryID($category);
    }
}
