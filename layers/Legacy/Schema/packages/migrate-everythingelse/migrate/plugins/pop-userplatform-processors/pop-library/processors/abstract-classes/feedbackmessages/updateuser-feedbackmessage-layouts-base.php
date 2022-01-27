<?php
use PoP\ComponentModel\State\ApplicationState;
use PoP\Root\Facades\Translation\TranslationAPIFacade;
use PoPCMSSchema\Users\Facades\UserTypeAPIFacade;

abstract class PoP_Module_Processor_UpdateUserFormMesageFeedbackLayoutsBase extends PoP_Module_Processor_FormFeedbackMessageLayoutsBase
{
    public function getMessages(array $module, array &$props)
    {
        $ret = parent::getMessages($module, $props);
            
        $userTypeAPI = UserTypeAPIFacade::getInstance();
        $ret['success-header'] = TranslationAPIFacade::getInstance()->__('User Account updated successfully.', 'pop-coreprocessors');
        $ret['success'] = sprintf(
            TranslationAPIFacade::getInstance()->__('View your <a href="%s">updated user account</a>.', 'pop-coreprocessors'),
            $userTypeAPI->getUserURL(\PoP\Root\App::getState('current-user-id'))
        );

        return $ret;
    }
}
