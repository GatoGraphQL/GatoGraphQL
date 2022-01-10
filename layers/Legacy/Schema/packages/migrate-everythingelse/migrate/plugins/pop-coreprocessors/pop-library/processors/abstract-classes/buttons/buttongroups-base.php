<?php
use PoP\ComponentModel\Misc\GeneralUtils;
use PoP\ComponentModel\State\ApplicationState;

abstract class PoP_Module_Processor_ButtonGroupsBase extends PoPEngine_QueryDataModuleProcessorBase
{
    public function getTemplateResource(array $module, array &$props): ?array
    {
        return [PoP_CoreProcessors_TemplateResourceLoaderProcessor::class, PoP_CoreProcessors_TemplateResourceLoaderProcessor::RESOURCE_BUTTONGROUP];
    }

    public function getHeaderType(array $module, array &$props)
    {
        return 'btn-group';
    }

    public function getHeadersData(array $module, array &$props)
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

    public function getItemClass(array $module, array &$props)
    {
        return 'btn btn-xs btn-default';
    }
    public function getItemdropdownClass(array $module, array &$props)
    {
        return 'btn-default btn-dropdown';
    }

    public function getDropdownTitle(array $module, array &$props)
    {
        return '';
    }

    public function getMutableonrequestConfiguration(array $module, array &$props): array
    {
        $ret = parent::getMutableonrequestConfiguration($module, $props);

        
        // Using runtimeconfiguration, because the URL can vary for Single, it must not be cached in the configuration
        if ($header_type = $this->getHeaderType($module, $props)) {
            if ($headers_data = $this->getHeadersData($module, $props)) {
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

    public function getImmutableConfiguration(array $module, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($module, $props);

        // Fill in all the properties
        if ($header_type = $this->getHeaderType($module, $props)) {
            $ret['type'] = $header_type;

            if ($item_class = $this->getItemClass($module, $props)) {
                $ret[GD_JS_CLASSES]['item'] = $item_class;
            }
            if ($itemdropdown_class = $this->getItemdropdownClass($module, $props)) {
                $ret[GD_JS_CLASSES]['item-dropdown'] = $itemdropdown_class;
            }

            if ($header_type == 'dropdown') {
                if ($dropdown_title = $this->getDropdownTitle($module, $props)) {
                    $ret[GD_JS_TITLES] = array(
                        'dropdown' => $dropdown_title
                    );
                }
            }
        }

        return $ret;
    }

    public function initModelProps(array $module, array &$props): void
    {
        if ($header_type = $this->getHeaderType($module, $props)) {
            // header type 'btn-group' needs that same class
            $this->appendProp($module, $props, 'class', $header_type);
        }

        parent::initModelProps($module, $props);
    }
}
