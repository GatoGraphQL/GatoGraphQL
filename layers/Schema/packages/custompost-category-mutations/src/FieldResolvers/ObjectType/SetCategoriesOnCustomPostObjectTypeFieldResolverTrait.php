<?php

declare(strict_types=1);

namespace PoPSchema\CustomPostCategoryMutations\FieldResolvers\ObjectType;

use PoP\ComponentModel\Services\BasicServiceTrait;
use PoP\Translation\Facades\TranslationAPIFacade;
use PoP\Translation\TranslationAPIInterface;
use Symfony\Contracts\Service\Attribute\Required;

trait SetCategoriesOnCustomPostObjectTypeFieldResolverTrait
{
    // use BasicServiceTrait;

    protected function getEntityName(): string
    {
        return $this->translationAPI->__('custom post', 'custompost-category-mutations');
    }
}
