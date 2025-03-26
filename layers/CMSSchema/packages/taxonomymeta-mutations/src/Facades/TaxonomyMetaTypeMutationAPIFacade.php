<?php

declare(strict_types=1);

namespace PoPCMSSchema\TaxonomyMetaMutations\Facades;

use PoP\Root\App;
use PoPCMSSchema\TaxonomyMetaMutations\TypeAPIs\TaxonomyMetaTypeMutationAPIInterface;

class TaxonomyMetaTypeMutationAPIFacade
{
    public static function getInstance(): TaxonomyMetaTypeMutationAPIInterface
    {
        /**
         * @var TaxonomyMetaTypeMutationAPIInterface
         */
        $service = App::getContainer()->get(TaxonomyMetaTypeMutationAPIInterface::class);
        return $service;
    }
}
