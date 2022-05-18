<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_CommonUserRoles_Module_Processor_UserCheckpointMessageLayouts extends PoP_Module_Processor_CheckpointMessageLayoutsBase
{
    public final const MODULE_LAYOUT_CHECKPOINTMESSAGE_PROFILEORGANIZATION = 'layout-checkpointmessage-profileorganization';
    public final const MODULE_LAYOUT_CHECKPOINTMESSAGE_PROFILEINDIVIDUAL = 'layout-checkpointmessage-profileindividual';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_LAYOUT_CHECKPOINTMESSAGE_PROFILEORGANIZATION],
            [self::class, self::MODULE_LAYOUT_CHECKPOINTMESSAGE_PROFILEINDIVIDUAL],
        );
    }

    public function getMessages(array $module, array &$props)
    {
        $ret = parent::getMessages($module, $props);

        $action = $this->getProp($module, $props, 'action');
        switch ($module[1]) {
            case self::MODULE_LAYOUT_CHECKPOINTMESSAGE_PROFILEORGANIZATION:
            case self::MODULE_LAYOUT_CHECKPOINTMESSAGE_PROFILEINDIVIDUAL:
                $ret['usernotloggedin'] = sprintf(
                    TranslationAPIFacade::getInstance()->__('You are not logged in yet, please %1$s first to %2$s.', 'poptheme-wassup'),
                    gdGetLoginHtml(),
                    $action
                );
                $ret['usernoprofileaccess'] = sprintf(
                    TranslationAPIFacade::getInstance()->__('Your user account doesn\'t have the permission to %1$s.', 'poptheme-wassup'),
                    $action
                );
                break;
        }

        switch ($module[1]) {
            case self::MODULE_LAYOUT_CHECKPOINTMESSAGE_PROFILEORGANIZATION:
                $ret['profilenotorganization'] = sprintf(
                    TranslationAPIFacade::getInstance()->__('Your user account is not an organization, as such you cannot %1$s under this URL.', 'poptheme-wassup'),
                    $action
                );
                break;

            case self::MODULE_LAYOUT_CHECKPOINTMESSAGE_PROFILEINDIVIDUAL:
                $ret['profilenotindividual'] = sprintf(
                    TranslationAPIFacade::getInstance()->__('Your user account is not an individual, as such you cannot %1$s under this URL.', 'poptheme-wassup'),
                    $action
                );
                break;
        }

        return $ret;
    }

    public function initModelProps(array $module, array &$props): void
    {
        $this->setProp($module, $props, 'action', TranslationAPIFacade::getInstance()->__('execute this operation', 'poptheme-wassup'));
        parent::initModelProps($module, $props);
    }
}



