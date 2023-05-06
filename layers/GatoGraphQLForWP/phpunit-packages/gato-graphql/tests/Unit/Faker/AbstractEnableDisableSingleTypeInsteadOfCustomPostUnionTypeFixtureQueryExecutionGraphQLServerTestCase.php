<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\GatoGraphQL\Unit\Faker;

use PHPUnitForGatoGraphQL\WPFakerSchema\Unit\AbstractEnableDisableWPFakerFixtureQueryExecutionGraphQLServerTestCase;
use PoP\Root\Module\ModuleInterface;

abstract class AbstractEnableDisableSingleTypeInsteadOfCustomPostUnionTypeFixtureQueryExecutionGraphQLServerTestCase extends AbstractEnableDisableWPFakerFixtureQueryExecutionGraphQLServerTestCase
{
    protected function getFixtureFolder(): string
    {
        return __DIR__ . '/fixture-single-type-instead-of-custom-post-union-type';
    }

    /**
     * @return array<class-string<ModuleInterface>,array<string,mixed>>
     */
    protected static function getGraphQLServerModuleClassConfiguration(): array
    {
        return [
            ...parent::getGraphQLServerModuleClassConfiguration(),
            ...[
                \PoPCMSSchema\CustomPosts\Module::class => [
                    \PoPCMSSchema\CustomPosts\Environment::USE_SINGLE_TYPE_INSTEAD_OF_CUSTOMPOST_UNION_TYPE => static::isEnabled(),
                ],
            ]
        ];
    }

    /**
     * @return array<class-string<ModuleInterface>>
     */
    protected static function getGraphQLServerModuleClasses(): array
    {
        return [
            ...parent::getGraphQLServerModuleClasses(),
            ...[
                \PoPCMSSchema\PagesWP\Module::class,
            ]
        ];
    }
}
