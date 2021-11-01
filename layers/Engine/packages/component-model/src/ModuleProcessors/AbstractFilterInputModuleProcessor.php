<?php

declare(strict_types=1);

namespace PoP\ComponentModel\ModuleProcessors;

use PoP\ComponentModel\Schema\SchemaDefinitionServiceInterface;

abstract class AbstractFilterInputModuleProcessor extends AbstractFormInputModuleProcessor
{
    use FilterInputModuleProcessorTrait;

    private ?SchemaDefinitionServiceInterface $schemaDefinitionService = null;

    final public function setSchemaDefinitionService(SchemaDefinitionServiceInterface $schemaDefinitionService): void
    {
        $this->schemaDefinitionService = $schemaDefinitionService;
    }
    final protected function getSchemaDefinitionService(): SchemaDefinitionServiceInterface
    {
        return $this->schemaDefinitionService ??= $this->instanceManager->getInstance(SchemaDefinitionServiceInterface::class);
    }
}
