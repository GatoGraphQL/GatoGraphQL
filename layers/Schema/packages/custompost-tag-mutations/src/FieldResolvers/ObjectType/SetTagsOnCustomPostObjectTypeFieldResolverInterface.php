<?php

declare(strict_types=1);

namespace PoPSchema\CustomPostTagMutations\FieldResolvers\ObjectType;

use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
use PoPSchema\CustomPosts\TypeResolvers\ObjectType\CustomPostObjectTypeResolverInterface;

interface SetTagsOnCustomPostObjectTypeFieldResolverInterface
{
    public function getCustomPostTypeResolver(): CustomPostObjectTypeResolverInterface;
    public function getSetTagsMutationResolver(): MutationResolverInterface;
}
