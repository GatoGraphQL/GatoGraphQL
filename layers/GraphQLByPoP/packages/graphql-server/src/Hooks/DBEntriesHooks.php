<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\Hooks;

use PoP\Hooks\AbstractHookSet;

class DBEntriesHooks extends AbstractHookSet
{
    protected function init()
    {
        $this->hooksAPI->addFilter(
            'PoP\API\DataloaderHooks:metaFields',
            array($this, 'moveEntriesUnderDBName')
        );
    }

    public function moveEntriesUnderDBName($metaFields)
    {
        $metaFields[] = '__schema';
        $metaFields[] = '__typename';
        return $metaFields;
    }
}
