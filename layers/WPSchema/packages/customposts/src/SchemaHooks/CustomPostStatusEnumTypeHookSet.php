<?php

declare(strict_types=1);

namespace PoPWPSchema\CustomPosts\SchemaHooks;

use PoP\ComponentModel\TypeResolvers\EnumType\EnumTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\EnumType\HookNames;
use PoP\Hooks\AbstractHookSet;
use PoPSchema\CustomPosts\TypeResolvers\EnumType\CustomPostStatusEnumTypeResolver;
use PoPWPSchema\CustomPosts\Enums\CustomPostStatus;

class CustomPostStatusEnumTypeHookSet extends AbstractHookSet
{
    protected function init(): void
    {
        $this->getHooksAPI()->addFilter(
            HookNames::ENUM_VALUES,
            [$this, 'getEnumValues'],
            10,
            2
        );
        $this->getHooksAPI()->addFilter(
            HookNames::ADMIN_ENUM_VALUES,
            [$this, 'getAdminEnumValues'],
            10,
            2
        );
        $this->getHooksAPI()->addFilter(
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
        if (!($enumTypeResolver instanceof CustomPostStatusEnumTypeResolver)) {
            return $enumValues;
        }
        return array_merge(
            $enumValues,
            [
                CustomPostStatus::FUTURE,
                CustomPostStatus::PRIVATE,
                CustomPostStatus::ANY,
            ]
        );
    }

    /**
     * @param string[] $enumValues
     */
    public function getAdminEnumValues(
        array $adminEnumValues,
        EnumTypeResolverInterface $enumTypeResolver,
    ): array {
        if (!($enumTypeResolver instanceof CustomPostStatusEnumTypeResolver)) {
            return $adminEnumValues;
        }
        return array_merge(
            $adminEnumValues,
            [
                CustomPostStatus::FUTURE,
                CustomPostStatus::PRIVATE,
                CustomPostStatus::ANY,
            ]
        );
    }

    public function getEnumValueDescription(
        ?string $enumValueDescription,
        EnumTypeResolverInterface $enumTypeResolver,
        string $enumValue
    ): ?string {
        if (!($enumTypeResolver instanceof CustomPostStatusEnumTypeResolver)) {
            return $enumValueDescription;
        }
        return match ($enumValue) {
            CustomPostStatus::FUTURE => $this->getTranslationAPI()->__('Future content - custom posts to publish in the future', 'customposts'),
            CustomPostStatus::PRIVATE => $this->getTranslationAPI()->__('Private content - not visible to users who are not logged in', 'customposts'),
            CustomPostStatus::ANY => $this->getTranslationAPI()->__('Custom posts with any status', 'customposts'),
            default => $enumValueDescription,
        };
    }
}
