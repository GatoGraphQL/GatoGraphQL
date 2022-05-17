<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostMutations\ConditionalOnModule\Users\SchemaHooks;

use PoP\ComponentModel\TypeResolvers\InputObjectType\InputObjectTypeResolverInterface;
use PoPCMSSchema\CustomPostMutations\TypeResolvers\InputObjectType\AbstractMyCustomPostsFilterInputObjectTypeResolver;
use PoPCMSSchema\Users\SchemaHooks\AbstractRemoveAuthorInputFieldsInputObjectTypeHookSet;

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
