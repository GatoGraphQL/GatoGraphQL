<?php

declare(strict_types=1);

namespace PoPWPSchema\Comments\SchemaHooks;

use PoP\ComponentModel\TypeResolvers\InputObjectType\InputObjectTypeResolverInterface;
use PoPCMSSchema\Comments\TypeResolvers\InputObjectType\AbstractCommentsFilterInputObjectTypeResolver;
use PoPWPSchema\SchemaCommons\SchemaHooks\AbstractConvertDateQueryInputFieldToArrayInputObjectTypeHookSet;

class ConvertDateQueryInputFieldToArrayInputObjectTypeHookSet extends AbstractConvertDateQueryInputFieldToArrayInputObjectTypeHookSet
{
    protected function isInputObjectTypeResolver(InputObjectTypeResolverInterface $inputObjectTypeResolver): bool
    {
        return $inputObjectTypeResolver instanceof AbstractCommentsFilterInputObjectTypeResolver;
    }
}
