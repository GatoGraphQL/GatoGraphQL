<?php

declare(strict_types=1);

namespace PoPSchema\Stances\TypeResolvers\ObjectType;

use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\AbstractObjectTypeResolver;
use PoPSchema\Stances\Facades\StanceTypeAPIFacade;
use PoPSchema\Stances\RelationalTypeDataLoaders\ObjectType\StanceObjectTypeDataLoader;

class StanceObjectTypeResolver extends AbstractObjectTypeResolver
{
    private ?StanceObjectTypeDataLoader $stanceObjectTypeDataLoader = null;
    
    final public function setStanceObjectTypeDataLoader(StanceObjectTypeDataLoader $stanceObjectTypeDataLoader): void
    {
        $this->stanceObjectTypeDataLoader = $stanceObjectTypeDataLoader;
    }
    final protected function getStanceObjectTypeDataLoader(): StanceObjectTypeDataLoader
    {
        if ($this->stanceObjectTypeDataLoader === null) {
            /** @var StanceObjectTypeDataLoader */
            $stanceObjectTypeDataLoader = $this->instanceManager->getInstance(StanceObjectTypeDataLoader::class);
            $this->stanceObjectTypeDataLoader = $stanceObjectTypeDataLoader;
        }
        return $this->stanceObjectTypeDataLoader;
    }
    
    public function getTypeName(): string
    {
        return 'Stance';
    }

    public function getTypeDescription(): ?string
    {
        return $this->getTranslationAPI()->__('A stance by the user (from among “positive”, “neutral” or “negative”) and why', 'stances');
    }

    public function getID(object $object): string|int|null
    {
        $stanceTypeAPI = StanceTypeAPIFacade::getInstance();
        return $stanceTypeAPI->getID($object);
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getStanceObjectTypeDataLoader();
    }
}
