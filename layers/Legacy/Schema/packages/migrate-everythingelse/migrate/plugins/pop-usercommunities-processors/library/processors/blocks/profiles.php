<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class GD_URE_Module_Processor_ProfileBlocks extends PoP_Module_Processor_BlocksBase
{
    public final const MODULE_BLOCK_MYCOMMUNITIES_UPDATE = 'block-mycommunities-update';
    public final const MODULE_BLOCK_INVITENEWMEMBERS = 'block-invitemembers';
    public final const MODULE_BLOCK_EDITMEMBERSHIP = 'block-editmembership';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_BLOCK_MYCOMMUNITIES_UPDATE],
            [self::class, self::MODULE_BLOCK_INVITENEWMEMBERS],
            [self::class, self::MODULE_BLOCK_EDITMEMBERSHIP],
        );
    }

    public function getRelevantRoute(array $componentVariation, array &$props): ?string
    {
        return match($componentVariation[1]) {
            self::MODULE_BLOCK_EDITMEMBERSHIP => POP_USERCOMMUNITIES_ROUTE_EDITMEMBERSHIP,
            self::MODULE_BLOCK_INVITENEWMEMBERS => POP_USERCOMMUNITIES_ROUTE_INVITENEWMEMBERS,
            self::MODULE_BLOCK_MYCOMMUNITIES_UPDATE => POP_USERCOMMUNITIES_ROUTE_MYCOMMUNITIES,
            default => parent::getRelevantRoute($componentVariation, $props),
        };
    }

    protected function getDescription(array $componentVariation, array &$props)
    {
        $cmsapplicationapi = \PoP\Application\FunctionAPIFactory::getInstance();
        switch ($componentVariation[1]) {
            case self::MODULE_BLOCK_INVITENEWMEMBERS:
                return sprintf(
                    '<div class="alert alert-info"><p>%s</p><ul><li>%s</li><li>%s</li></ul></div>',
                    sprintf(
                        TranslationAPIFacade::getInstance()->__('Send an invite for all your physical members to also become your members on <em><strong>%s</strong></em>:', 'ure-popprocessors'),
                        $cmsapplicationapi->getSiteName()
                    ),
                    TranslationAPIFacade::getInstance()->__('<strong>Become a community: </strong>all their content in the website will also show up under your profile'),
                    TranslationAPIFacade::getInstance()->__('<strong>Keep them engaged: </strong>all members will receive notifications of any activity by any member or your community')
                );
        }

        return parent::getDescription($componentVariation, $props);
    }

    protected function getInnerSubmodules(array $componentVariation): array
    {
        $ret = parent::getInnerSubmodules($componentVariation);

        $inners = array(
            self::MODULE_BLOCK_MYCOMMUNITIES_UPDATE => [GD_URE_Module_Processor_ProfileDataloads::class, GD_URE_Module_Processor_ProfileDataloads::MODULE_DATALOAD_MYCOMMUNITIES_UPDATE],
            self::MODULE_BLOCK_INVITENEWMEMBERS => [GD_URE_Module_Processor_ProfileDataloads::class, GD_URE_Module_Processor_ProfileDataloads::MODULE_DATALOAD_INVITENEWMEMBERS],
            self::MODULE_BLOCK_EDITMEMBERSHIP => [GD_URE_Module_Processor_ProfileDataloads::class, GD_URE_Module_Processor_ProfileDataloads::MODULE_DATALOAD_EDITMEMBERSHIP],
        );
        if ($inner = $inners[$componentVariation[1]] ?? null) {
            $ret[] = $inner;
        }

        return $ret;
    }

    protected function showDisabledLayerIfCheckpointFailed(array $componentVariation, array &$props)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_BLOCK_MYCOMMUNITIES_UPDATE:
            case self::MODULE_BLOCK_EDITMEMBERSHIP:
                return true;
        }

        return parent::showDisabledLayerIfCheckpointFailed($componentVariation, $props);
        ;
    }
}



