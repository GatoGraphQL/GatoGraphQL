<?php

declare(strict_types=1);

namespace PoPSchema\Stances\TypeResolvers;

use PoPSchema\Stances\Facades\StanceTypeAPIFacade;
use PoP\Translation\Facades\TranslationAPIFacade;
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
        $translationAPI = TranslationAPIFacade::getInstance();
        return $translationAPI->__('A stance by the user (from among “positive”, “neutral” or “negative”) and why', 'stances');
    }

    public function getID(object $resultItem): mixed
    {
        $stanceTypeAPI = StanceTypeAPIFacade::getInstance();
        return $stanceTypeAPI->getID($resultItem);
    }

    public function getTypeDataLoaderClass(): string
    {
        return StanceTypeDataLoader::class;
    }
}
