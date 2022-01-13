<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\Hooks;

use PoP\Root\App;
use PoP\Root\Hooks\AbstractHookSet;

class DBEntriesHookSet extends AbstractHookSet
{
    protected function init(): void
    {
        App::addFilter(
            'PoP\API\DataloaderHooks:metaFields',
            array($this, 'moveEntriesUnderDBName')
        );
    }

    /**
     * All fields starting with "__" (such as "__schema") are meta
     */
    public function moveEntriesUnderDBName(array $metaFields): array
    {
        $metaFields[] = '__schema';
        $metaFields[] = '__typename';
        $metaFields[] = '__type';
        return $metaFields;
    }
}
