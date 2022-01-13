<?php
use PoP\ComponentModel\State\ApplicationState;
use PoP\Root\Facades\Hooks\HooksAPIFacade;

abstract class PoP_ResourceLoader_ResourcesFileBase extends \PoP\FileStore\File\AbstractAccessibleRenderableFile {

	protected function acrossThememodes() {

		return false;
	}

	// function skipIfFileExists() {

	// 	// Skip if the bundle/bundleGroup has already been generated! That is because these are shared across thememodes,
	// 	// then no need to re-create the files when running /generate-thememode/ for the 2nd, 3rd, etc rounds
	// 	if ($this->acrossThememodes()) {

	// 		return true;
	// 	}

	// 	return parent::skipIfFileExists();
	// }

	protected function getFolderSubpath() {

		// We must create different mapping files depending on if we're adding the CDN resources inside the bundles or not
		$subfolder = PoP_ResourceLoader_ServerUtils::bundleExternalFiles() ? 'global' : 'local';
		if (defined('POP_THEME_INITIALIZED')) {

			if ($this->acrossThememodes()) {

				return '/shared';
			}

						return '/'.\PoP\Root\App::getState('theme').'/'.\PoP\Root\App::getState('thememode').'/'.$subfolder;
		}

		return $subfolder;
	}

	public function getDir(): string {

		return $this->getBaseDir().$this->getFolderSubpath();
	}
	public function getUrl(): string {

		return $this->getBaseUrl().$this->getFolderSubpath();
	}

	protected function getBaseDir(): string {

		// Allow pop-cluster-resourceloader to change the dir to pop-cluster-generatecache/
		return HooksAPIFacade::getInstance()->applyFilters(
			'PoP_ResourceLoader_ResourcesFileBase:base-dir',
			POP_RESOURCES_DIR,
			defined('POP_THEME_INITIALIZED') && $this->acrossThememodes()
		);
	}
	protected function getBaseUrl(): string {

		// Allow pop-cluster-resourceloader to change the dir to pop-cluster-generatecache/
		return HooksAPIFacade::getInstance()->applyFilters(
			'PoP_ResourceLoader_ResourcesFileBase:base-url',
			POP_RESOURCES_URL,
			defined('POP_THEME_INITIALIZED') && $this->acrossThememodes()
		);
	}
}
