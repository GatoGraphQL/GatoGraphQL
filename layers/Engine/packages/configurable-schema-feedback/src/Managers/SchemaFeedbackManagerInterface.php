<?php

declare(strict_types=1);

namespace PoP\ConfigurableSchemaFeedback\Managers;

interface SchemaFeedbackManagerInterface
{
    public function getEntriesForFields(): array;
    public function addEntriesForFields(array $fieldEntries): void;
}
