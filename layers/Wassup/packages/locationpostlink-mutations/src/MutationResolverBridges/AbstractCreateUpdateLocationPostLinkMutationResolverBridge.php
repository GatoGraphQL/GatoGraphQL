<?php

declare(strict_types=1);

namespace PoPSitesWassup\LocationPostLinkMutations\MutationResolverBridges;

use PoPSitesWassup\LocationPostMutations\MutationResolverBridges\AbstractCreateUpdateLocationPostMutationResolverBridge;

abstract class AbstractCreateUpdateLocationPostLinkMutationResolverBridge extends AbstractCreateUpdateLocationPostMutationResolverBridge
{
    /**
     * Function below was copied from class GD_CreateUpdate_PostLink
     */
    protected function getEditorInput()
    {
        return [\PoP_Module_Processor_CreateUpdatePostTextFormInputs::class, \PoP_Module_Processor_CreateUpdatePostTextFormInputs::MODULE_CONTENTPOSTLINKS_FORMINPUT_LINK];
    }
}
