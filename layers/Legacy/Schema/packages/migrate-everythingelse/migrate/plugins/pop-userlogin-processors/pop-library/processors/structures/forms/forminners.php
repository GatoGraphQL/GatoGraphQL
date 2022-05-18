<?php
use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;
use PoP\ComponentModel\Misc\RequestUtils;

class GD_UserLogin_Module_Processor_UserFormInners extends PoP_Module_Processor_FormInnersBase
{
    public final const MODULE_FORMINNER_LOGIN = 'forminner-login';
    public final const MODULE_FORMINNER_LOSTPWD = 'forminner-lostpwd';
    public final const MODULE_FORMINNER_LOSTPWDRESET = 'forminner-lostpwdreset';
    public final const MODULE_FORMINNER_LOGOUT = 'forminner-logout';
    public final const MODULE_FORMINNER_USER_CHANGEPASSWORD = 'forminner-user-changepwd';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FORMINNER_LOGIN],
            [self::class, self::MODULE_FORMINNER_LOSTPWD],
            [self::class, self::MODULE_FORMINNER_LOSTPWDRESET],
            [self::class, self::MODULE_FORMINNER_LOGOUT],
            [self::class, self::MODULE_FORMINNER_USER_CHANGEPASSWORD],
        );
    }

    public function getLayoutSubmodules(array $componentVariation)
    {
        $ret = parent::getLayoutSubmodules($componentVariation);

        switch ($componentVariation[1]) {
            case self::MODULE_FORMINNER_LOGIN:
                $ret = array_merge(
                    $ret,
                    array(
                        [PoP_Module_Processor_LoginFormGroups::class, PoP_Module_Processor_LoginFormGroups::MODULE_FORMINPUTGROUP_LOGIN_USERNAME],
                        [PoP_Module_Processor_LoginFormGroups::class, PoP_Module_Processor_LoginFormGroups::MODULE_FORMINPUTGROUP_LOGIN_PWD],
                        [PoP_Module_Processor_TextFormInputs::class, PoP_Module_Processor_TextFormInputs::MODULE_FORMINPUT_BROWSERURL],
                        [PoP_Module_Processor_LoginSubmitButtons::class, PoP_Module_Processor_LoginSubmitButtons::MODULE_SUBMITBUTTON_LOGIN],
                    )
                );
                break;

            case self::MODULE_FORMINNER_LOSTPWD:
                $ret = array_merge(
                    $ret,
                    array(
                        [PoP_Module_Processor_LoginFormGroups::class, PoP_Module_Processor_LoginFormGroups::MODULE_FORMINPUTGROUP_LOSTPWD_USERNAME],
                        [PoP_Module_Processor_LoginSubmitButtons::class, PoP_Module_Processor_LoginSubmitButtons::MODULE_SUBMITBUTTON_LOSTPWD],
                    )
                );
                break;

            case self::MODULE_FORMINNER_LOSTPWDRESET:
                $ret = array_merge(
                    $ret,
                    array(
                        [PoP_Module_Processor_LoginFormGroups::class, PoP_Module_Processor_LoginFormGroups::MODULE_FORMINPUTGROUP_LOSTPWDRESET_CODE],
                        [PoP_Module_Processor_LoginFormGroups::class, PoP_Module_Processor_LoginFormGroups::MODULE_FORMINPUTGROUP_LOSTPWDRESET_NEWPASSWORD],
                        [PoP_Module_Processor_LoginFormGroups::class, PoP_Module_Processor_LoginFormGroups::MODULE_FORMINPUTGROUP_LOSTPWDRESET_PASSWORDREPEAT],
                        [PoP_Module_Processor_LoginSubmitButtons::class, PoP_Module_Processor_LoginSubmitButtons::MODULE_SUBMITBUTTON_LOSTPWDRESET],
                    )
                );
                break;

            case self::MODULE_FORMINNER_LOGOUT:
                $ret = array_merge(
                    $ret,
                    array(
                        [PoP_Module_Processor_TextFormInputs::class, PoP_Module_Processor_TextFormInputs::MODULE_FORMINPUT_BROWSERURL],
                        [PoP_Module_Processor_LoginSubmitButtons::class, PoP_Module_Processor_LoginSubmitButtons::MODULE_SUBMITBUTTON_LOGOUT],
                    )
                );
                break;

            case self::MODULE_FORMINNER_USER_CHANGEPASSWORD:
                $ret = array_merge(
                    array(
                        [PoP_Module_Processor_UserFormGroups::class, PoP_Module_Processor_UserFormGroups::MODULE_FORMINPUTGROUP_CUU_CURRENTPASSWORD],
                        [PoP_Module_Processor_UserFormGroups::class, PoP_Module_Processor_UserFormGroups::MODULE_FORMINPUTGROUP_CUU_NEWPASSWORD],
                        [PoP_Module_Processor_UserFormGroups::class, PoP_Module_Processor_UserFormGroups::MODULE_FORMINPUTGROUP_CUU_NEWPASSWORDREPEAT],
                        [PoP_Module_Processor_SubmitButtons::class, PoP_Module_Processor_SubmitButtons::MODULE_SUBMITBUTTON_UPDATE],
                    )
                );
                break;
        }

        return $ret;
    }

    public function initRequestProps(array $componentVariation, array &$props): void
    {
        switch ($componentVariation[1]) {
            case self::MODULE_FORMINNER_LOSTPWDRESET:
                // If loading the page straight, then set the value on the input directly
                // Otherwise, use Javascript to fill in the value
                if (RequestUtils::loadingSite()) {
                    if ($value = \PoP\Root\App::query(POP_INPUTNAME_CODE)) {
                        $this->setProp([PoP_Module_Processor_LoginTextFormInputs::class, PoP_Module_Processor_LoginTextFormInputs::MODULE_FORMINPUT_LOSTPWDRESET_CODE], $props, 'default-value'/*'selected-value'*/, $value);
                        $this->appendProp([PoP_Module_Processor_LoginFormGroups::class, PoP_Module_Processor_LoginFormGroups::MODULE_FORMINPUTGROUP_LOSTPWDRESET_CODE], $props, 'class', 'hidden');
                    }
                }
                break;
        }

        parent::initRequestProps($componentVariation, $props);
    }

    // function initModelProps(array $componentVariation, array &$props) {

    //     switch ($componentVariation[1]) {

    //         case self::MODULE_FORMINNER_LOSTPWDRESET:

    //             $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();

    //             // If loading the page straight, then set the value on the input directly
    //             // Otherwise, use Javascript to fill in the value
    //             // if (!RequestUtils::loadingSite()) {

    //             //     // This also works for replicable
    //             //     $this->mergeJsmethodsProp([PoP_Module_Processor_LoginTextFormInputs::class, PoP_Module_Processor_LoginTextFormInputs::MODULE_FORMINPUT_LOSTPWDRESET_CODE], $props, array('fillURLParamInput'));
    //             //     $this->mergeProp([PoP_Module_Processor_LoginTextFormInputs::class, PoP_Module_Processor_LoginTextFormInputs::MODULE_FORMINPUT_LOSTPWDRESET_CODE], $props, 'params', array(
    //             //         'data-urlparam' => $componentprocessor_manager->getProcessor([PoP_Module_Processor_LoginTextFormInputs::class, PoP_Module_Processor_LoginTextFormInputs::MODULE_FORMINPUT_LOSTPWDRESET_CODE])->getName([PoP_Module_Processor_LoginTextFormInputs::class, PoP_Module_Processor_LoginTextFormInputs::MODULE_FORMINPUT_LOSTPWDRESET_CODE]),
    //             //     ));
    //             // }
    //             break;
    //     }

    //     parent::initModelProps($componentVariation, $props);
    // }
}



