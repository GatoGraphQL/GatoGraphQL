<?php

declare(strict_types=1);

namespace PoPWPSchema\CustomPostMeta\SchemaHooks;

use PoP\ComponentModel\TypeResolvers\EnumType\EnumTypeResolverInterface;
use PoPCMSSchema\CustomPosts\TypeResolvers\EnumType\CustomPostOrderByEnumTypeResolver;
use PoPWPSchema\Meta\SchemaHooks\AbstractMetaOrderByEnumTypeHookSet;

class CustomPostMetaOrderByEnumTypeHookSet extends AbstractMetaOrderByEnumTypeHookSet
{
    protected function isEnumTypeResolver(
        EnumTypeResolverInterface $enumTypeResolver,
    ): bool {
        return $enumTypeResolver instanceof CustomPostOrderByEnumTypeResolver;
    }
}
