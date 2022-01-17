<?php

declare(strict_types=1);

namespace PoPWPSchema\TaxonomyMeta\SchemaHooks;

use PoP\ComponentModel\TypeResolvers\EnumType\EnumTypeResolverInterface;
use PoPCMSSchema\Taxonomies\TypeResolvers\EnumType\TaxonomyOrderByEnumTypeResolver;
use PoPWPSchema\Meta\SchemaHooks\AbstractMetaOrderByEnumTypeHookSet;

class TaxonomyMetaOrderByEnumTypeHookSet extends AbstractMetaOrderByEnumTypeHookSet
{
    protected function isEnumTypeResolver(
        EnumTypeResolverInterface $enumTypeResolver,
    ): bool {
        return $enumTypeResolver instanceof TaxonomyOrderByEnumTypeResolver;
    }
}
