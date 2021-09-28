<?php

declare(strict_types=1);

namespace PoPSchema\UserState\Hooks;

use Symfony\Contracts\Service\Attribute\Required;
use PoP\Hooks\AbstractHookSet;
use PoP\Hooks\HooksAPIInterface;
use PoP\Translation\TranslationAPIInterface;
use PoP\ComponentModel\Instances\InstanceManagerInterface;
use PoPSchema\UserState\FieldResolvers\ObjectType\GlobalObjectTypeFieldResolver;

class DBEntriesHookSet extends AbstractHookSet
{
    protected GlobalObjectTypeFieldResolver $globalObjectTypeFieldResolver;

    #[Required]
    public function autowireDBEntriesHookSet(
        GlobalObjectTypeFieldResolver $globalObjectTypeFieldResolver
    ) {
        $this->globalObjectTypeFieldResolver = $globalObjectTypeFieldResolver;
    }

    protected function init(): void
    {
        $this->hooksAPI->addFilter(
            'PoP\ComponentModel\Engine:moveEntriesUnderDBName:dbName-dataFields',
            array($this, 'moveEntriesUnderDBName'),
            10,
            1
        );
    }

    public function moveEntriesUnderDBName(array $dbname_datafields): array
    {
        $dbname_datafields['userstate'] = $this->hooksAPI->applyFilters(
            'PoPSchema\UserState\DataloaderHooks:metaFields',
            $this->globalObjectTypeFieldResolver->getFieldNamesToResolve()
        );
        return $dbname_datafields;
    }
}
