<?php
class PoP_WebPlatform_CSSConverter_Hooks
{
    public function __construct()
    {
        \PoP\Root\App::addAction(
            'PoP_WebPlatformQueryDataComponentProcessorBase:module-immutable-configuration',
            $this->getImmutableConfiguration(...),
            10,
            4
        );
        \PoP\Root\App::addAction(
            'PoP_WebPlatformQueryDataComponentProcessorBase:module-mutableonrequest-configuration',
            $this->getMutableonrequestConfiguration(...),
            10,
            4
        );
    }

    public function getImmutableConfiguration($configuration, array $component, array $props, $processor)
    {

        // After saving the configuration, we can manipulate it, to convert values if needed
        // Replace classes with styles, if set in the general props
        if ($processor->getProp($component, $props, 'convert-classes-to-styles')) {
            $moduleOutputName = \PoP\ComponentModel\Facades\Modules\ComponentHelpersFacade::getInstance()->getModuleOutputName($component);

            // Classes to convert to styles are set in $configuration[GD_JS_CLASS] and $configuration[GD_JS_CLASSES]
            if ($allclasses = array_filter(explode(' ', $configuration[GD_JS_CLASS]))) {
                $styles = PoP_CSSConverter_ConversionUtils::getStylesFromClasses($allclasses);

                // Set the styles as a param
                $configuration[GD_JS_STYLE] = implode(' ', $styles);

                // Remove the class
                $configuration[GD_JS_CLASS] = '';
            }
            if ($entries = $configuration[GD_JS_CLASSES]) {
                foreach ($entries as $key => $class) {
                    if ($allclasses = array_filter(explode(' ', $class))) {
                        $styles = PoP_CSSConverter_ConversionUtils::getStylesFromClasses($allclasses);

                        // Set the styles as a param
                        $configuration[GD_JS_STYLES][$key] = implode(' ', $styles);

                        // Remove the class
                        $configuration[GD_JS_CLASSES][$key] = '';
                    }
                }
            }
        }

        return $configuration;
    }

    public function getMutableonrequestConfiguration($configuration, array $component, array $props, $processor)
    {

        // After saving the configuration, we can manipulate it, to convert values if needed
        // Replace classes with styles, if set in the general props
        if ($processor->getProp($component, $props, 'convert-classes-to-styles')) {
            // Classes to convert to styles are set in $configuration[GD_JS_CLASS] and $configuration[GD_JS_CLASSES]
            if ($allclasses = array_filter(explode(' ', $configuration['runtime-class']))) {
                $styles = PoP_CSSConverter_ConversionUtils::getStylesFromClasses($allclasses);

                // Set the styles as a param
                $configuration['runtime-style'] = implode(' ', $styles);

                // Remove the class
                $configuration['runtime-class'] = '';
            }
        }

        return $configuration;
    }
}

/**
 * Initialization
 */
new PoP_WebPlatform_CSSConverter_Hooks();
