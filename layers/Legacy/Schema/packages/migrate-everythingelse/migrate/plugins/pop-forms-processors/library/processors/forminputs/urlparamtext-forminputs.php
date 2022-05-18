<?php

class PoP_Module_Processor_UrlParamTextFormInputs extends PoP_Module_Processor_UrlParamTextFormInputsBase
{
    public final const COMPONENT_FORMINPUT_URLPARAMTEXT_POSTID = 'forminput-urlparamtext-pid';
    public final const COMPONENT_FORMINPUT_URLPARAMTEXT_USERID = 'forminput-urlparamtext-uid';
    public final const COMPONENT_FORMINPUT_URLPARAMTEXT_COMMENTID = 'forminput-urlparamtext-cid';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_FORMINPUT_URLPARAMTEXT_POSTID],
            [self::class, self::COMPONENT_FORMINPUT_URLPARAMTEXT_USERID],
            [self::class, self::COMPONENT_FORMINPUT_URLPARAMTEXT_COMMENTID],
        );
    }

    public function getName(array $component): string
    {
        switch ($component[1]) {
            case self::COMPONENT_FORMINPUT_URLPARAMTEXT_POSTID:
            case self::COMPONENT_FORMINPUT_URLPARAMTEXT_USERID:
            case self::COMPONENT_FORMINPUT_URLPARAMTEXT_COMMENTID:
                $names = array(
                    self::COMPONENT_FORMINPUT_URLPARAMTEXT_POSTID => \PoPCMSSchema\Posts\Constants\InputNames::POST_ID,
                    self::COMPONENT_FORMINPUT_URLPARAMTEXT_USERID => \PoPCMSSchema\Users\Constants\InputNames::USER_ID,
                    self::COMPONENT_FORMINPUT_URLPARAMTEXT_COMMENTID => \PoPCMSSchema\Comments\Constants\InputNames::COMMENT_ID,
                );

                return $names[$component[1]];
        }

        return parent::getName($component);
    }
}



