<?php
namespace PoP\ExampleModules;
use PoP\Root\Routing\RequestNature;
use PoPSchema\CustomPosts\Routing\RequestNature as CustomPostRequestNature;
use PoPSchema\Pages\Routing\RequestNature as PageRequestNature;
use PoPSchema\Tags\Routing\RequestNature as TagRequestNature;
use PoPSchema\Users\Routing\RequestNature as UserRequestNature;

class MainContentRouteModuleProcessor extends \PoP\Application\AbstractMainContentRouteModuleProcessor
{
    /**
     * @return array<string, array<array>>
     */
    public function getModulesVarsPropertiesByNature(): array
    {
        return array(
            RequestNature::HOME => [
                [
                    'module' => [ModuleProcessor_Groups::class, ModuleProcessor_Groups::MODULE_EXAMPLE_HOME],
                ],
            ],
            RequestNature::NOTFOUND => [
                [
                    'module' => [ModuleProcessor_Layouts::class, ModuleProcessor_Layouts::MODULE_EXAMPLE_404],
                ],
            ],
            TagRequestNature::TAG => [
                [
                    'module' => [ModuleProcessor_Groups::class, ModuleProcessor_Groups::MODULE_EXAMPLE_TAG],
                ],
            ],
            UserRequestNature::USER => [
                [
                    'module' => [ModuleProcessor_Groups::class, ModuleProcessor_Groups::MODULE_EXAMPLE_AUTHOR],
                ],
            ],
            CustomPostRequestNature::CUSTOMPOST => [
                [
                    'module' => [ModuleProcessor_Dataloads::class, ModuleProcessor_Dataloads::MODULE_EXAMPLE_SINGLE],
                ],
            ],
            PageRequestNature::PAGE => [
                [
                    'module' => [ModuleProcessor_Dataloads::class, ModuleProcessor_Dataloads::MODULE_EXAMPLE_PAGE],
                ],
            ],
        );
    }
}

/**
 * Initialization
 */
add_action('init', function() {
	\PoP\ModuleRouting\Facades\RouteModuleProcessorManagerFacade::getInstance()->addRouteModuleProcessor(
		new MainContentRouteModuleProcessor()
	);
}, 200);
