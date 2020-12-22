<?php
use PoP\Translation\Facades\TranslationAPIFacade;
use PoP\ComponentModel\State\ApplicationState;

abstract class PoP_Module_Processor_UpdateUserFormMesageFeedbackLayoutsBase extends PoP_Module_Processor_FormFeedbackMessageLayoutsBase
{
    public function getMessages(array $module, array &$props)
    {
        $ret = parent::getMessages($module, $props);
            
        $cmsusersapi = \PoPSchema\Users\FunctionAPIFactory::getInstance();
        $vars = ApplicationState::getVars();
        $ret['success-header'] = TranslationAPIFacade::getInstance()->__('User Account updated successfully.', 'pop-coreprocessors');
        $ret['success'] = sprintf(
            TranslationAPIFacade::getInstance()->__('View your <a href="%s">updated user account</a>.', 'pop-coreprocessors'),
            $cmsusersapi->getUserURL($vars['global-userstate']['current-user-id'])
        );

        return $ret;
    }
}
