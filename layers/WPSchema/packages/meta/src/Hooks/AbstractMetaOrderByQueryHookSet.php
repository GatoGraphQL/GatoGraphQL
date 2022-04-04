<?php

declare(strict_types=1);

namespace PoPWPSchema\Meta\Hooks;

use PoP\Root\App;
use PoP\Root\Hooks\AbstractHookSet;
use PoPWPSchema\Meta\Constants\MetaOrderBy;

abstract class AbstractMetaOrderByQueryHookSet extends AbstractHookSet
{
    protected function init(): void
    {
        App::addFilter(
            $this->getHookName(),
            $this->getOrderByQueryArgValue(...)
        );
    }

    abstract protected function getHookName(): string;

    public function getOrderByQueryArgValue(string $orderBy): string
    {
        return match ($orderBy) {
            MetaOrderBy::META_VALUE => 'meta_value',
            default => $orderBy,
        };
    }
}
