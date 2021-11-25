<?php

declare(strict_types=1);

namespace PoPSchema\Users\ConditionalOnComponent\CustomPosts\SchemaHooks;

use PoP\ComponentModel\TypeResolvers\InputObjectType\InputObjectTypeResolverInterface;
use PoPSchema\CustomPosts\TypeResolvers\InputObjectType\AbstractCustomPostsFilterInputObjectTypeResolver;
use PoPSchema\Users\ConditionalOnComponent\CustomPosts\TypeResolvers\InputObjectType\UserCustomPostsFilterInputObjectTypeResolver;
use PoPSchema\Users\SchemaHooks\AbstractAddAuthorInputFieldsInputObjectTypeHookSet;

class AddAuthorInputFieldsToCustomPostInputObjectTypeHookSet extends AbstractAddAuthorInputFieldsInputObjectTypeHookSet
{
    protected function addAuthorInputFields(
        InputObjectTypeResolverInterface $inputObjectTypeResolver,
    ): bool {
        return $inputObjectTypeResolver instanceof AbstractCustomPostsFilterInputObjectTypeResolver
            && !($inputObjectTypeResolver instanceof UserCustomPostsFilterInputObjectTypeResolver);
    }
}
