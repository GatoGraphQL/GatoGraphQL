<?php

declare(strict_types=1);

namespace PoP\ComponentModel\ComponentConfiguration;

use PoP\ComponentModel\Component;
use PoP\ComponentModel\Constants\Outputs;
use PoP\ComponentModel\Constants\Params;
use PoP\ComponentModel\Engine\EngineInterface;
use PoP\ComponentModel\Environment;
use PoP\Root\AbstractTestCase;
use PoP\Root\App;

abstract class AbstractModifyingEngineBehaviorViaRequestTestCase extends AbstractTestCase
{
    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();

        // Pretend we are sending ?output=json in the request
        App::getRequest()->query->set(Params::OUTPUT, Outputs::JSON);
    }

    /**
     * Add configuration for the Component classes
     *
     * @return array<string, mixed> [key]: Component class, [value]: Configuration
     */
    protected static function getComponentClassConfiguration(): array
    {
        return [
            Component::class => [
                Environment::ENABLE_MODIFYING_ENGINE_BEHAVIOR_VIA_REQUEST => static::enableModifyingEngineBehaviorViaRequest(),
            ],
        ];
    }

    abstract protected static function enableModifyingEngineBehaviorViaRequest(): bool;
    
    protected function getExpectedContentType(): string
    {
        if (static::enableModifyingEngineBehaviorViaRequest()) {
            return 'application/json';
        }
        return 'text/html';
    }

    /**
     * Execute a request, pretending to do ?output=json.
     * Depending on the value of env var ENABLE_MODIFYING_ENGINE_BEHAVIOR_VIA_REQUEST:
     * 
     *   if `true`, then the process must return JSON.
     *   if `false`, then the process must return HTML.
     */
    public function testEnableModifyingEngineBehaviorViaRequestEnvVar(): void
    {
        $engine = $this->getService(EngineInterface::class);
        $engine->generateDataAndPrepareResponse();
        $this->assertEquals(
            App::getResponse()->headers->get('content-type'),
            $this->getExpectedContentType()
        );
    }
}
