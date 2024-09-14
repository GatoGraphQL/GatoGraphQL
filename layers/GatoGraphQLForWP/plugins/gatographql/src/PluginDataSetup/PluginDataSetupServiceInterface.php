<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\PluginDataSetup;

interface PluginDataSetupServiceInterface
{
    public function getNestedMutationsSchemaConfigurationCustomPostID(): ?int;

    public function getBulkMutationsSchemaConfigurationCustomPostID(): ?int;

    /**
     * @return array<string,mixed>
     */
    public function getAdminEndpointTaxInputData(): array;

    /**
     * @return array<string,mixed>
     */
    public function getWebhookEndpointTaxInputData(): array;
}
