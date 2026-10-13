<?php

declare(strict_types=1);

namespace PoP\ComponentModel\DirectivePipeline\SpecCompliantErrorHandling;

use PoP\ComponentModel\AbstractTestCase;
use PoP\ComponentModel\App;
use PoP\ComponentModel\DirectivePipeline\DirectiveNullabilityValidationService;
use PoP\ComponentModel\Environment;
use PoP\ComponentModel\Module;
use PoP\Root\Facades\Instances\InstanceManagerFacade;
use PoP\Root\Module\ModuleInterface;

use function sprintf;

abstract class AbstractDirectiveNullabilityValidationTestCase extends AbstractTestCase
{
    /**
     * @return class-string<ModuleInterface>
     */
    protected static function getModuleClass(): string
    {
        return Module::class;
    }

    /**
     * @return array<class-string<ModuleInterface>,array<string,mixed>> [key]: Module class, [value]: Configuration
     */
    protected static function getModuleClassConfiguration(): array
    {
        $moduleClassConfiguration = parent::getModuleClassConfiguration();
        $moduleClassConfiguration[Module::class][Environment::ENABLE_SPEC_COMPLIANT_ERROR_HANDLING] = static::enabled();
        return $moduleClassConfiguration;
    }

    abstract protected static function enabled(): bool;

    private function createDirectiveNullabilityValidationService(): DirectiveNullabilityValidationService
    {
        $directiveNullabilityValidationService = new DirectiveNullabilityValidationService();
        $directiveNullabilityValidationService->setInstanceManager(InstanceManagerFacade::getInstance());
        return $directiveNullabilityValidationService;
    }

    /**
     * The check runs only for the pipeline started by the engine
     * (depth 1), and never for the pipelines of the directives
     * nested within a meta directive (depth 2 or more).
     */
    public function testMustValidateDirectivePipelineStage(): void
    {
        $directiveNullabilityValidationService = $this->createDirectiveNullabilityValidationService();
        $appStateManager = App::getAppStateManager();
        $expectedByDepth = [
            0 => false,
            1 => static::enabled(),
            2 => false,
            3 => false,
        ];
        foreach ($expectedByDepth as $depth => $expected) {
            $appStateManager->override('directive-pipeline-depth', $depth);
            $this->assertSame(
                $expected,
                $directiveNullabilityValidationService->mustValidateDirectivePipeline(),
                sprintf('Directive pipeline depth %s', $depth)
            );
        }
    }
}
