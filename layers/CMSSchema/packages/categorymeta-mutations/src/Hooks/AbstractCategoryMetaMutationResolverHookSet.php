<?php

declare(strict_types=1);

namespace PoPCMSSchema\CategoryMetaMutations\Hooks;

use PoPCMSSchema\CategoryMutations\Constants\CategoryCRUDHookNames;
use PoPCMSSchema\TaxonomyMetaMutations\Hooks\AbstractTaxonomyMetaMutationResolverHookSet;

abstract class AbstractCategoryMetaMutationResolverHookSet extends AbstractTaxonomyMetaMutationResolverHookSet
{
    protected function getErrorPayloadHookName(): string
    {
        return CategoryCRUDHookNames::ERROR_PAYLOAD;
    }
}
