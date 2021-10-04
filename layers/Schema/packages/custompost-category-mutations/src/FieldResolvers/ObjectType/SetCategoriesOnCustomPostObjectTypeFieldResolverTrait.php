<?php

declare(strict_types=1);

namespace PoPSchema\CustomPostCategoryMutations\FieldResolvers\ObjectType;

use PoP\Translation\Facades\TranslationAPIFacade;
use PoP\Translation\TranslationAPIInterface;
use Symfony\Contracts\Service\Attribute\Required;

trait SetCategoriesOnCustomPostObjectTypeFieldResolverTrait
{
    protected TranslationAPIInterface $translationAPI;

    #[Required]
    public function autowireSetCategoriesOnCustomPostObjectTypeFieldResolverTrait(
        TranslationAPIInterface $translationAPI,
    ): void {
        $this->translationAPI = $translationAPI;
    }

    protected function getEntityName(): string
    {
        return $this->translationAPI->__('custom post', 'custompost-category-mutations');
    }
}
