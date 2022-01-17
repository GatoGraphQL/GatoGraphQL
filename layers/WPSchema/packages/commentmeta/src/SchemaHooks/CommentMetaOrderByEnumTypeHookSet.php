<?php

declare(strict_types=1);

namespace PoPWPSchema\CommentMeta\SchemaHooks;

use PoP\ComponentModel\TypeResolvers\EnumType\EnumTypeResolverInterface;
use PoPCMSSchema\Comments\TypeResolvers\EnumType\CommentOrderByEnumTypeResolver;
use PoPWPSchema\Meta\SchemaHooks\AbstractMetaOrderByEnumTypeHookSet;

class CommentMetaOrderByEnumTypeHookSet extends AbstractMetaOrderByEnumTypeHookSet
{
    protected function isEnumTypeResolver(
        EnumTypeResolverInterface $enumTypeResolver,
    ): bool {
        return $enumTypeResolver instanceof CommentOrderByEnumTypeResolver;
    }
}
