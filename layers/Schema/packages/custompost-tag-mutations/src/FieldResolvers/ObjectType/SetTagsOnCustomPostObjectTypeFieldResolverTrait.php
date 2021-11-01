<?php

declare(strict_types=1);

namespace PoPSchema\CustomPostTagMutations\FieldResolvers\ObjectType;

trait SetTagsOnCustomPostObjectTypeFieldResolverTrait
{
    protected function getEntityName(): string
    {
        return $this->getTranslationAPI()->__('custom post', 'custompost-tag-mutations');
    }
}
