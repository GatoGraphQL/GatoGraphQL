<?php
use PoP\Engine\FormInputs\SelectFormInput;

class GD_FormInput_Stance extends SelectFormInput
{
    public function getAllValues($label = null): array
    {
        $values = parent::getAllValues($label);

        $term_names = PoP_UserStance_PostNameUtils::getTermNames();
        $values[POP_USERSTANCE_TERM_STANCE_PRO] = '<i class="fa fa-fw fa-thumbs-o-up"></i>'.$term_names[POP_USERSTANCE_TERM_STANCE_PRO];
        $values[POP_USERSTANCE_TERM_STANCE_NEUTRAL] = '<i class="fa fa-fw fa-hand-peace-o"></i>'.$term_names[POP_USERSTANCE_TERM_STANCE_NEUTRAL];
        $values[POP_USERSTANCE_TERM_STANCE_AGAINST] = '<i class="fa fa-fw fa-thumbs-o-down"></i>'.$term_names[POP_USERSTANCE_TERM_STANCE_AGAINST];

        return $values;
    }
}
