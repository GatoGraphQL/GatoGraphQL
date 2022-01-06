<?php

declare(strict_types=1);

namespace PoPSchema\CustomPostTagMutations\FieldResolvers\ObjectType;

use PoP\Translation\TranslationAPIInterface;

trait SetTagsOnCustomPostObjectTypeFieldResolverTrait
{
    abstract protected function getTranslationAPI(): TranslationAPIInterface;

    protected function getEntityName(): string
    {
        return $this->__('custom post', 'custompost-tag-mutations');
    }
}
