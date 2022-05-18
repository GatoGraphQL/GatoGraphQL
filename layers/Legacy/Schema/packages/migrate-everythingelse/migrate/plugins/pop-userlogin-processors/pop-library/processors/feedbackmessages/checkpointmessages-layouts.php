<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class GD_UserLogin_Module_Processor_UserCheckpointMessageLayouts extends PoP_Module_Processor_CheckpointMessageLayoutsBase
{
    public final const MODULE_LAYOUT_CHECKPOINTMESSAGE_NOTLOGGEDIN = 'layout-checkpointmessage-notloggedin';
    public final const MODULE_LAYOUT_CHECKPOINTMESSAGE_LOGGEDIN = 'layout-checkpointmessage-loggedin';
    public final const MODULE_LAYOUT_CHECKPOINTMESSAGE_LOGGEDINCANEDIT = 'layout-checkpointmessage-loggedincanedit';
    public final const MODULE_LAYOUT_CHECKPOINTMESSAGE_LOGGEDINISADMIN = 'layout-checkpointmessage-loggedinisadmin';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_LAYOUT_CHECKPOINTMESSAGE_NOTLOGGEDIN],
            [self::class, self::MODULE_LAYOUT_CHECKPOINTMESSAGE_LOGGEDIN],
            [self::class, self::MODULE_LAYOUT_CHECKPOINTMESSAGE_LOGGEDINCANEDIT],
            [self::class, self::MODULE_LAYOUT_CHECKPOINTMESSAGE_LOGGEDINISADMIN],
        );
    }

    public function getMessages(array $module, array &$props)
    {
        $ret = parent::getMessages($module, $props);

        $action = $this->getProp($module, $props, 'action');

        $cmsuseraccountapi = \PoP\UserAccount\FunctionAPIFactory::getInstance();
        switch ($module[1]) {
            case self::MODULE_LAYOUT_CHECKPOINTMESSAGE_NOTLOGGEDIN:
                // User already logged in (cannot welcome the person, can't say "Hi Peter!" since this message will be recorded at the beginning, when we still don't have the person logged in)
                $ret['error-header'] = TranslationAPIFacade::getInstance()->__('Login/Register', 'pop-coreprocessors');
                $ret['userloggedin'] = sprintf(
                    TranslationAPIFacade::getInstance()->__('You are currently logged in, please <a href="%1$s">logout</a> first to %2$s.', 'pop-coreprocessors'),
                    $cmsuseraccountapi->getLogoutURL(),
                    $action
                );
                break;

            case self::MODULE_LAYOUT_CHECKPOINTMESSAGE_LOGGEDIN:
            case self::MODULE_LAYOUT_CHECKPOINTMESSAGE_LOGGEDINCANEDIT:
            case self::MODULE_LAYOUT_CHECKPOINTMESSAGE_LOGGEDINISADMIN:
                $ret['error-header'] = TranslationAPIFacade::getInstance()->__('Login/Register', 'pop-coreprocessors');
                $ret['usernotloggedin'] = sprintf(
                    TranslationAPIFacade::getInstance()->__('You are not logged in yet, please %1$s first to %2$s.', 'poptheme-wassup'),
                    gdGetLoginHtml(),
                    $action
                );

                // Allow to add extra messages by WSL for Change Pwd
                $extra_checkpoint_msgs = \PoP\Root\App::applyFilters(
                    'GD_UserLogin_Module_Processor_UserCheckpointMessageLayouts:checkpoint-messages:loggedin',
                    $this->getProp($module, $props, 'extra-checkpoint-messages'),
                    $module
                );
                if ($extra_checkpoint_msgs) {
                    $ret = array_merge(
                        $ret,
                        $extra_checkpoint_msgs
                    );
                }
                break;
        }

        switch ($module[1]) {
            case self::MODULE_LAYOUT_CHECKPOINTMESSAGE_LOGGEDINCANEDIT:
                $ret['usercannotedit'] = sprintf(
                    TranslationAPIFacade::getInstance()->__('You have no permissions to %1$s.', 'pop-coreprocessors'),
                    $action
                );
                $ret['nonceinvalid'] = TranslationAPIFacade::getInstance()->__('The URL is invalid, please reload the page and try again.', 'pop-coreprocessors');
                break;

            case self::MODULE_LAYOUT_CHECKPOINTMESSAGE_LOGGEDINISADMIN:
                $ret['userisnotadmin'] = sprintf(
                    TranslationAPIFacade::getInstance()->__('Only site administrators can %1$s.', 'pop-coreprocessors'),
                    $action
                );
                break;
        }

        return $ret;
    }

    public function initModelProps(array $module, array &$props): void
    {
        $this->setProp($module, $props, 'extra-checkpoint-messages', array());
        $this->setProp($module, $props, 'action', TranslationAPIFacade::getInstance()->__('execute this operation', 'poptheme-wassup'));
        parent::initModelProps($module, $props);
    }
}



