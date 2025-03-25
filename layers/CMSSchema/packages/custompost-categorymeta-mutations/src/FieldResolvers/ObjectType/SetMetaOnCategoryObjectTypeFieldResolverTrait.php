<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostCategoryMetaMutations\FieldResolvers\ObjectType;

use PoP\Root\Translation\TranslationAPIInterface;

trait SetMetaOnCategoryObjectTypeFieldResolverTrait
{
    abstract protected function getTranslationAPI(): TranslationAPIInterface;

    protected function getEntityName(): string
    {
        return $this->getTranslationAPI()->__('category', 'custompost-categorymeta-mutations');
    }
}
