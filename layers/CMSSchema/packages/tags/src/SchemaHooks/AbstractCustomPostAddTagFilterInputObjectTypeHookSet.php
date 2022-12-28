<?php

declare(strict_types=1);

namespace PoPCMSSchema\Tags\SchemaHooks;

abstract class AbstractCustomPostAddTagFilterInputObjectTypeHookSet extends AbstractAddTagFilterInputObjectTypeHookSet
{
    protected function addTagTaxonomyFilterInput(): bool
    {
        return true;
    }
}
