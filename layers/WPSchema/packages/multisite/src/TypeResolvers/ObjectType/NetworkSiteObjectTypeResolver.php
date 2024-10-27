<?php

declare(strict_types=1);

namespace PoPWPSchema\Multisite\TypeResolvers\ObjectType;

use PoPWPSchema\Multisite\RelationalTypeDataLoaders\ObjectType\NetworkSiteObjectTypeDataLoader;
use PoPWPSchema\Multisite\TypeAPIs\MultisiteTypeAPIInterface;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\AbstractObjectTypeResolver;

class NetworkSiteObjectTypeResolver extends AbstractObjectTypeResolver
{
    private ?MultisiteTypeAPIInterface $multisiteTypeAPI = null;
    private ?NetworkSiteObjectTypeDataLoader $networkSiteObjectTypeDataLoader = null;

    final protected function getMultisiteTypeAPI(): MultisiteTypeAPIInterface
    {
        if ($this->multisiteTypeAPI === null) {
            /** @var MultisiteTypeAPIInterface */
            $multisiteTypeAPI = $this->instanceManager->getInstance(MultisiteTypeAPIInterface::class);
            $this->multisiteTypeAPI = $multisiteTypeAPI;
        }
        return $this->multisiteTypeAPI;
    }
    final protected function getNetworkSiteObjectTypeDataLoader(): NetworkSiteObjectTypeDataLoader
    {
        if ($this->networkSiteObjectTypeDataLoader === null) {
            /** @var NetworkSiteObjectTypeDataLoader */
            $networkSiteObjectTypeDataLoader = $this->instanceManager->getInstance(NetworkSiteObjectTypeDataLoader::class);
            $this->networkSiteObjectTypeDataLoader = $networkSiteObjectTypeDataLoader;
        }
        return $this->networkSiteObjectTypeDataLoader;
    }

    public function getTypeName(): string
    {
        return 'NetworkSite';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Site in a WordPress multisite network', 'multisite');
    }

    public function getID(object $object): string|int|null
    {
        $networkSite = $object;
        return $this->getMultisiteTypeAPI()->getNetworkSiteID($networkSite);
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getNetworkSiteObjectTypeDataLoader();
    }
}
