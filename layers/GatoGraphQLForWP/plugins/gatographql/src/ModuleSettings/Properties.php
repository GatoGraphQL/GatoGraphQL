<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\ModuleSettings;

class Properties
{
    public final const NAME = 'name';
    public final const INPUT = 'input';
    public final const TITLE = 'title';
    public final const DESCRIPTION = 'description';
    public final const POSSIBLE_VALUES = 'possibleValues';
    public final const CAN_BE_EMPTY = 'canBeEmpty';
    public final const SORT_VALUES = 'sortValues';
    public final const APPEND_VALUE_IF_NON_EXISTING = 'appendValueIfNonExisting';
    public final const DEFAULT_VALUE = 'defaultValue';
    // Used to define additional Settings pages that the item can be printed on.
    public final const FORM_TARGETS = 'formTargets';
    // Used for Property Array
    public final const KEY_LABELS = 'keyLabels';
    // Used for Select inputs
    public final const IS_MULTIPLE = 'isMultiple';
    // Used for Strings
    public final const USE_TEXTAREA = 'useTextarea';
    // Used for Integers
    public final const MIN_NUMBER = 'minNumber';
    public final const TYPE = 'type';
    public final const SUBTYPE = 'subtype';
    public final const TYPE_STRING = 'string';
    public final const TYPE_PASSWORD = 'password';
    public final const TYPE_BOOL = 'bool';
    public final const TYPE_INT = 'int';
    public final const TYPE_ARRAY = 'array';
    public final const TYPE_PROPERTY_ARRAY = 'propertyArray';
    public final const TYPE_NULL = 'null';
    public final const TYPE_HIDDEN = 'hidden';
    public final const CSS_STYLE = 'css-style';

    /**
     * Optional properties to render the setting. When absent, the
     * Settings page picks the control from the type and values above.
     */

    // On a TYPE_NULL heading: what its group applies to, shown beside it
    public final const SCOPE = 'scope';
    // Force a control, when the one picked from the type does not fit
    public final const CONTROL = 'control';
    // Longer explanation, behind the "?" button (DESCRIPTION stays the one line under the title)
    public final const HELP = 'help';
    // Link to the docs page about the setting
    public final const DOCS_URL = 'docsURL';
    // A text field that takes the whole width of the row (eg: a message, a URL), instead of sitting beside the title
    public final const FULL_WIDTH = 'fullWidth';
    // Unit after a number field (eg: "seconds")
    public final const UNIT = 'unit';
    // Example value shown inside an empty field
    public final const PLACEHOLDER = 'placeholder';
    // Used for Integers: above it, the default value is stored
    public final const MAX_NUMBER = 'maxNumber';
    // [option => value]: the setting applies only when that other option (of the same module) has that value (or one of those values, if an array)
    public final const DEPENDS_ON = 'dependsOn';
    // Extra words to find the setting by, in "Search all settings"
    public final const SEARCH_KEYWORDS = 'searchKeywords';
    // On a TYPE_PROPERTY_ARRAY: [key => ['label' => ..., 'tone' => KEY_STATUS_TONE_*, 'detail' => ...]], a status shown beside each entry (eg: of a license)
    public final const KEY_STATUSES = 'keyStatuses';
    public final const KEY_STATUS_TONE_SUCCESS = 'success';
    public final const KEY_STATUS_TONE_WARNING = 'warning';
    public final const KEY_STATUS_TONE_DANGER = 'danger';
    public final const KEY_STATUS_TONE_NEUTRAL = 'neutral';

    public final const CONTROL_GROUP = 'group';
    public final const CONTROL_DIVIDER = 'divider';
    public final const CONTROL_CONTENT = 'content';
    public final const CONTROL_SWITCH = 'switch';
    public final const CONTROL_SEGMENTED = 'segmented';
    public final const CONTROL_SELECT = 'select';
    public final const CONTROL_CHECKLIST = 'checklist';
    public final const CONTROL_TAGS = 'tags';
    public final const CONTROL_TEXTAREA = 'textarea';
    public final const CONTROL_INPUT = 'input';
    public final const CONTROL_GRID = 'grid';
    public final const CONTROL_CHIPS = 'chips';
}
