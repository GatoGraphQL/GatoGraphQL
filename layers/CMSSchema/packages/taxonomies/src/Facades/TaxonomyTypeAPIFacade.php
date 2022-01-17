<?php

declare(strict_types=1);

namespace PoPCMSSchema\Taxonomies\Facades;

use PoP\Root\App;
use PoPCMSSchema\Taxonomies\TypeAPIs\TaxonomyTypeAPIInterface;

class TaxonomyTypeAPIFacade
{
    public static function getInstance(): TaxonomyTypeAPIInterface
    {
        /**
         * @var TaxonomyTypeAPIInterface
         */
        $service = App::getContainer()->get(TaxonomyTypeAPIInterface::class);
        return $service;
    }
}
