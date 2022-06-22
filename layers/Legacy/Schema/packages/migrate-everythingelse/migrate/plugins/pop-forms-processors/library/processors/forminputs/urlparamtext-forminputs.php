<?php

class PoP_Module_Processor_UrlParamTextFormInputs extends PoP_Module_Processor_UrlParamTextFormInputsBase
{
    public final const COMPONENT_FORMINPUT_URLPARAMTEXT_POSTID = 'forminput-urlparamtext-pid';
    public final const COMPONENT_FORMINPUT_URLPARAMTEXT_USERID = 'forminput-urlparamtext-uid';
    public final const COMPONENT_FORMINPUT_URLPARAMTEXT_COMMENTID = 'forminput-urlparamtext-cid';

    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_FORMINPUT_URLPARAMTEXT_POSTID,
            self::COMPONENT_FORMINPUT_URLPARAMTEXT_USERID,
            self::COMPONENT_FORMINPUT_URLPARAMTEXT_COMMENTID,
        );
    }

    public function getName(\PoP\ComponentModel\Component\Component $component): string
    {
        switch ($component->name) {
            case self::COMPONENT_FORMINPUT_URLPARAMTEXT_POSTID:
            case self::COMPONENT_FORMINPUT_URLPARAMTEXT_USERID:
            case self::COMPONENT_FORMINPUT_URLPARAMTEXT_COMMENTID:
                $names = array(
                    self::COMPONENT_FORMINPUT_URLPARAMTEXT_POSTID => \PoPCMSSchema\Posts\Constants\InputNames::POST_ID,
                    self::COMPONENT_FORMINPUT_URLPARAMTEXT_USERID => \PoPCMSSchema\Users\Constants\InputNames::USER_ID,
                    self::COMPONENT_FORMINPUT_URLPARAMTEXT_COMMENTID => \PoPCMSSchema\Comments\Constants\InputNames::COMMENT_ID,
                );

                return $names[$component->name];
        }

        return parent::getName($component);
    }
}



