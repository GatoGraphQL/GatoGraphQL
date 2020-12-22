<?php

declare(strict_types=1);

namespace PoP\ConfigurableSchemaFeedback\Facades;

use PoP\ConfigurableSchemaFeedback\Managers\SchemaFeedbackManagerInterface;
use PoP\Root\Container\ContainerBuilderFactory;

class SchemaFeedbackManagerFacade
{
    public static function getInstance(): SchemaFeedbackManagerInterface
    {
        /**
         * @var SchemaFeedbackManagerInterface
         */
        $service = ContainerBuilderFactory::getInstance()->get(SchemaFeedbackManagerInterface::class);
        return $service;
    }
}
