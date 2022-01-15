<?php

declare(strict_types=1);

namespace PoPSitesWassup\SystemMutations\MutationResolvers;

use PoP\Root\App;
use PoP\ComponentModel\Info\ApplicationInfoInterface;
use PoP\ComponentModel\MutationResolvers\AbstractMutationResolver;
use PoPSchema\SchemaCommons\CMS\CMSServiceInterface;

class ActivatePluginsMutationResolver extends AbstractMutationResolver
{
    private ?CMSServiceInterface $cmsService = null;
    private ?ApplicationInfoInterface $applicationInfo = null;

    final public function setCMSService(CMSServiceInterface $cmsService): void
    {
        $this->cmsService = $cmsService;
    }
    final protected function getCMSService(): CMSServiceInterface
    {
        return $this->cmsService ??= $this->instanceManager->getInstance(CMSServiceInterface::class);
    }
    final public function setApplicationInfo(ApplicationInfoInterface $applicationInfo): void
    {
        $this->applicationInfo = $applicationInfo;
    }
    final protected function getApplicationInfo(): ApplicationInfoInterface
    {
        return $this->applicationInfo ??= $this->instanceManager->getInstance(ApplicationInfoInterface::class);
    }

    // Taken from https://wordpress.stackexchange.com/questions/4041/how-to-activate-plugins-via-code
    private function runActivatePlugin($plugin)
    {
        $current = $this->getCMSService()->getOption('active_plugins');
        // @todo Rename package!
        // `plugin_basename` is a WordPress function,
        // so this package must be called "system-mutations-wp",
        // or have this code extracted to some WP-specific package
        $plugin = plugin_basename(trim($plugin));

        if (!in_array($plugin, $current)) {
            $current[] = $plugin;
            sort($current);
            App::doAction('activate_plugin', trim($plugin));
            update_option('active_plugins', $current);
            App::doAction('activate_' . trim($plugin));
            App::doAction('activated_plugin', trim($plugin));
            return true;
        }

        return false;
    }

    public function executeMutation(array $form_data): mixed
    {
        // Plugins needed by the website. Check the website version, if it's the one indicated,
        // then proceed to install the required plugin
        $plugin_version = App::applyFilters(
            'PoP:system-activateplugins:plugins',
            array()
        );

        // Iterate all plugins and check what version they require to be installed. If it matches the current version => activate
        $version = $this->getApplicationInfo()->getVersion();
        $activated = [];
        foreach ($plugin_version as $plugin => $activate_version) {
            if ($activate_version == $version) {
                if ($this->runActivatePlugin("${plugin}/${plugin}.php")) {
                    $activated[] = $plugin;
                }
            }
        }

        return $activated;
    }
}
