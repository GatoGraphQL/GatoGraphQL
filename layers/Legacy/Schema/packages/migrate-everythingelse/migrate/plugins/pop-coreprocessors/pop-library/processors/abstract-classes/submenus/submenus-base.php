<?php
use PoP\ComponentModel\State\ApplicationState;
use PoP\Engine\Route\RouteUtils;

abstract class PoP_Module_Processor_SubMenusBase extends PoPEngine_QueryDataComponentProcessorBase
{
    public function getTemplateResource(array $component, array &$props): ?array
    {
        return [PoP_CoreProcessors_TemplateResourceLoaderProcessor::class, PoP_CoreProcessors_TemplateResourceLoaderProcessor::RESOURCE_SUBMENU];
    }
    
    public function fixedId(array $component, array &$props): bool
    {
        return true;
    }

    public function getRoutes(array $component, array &$props)
    {
        return array();
    }

    public function getClass(array $component)
    {
        return '';
    }
    public function getXsClass(array $component)
    {
        return '';
    }
    public function getDropdownClass(array $component)
    {
        return '';
    }
    
    protected function getUrl(array $component, $route, array &$props)
    {
        return RouteUtils::getRouteURL($route);
    }
    
    protected function getTitle(array $component, $route, array &$props)
    {
        return RouteUtils::getRouteTitle($route);
    }

    public function getImmutableConfiguration(array $component, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($component, $props);
        
        if ($class = $this->getClass($component)) {
            $ret[GD_JS_CLASSES]['item'] = $class;
        }
        if ($class = $this->getXsClass($component)) {
            $ret[GD_JS_CLASSES]['item-xs'] = $class;
        }
        if ($dropdown_class = $this->getDropdownClass($component)) {
            $ret[GD_JS_CLASSES]['item-dropdown'] = $dropdown_class;
        }
        
        return $ret;
    }

    public function getMutableonrequestConfiguration(array $component, array &$props): array
    {
        $ret = parent::getMutableonrequestConfiguration($component, $props);

        // Pages can vary with the URL: /u/mesym/ has tab "Members", but /u/leo/ does not, then place this logic under "mutableonrequest"
        $route = \PoP\Root\App::getState('route');
        $headers = array();
        foreach ($this->getRoutes($component, $props) as $header_route => $subheader_routes) {
            $headers[$header_route] = array(
                'title' => $this->getTitle($component, $header_route, $props),
                'url' => $this->getUrl($component, $header_route, $props),
            );
            if ($header_route == $route) {
                $headers[$header_route]['active'] = true;
            }
            
            if ($subheader_routes) {
                $subheaders = array();
                foreach ($subheader_routes as $subheader_route) {
                    $subheaders[$subheader_route] = array(
                        'title' => $this->getTitle($component, $subheader_route, $props),
                        'url' => $this->getUrl($component, $subheader_route, $props),
                    );
                }
                if (in_array($route, $subheader_routes)) {
                    $subheaders[$route]['active'] = true;
                }

                $headers[$header_route]['subheaders'] = $subheaders;
            }
        }
        $ret['headers'] = $headers;
        
        return $ret;
    }
}
