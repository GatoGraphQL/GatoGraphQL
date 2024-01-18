<?php

declare(strict_types=1);

namespace PoPCMSSchema\MediaMutations\ConditionalOnModule\CustomPosts\SchemaHooks;

use PoPCMSSchema\MediaMutations\TypeResolvers\InputObjectType\RootCreateMediaItemInputObjectTypeResolver;
use PoP\ComponentModel\TypeResolvers\InputObjectType\InputObjectTypeResolverInterface;

class AddCustomPostInputFieldsInputObjectTypeHookSet extends AbstractAddCustomPostInputFieldsInputObjectTypeHookSet
{
    protected function isInputObjectTypeResolver(
        InputObjectTypeResolverInterface $inputObjectTypeResolver,
    ): bool {
        return $inputObjectTypeResolver instanceof RootCreateMediaItemInputObjectTypeResolver;
    }
}
