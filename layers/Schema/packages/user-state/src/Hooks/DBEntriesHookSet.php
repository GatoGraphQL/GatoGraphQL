<?php

declare(strict_types=1);

namespace PoPSchema\UserState\Hooks;

use PoP\Hooks\AbstractHookSet;
use PoP\Hooks\HooksAPIInterface;
use PoP\Translation\TranslationAPIInterface;
use PoP\ComponentModel\Instances\InstanceManagerInterface;
use PoPSchema\UserState\FieldResolvers\GlobalFieldResolver;

class DBEntriesHookSet extends AbstractHookSet
{
    public function __construct(
        HooksAPIInterface $hooksAPI,
        TranslationAPIInterface $translationAPI,
        InstanceManagerInterface $instanceManager,
        protected GlobalFieldResolver $globalFieldResolver
    ) {
        parent::__construct(
            $hooksAPI,
            $translationAPI,
            $instanceManager,
        );
    }

    protected function init(): void
    {
        $this->hooksAPI->addFilter(
            'PoP\ComponentModel\Engine:moveEntriesUnderDBName:dbName-dataFields',
            array($this, 'moveEntriesUnderDBName'),
            10,
            2
        );
    }

    public function moveEntriesUnderDBName($dbname_datafields, $typeResolver)
    {
        $dbname_datafields['userstate'] = $this->hooksAPI->applyFilters(
            'PoPSchema\UserState\DataloaderHooks:metaFields',
            $this->globalFieldResolver->getFieldNamesToResolve()
        );
        return $dbname_datafields;
    }
}
