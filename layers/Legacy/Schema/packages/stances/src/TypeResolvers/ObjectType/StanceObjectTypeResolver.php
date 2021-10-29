<?php

declare(strict_types=1);

namespace PoPSchema\Stances\TypeResolvers\ObjectType;

use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\AbstractObjectTypeResolver;
use PoPSchema\Stances\Facades\StanceTypeAPIFacade;
use PoPSchema\Stances\RelationalTypeDataLoaders\ObjectType\StanceTypeDataLoader;
use Symfony\Contracts\Service\Attribute\Required;

class StanceObjectTypeResolver extends AbstractObjectTypeResolver
{
    protected ?StanceTypeDataLoader $stanceTypeDataLoader = null;
    
    #[Required]
    final public function autowireStanceObjectTypeResolver(
        StanceTypeDataLoader $stanceTypeDataLoader,
    ): void {
        $this->stanceTypeDataLoader = $stanceTypeDataLoader;
    }
    
    public function getTypeName(): string
    {
        return 'Stance';
    }

    public function getTypeDescription(): ?string
    {
        return $this->getTranslationAPI()->__('A stance by the user (from among “positive”, “neutral” or “negative”) and why', 'stances');
    }

    public function getID(object $object): string | int | null
    {
        $stanceTypeAPI = StanceTypeAPIFacade::getInstance();
        return $stanceTypeAPI->getID($object);
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getStanceTypeDataLoader();
    }
}
