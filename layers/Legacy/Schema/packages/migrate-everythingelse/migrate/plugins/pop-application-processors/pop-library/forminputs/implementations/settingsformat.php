<?php
use PoP\ComponentModel\State\ApplicationState;
use PoP\Engine\FormInputs\SelectFormInput;
use PoP\Translation\Facades\TranslationAPIFacade;

class GD_FormInput_SettingsFormat extends SelectFormInput
{
    public function getAllValues($label = null): array
    {
        $values = parent::getAllValues($label);

        $values = array_merge(
            $values,
            array(
                POP_FORMAT_SIMPLEVIEW => TranslationAPIFacade::getInstance()->__('Feed', 'poptheme-wassup'),
                POP_FORMAT_FULLVIEW => TranslationAPIFacade::getInstance()->__('Extended feed', 'poptheme-wassup'),
            )
        );
        if (defined('POP_LOCATIONS_INITIALIZED')) {
            $values = array_merge(
                $values,
                array(
                    POP_FORMAT_MAP => TranslationAPIFacade::getInstance()->__('Map', 'poptheme-wassup'),
                )
            );
        }
        $values = array_merge(
            $values,
            array(
                POP_FORMAT_DETAILS => TranslationAPIFacade::getInstance()->__('Details', 'poptheme-wassup'),
                POP_FORMAT_THUMBNAIL => TranslationAPIFacade::getInstance()->__('Thumbnail', 'poptheme-wassup'),
                POP_FORMAT_LIST => TranslationAPIFacade::getInstance()->__('List', 'poptheme-wassup'),
            )
        );

        return $values;
    }

    public function getDefaultValue(): mixed
    {
        if ($selected = \PoP\Root\App::getState('settingsformat')) {
            $allvalues = array(
                POP_FORMAT_SIMPLEVIEW,
                POP_FORMAT_FULLVIEW,
                POP_FORMAT_DETAILS,
                POP_FORMAT_THUMBNAIL,
                POP_FORMAT_LIST,
            );
            if (defined('POP_LOCATIONS_INITIALIZED')) {
                $allvalues[] = POP_FORMAT_MAP;
            }

            if (in_array($selected, $allvalues)) {
                return $selected;
            }
        }

        return POP_FORMAT_SIMPLEVIEW;
    }
}
