<?php
use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;
use PoP\ComponentModel\Misc\RequestUtils;

class GD_UserLogin_Module_Processor_UserFormInners extends PoP_Module_Processor_FormInnersBase
{
    public final const COMPONENT_FORMINNER_LOGIN = 'forminner-login';
    public final const COMPONENT_FORMINNER_LOSTPWD = 'forminner-lostpwd';
    public final const COMPONENT_FORMINNER_LOSTPWDRESET = 'forminner-lostpwdreset';
    public final const COMPONENT_FORMINNER_LOGOUT = 'forminner-logout';
    public final const COMPONENT_FORMINNER_USER_CHANGEPASSWORD = 'forminner-user-changepwd';

    /**
     * @return string[]
     */
    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_FORMINNER_LOGIN,
            self::COMPONENT_FORMINNER_LOSTPWD,
            self::COMPONENT_FORMINNER_LOSTPWDRESET,
            self::COMPONENT_FORMINNER_LOGOUT,
            self::COMPONENT_FORMINNER_USER_CHANGEPASSWORD,
        );
    }

    /**
     * @return \PoP\ComponentModel\Component\Component[]
     */
    public function getLayoutSubcomponents(\PoP\ComponentModel\Component\Component $component): array
    {
        $ret = parent::getLayoutSubcomponents($component);

        switch ($component->name) {
            case self::COMPONENT_FORMINNER_LOGIN:
                $ret = array_merge(
                    $ret,
                    array(
                        [PoP_Module_Processor_LoginFormGroups::class, PoP_Module_Processor_LoginFormGroups::COMPONENT_FORMINPUTGROUP_LOGIN_USERNAME],
                        [PoP_Module_Processor_LoginFormGroups::class, PoP_Module_Processor_LoginFormGroups::COMPONENT_FORMINPUTGROUP_LOGIN_PWD],
                        [PoP_Module_Processor_TextFormInputs::class, PoP_Module_Processor_TextFormInputs::COMPONENT_FORMINPUT_BROWSERURL],
                        [PoP_Module_Processor_LoginSubmitButtons::class, PoP_Module_Processor_LoginSubmitButtons::COMPONENT_SUBMITBUTTON_LOGIN],
                    )
                );
                break;

            case self::COMPONENT_FORMINNER_LOSTPWD:
                $ret = array_merge(
                    $ret,
                    array(
                        [PoP_Module_Processor_LoginFormGroups::class, PoP_Module_Processor_LoginFormGroups::COMPONENT_FORMINPUTGROUP_LOSTPWD_USERNAME],
                        [PoP_Module_Processor_LoginSubmitButtons::class, PoP_Module_Processor_LoginSubmitButtons::COMPONENT_SUBMITBUTTON_LOSTPWD],
                    )
                );
                break;

            case self::COMPONENT_FORMINNER_LOSTPWDRESET:
                $ret = array_merge(
                    $ret,
                    array(
                        [PoP_Module_Processor_LoginFormGroups::class, PoP_Module_Processor_LoginFormGroups::COMPONENT_FORMINPUTGROUP_LOSTPWDRESET_CODE],
                        [PoP_Module_Processor_LoginFormGroups::class, PoP_Module_Processor_LoginFormGroups::COMPONENT_FORMINPUTGROUP_LOSTPWDRESET_NEWPASSWORD],
                        [PoP_Module_Processor_LoginFormGroups::class, PoP_Module_Processor_LoginFormGroups::COMPONENT_FORMINPUTGROUP_LOSTPWDRESET_PASSWORDREPEAT],
                        [PoP_Module_Processor_LoginSubmitButtons::class, PoP_Module_Processor_LoginSubmitButtons::COMPONENT_SUBMITBUTTON_LOSTPWDRESET],
                    )
                );
                break;

            case self::COMPONENT_FORMINNER_LOGOUT:
                $ret = array_merge(
                    $ret,
                    array(
                        [PoP_Module_Processor_TextFormInputs::class, PoP_Module_Processor_TextFormInputs::COMPONENT_FORMINPUT_BROWSERURL],
                        [PoP_Module_Processor_LoginSubmitButtons::class, PoP_Module_Processor_LoginSubmitButtons::COMPONENT_SUBMITBUTTON_LOGOUT],
                    )
                );
                break;

            case self::COMPONENT_FORMINNER_USER_CHANGEPASSWORD:
                $ret = array_merge(
                    array(
                        [PoP_Module_Processor_UserFormGroups::class, PoP_Module_Processor_UserFormGroups::COMPONENT_FORMINPUTGROUP_CUU_CURRENTPASSWORD],
                        [PoP_Module_Processor_UserFormGroups::class, PoP_Module_Processor_UserFormGroups::COMPONENT_FORMINPUTGROUP_CUU_NEWPASSWORD],
                        [PoP_Module_Processor_UserFormGroups::class, PoP_Module_Processor_UserFormGroups::COMPONENT_FORMINPUTGROUP_CUU_NEWPASSWORDREPEAT],
                        [PoP_Module_Processor_SubmitButtons::class, PoP_Module_Processor_SubmitButtons::COMPONENT_SUBMITBUTTON_UPDATE],
                    )
                );
                break;
        }

        return $ret;
    }

    public function initRequestProps(\PoP\ComponentModel\Component\Component $component, array &$props): void
    {
        switch ($component->name) {
            case self::COMPONENT_FORMINNER_LOSTPWDRESET:
                // If loading the page straight, then set the value on the input directly
                // Otherwise, use Javascript to fill in the value
                if (RequestUtils::loadingSite()) {
                    if ($value = \PoP\Root\App::query(POP_INPUTNAME_CODE)) {
                        $this->setProp([PoP_Module_Processor_LoginTextFormInputs::class, PoP_Module_Processor_LoginTextFormInputs::COMPONENT_FORMINPUT_LOSTPWDRESET_CODE], $props, 'default-value'/*'selected-value'*/, $value);
                        $this->appendProp([PoP_Module_Processor_LoginFormGroups::class, PoP_Module_Processor_LoginFormGroups::COMPONENT_FORMINPUTGROUP_LOSTPWDRESET_CODE], $props, 'class', 'hidden');
                    }
                }
                break;
        }

        parent::initRequestProps($component, $props);
    }

    // function initModelProps(\PoP\ComponentModel\Component\Component $component, array &$props) {

    //     switch ($component->name) {

    //         case self::COMPONENT_FORMINNER_LOSTPWDRESET:

    //             $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();

    //             // If loading the page straight, then set the value on the input directly
    //             // Otherwise, use Javascript to fill in the value
    //             // if (!RequestUtils::loadingSite()) {

    //             //     // This also works for replicable
    //             //     $this->mergeJsmethodsProp([PoP_Module_Processor_LoginTextFormInputs::class, PoP_Module_Processor_LoginTextFormInputs::COMPONENT_FORMINPUT_LOSTPWDRESET_CODE], $props, array('fillURLParamInput'));
    //             //     $this->mergeProp([PoP_Module_Processor_LoginTextFormInputs::class, PoP_Module_Processor_LoginTextFormInputs::COMPONENT_FORMINPUT_LOSTPWDRESET_CODE], $props, 'params', array(
    //             //         'data-urlparam' => $componentprocessor_manager->getComponentProcessor([PoP_Module_Processor_LoginTextFormInputs::class, PoP_Module_Processor_LoginTextFormInputs::COMPONENT_FORMINPUT_LOSTPWDRESET_CODE])->getName([PoP_Module_Processor_LoginTextFormInputs::class, PoP_Module_Processor_LoginTextFormInputs::COMPONENT_FORMINPUT_LOSTPWDRESET_CODE]),
    //             //     ));
    //             // }
    //             break;
    //     }

    //     parent::initModelProps($component, $props);
    // }
}



