<?php

declare(strict_types=1);

namespace PoPSchema\Media\ConditionalOnComponent\Users\SchemaHooks;

use PoP\ComponentModel\TypeResolvers\InputObjectType\InputObjectTypeResolverInterface;
use PoPSchema\Media\TypeResolvers\InputObjectType\AbstractMediaItemsFilterInputObjectTypeResolver;
use PoPSchema\Users\SchemaHooks\AbstractAddAuthorInputFieldsInputObjectTypeHookSet;

class AddAuthorInputFieldsToCustomPostInputObjectTypeHookSet extends AbstractAddAuthorInputFieldsInputObjectTypeHookSet
{
    protected function addAuthorInputFields(
        InputObjectTypeResolverInterface $inputObjectTypeResolver,
    ): bool {
        return $inputObjectTypeResolver instanceof AbstractMediaItemsFilterInputObjectTypeResolver;
    }
}
