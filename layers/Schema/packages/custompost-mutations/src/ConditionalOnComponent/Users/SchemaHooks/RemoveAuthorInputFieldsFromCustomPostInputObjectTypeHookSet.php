<?php

declare(strict_types=1);

namespace PoPSchema\CustomPostMutations\ConditionalOnComponent\Users\SchemaHooks;

use PoP\ComponentModel\TypeResolvers\InputObjectType\InputObjectTypeResolverInterface;
use PoPSchema\CustomPostMutations\TypeResolvers\InputObjectType\AbstractMyCustomPostsFilterInputObjectTypeResolver;
use PoPSchema\Users\SchemaHooks\AbstractRemoveAuthorInputFieldsInputObjectTypeHookSet;

class RemoveAuthorInputFieldsFromCustomPostInputObjectTypeHookSet extends AbstractRemoveAuthorInputFieldsInputObjectTypeHookSet
{
    /**
     * Remove author inputs from field "myCustomPosts" and "myPosts"
     */
    protected function removeAuthorInputFields(
        InputObjectTypeResolverInterface $inputObjectTypeResolver,
    ): bool {
        return $inputObjectTypeResolver instanceof AbstractMyCustomPostsFilterInputObjectTypeResolver;
    }
}
