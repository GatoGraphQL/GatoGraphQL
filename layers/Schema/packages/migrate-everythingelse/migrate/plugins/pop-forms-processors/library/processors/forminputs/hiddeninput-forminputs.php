<?php

class PoP_Module_Processor_HiddenInputFormInputs extends PoP_Module_Processor_HiddenInputFormInputsBase
{
    public const MODULE_FORMINPUT_HIDDENINPUT_LAYOUTPOST = 'forminput-hiddeninput-post';
    public const MODULE_FORMINPUT_HIDDENINPUT_LAYOUTCOMMENTPOST = 'forminput-hiddeninput-commentpost';
    public const MODULE_FORMINPUT_HIDDENINPUT_LAYOUTUSER = 'forminput-hiddeninput-user';
    public const MODULE_FORMINPUT_HIDDENINPUT_LAYOUTCOMMENT = 'forminput-hiddeninput-comment';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FORMINPUT_HIDDENINPUT_LAYOUTPOST],
            [self::class, self::MODULE_FORMINPUT_HIDDENINPUT_LAYOUTCOMMENTPOST],
            [self::class, self::MODULE_FORMINPUT_HIDDENINPUT_LAYOUTUSER],
            [self::class, self::MODULE_FORMINPUT_HIDDENINPUT_LAYOUTCOMMENT],
        );
    }

    public function getName(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_FORMINPUT_HIDDENINPUT_LAYOUTPOST:
            case self::MODULE_FORMINPUT_HIDDENINPUT_LAYOUTCOMMENTPOST:
                return POP_INPUTNAME_POSTID;

            case self::MODULE_FORMINPUT_HIDDENINPUT_LAYOUTUSER:
                return POP_INPUTNAME_USERID;

            case self::MODULE_FORMINPUT_HIDDENINPUT_LAYOUTCOMMENT:
                return POP_INPUTNAME_COMMENTID;
        }

        return parent::getName($module);
    }
}



