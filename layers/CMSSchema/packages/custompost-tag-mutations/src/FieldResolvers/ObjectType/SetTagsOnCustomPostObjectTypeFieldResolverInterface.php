<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostTagMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\CustomPostTagMutations\TypeResolvers\InputObjectType\AbstractSetTagsOnCustomPostInputObjectTypeResolver;
use PoPCMSSchema\CustomPosts\TypeResolvers\ObjectType\CustomPostObjectTypeResolverInterface;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;

interface SetTagsOnCustomPostObjectTypeFieldResolverInterface
{
    public function getCustomPostObjectTypeResolver(): CustomPostObjectTypeResolverInterface;
    public function getSetTagsMutationResolver(): MutationResolverInterface;
    public function getSetTagsBulkOperationMutationResolver(): MutationResolverInterface;
    public function getCustomPostSetTagsInputObjectTypeResolver(): AbstractSetTagsOnCustomPostInputObjectTypeResolver;
    public function getPayloadableSetTagsMutationResolver(): MutationResolverInterface;
    public function getPayloadableSetTagsBulkOperationMutationResolver(): MutationResolverInterface;
}
