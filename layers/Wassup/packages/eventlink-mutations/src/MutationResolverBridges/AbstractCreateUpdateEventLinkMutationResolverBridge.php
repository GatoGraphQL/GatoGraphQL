<?php

declare(strict_types=1);

namespace PoPSitesWassup\EventLinkMutations\MutationResolverBridges;

use PoPSitesWassup\EventMutations\MutationResolverBridges\AbstractCreateUpdateEventMutationResolverBridge;

abstract class AbstractCreateUpdateEventLinkMutationResolverBridge extends AbstractCreateUpdateEventMutationResolverBridge
{
    /**
     * Function below was copied from class GD_CreateUpdate_PostLink
     */
    protected function getEditorInput()
    {
        return [\PoP_Module_Processor_CreateUpdatePostTextFormInputs::class, \PoP_Module_Processor_CreateUpdatePostTextFormInputs::MODULE_CONTENTPOSTLINKS_FORMINPUT_LINK];
    }
}
