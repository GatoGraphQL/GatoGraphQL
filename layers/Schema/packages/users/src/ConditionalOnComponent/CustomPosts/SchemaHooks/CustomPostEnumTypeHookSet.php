<?php

declare(strict_types=1);

namespace PoPSchema\Users\ConditionalOnComponent\CustomPosts\SchemaHooks;

use PoP\ComponentModel\TypeResolvers\EnumType\EnumTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\EnumType\HookNames;
use PoP\Root\Hooks\AbstractHookSet;
use PoPSchema\CustomPosts\TypeResolvers\EnumType\CustomPostOrderByEnumTypeResolver;
use PoPSchema\Users\ConditionalOnComponent\CustomPosts\Constants\CustomPostOrderBy;

class CustomPostEnumTypeHookSet extends AbstractHookSet
{
    protected function init(): void
    {
        \PoP\Root\App::getHookManager()->addFilter(
            HookNames::ENUM_VALUES,
            [$this, 'getEnumValues'],
            10,
            2
        );
        \PoP\Root\App::getHookManager()->addFilter(
            HookNames::ENUM_VALUE_DESCRIPTION,
            [$this, 'getEnumValueDescription'],
            10,
            3
        );
    }

    /**
     * @param string[] $enumValues
     */
    public function getEnumValues(
        array $enumValues,
        EnumTypeResolverInterface $enumTypeResolver,
    ): array {
        if (!($enumTypeResolver instanceof CustomPostOrderByEnumTypeResolver)) {
            return $enumValues;
        }
        return array_merge(
            $enumValues,
            [
                CustomPostOrderBy::AUTHOR,
            ]
        );
    }

    public function getEnumValueDescription(
        ?string $enumValueDescription,
        EnumTypeResolverInterface $enumTypeResolver,
        string $enumValue
    ): ?string {
        if (!($enumTypeResolver instanceof CustomPostOrderByEnumTypeResolver)) {
            return $enumValueDescription;
        }
        return match ($enumValue) {
            CustomPostOrderBy::AUTHOR => $this->__('Order by custom post author', 'pop-users'),
            default => $enumValueDescription,
        };
    }
}
