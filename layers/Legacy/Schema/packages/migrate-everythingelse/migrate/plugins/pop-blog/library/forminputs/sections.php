<?php
use PoP\Engine\FormInputs\MultipleSelectFormInput;

class GD_FormInput_ContentSections extends MultipleSelectFormInput
{
    public function getAllValues($label = null): array
    {
        $values = parent::getAllValues($label);

        // The sections are received in this format:
        // array('taxonomy' => 'terms')
        // To print it in the forminput, it must be transformed to a list with this format:
        // array('taxonomy|term')
        $formatted = array();
        $section_taxonomyterms = \PoP\Root\App::applyFilters('wassup_section_taxonomyterms', array());
        foreach ($section_taxonomyterms as $taxonomy => $terms) {
            foreach ($terms as $term) {
                $item = $taxonomy.'|'.$term;
                $formatted[$item] = \PoP\Root\App::applyFilters('GD_FormInput_ContentSections:taxonomyterms:name', $item, $taxonomy, $term);
            }
        }

        $values = array_merge(
            $values,
            $formatted
        );

        return $values;
    }
}
