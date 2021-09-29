<?php

declare(strict_types=1);

namespace PoP\Multisite\RelationalTypeDataLoaders\ObjectType;

use Symfony\Contracts\Service\Attribute\Required;
use PoP\ComponentModel\RelationalTypeDataLoaders\ObjectType\AbstractObjectTypeDataLoader;
use PoP\Engine\FunctionAPIFactory;
use PoP\Multisite\ObjectModels\Site;

class SiteTypeDataLoader extends AbstractObjectTypeDataLoader
{
    protected Site $site;

    #[Required]
    public function autowireSiteTypeDataLoader(
        Site $site,
    ): void {
        $this->site = $site;
    }

    public function getObjects(array $ids): array
    {
        // Currently it deals only with the current site and nothing else
        $ret = [];
        $cmsengineapi = FunctionAPIFactory::getInstance();
        if (in_array($cmsengineapi->getHost(), $ids)) {
            $ret[] = $this->site;
        }
        return $ret;
    }
}
