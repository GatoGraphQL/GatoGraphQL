<?php

declare(strict_types=1);

namespace PoPCMSSchema\Users\ConditionalOnModule\CustomPosts\SchemaHooks;

use PoP\ComponentModel\TypeResolvers\InputObjectType\InputObjectTypeResolverInterface;
use PoPCMSSchema\CustomPosts\TypeResolvers\InputObjectType\AbstractCustomPostsFilterInputObjectTypeResolver;
use PoPCMSSchema\Users\ConditionalOnModule\CustomPosts\TypeResolvers\InputObjectType\UserCustomPostsFilterInputObjectTypeResolver;
use PoPCMSSchema\Users\SchemaHooks\AbstractAddAuthorInputFieldsInputObjectTypeHookSet;

class AddAuthorInputFieldsToCustomPostInputObjectTypeHookSet extends AbstractAddAuthorInputFieldsInputObjectTypeHookSet
{
    protected function addAuthorInputFields(
        InputObjectTypeResolverInterface $inputObjectTypeResolver,
    ): bool {
        return $inputObjectTypeResolver instanceof AbstractCustomPostsFilterInputObjectTypeResolver
            && !($inputObjectTypeResolver instanceof UserCustomPostsFilterInputObjectTypeResolver);
    }
}
