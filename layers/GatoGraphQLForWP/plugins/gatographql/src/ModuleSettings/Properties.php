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

    // Force a control, when the one picked from the type does not fit
    public final const CONTROL = 'control';
    // On a TYPE_PROPERTY_ARRAY: force the control of each entry (CONTROL_SELECT or CONTROL_SEGMENTED), eg: a dropdown for a list of options that grows
    public final const ENTRY_CONTROL = 'entryControl';
    // Longer explanation, behind the "i" button beside the title (DESCRIPTION stays the one line under the title)
    public final const HELP = 'help';
    // Link to the docs page about the setting
    public final const DOCS_URL = 'docsURL';
    // A text field that takes the whole width of the row (eg: a message, a URL), instead of sitting beside the title
    public final const FULL_WIDTH = 'fullWidth';
    // A textarea that holds JSON: printed formatted, and flagged while it is not valid JSON
    public final const JSON = 'json';
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
    // On a TYPE_PROPERTY_ARRAY: [key => ['label' => ..., 'tone' => KEY_STATUS_TONE_*, 'detail' => ..., 'detailTone' => KEY_STATUS_TONE_*]], a status shown beside each entry (eg: of a license), and a message below it, in its own tone (by default, the status's)
    public final const KEY_STATUSES = 'keyStatuses';
    public final const KEY_STATUS_TONE_SUCCESS = 'success';
    public final const KEY_STATUS_TONE_WARNING = 'warning';
    public final const KEY_STATUS_TONE_DANGER = 'danger';
    public final const KEY_STATUS_TONE_NEUTRAL = 'neutral';
    // On a TYPE_PROPERTY_ARRAY: [key => [value => label]], buttons beside each entry, which submit the form with that value under KEY_ACTIONS_FIELD (eg: activate that license only); with more than one, they go in a "Manage" menu
    public final const KEY_ACTIONS = 'keyActions';
    // The name of the form field that the buttons of KEY_ACTIONS submit
    public final const KEY_ACTIONS_FIELD = 'keyActionsField';
    // On a TYPE_PROPERTY_ARRAY with KEY_ACTIONS: [key => [KEY_EDIT_CHANGED|KEY_EDIT_CLEARED => [value => label]]], the button that replaces the entry's KEY_ACTIONS once its value is changed, or cleared (eg: "Activate" the new license key, "Deactivate" the license whose key was removed)
    public final const KEY_EDIT_ACTIONS = 'keyEditActions';
    public final const KEY_EDIT_CHANGED = 'changed';
    public final const KEY_EDIT_CLEARED = 'cleared';

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
    // On a TYPE_NULL item: what the whole section does (its HELP), behind the "i" beside the title of the section
    public final const CONTROL_SECTION_HELP = 'sectionHelp';
}
