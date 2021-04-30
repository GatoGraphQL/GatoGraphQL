<?php

declare(strict_types=1);

namespace PoPSchema\Events\FieldResolvers;

use PoP\Translation\Facades\TranslationAPIFacade;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoP\Engine\TypeResolvers\RootTypeResolver;
use PoPSchema\Events\FieldResolvers\AbstractEventFieldResolver;

class RootEventListFieldResolver extends AbstractEventFieldResolver
{
    public function getClassesToAttachTo(): array
    {
        return array(RootTypeResolver::class);
    }

    public function getSchemaFieldDescription(TypeResolverInterface $typeResolver, string $fieldName): ?string
    {
        $descriptions = [
            'events' => $this->translationAPI->__('Events in the site', 'events'),
        ];
        return $descriptions[$fieldName] ?? parent::getSchemaFieldDescription($typeResolver, $fieldName);
    }
}
