<?php

declare(strict_types=1);

namespace PoPSchema\TaxonomyMeta\Facades;

use PoPSchema\TaxonomyMeta\TypeAPIs\TaxonomyMetaTypeAPIInterface;
use PoP\Root\Container\ContainerBuilderFactory;

class TaxonomyMetaTypeAPIFacade
{
    public static function getInstance(): TaxonomyMetaTypeAPIInterface
    {
        /**
         * @var TaxonomyMetaTypeAPIInterface
         */
        $service = ContainerBuilderFactory::getInstance()->get(TaxonomyMetaTypeAPIInterface::class);
        return $service;
    }
}
