<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Services\SchemaConfigurators;

use GatoGraphQL\GatoGraphQL\App;
use GatoGraphQL\GatoGraphQL\AppHelpers;
use GatoGraphQL\GatoGraphQL\Module;
use GatoGraphQL\GatoGraphQL\ModuleConfiguration;

abstract class AbstractEndpointSchemaConfigurator extends AbstractSchemaConfigurator
{
    public function isServiceEnabled(): bool
    {
        if (!parent::isServiceEnabled()) {
            return false;
        }

        /**
         * @var ModuleConfiguration
         */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        if (!$moduleConfiguration->enableSchemaConfiguration()) {
            return false;
        }
        
        /**
         * Maybe do not initialize for the Internal AppThread
         */
        if (
            !$moduleConfiguration->useSchemaConfigurationInInternalGraphQLServer()
            && AppHelpers::isInternalGraphQLServerAppThread()
        ) {
            return false;
        }

        return true;
    }
}
