<?php
use PoP\Engine\FormInputs\SelectFormInput;

class GD_QT_FormInput_Languages extends SelectFormInput
{
    public function getAllValues($label = null): array
    {
        $values = parent::getAllValues($label);

        // Code copied from wp-content/plugins/qtranslate/qtranslate_widget.php
        $pluginapi = PoP_Multilingual_FunctionsAPIFactory::getInstance();
        $sorted_languages = $pluginapi->getEnabledLanguages();
        array_multisort($sorted_languages);
        foreach ($sorted_languages as $language) {
            $values[$language] = $pluginapi->getLanguageName($language);
        }

        return $values;
    }

    public function getDefaultValue(): mixed
    {
        $pluginapi = PoP_Multilingual_FunctionsAPIFactory::getInstance();
        return $pluginapi->getCurrentLanguage();
    }
}
