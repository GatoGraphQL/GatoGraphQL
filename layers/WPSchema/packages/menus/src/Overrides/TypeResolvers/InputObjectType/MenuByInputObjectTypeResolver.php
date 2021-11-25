<?php

declare(strict_types=1);

namespace PoPWPSchema\Menus\Overrides\TypeResolvers\InputObjectType;

use PoPSchema\Menus\TypeResolvers\InputObjectType\MenuByInputObjectTypeResolver as UpstreamMenuByInputObjectTypeResolver;
use PoP\Engine\TypeResolvers\ScalarType\StringScalarTypeResolver;

class MenuByInputObjectTypeResolver extends UpstreamMenuByInputObjectTypeResolver
{
    private ?StringScalarTypeResolver $stringScalarTypeResolver = null;

    final public function setStringScalarTypeResolver(StringScalarTypeResolver $stringScalarTypeResolver): void
    {
        $this->stringScalarTypeResolver = $stringScalarTypeResolver;
    }
    final protected function getStringScalarTypeResolver(): StringScalarTypeResolver
    {
        return $this->stringScalarTypeResolver ??= $this->instanceManager->getInstance(StringScalarTypeResolver::class);
    }

    public function getInputFieldNameTypeResolvers(): array
    {
        return array_merge(
            parent::getInputFieldNameTypeResolvers(),
            [
                'slug' => $this->getStringScalarTypeResolver(),
                'location' => $this->getStringScalarTypeResolver(),
            ]
        );
    }

    public function getInputFieldDescription(string $inputFieldName): ?string
    {
        return match ($inputFieldName) {
            'slug' => $this->getTranslationAPI()->__('Query by slug', 'menus'),
            'location' => $this->getTranslationAPI()->__('Query by location', 'menus'),
            default => parent::getInputFieldDescription($inputFieldName),
        };
    }
}
