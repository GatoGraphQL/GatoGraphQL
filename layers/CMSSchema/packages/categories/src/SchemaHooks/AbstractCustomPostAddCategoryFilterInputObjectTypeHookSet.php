<?php

declare(strict_types=1);

namespace PoPCMSSchema\Categories\SchemaHooks;

abstract class AbstractCustomPostAddCategoryFilterInputObjectTypeHookSet extends AbstractAddCategoryFilterInputObjectTypeHookSet
{
    protected function addCategoryTaxonomyFilterInput(): bool
    {
        return true;
    }
}
