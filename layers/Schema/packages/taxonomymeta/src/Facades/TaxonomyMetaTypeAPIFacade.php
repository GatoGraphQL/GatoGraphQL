<?php

declare(strict_types=1);

namespace PoPSchema\TaxonomyMeta\Facades;

use PoP\Engine\App;
use PoP\Root\Container\ContainerBuilderFactory;
use PoPSchema\TaxonomyMeta\TypeAPIs\TaxonomyMetaTypeAPIInterface;

class TaxonomyMetaTypeAPIFacade
{
    public static function getInstance(): TaxonomyMetaTypeAPIInterface
    {
        /**
         * @var TaxonomyMetaTypeAPIInterface
         */
        $service = App::getContainerBuilderFactory()->getInstance()->get(TaxonomyMetaTypeAPIInterface::class);
        return $service;
    }
}
