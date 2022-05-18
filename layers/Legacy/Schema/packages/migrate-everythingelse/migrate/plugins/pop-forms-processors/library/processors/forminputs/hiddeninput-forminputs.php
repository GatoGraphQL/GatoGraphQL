<?php

class PoP_Module_Processor_HiddenInputFormInputs extends PoP_Module_Processor_HiddenInputFormInputsBase
{
    public final const MODULE_FORMINPUT_HIDDENINPUT_LAYOUTPOST = 'forminput-hiddeninput-post';
    public final const MODULE_FORMINPUT_HIDDENINPUT_LAYOUTCOMMENTPOST = 'forminput-hiddeninput-commentpost';
    public final const MODULE_FORMINPUT_HIDDENINPUT_LAYOUTUSER = 'forminput-hiddeninput-user';
    public final const MODULE_FORMINPUT_HIDDENINPUT_LAYOUTCOMMENT = 'forminput-hiddeninput-comment';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FORMINPUT_HIDDENINPUT_LAYOUTPOST],
            [self::class, self::MODULE_FORMINPUT_HIDDENINPUT_LAYOUTCOMMENTPOST],
            [self::class, self::MODULE_FORMINPUT_HIDDENINPUT_LAYOUTUSER],
            [self::class, self::MODULE_FORMINPUT_HIDDENINPUT_LAYOUTCOMMENT],
        );
    }

    public function getName(array $module): string
    {
        switch ($module[1]) {
            case self::MODULE_FORMINPUT_HIDDENINPUT_LAYOUTPOST:
            case self::MODULE_FORMINPUT_HIDDENINPUT_LAYOUTCOMMENTPOST:
                return \PoPCMSSchema\Posts\Constants\InputNames::POST_ID;

            case self::MODULE_FORMINPUT_HIDDENINPUT_LAYOUTUSER:
                return \PoPCMSSchema\Users\Constants\InputNames::USER_ID;

            case self::MODULE_FORMINPUT_HIDDENINPUT_LAYOUTCOMMENT:
                return \PoPCMSSchema\Comments\Constants\InputNames::COMMENT_ID;
        }

        return parent::getName($module);
    }
}



