<?php

declare(strict_types=1);

namespace PoPWPSchema\Pages\SchemaHooks;

use PoP\ComponentModel\TypeResolvers\InputObjectType\InputObjectTypeResolverInterface;
use PoPCMSSchema\Pages\TypeResolvers\InputObjectType\PagesFilterInputObjectTypeResolverInterface;
use PoPWPSchema\CustomPosts\SchemaHooks\AbstractAddCustomPostPasswordInputFieldsInputObjectTypeHookSet;

class AddPagePasswordInputFieldsInputObjectTypeHookSet extends AbstractAddCustomPostPasswordInputFieldsInputObjectTypeHookSet
{
    protected function isInputObjectTypeResolver(
        InputObjectTypeResolverInterface $inputObjectTypeResolver,
    ): bool {
        return $inputObjectTypeResolver instanceof PagesFilterInputObjectTypeResolverInterface;
    }
}
