<?php

declare(strict_types=1);

namespace PoPSchema\Taxonomies\Facades;

use PoP\Root\Container\ContainerBuilderFactory;
use PoPSchema\Taxonomies\TypeAPIs\TaxonomyTypeAPIInterface;

class TaxonomyTypeAPIFacade
{
    public static function getInstance(): TaxonomyTypeAPIInterface
    {
        /**
         * @var TaxonomyTypeAPIInterface
         */
        $service = ContainerBuilderFactory::getInstance()->get(TaxonomyTypeAPIInterface::class);
        return $service;
    }
}
