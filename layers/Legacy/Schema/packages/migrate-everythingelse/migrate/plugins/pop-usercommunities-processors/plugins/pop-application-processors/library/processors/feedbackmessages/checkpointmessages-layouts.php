<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class GD_UserCommunities_Module_Processor_UserCheckpointMessageLayouts extends PoP_Module_Processor_CheckpointMessageLayoutsBase
{
    public final const MODULE_LAYOUT_CHECKPOINTMESSAGE_PROFILECOMMUNITY = 'layout-checkpointmessage-profilecommunity';
    public final const MODULE_LAYOUT_CHECKPOINTMESSAGE_PROFILECOMMUNITYEDITMEMBERSHIP = 'layout-checkpointmessage-profilecommunityeditmembership';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_LAYOUT_CHECKPOINTMESSAGE_PROFILECOMMUNITY],
            [self::class, self::MODULE_LAYOUT_CHECKPOINTMESSAGE_PROFILECOMMUNITYEDITMEMBERSHIP],
        );
    }

    public function getMessages(array $componentVariation, array &$props)
    {
        $ret = parent::getMessages($componentVariation, $props);

        switch ($componentVariation[1]) {
            case self::MODULE_LAYOUT_CHECKPOINTMESSAGE_PROFILECOMMUNITY:
            case self::MODULE_LAYOUT_CHECKPOINTMESSAGE_PROFILECOMMUNITYEDITMEMBERSHIP:
                $action = $this->getProp($componentVariation, $props, 'action');
                $ret['usernotloggedin'] = sprintf(
                    TranslationAPIFacade::getInstance()->__('You are not logged in yet, please %1$s first to %2$s.', 'poptheme-wassup'),
                    gdGetLoginHtml(),
                    $action
                );
                $ret['usernoprofileaccess'] = sprintf(
                    TranslationAPIFacade::getInstance()->__('Your user account doesn\'t have the permission to %1$s.', 'poptheme-wassup'),
                    $action
                );
                $ret['profilenotcommunity'] = sprintf(
                    TranslationAPIFacade::getInstance()->__('Your user account is not a community, as such you cannot %1$s.', 'poptheme-wassup'),
                    $action
                );
                break;
        }

        switch ($componentVariation[1]) {
            case self::MODULE_LAYOUT_CHECKPOINTMESSAGE_PROFILECOMMUNITYEDITMEMBERSHIP:
                $ret['editingnotcommunitymember'] = TranslationAPIFacade::getInstance()->__('This user is not a member of your community.', 'ure-popprocessors');
                $ret['nonceinvalid'] = TranslationAPIFacade::getInstance()->__('The URL is invalid, please reload the page and try again.', 'pop-coreprocessors');
                break;
        }

        return $ret;
    }

    public function initModelProps(array $componentVariation, array &$props): void
    {
        $this->setProp($componentVariation, $props, 'action', TranslationAPIFacade::getInstance()->__('execute this operation', 'poptheme-wassup'));
        parent::initModelProps($componentVariation, $props);
    }
}



