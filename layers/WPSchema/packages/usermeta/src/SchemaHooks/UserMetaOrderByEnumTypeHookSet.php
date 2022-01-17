<?php

declare(strict_types=1);

namespace PoPWPSchema\UserMeta\SchemaHooks;

use PoP\ComponentModel\TypeResolvers\EnumType\EnumTypeResolverInterface;
use PoPCMSSchema\Users\TypeResolvers\EnumType\UserOrderByEnumTypeResolver;
use PoPWPSchema\Meta\SchemaHooks\AbstractMetaOrderByEnumTypeHookSet;

class UserMetaOrderByEnumTypeHookSet extends AbstractMetaOrderByEnumTypeHookSet
{
    protected function isEnumTypeResolver(
        EnumTypeResolverInterface $enumTypeResolver,
    ): bool {
        return $enumTypeResolver instanceof UserOrderByEnumTypeResolver;
    }
}
