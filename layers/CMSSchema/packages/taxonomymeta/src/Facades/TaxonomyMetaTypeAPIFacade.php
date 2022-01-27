<?php

declare(strict_types=1);

namespace PoPCMSSchema\TaxonomyMeta\Facades;

use PoP\Root\App;
use PoPCMSSchema\TaxonomyMeta\TypeAPIs\TaxonomyMetaTypeAPIInterface;

class TaxonomyMetaTypeAPIFacade
{
    public static function getInstance(): TaxonomyMetaTypeAPIInterface
    {
        /**
         * @var TaxonomyMetaTypeAPIInterface
         */
        $service = App::getContainer()->get(TaxonomyMetaTypeAPIInterface::class);
        return $service;
    }
}
