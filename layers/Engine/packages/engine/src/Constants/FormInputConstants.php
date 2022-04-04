<?php

declare(strict_types=1);

namespace PoP\Engine\Constants;

class FormInputConstants
{
    // Instead of using true/false values, use representative string, so it can be operated through #compare as a string (otherwise, true != 'true')
    // Watch out! FALSE cannot be "", because then it's not sent as a value (it's filtered out when submitting a form when doing blockQueryState.filter = filter.find('.' + M.FORM_INPUT).filter(function () {return $.trim(this.value);}).serialize();)
    // Watch out! They also cannot be true => "1" and false "0" because then it doens't send the keys in the array, then we have [Yes, No] with the values actually inverted!
    public final const BOOLSTRING_TRUE = 'true';
    public final const BOOLSTRING_FALSE = 'false';
}
