<?php

declare(strict_types=1);

namespace PoPSchema\TaxonomyMeta\Facades;

use PoP\Root\Container\ContainerBuilderFactory;
use PoPSchema\TaxonomyMeta\TypeAPIs\TaxonomyMetaTypeAPIInterface;

class TaxonomyMetaTypeAPIFacade
{
    public static function getInstance(): TaxonomyMetaTypeAPIInterface
    {
        /**
         * @var TaxonomyMetaTypeAPIInterface
         */
        $service = \PoP\Engine\App::getContainerBuilderFactory()->getInstance()->get(TaxonomyMetaTypeAPIInterface::class);
        return $service;
    }
}
