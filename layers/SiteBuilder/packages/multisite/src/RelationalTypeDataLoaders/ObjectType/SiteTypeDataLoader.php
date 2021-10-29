<?php

declare(strict_types=1);

namespace PoP\Multisite\RelationalTypeDataLoaders\ObjectType;

use PoP\ComponentModel\RelationalTypeDataLoaders\ObjectType\AbstractObjectTypeDataLoader;
use PoP\Engine\FunctionAPIFactory;
use PoP\Multisite\ObjectModels\Site;
use Symfony\Contracts\Service\Attribute\Required;

class SiteTypeDataLoader extends AbstractObjectTypeDataLoader
{
    private ?Site $site = null;

    public function setSite(Site $site): void
    {
        $this->site = $site;
    }
    protected function getSite(): Site
    {
        return $this->site ??= $this->instanceManager->getInstance(Site::class);
    }

    //#[Required]
    final public function autowireSiteTypeDataLoader(
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
            $ret[] = $this->getSite();
        }
        return $ret;
    }
}
