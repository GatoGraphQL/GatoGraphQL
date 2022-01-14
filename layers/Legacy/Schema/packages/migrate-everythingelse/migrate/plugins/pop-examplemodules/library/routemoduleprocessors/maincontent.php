<?php
namespace PoP\ExampleModules;
use PoP\Root\Routing\RouteNatures;
use PoPSchema\CustomPosts\Routing\RouteNatures as CustomPostRouteNatures;
use PoPSchema\Pages\Routing\RouteNatures as PageRouteNatures;
use PoPSchema\Tags\Routing\RouteNatures as TagRouteNatures;
use PoPSchema\Users\Routing\RouteNatures as UserRouteNatures;

class MainContentRouteModuleProcessor extends \PoP\Application\AbstractMainContentRouteModuleProcessor
{
    /**
     * @return array<string, array<array>>
     */
    public function getModulesVarsPropertiesByNature(): array
    {
        return array(
            RouteNatures::HOME => [
                [
                    'module' => [ModuleProcessor_Groups::class, ModuleProcessor_Groups::MODULE_EXAMPLE_HOME],
                ],
            ],
            RouteNatures::NOTFOUND => [
                [
                    'module' => [ModuleProcessor_Layouts::class, ModuleProcessor_Layouts::MODULE_EXAMPLE_404],
                ],
            ],
            TagRouteNatures::TAG => [
                [
                    'module' => [ModuleProcessor_Groups::class, ModuleProcessor_Groups::MODULE_EXAMPLE_TAG],
                ],
            ],
            UserRouteNatures::USER => [
                [
                    'module' => [ModuleProcessor_Groups::class, ModuleProcessor_Groups::MODULE_EXAMPLE_AUTHOR],
                ],
            ],
            CustomPostRouteNatures::CUSTOMPOST => [
                [
                    'module' => [ModuleProcessor_Dataloads::class, ModuleProcessor_Dataloads::MODULE_EXAMPLE_SINGLE],
                ],
            ],
            PageRouteNatures::PAGE => [
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
