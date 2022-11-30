<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostTagMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\CustomPostTagMutations\TypeResolvers\InputObjectType\AbstractSetTagsOnCustomPostFilterInputObjectTypeResolver;
use PoPCMSSchema\CustomPosts\TypeResolvers\ObjectType\CustomPostObjectTypeResolverInterface;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;

interface SetTagsOnCustomPostObjectTypeFieldResolverInterface
{
    public function getCustomPostObjectTypeResolver(): CustomPostObjectTypeResolverInterface;
    public function getSetTagsMutationResolver(): MutationResolverInterface;
    public function getCustomPostSetTagsFilterInputObjectTypeResolver(): AbstractSetTagsOnCustomPostFilterInputObjectTypeResolver;
    public function getPayloadableSetTagsMutationResolver(): MutationResolverInterface;
    public function getRootSetTagsMutationPayloadObjectTypeResolver(): ConcreteTypeResolverInterface;
}
