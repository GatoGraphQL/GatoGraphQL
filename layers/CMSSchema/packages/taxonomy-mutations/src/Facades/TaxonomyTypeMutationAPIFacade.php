<?php

declare(strict_types=1);

namespace PoPCMSSchema\TaxonomyMutations\Facades;

use PoP\Root\App;
use PoPCMSSchema\TaxonomyMutations\TypeAPIs\TaxonomyTypeMutationAPIInterface;

class TaxonomyTypeMutationAPIFacade
{
    public static function getInstance(): TaxonomyTypeMutationAPIInterface
    {
        /**
         * @var TaxonomyTypeMutationAPIInterface
         */
        $service = App::getContainer()->get(TaxonomyTypeMutationAPIInterface::class);
        return $service;
    }
}
