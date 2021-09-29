<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\Hooks;

use PoP\Hooks\AbstractHookSet;

class DBEntriesHookSet extends AbstractHookSet
{
    protected function init(): void
    {
        $this->hooksAPI->addFilter(
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
