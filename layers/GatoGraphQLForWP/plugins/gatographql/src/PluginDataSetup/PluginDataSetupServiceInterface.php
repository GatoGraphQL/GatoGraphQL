<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\PluginDataSetup;

interface PluginDataSetupServiceInterface
{
    public function getNestedMutationsSchemaConfigurationCustomPostID(): ?int;

    /**
     * @return array<string,mixed>
     */
    public function getNestedMutationsBlockDataItem(): array;

    /**
     * @param array<array<string,mixed>> $blockDataItems
     */
    public function createSchemaConfigurationID(string $slug, string $title, array $blockDataItems): ?int;

    public function getBulkMutationsSchemaConfigurationCustomPostID(): ?int;

    /**
     * @return array<string,mixed>
     */
    public function getUseAndQueryPayloadTypeForMutationseBlockDataItem(): array;

    public function getAdminEndpointCategoryID(): ?int;

    public function createEndpointCategoryID(string $slug, string $name, string $description): ?int;

    public function getWebhookEndpointCategoryID(): ?int;

    /**
     * @return array<string,mixed>
     */
    public function getAdminEndpointTaxInputData(): array;

    /**
     * @return array<string,mixed>
     */
    public function getWebhookEndpointTaxInputData(): array;    
}
