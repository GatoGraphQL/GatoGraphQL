<?php

declare(strict_types=1);

namespace PoPWPSchema\CustomPosts\SchemaHooks;

use PoP\Root\App;
use PoP\ComponentModel\TypeResolvers\EnumType\EnumTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\EnumType\HookNames;
use PoP\Root\Hooks\AbstractHookSet;
use PoPCMSSchema\CustomPosts\TypeResolvers\EnumType\FilterCustomPostStatusEnumTypeResolver;
use PoPWPSchema\CustomPosts\Enums\CustomPostStatus;

/**
 * Add the additional WordPress Post Statuses
 *
 * @see https://wordpress.org/support/article/post-status/
 */
class FilterCustomPostStatusEnumTypeHookSet extends AbstractHookSet
{
    protected function init(): void
    {
        App::addFilter(
            HookNames::ENUM_VALUES,
            $this->getEnumValues(...),
            10,
            2
        );
        App::addFilter(
            HookNames::ADMIN_ENUM_VALUES,
            $this->getSensitiveEnumValues(...),
            10,
            2
        );
        App::addFilter(
            HookNames::ENUM_VALUE_DESCRIPTION,
            $this->getEnumValueDescription(...),
            10,
            3
        );
    }

    /**
     * @param string[] $enumValues
     * @return string[]
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
                CustomPostStatus::INHERIT,
                CustomPostStatus::ANY,
                /**
                 * @todo "auto-draft" must be converted to enum value "auto_draft" on `Post.status`.
                 *       Until then, this code is commented
                 */
                // CustomPostStatus::AUTO_DRAFT,
            ]
        );
    }

    /**
     * @param string[] $adminEnumValues
     * @return string[]|mixed[]
     */
    public function getSensitiveEnumValues(
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
                CustomPostStatus::INHERIT,
                CustomPostStatus::ANY,
                /**
                 * @todo "auto-draft" must be converted to enum value "auto_draft" on `Post.status`.
                 *       Until then, this code is commented
                 */
                //CustomPostStatus::AUTO_DRAFT,
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
            CustomPostStatus::INHERIT => $this->__('Used with a child custom post (such as Attachments and Revisions) to determine the actual status from the parent custom post', 'customposts'),
            CustomPostStatus::ANY => $this->__('Any custom post status', 'customposts'),
            /**
             * @todo "auto-draft" must be converted to enum value "auto_draft" on `Post.status`.
             *       Until then, this code is commented
             */
            //CustomPostStatus::AUTO_DRAFT => $this->__('Revisions that WordPress saves automatically while you are editing', 'customposts'),
            default => $enumValueDescription,
        };
    }
}
