<?php

declare(strict_types=1);

namespace PoPCMSSchema\Media\ConditionalOnComponent\Users\SchemaHooks;

use PoP\ComponentModel\TypeResolvers\InputObjectType\InputObjectTypeResolverInterface;
use PoPCMSSchema\Media\TypeResolvers\InputObjectType\AbstractMediaItemsFilterInputObjectTypeResolver;
use PoPCMSSchema\Users\SchemaHooks\AbstractAddAuthorInputFieldsInputObjectTypeHookSet;

class AddAuthorInputFieldsToCustomPostInputObjectTypeHookSet extends AbstractAddAuthorInputFieldsInputObjectTypeHookSet
{
    protected function addAuthorInputFields(
        InputObjectTypeResolverInterface $inputObjectTypeResolver,
    ): bool {
        return $inputObjectTypeResolver instanceof AbstractMediaItemsFilterInputObjectTypeResolver;
    }
}
