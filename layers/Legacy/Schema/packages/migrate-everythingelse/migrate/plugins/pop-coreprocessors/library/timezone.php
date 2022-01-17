<?php
use PoPCMSSchema\SchemaCommons\Facades\CMS\CMSServiceFacade;
use PoP\LooseContracts\Facades\NameResolverFacade;

// The timezone is originally set to UTC, we must set it to the chosen Timezone (eg: Asia/Kuala Lumpur)
// http://wordpress.org/support/topic/why-does-wordpress-set-timezone-to-utc
$cmsService = CMSServiceFacade::getInstance();
date_default_timezone_set($cmsService->getOption(NameResolverFacade::getInstance()->getName('popcms:option:timezone')));
