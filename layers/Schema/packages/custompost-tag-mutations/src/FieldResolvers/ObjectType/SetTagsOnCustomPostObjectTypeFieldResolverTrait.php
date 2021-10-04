<?php

declare(strict_types=1);

namespace PoPSchema\CustomPostTagMutations\FieldResolvers\ObjectType;

use PoP\Translation\TranslationAPIInterface;
use Symfony\Contracts\Service\Attribute\Required;

trait SetTagsOnCustomPostObjectTypeFieldResolverTrait
{
    protected TranslationAPIInterface $translationAPI;

    #[Required]
    public function autowireSetTagsOnCustomPostObjectTypeFieldResolverTrait(
        TranslationAPIInterface $translationAPI,
    ): void {
        $this->translationAPI = $translationAPI;
    }

    protected function getEntityName(): string
    {
        return $this->translationAPI->__('custom post', 'custompost-tag-mutations');
    }
}
