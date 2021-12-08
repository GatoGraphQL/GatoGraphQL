<?php

declare(strict_types=1);

namespace PoPWPSchema\CustomPosts\SchemaHooks;

use PoP\ComponentModel\TypeResolvers\InputObjectType\InputObjectTypeResolverInterface;
use PoPSchema\CustomPosts\TypeResolvers\InputObjectType\CustomPostsFilterInputObjectTypeResolverInterface;
use PoPWPSchema\CustomPosts\SchemaHooks\AbstractAddCustomPostPasswordInputFieldsInputObjectTypeHookSet;

class AddCustomPostPasswordInputFieldsInputObjectTypeHookSet extends AbstractAddCustomPostPasswordInputFieldsInputObjectTypeHookSet
{
    protected function isInputObjectTypeResolver(
        InputObjectTypeResolverInterface $inputObjectTypeResolver,
    ): bool {
        return $inputObjectTypeResolver instanceof CustomPostsFilterInputObjectTypeResolverInterface;
    }
}
