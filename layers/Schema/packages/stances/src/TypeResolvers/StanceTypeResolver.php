<?php

declare(strict_types=1);

namespace PoPSchema\Stances\TypeResolvers;

use PoPSchema\Stances\Facades\StanceTypeAPIFacade;
use PoPSchema\Stances\TypeDataLoaders\StanceTypeDataLoader;
use PoP\ComponentModel\TypeResolvers\AbstractTypeResolver;

class StanceTypeResolver extends AbstractTypeResolver
{
    public function getTypeName(): string
    {
        return 'Stance';
    }

    public function getSchemaTypeDescription(): ?string
    {
        return $this->translationAPI->__('A stance by the user (from among “positive”, “neutral” or “negative”) and why', 'stances');
    }

    public function getID(object $resultItem): string | int
    {
        $stanceTypeAPI = StanceTypeAPIFacade::getInstance();
        return $stanceTypeAPI->getID($resultItem);
    }

    public function getTypeDataLoaderClass(): string
    {
        return StanceTypeDataLoader::class;
    }
}
