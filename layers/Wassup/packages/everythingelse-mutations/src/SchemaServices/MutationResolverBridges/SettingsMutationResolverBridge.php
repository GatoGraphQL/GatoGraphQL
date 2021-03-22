<?php

declare(strict_types=1);

namespace PoPSitesWassup\EverythingElseMutations\SchemaServices\MutationResolverBridges;

use PoP\ComponentModel\QueryInputOutputHandlers\ResponseConstants;
use PoP\ComponentModel\Facades\MutationResolution\MutationResolutionManagerFacade;
use PoPSitesWassup\EverythingElseMutations\SchemaServices\MutationResolvers\SettingsMutationResolver;
use PoP\ComponentModel\MutationResolverBridges\AbstractComponentMutationResolverBridge;

class SettingsMutationResolverBridge extends AbstractComponentMutationResolverBridge
{
    public function getMutationResolverClass(): string
    {
        return SettingsMutationResolver::class;
    }

    public function getFormData(): array
    {
        return [];
    }

    /**
     * @return array<string, mixed>|null
     */
    public function execute(array &$data_properties): ?array
    {
        $executed = parent::execute($data_properties);
        if ($executed !== null && $executed[ResponseConstants::SUCCESS]) {
            // Add the result from the MutationResolver as hard redirect
            $gd_dataload_actionexecution_manager = MutationResolutionManagerFacade::getInstance();
            $redirect_to = $gd_dataload_actionexecution_manager->getResult(get_called_class());
            $executed[GD_DATALOAD_QUERYHANDLERRESPONSE_HARDREDIRECT] = $redirect_to;
        }
        return $executed;
    }
}
