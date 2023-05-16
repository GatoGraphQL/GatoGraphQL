<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\GatoGraphQL\Unit;

use PHPUnitForGatoGraphQL\WPFakerSchema\Unit\AbstractWPFakerFixtureQueryExecutionGraphQLServerTestCase;
use PoP\Root\Module\ModuleInterface;

/**
 * Test that "global mutations" appear under MutationRoot and not under QueryRoot,
 * and viceversa with "global fields"
 */
class GlobalFieldsAndMutationsWPFakerFixtureQueryExecutionGraphQLServerTest extends AbstractWPFakerFixtureQueryExecutionGraphQLServerTestCase
{
    protected function getFixtureFolder(): string
    {
        return __DIR__ . '/fixture-global-fields-and-mutations';
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
