<?php

class PoP_Module_Processor_HiddenInputFormInputs extends PoP_Module_Processor_HiddenInputFormInputsBase
{
    public final const MODULE_FORMINPUT_HIDDENINPUT_LAYOUTPOST = 'forminput-hiddeninput-post';
    public final const MODULE_FORMINPUT_HIDDENINPUT_LAYOUTCOMMENTPOST = 'forminput-hiddeninput-commentpost';
    public final const MODULE_FORMINPUT_HIDDENINPUT_LAYOUTUSER = 'forminput-hiddeninput-user';
    public final const MODULE_FORMINPUT_HIDDENINPUT_LAYOUTCOMMENT = 'forminput-hiddeninput-comment';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_FORMINPUT_HIDDENINPUT_LAYOUTPOST],
            [self::class, self::COMPONENT_FORMINPUT_HIDDENINPUT_LAYOUTCOMMENTPOST],
            [self::class, self::COMPONENT_FORMINPUT_HIDDENINPUT_LAYOUTUSER],
            [self::class, self::COMPONENT_FORMINPUT_HIDDENINPUT_LAYOUTCOMMENT],
        );
    }

    public function getName(array $component): string
    {
        switch ($component[1]) {
            case self::COMPONENT_FORMINPUT_HIDDENINPUT_LAYOUTPOST:
            case self::COMPONENT_FORMINPUT_HIDDENINPUT_LAYOUTCOMMENTPOST:
                return \PoPCMSSchema\Posts\Constants\InputNames::POST_ID;

            case self::COMPONENT_FORMINPUT_HIDDENINPUT_LAYOUTUSER:
                return \PoPCMSSchema\Users\Constants\InputNames::USER_ID;

            case self::COMPONENT_FORMINPUT_HIDDENINPUT_LAYOUTCOMMENT:
                return \PoPCMSSchema\Comments\Constants\InputNames::COMMENT_ID;
        }

        return parent::getName($component);
    }
}



