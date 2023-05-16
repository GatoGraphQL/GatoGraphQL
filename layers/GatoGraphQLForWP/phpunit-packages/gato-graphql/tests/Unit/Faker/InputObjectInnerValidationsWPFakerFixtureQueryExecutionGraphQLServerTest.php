<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\GatoGraphQL\Unit;

use PHPUnitForGatoGraphQL\WPFakerSchema\Unit\AbstractWPFakerFixtureQueryExecutionGraphQLServerTestCase;
use PoP\Root\Module\ModuleInterface;

/**
 * Test that calling method `validateInputFieldValue` on an inner
 * Input Object (nested from some other Input Object) works.
 *
 * @see layers/GatoGraphQLForWP/phpunit-packages/dummy-schema/src/TypeResolvers/InputObjectType/FourthLayerInputObjectTypeResolver.php function `validateInputFieldValue`
 */
class InputObjectInnerValidationsWPFakerFixtureQueryExecutionGraphQLServerTest extends AbstractWPFakerFixtureQueryExecutionGraphQLServerTestCase
{
    protected function getFixtureFolder(): string
    {
        return __DIR__ . '/fixture-input-object-inner-validations';
    }

    /**
     * @return array<class-string<ModuleInterface>>
     */
    protected static function getGraphQLServerModuleClasses(): array
    {
        return [
            ...parent::getGraphQLServerModuleClasses(),
            ...[
                \PHPUnitForGatoGraphQL\GatoGraphQL\Module::class,
            ]
        ];
    }
}
