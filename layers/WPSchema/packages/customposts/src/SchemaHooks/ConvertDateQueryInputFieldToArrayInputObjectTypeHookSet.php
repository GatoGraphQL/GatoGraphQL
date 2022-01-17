<?php

declare(strict_types=1);

namespace PoPWPSchema\CustomPosts\SchemaHooks;

use PoP\ComponentModel\TypeResolvers\InputObjectType\InputObjectTypeResolverInterface;
use PoPCMSSchema\CustomPosts\TypeResolvers\InputObjectType\AbstractCustomPostsFilterInputObjectTypeResolver;
use PoPWPSchema\SchemaCommons\SchemaHooks\AbstractConvertDateQueryInputFieldToArrayInputObjectTypeHookSet;

class ConvertDateQueryInputFieldToArrayInputObjectTypeHookSet extends AbstractConvertDateQueryInputFieldToArrayInputObjectTypeHookSet
{
    protected function isInputObjectTypeResolver(InputObjectTypeResolverInterface $inputObjectTypeResolver): bool
    {
        return $inputObjectTypeResolver instanceof AbstractCustomPostsFilterInputObjectTypeResolver;
    }
}
