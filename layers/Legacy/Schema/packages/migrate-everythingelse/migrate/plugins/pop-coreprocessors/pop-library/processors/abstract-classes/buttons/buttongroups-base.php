<?php
use PoP\ComponentModel\Misc\GeneralUtils;
use PoP\ComponentModel\State\ApplicationState;

abstract class PoP_Module_Processor_ButtonGroupsBase extends PoPEngine_QueryDataComponentProcessorBase
{
    public function getTemplateResource(array $componentVariation, array &$props): ?array
    {
        return [PoP_CoreProcessors_TemplateResourceLoaderProcessor::class, PoP_CoreProcessors_TemplateResourceLoaderProcessor::RESOURCE_BUTTONGROUP];
    }

    public function getHeaderType(array $componentVariation, array &$props)
    {
        return 'btn-group';
    }

    public function getHeadersData(array $componentVariation, array &$props)
    {

        // The following items must be provided in the array
        return array(
            'titles' => array(),
            'icons' => array(),
            'formats' => array(),
            'screen' => null,
            'url' => null,
        );
    }

    public function getItemClass(array $componentVariation, array &$props)
    {
        return 'btn btn-xs btn-default';
    }
    public function getItemdropdownClass(array $componentVariation, array &$props)
    {
        return 'btn-default btn-dropdown';
    }

    public function getDropdownTitle(array $componentVariation, array &$props)
    {
        return '';
    }

    public function getMutableonrequestConfiguration(array $componentVariation, array &$props): array
    {
        $ret = parent::getMutableonrequestConfiguration($componentVariation, $props);

        
        // Using runtimeconfiguration, because the URL can vary for Single, it must not be cached in the configuration
        if ($header_type = $this->getHeaderType($componentVariation, $props)) {
            if ($headers_data = $this->getHeadersData($componentVariation, $props)) {
                $headers = array();
                $url = (string)$headers_data['url'];
                $default_active_format = PoP_Application_Utils::getDefaultformatByScreen($headers_data['screen']);
                foreach ($headers_data['formats'] as $format => $subformats) {
                    $header = array(
                        'url' => GeneralUtils::addQueryArgs([
                            \PoP\ConfigurationComponentModel\Constants\Params::FORMAT => $format,
                        ], $url),
                        'title' => $headers_data['titles'][$format],
                        'fontawesome' => $headers_data['icons'][$format],
                    );
                    if ((\PoP\Root\App::getState('format') == $format) || ((!\PoP\Root\App::getState('format') || \PoP\Root\App::getState('format') == \PoP\ConfigurationComponentModel\Constants\Values::DEFAULT) && ($format == $default_active_format))) {
                        $header['active'] = true;
                    }
                    if ($subformats) {
                        $subheaders = array();
                        foreach ($subformats as $subformat) {
                            $subheader = array(
                                'url' => GeneralUtils::addQueryArgs([
                                    \PoP\ConfigurationComponentModel\Constants\Params::FORMAT => $subformat,
                                ], $url),
                                'title' => $headers_data['titles'][$subformat],
                                'fontawesome' => $headers_data['icons'][$subformat],
                            );
                            if ((\PoP\Root\App::getState('format') == $subformat) || ((!\PoP\Root\App::getState('format') || \PoP\Root\App::getState('format') == \PoP\ConfigurationComponentModel\Constants\Values::DEFAULT) && ($subformat == $default_active_format))) {
                                $subheader['active'] = true;
                                $header['active'] = true;
                            }
                            $subheaders[] = $subheader;
                        }

                        $header['subheaders'] = $subheaders;
                    }
                    $headers[] = $header;
                }

                $ret['headers'] = $headers;
            }
        }

        return $ret;
    }

    public function getImmutableConfiguration(array $componentVariation, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($componentVariation, $props);

        // Fill in all the properties
        if ($header_type = $this->getHeaderType($componentVariation, $props)) {
            $ret['type'] = $header_type;

            if ($item_class = $this->getItemClass($componentVariation, $props)) {
                $ret[GD_JS_CLASSES]['item'] = $item_class;
            }
            if ($itemdropdown_class = $this->getItemdropdownClass($componentVariation, $props)) {
                $ret[GD_JS_CLASSES]['item-dropdown'] = $itemdropdown_class;
            }

            if ($header_type == 'dropdown') {
                if ($dropdown_title = $this->getDropdownTitle($componentVariation, $props)) {
                    $ret[GD_JS_TITLES] = array(
                        'dropdown' => $dropdown_title
                    );
                }
            }
        }

        return $ret;
    }

    public function initModelProps(array $componentVariation, array &$props): void
    {
        if ($header_type = $this->getHeaderType($componentVariation, $props)) {
            // header type 'btn-group' needs that same class
            $this->appendProp($componentVariation, $props, 'class', $header_type);
        }

        parent::initModelProps($componentVariation, $props);
    }
}
