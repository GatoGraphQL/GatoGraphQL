<?php
namespace PoP\ExampleModules;
use PoP\Root\Routing\RequestNature;
use PoPCMSSchema\CustomPosts\Routing\RequestNature as CustomPostRequestNature;
use PoPCMSSchema\Pages\Routing\RequestNature as PageRequestNature;
use PoPCMSSchema\Tags\Routing\RequestNature as TagRequestNature;
use PoPCMSSchema\Users\Routing\RequestNature as UserRequestNature;

class MainContentComponentRoutingProcessor extends \PoP\Application\AbstractMainContentComponentRoutingProcessor
{
    /**
     * @return array<string, array<array>>
     */
    public function getStatePropertiesToSelectComponentByNature(): array
    {
        return array(
            RequestNature::HOME => [
                [
                    'component' => [ComponentProcessor_Groups::class, ComponentProcessor_Groups::MODULE_EXAMPLE_HOME],
                ],
            ],
            RequestNature::NOTFOUND => [
                [
                    'component' => [ComponentProcessor_Layouts::class, ComponentProcessor_Layouts::MODULE_EXAMPLE_404],
                ],
            ],
            TagRequestNature::TAG => [
                [
                    'component' => [ComponentProcessor_Groups::class, ComponentProcessor_Groups::MODULE_EXAMPLE_TAG],
                ],
            ],
            UserRequestNature::USER => [
                [
                    'component' => [ComponentProcessor_Groups::class, ComponentProcessor_Groups::MODULE_EXAMPLE_AUTHOR],
                ],
            ],
            CustomPostRequestNature::CUSTOMPOST => [
                [
                    'component' => [ComponentProcessor_Dataloads::class, ComponentProcessor_Dataloads::MODULE_EXAMPLE_SINGLE],
                ],
            ],
            PageRequestNature::PAGE => [
                [
                    'component' => [ComponentProcessor_Dataloads::class, ComponentProcessor_Dataloads::MODULE_EXAMPLE_PAGE],
                ],
            ],
        );
    }
}

/**
 * Initialization
 */
add_action('init', function() {
	\PoP\ComponentRouting\Facades\ComponentRoutingProcessorManagerFacade::getInstance()->addComponentRoutingProcessor(
		new MainContentComponentRoutingProcessor()
	);
}, 200);
