<?php

declare(strict_types=1);

namespace PoPWPSchema\Media\SchemaHooks;

use PoP\ComponentModel\TypeResolvers\InputObjectType\InputObjectTypeResolverInterface;
use PoPCMSSchema\Media\TypeResolvers\InputObjectType\AbstractMediaItemsFilterInputObjectTypeResolver;
use PoPWPSchema\SchemaCommons\SchemaHooks\AbstractConvertDateQueryInputFieldToArrayInputObjectTypeHookSet;

class ConvertDateQueryInputFieldToArrayInputObjectTypeHookSet extends AbstractConvertDateQueryInputFieldToArrayInputObjectTypeHookSet
{
    protected function isInputObjectTypeResolver(InputObjectTypeResolverInterface $inputObjectTypeResolver): bool
    {
        return $inputObjectTypeResolver instanceof AbstractMediaItemsFilterInputObjectTypeResolver;
    }
}
