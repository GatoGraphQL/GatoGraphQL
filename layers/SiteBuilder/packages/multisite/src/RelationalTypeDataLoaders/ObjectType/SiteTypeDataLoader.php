<?php

declare(strict_types=1);

namespace PoP\Multisite\RelationalTypeDataLoaders\ObjectType;

use PoP\ComponentModel\RelationalTypeDataLoaders\ObjectType\AbstractObjectTypeDataLoader;
use PoP\Engine\FunctionAPIFactory;
use PoP\Multisite\ObjectModels\Site;

class SiteTypeDataLoader extends AbstractObjectTypeDataLoader
{
    private ?Site $site = null;

    final public function setSite(Site $site): void
    {
        $this->site = $site;
    }
    final protected function getSite(): Site
    {
        /** @var Site */
        return $this->site ??= $this->instanceManager->getInstance(Site::class);
    }

    /**
     * @param array<string|int> $ids
     * @return array<object|null>
     */
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
