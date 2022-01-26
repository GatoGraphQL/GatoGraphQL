<?php

declare(strict_types=1);

namespace PoP\ComponentModel\ComponentConfiguration;

use PoP\ComponentModel\Component as ComponentModelComponent;
use PoP\ComponentModel\Constants\Params;
use PoP\ComponentModel\DataStructureFormatters\HTMLDataStructureFormatter;
use PoP\ComponentModel\Engine\EngineInterface;
use PoP\ComponentModel\Environment as ComponentModelEnvironment;
use PoP\Root\AbstractTestCase;
use PoP\Root\App;
use PoP\Root\Component as RootComponent;
use PoP\Root\Environment as RootEnvironment;

abstract class AbstractModifyingEngineBehaviorViaRequestTestCase extends AbstractTestCase
{
    public static function setUpBeforeClass(): void
    {
        $_REQUEST['output'] = 'json';
        $_REQUEST[Params::DATASTRUCTURE] = 'html';
        parent::setUpBeforeClass();

        // /**
        //  * Pretend we are sending ?datastructure=html in the request.
        //  */
        // $htmlDataStructureFormatter = self::$container->get(HTMLDataStructureFormatter::class);
        // App::getRequest()->query->set(Params::DATASTRUCTURE, $htmlDataStructureFormatter->getName());        
    }

    /**
     * Add configuration for the Component classes
     *
     * @return array<string, mixed> [key]: Component class, [value]: Configuration
     */
    protected static function getComponentClassConfiguration(): array
    {
        return [
            RootComponent::class => [
                RootEnvironment::ENABLE_PASSING_STATE_VIA_REQUEST => true,
                RootEnvironment::ENABLE_PASSING_ROUTING_STATE_VIA_REQUEST => true,
            ],
            ComponentModelComponent::class => [
                ComponentModelEnvironment::ENABLE_MODIFYING_ENGINE_BEHAVIOR_VIA_REQUEST => static::enableModifyingEngineBehaviorViaRequest(),
            ],
        ];
    }

    abstract protected static function enableModifyingEngineBehaviorViaRequest(): bool;
    
    protected function getExpectedContentType(): string
    {
        if (static::enableModifyingEngineBehaviorViaRequest()) {
            return 'text/html';
        }
        return 'application/json';
    }

    /**
     * Execute a request, pretending to do ?datastructure=html.
     * Depending on the value of env var ENABLE_MODIFYING_ENGINE_BEHAVIOR_VIA_REQUEST:
     * 
     *   if `true`, then the output will be HTML.
     *   if `false`, then the output will be JSON (which is the default on)
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
