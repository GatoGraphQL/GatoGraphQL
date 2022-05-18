<?php

declare(strict_types=1);

namespace PoP\Application\ComponentProcessors;

use PoP\Application\Constants\Actions;
use PoP\Application\Environment;
use PoP\Root\Feedback\FeedbackItemResolution;
use PoP\ConfigurationComponentModel\ComponentProcessors\AbstractComponentProcessor as UpstreamAbstractComponentProcessor;
use PoP\Root\App;
use PoP\SiteBuilderAPI\ComponentProcessors\AddAPIQueryToSourcesComponentProcessorTrait;
use PoPCMSSchema\SchemaCommons\CMS\CMSServiceInterface;

abstract class AbstractComponentProcessor extends UpstreamAbstractComponentProcessor implements ComponentProcessorInterface
{
    use AddAPIQueryToSourcesComponentProcessorTrait;

    private ?CMSServiceInterface $cmsService = null;

    final public function setCMSService(CMSServiceInterface $cmsService): void
    {
        $this->cmsService = $cmsService;
    }
    final protected function getCMSService(): CMSServiceInterface
    {
        return $this->cmsService ??= $this->instanceManager->getInstance(CMSServiceInterface::class);
    }

    public function getDatasetmeta(array $componentVariation, array &$props, array $data_properties, ?FeedbackItemResolution $dataaccess_checkpoint_validation, ?FeedbackItemResolution $actionexecution_checkpoint_validation, ?array $executed, array $dbObjectIDOrIDs): array
    {
        $ret = parent::getDatasetmeta($componentVariation, $props, $data_properties, $dataaccess_checkpoint_validation, $actionexecution_checkpoint_validation, $executed, $dbObjectIDOrIDs);

        if ($query_multidomain_urls = $this->getDataloadMultidomainQuerySources($componentVariation, $props)) {
            $ret['multidomaindataloadsources'] = $query_multidomain_urls;
            unset($ret['dataloadsource']);
        }
        // if ($data_properties[DataloadingConstants::EXTERNALLOAD] ?? null) {
        //     $ret['externalload'] = true;
        // }
        if ($data_properties[DataloadingConstants::LAZYLOAD] ?? null) {
            $ret['lazyload'] = true;
        }

        return $ret;
    }

    public function getModelPropsForDescendantDatasetmodules(array $componentVariation, array &$props): array
    {
        $ret = parent::getModelPropsForDescendantDatasetmodules($componentVariation, $props);

        // If this module loads data, then add several properties
        if ($this->moduleLoadsData($componentVariation)) {
            if ($this->queriesExternalDomain($componentVariation, $props)) {
                $ret['external-domain'] = true;
            }

            // If it is multidomain, add a flag for inner layouts to know and react
            if ($this->isMultidomain($componentVariation, $props)) {
                $ret['multidomain'] = true;
            }
        }

        return $ret;
    }

    protected function addHeaddatasetmoduleDataProperties(&$ret, array $componentVariation, array &$props): void
    {
        parent::addHeaddatasetmoduleDataProperties($ret, $componentVariation, $props);


        // Is the component lazy-load?
        $ret[DataloadingConstants::LAZYLOAD] = $this->isLazyload($componentVariation, $props);

        // Do not load data when doing lazy load, unless passing URL param ?action=loadlazy, which is needed to initialize the lazy components.
        // Do not load data for Search page (initially, before the query was submitted)
        // Do not load data when querying data from another domain, since evidently we don't have that data in this application, then the load must be triggered from the client
        $ret[DataloadingConstants::SKIPDATALOAD] =
            (
                $ret[DataloadingConstants::LAZYLOAD] &&
                !in_array(Actions::LOADLAZY, App::getState('actions'))
            ) ||
            $ret[DataloadingConstants::EXTERNALLOAD] ||
            $this->getProp($componentVariation, $props, 'skip-data-load');

        // Use Mock DB Object Data for the Skeleton Screen
        $ret[DataloadingConstants::USEMOCKDBOBJECTDATA] = $this->getProp($componentVariation, $props, 'use-mock-dbobject-data') ?? false;

        // Loading data from a different application?
        $ret[DataloadingConstants::EXTERNALLOAD] = $this->queriesExternalDomain($componentVariation, $props);
    }

    public function getDataloadMultidomainQuerySources(array $componentVariation, array &$props): array
    {
        $sources = $this->getDataloadMultidomainSources($componentVariation, $props);
        // If this website and the external one have the same software installed, then the external application can already retrieve the needed data
        // Otherwise, this website needs to explicitly request what data is needed to the external one
        if (Environment::externalSitesRunSameSoftware()) {
            return $sources;
        }
        return $this->addAPIQueryToSources($sources, $componentVariation, $props);
    }

    public function getDataloadMultidomainSources(array $componentVariation, array &$props): array
    {
        if ($sources = $this->getProp($componentVariation, $props, 'dataload-multidomain-sources')) {
            return is_array($sources) ? $sources : [$sources];
        }

        return [];
    }

    public function queriesExternalDomain(array $componentVariation, array &$props): bool
    {
        if ($sources = $this->getDataloadMultidomainSources($componentVariation, $props)) {
            $domain = $this->getCMSService()->getSiteURL();
            foreach ($sources as $source) {
                if (substr($source, 0, strlen($domain)) != $domain) {
                    return true;
                }
            }
        }

        return false;
    }

    public function isMultidomain(array $componentVariation, array &$props): bool
    {
        if (!$this->queriesExternalDomain($componentVariation, $props)) {
            return false;
        }

        $multidomain_urls = $this->getDataloadMultidomainSources($componentVariation, $props);
        return is_array($multidomain_urls) && count($multidomain_urls) >= 2;
    }

    public function isLazyload(array $componentVariation, array &$props): bool
    {
        return $this->getProp($componentVariation, $props, 'lazy-load') ?? false;
    }

    public function initModelProps(array $componentVariation, array &$props): void
    {
        // If it is a dataloading module, then set all the props related to data
        if ($this->moduleLoadsData($componentVariation)) {
            // If it is multidomain, add a flag for inner layouts to know and react
            if ($this->isMultidomain($componentVariation, $props)) {
                // $this->add_general_prop($props, 'is-multidomain', true);
                $this->appendProp($componentVariation, $props, 'class', 'pop-multidomain');
            }
        }

        parent::initModelProps($componentVariation, $props);
    }
}
