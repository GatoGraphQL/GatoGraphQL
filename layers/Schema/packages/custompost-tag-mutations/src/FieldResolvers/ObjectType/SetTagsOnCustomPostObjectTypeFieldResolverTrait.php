<?php

declare(strict_types=1);

namespace PoPSchema\CustomPostTagMutations\FieldResolvers\ObjectType;

use PoP\Translation\Facades\TranslationAPIFacade;

trait SetTagsOnCustomPostObjectTypeFieldResolverTrait
{
    protected function getEntityName(): string
    {
        $translationAPI = TranslationAPIFacade::getInstance();
        return $translationAPI->__('custom post', 'custompost-tag-mutations');
    }
}
