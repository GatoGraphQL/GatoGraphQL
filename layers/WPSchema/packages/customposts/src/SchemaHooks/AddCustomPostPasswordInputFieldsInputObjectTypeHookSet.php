<?php

declare(strict_types=1);

namespace PoPWPSchema\CustomPosts\SchemaHooks;

use PoP\ComponentModel\TypeResolvers\InputObjectType\InputObjectTypeResolverInterface;
use PoPCMSSchema\CustomPosts\TypeResolvers\InputObjectType\CustomPostsFilterInputObjectTypeResolverInterface;

class AddCustomPostPasswordInputFieldsInputObjectTypeHookSet extends AbstractAddCustomPostPasswordInputFieldsInputObjectTypeHookSet
{
    protected function isInputObjectTypeResolver(
        InputObjectTypeResolverInterface $inputObjectTypeResolver,
    ): bool {
        return $inputObjectTypeResolver instanceof CustomPostsFilterInputObjectTypeResolverInterface;
    }
}
