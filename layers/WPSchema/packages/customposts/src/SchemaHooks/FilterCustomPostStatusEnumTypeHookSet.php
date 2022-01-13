<?php

declare(strict_types=1);

namespace PoPWPSchema\CustomPosts\SchemaHooks;

use PoP\Root\App;
use PoP\ComponentModel\TypeResolvers\EnumType\EnumTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\EnumType\HookNames;
use PoP\Root\Hooks\AbstractHookSet;
use PoPSchema\CustomPosts\TypeResolvers\EnumType\FilterCustomPostStatusEnumTypeResolver;
use PoPWPSchema\CustomPosts\Enums\CustomPostStatus;

class FilterCustomPostStatusEnumTypeHookSet extends AbstractHookSet
{
    protected function init(): void
    {
        App::addFilter(
            HookNames::ENUM_VALUES,
            [$this, 'getEnumValues'],
            10,
            2
        );
        App::addFilter(
            HookNames::ADMIN_ENUM_VALUES,
            [$this, 'getAdminEnumValues'],
            10,
            2
        );
        App::addFilter(
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
        if (!($enumTypeResolver instanceof FilterCustomPostStatusEnumTypeResolver)) {
            return $enumValues;
        }
        return array_merge(
            $enumValues,
            [
                CustomPostStatus::FUTURE,
                CustomPostStatus::PRIVATE,
                // CustomPostStatus::ANY,
            ]
        );
    }

    /**
     * @param string[] $adminEnumValues
     */
    public function getAdminEnumValues(
        array $adminEnumValues,
        EnumTypeResolverInterface $enumTypeResolver,
    ): array {
        if (!($enumTypeResolver instanceof FilterCustomPostStatusEnumTypeResolver)) {
            return $adminEnumValues;
        }
        return array_merge(
            $adminEnumValues,
            [
                CustomPostStatus::FUTURE,
                CustomPostStatus::PRIVATE,
                // CustomPostStatus::ANY,
            ]
        );
    }

    public function getEnumValueDescription(
        ?string $enumValueDescription,
        EnumTypeResolverInterface $enumTypeResolver,
        string $enumValue
    ): ?string {
        if (!($enumTypeResolver instanceof FilterCustomPostStatusEnumTypeResolver)) {
            return $enumValueDescription;
        }
        return match ($enumValue) {
            CustomPostStatus::FUTURE => $this->__('Future content - custom posts to publish in the future', 'customposts'),
            CustomPostStatus::PRIVATE => $this->__('Private content - not visible to users who are not logged in', 'customposts'),
            // CustomPostStatus::ANY => $this->__('Custom posts with any status', 'customposts'),
            default => $enumValueDescription,
        };
    }
}
