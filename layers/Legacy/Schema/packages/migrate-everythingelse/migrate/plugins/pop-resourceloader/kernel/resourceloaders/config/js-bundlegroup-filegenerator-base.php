<?php
abstract class PoP_ResourceLoader_JSBundleGroupFileBase extends PoP_ResourceLoader_BundleFileFileBase {

	public function getDir(): string {

		return parent::getDir().'/bundlegroups';
	}
	public function getUrl(): string {

		return parent::getUrl().'/bundlegroups';
	}
}
