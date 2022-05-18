<?php

class PoP_Module_Processor_UrlParamTextFormInputs extends PoP_Module_Processor_UrlParamTextFormInputsBase
{
    public final const MODULE_FORMINPUT_URLPARAMTEXT_POSTID = 'forminput-urlparamtext-pid';
    public final const MODULE_FORMINPUT_URLPARAMTEXT_USERID = 'forminput-urlparamtext-uid';
    public final const MODULE_FORMINPUT_URLPARAMTEXT_COMMENTID = 'forminput-urlparamtext-cid';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FORMINPUT_URLPARAMTEXT_POSTID],
            [self::class, self::MODULE_FORMINPUT_URLPARAMTEXT_USERID],
            [self::class, self::MODULE_FORMINPUT_URLPARAMTEXT_COMMENTID],
        );
    }

    public function getName(array $componentVariation): string
    {
        switch ($componentVariation[1]) {
            case self::MODULE_FORMINPUT_URLPARAMTEXT_POSTID:
            case self::MODULE_FORMINPUT_URLPARAMTEXT_USERID:
            case self::MODULE_FORMINPUT_URLPARAMTEXT_COMMENTID:
                $names = array(
                    self::MODULE_FORMINPUT_URLPARAMTEXT_POSTID => \PoPCMSSchema\Posts\Constants\InputNames::POST_ID,
                    self::MODULE_FORMINPUT_URLPARAMTEXT_USERID => \PoPCMSSchema\Users\Constants\InputNames::USER_ID,
                    self::MODULE_FORMINPUT_URLPARAMTEXT_COMMENTID => \PoPCMSSchema\Comments\Constants\InputNames::COMMENT_ID,
                );

                return $names[$componentVariation[1]];
        }

        return parent::getName($componentVariation);
    }
}



