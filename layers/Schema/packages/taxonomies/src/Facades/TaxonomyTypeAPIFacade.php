<?php

declare(strict_types=1);

namespace PoPSchema\Taxonomies\Facades;

use PoP\Engine\App;
use PoPSchema\Taxonomies\TypeAPIs\TaxonomyTypeAPIInterface;

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
