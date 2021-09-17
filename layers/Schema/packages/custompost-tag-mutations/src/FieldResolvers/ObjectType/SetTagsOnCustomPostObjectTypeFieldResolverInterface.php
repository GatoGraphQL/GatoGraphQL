<?php

declare(strict_types=1);

namespace PoPSchema\CustomPostTagMutations\FieldResolvers\ObjectType;

use PoPSchema\CustomPosts\TypeResolvers\ObjectType\CustomPostObjectTypeResolverInterface;

interface SetTagsOnCustomPostObjectTypeFieldResolverInterface
{
    public function getCustomPostTypeResolver(): CustomPostObjectTypeResolverInterface;
    public function getTypeMutationResolverClass(): string;
}
