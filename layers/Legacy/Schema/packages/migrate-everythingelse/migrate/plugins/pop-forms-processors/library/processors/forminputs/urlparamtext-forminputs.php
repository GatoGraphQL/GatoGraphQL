<?php

class PoP_Module_Processor_UrlParamTextFormInputs extends PoP_Module_Processor_UrlParamTextFormInputsBase
{
    public const MODULE_FORMINPUT_URLPARAMTEXT_POSTID = 'forminput-urlparamtext-pid';
    public const MODULE_FORMINPUT_URLPARAMTEXT_USERID = 'forminput-urlparamtext-uid';
    public const MODULE_FORMINPUT_URLPARAMTEXT_COMMENTID = 'forminput-urlparamtext-cid';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FORMINPUT_URLPARAMTEXT_POSTID],
            [self::class, self::MODULE_FORMINPUT_URLPARAMTEXT_USERID],
            [self::class, self::MODULE_FORMINPUT_URLPARAMTEXT_COMMENTID],
        );
    }

    public function getName(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_FORMINPUT_URLPARAMTEXT_POSTID:
            case self::MODULE_FORMINPUT_URLPARAMTEXT_USERID:
            case self::MODULE_FORMINPUT_URLPARAMTEXT_COMMENTID:
                $names = array(
                    self::MODULE_FORMINPUT_URLPARAMTEXT_POSTID => \PoPSchema\Posts\Constants\InputNames::POST_ID,
                    self::MODULE_FORMINPUT_URLPARAMTEXT_USERID => \PoPSchema\Users\Constants\InputNames::USER_ID,
                    self::MODULE_FORMINPUT_URLPARAMTEXT_COMMENTID => \PoPSchema\Comments\Constants\InputNames::COMMENT_ID,
                );

                return $names[$module[1]];
        }

        return parent::getName($module);
    }
}



