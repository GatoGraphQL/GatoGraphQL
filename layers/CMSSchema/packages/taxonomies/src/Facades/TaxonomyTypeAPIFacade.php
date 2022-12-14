<?php

declare(strict_types=1);

namespace PoPCMSSchema\Taxonomies\Facades;

use PoP\Root\App;
use PoPCMSSchema\Taxonomies\TypeAPIs\TaxonomyTermTypeAPIInterface;

class TaxonomyTypeAPIFacade
{
    public static function getInstance(): TaxonomyTermTypeAPIInterface
    {
        /**
         * @var TaxonomyTermTypeAPIInterface
         */
        $service = App::getContainer()->get(TaxonomyTermTypeAPIInterface::class);
        return $service;
    }
}
