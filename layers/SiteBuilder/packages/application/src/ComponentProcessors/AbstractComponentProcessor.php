<?php

declare(strict_types=1);

namespace PoP\Application\ComponentProcessors;

use PoP\ComponentModel\Component\Component;
use PoP\Application\Constants\Actions;
use PoP\Application\Environment;
use PoP\ComponentModel\Feedback\FeedbackItemResolution;
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
        if ($this->cmsService === null) {
            /** @var CMSServiceInterface */
            $cmsService = $this->instanceManager->getInstance(CMSServiceInterface::class);
            $this->cmsService = $cmsService;
        }
        return $this->cmsService;
    }

    /**
     * @param array<string,mixed> $props
     * @param array<string,mixed> $data_properties
     * @param string|int|array<string|int> $objectIDOrIDs
     * @param array<string,mixed>|null $executed
     * @return array<string,mixed>
     */
    public function getDatasetmeta(
        Component $component,
        array &$props,
        array $data_properties,
        ?FeedbackItemResolution $dataaccess_checkpoint_validation,
        ?FeedbackItemResolution $actionexecution_checkpoint_validation,
        ?array $executed,
        string|int|array $objectIDOrIDs,
    ): array {
        $ret = parent::getDatasetmeta($component, $props, $data_properties, $dataaccess_checkpoint_validation, $actionexecution_checkpoint_validation, $executed, $objectIDOrIDs);

        if ($query_multidomain_urls = $this->getDataloadMultidomainQuerySources($component, $props)) {
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

    /**
     * @return array<string,mixed>
     * @param array<string,mixed> $props
     */
    public function getModelPropsForDescendantDatasetComponents(Component $component, array &$props): array
    {
        $ret = parent::getModelPropsForDescendantDatasetComponents($component, $props);

        // If this component loads data, then add several properties
        if ($this->doesComponentLoadData($component)) {
            if ($this->queriesExternalDomain($component, $props)) {
                $ret['external-domain'] = true;
            }

            // If it is multidomain, add a flag for inner layouts to know and react
            if ($this->isMultidomain($component, $props)) {
                $ret['multidomain'] = true;
            }
        }

        return $ret;
    }

    /**
     * @param array<string,mixed> $ret
     * @param array<string,mixed> $props
     */
    protected function addHeaddatasetcomponentDataProperties(array &$ret, Component $component, array &$props): void
    {
        parent::addHeaddatasetcomponentDataProperties($ret, $component, $props);


        // Is the component lazy-load?
        $ret[DataloadingConstants::LAZYLOAD] = $this->isLazyload($component, $props);

        // Do not load data when doing lazy load, unless passing URL param ?action=loadlazy, which is needed to initialize the lazy components.
        // Do not load data for Search page (initially, before the query was submitted)
        // Do not load data when querying data from another domain, since evidently we don't have that data in this application, then the load must be triggered from the client
        $ret[DataloadingConstants::SKIPDATALOAD] =
            (
                $ret[DataloadingConstants::LAZYLOAD] &&
                !in_array(Actions::LOADLAZY, App::getState('actions'))
            ) ||
            $ret[DataloadingConstants::EXTERNALLOAD] ||
            $this->getProp($component, $props, 'skip-data-load');

        // Use Mock DB Object Data for the Skeleton Screen
        $ret[DataloadingConstants::USEMOCKDBOBJECTDATA] = $this->getProp($component, $props, 'use-mock-dbobject-data') ?? false;

        // Loading data from a different application?
        $ret[DataloadingConstants::EXTERNALLOAD] = $this->queriesExternalDomain($component, $props);
    }

    /**
     * @return string[]
     * @param array<string,mixed> $props
     */
    public function getDataloadMultidomainQuerySources(Component $component, array &$props): array
    {
        $sources = $this->getDataloadMultidomainSources($component, $props);
        // If this website and the external one have the same software installed, then the external application can already retrieve the needed data
        // Otherwise, this website needs to explicitly request what data is needed to the external one
        if (Environment::externalSitesRunSameSoftware()) {
            return $sources;
        }
        return $this->addAPIQueryToSources($sources, $component, $props);
    }

    /**
     * @return string[]
     * @param array<string,mixed> $props
     */
    public function getDataloadMultidomainSources(Component $component, array &$props): array
    {
        if ($sources = $this->getProp($component, $props, 'dataload-multidomain-sources')) {
            return is_array($sources) ? $sources : [$sources];
        }

        return [];
    }

    /**
     * @param array<string,mixed> $props
     */
    public function queriesExternalDomain(Component $component, array &$props): bool
    {
        if ($sources = $this->getDataloadMultidomainSources($component, $props)) {
            $domain = $this->getCMSService()->getSiteURL();
            foreach ($sources as $source) {
                if (substr($source, 0, strlen($domain)) !== $domain) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * @param array<string,mixed> $props
     */
    public function isMultidomain(Component $component, array &$props): bool
    {
        if (!$this->queriesExternalDomain($component, $props)) {
            return false;
        }

        $multidomain_urls = $this->getDataloadMultidomainSources($component, $props);
        return is_array($multidomain_urls) && count($multidomain_urls) >= 2;
    }

    /**
     * @param array<string,mixed> $props
     */
    public function isLazyload(Component $component, array &$props): bool
    {
        return $this->getProp($component, $props, 'lazy-load') ?? false;
    }

    /**
     * @param array<string,mixed> $props
     */
    public function initModelProps(Component $component, array &$props): void
    {
        // If it is a dataloading component, then set all the props related to data
        if ($this->doesComponentLoadData($component)) {
            // If it is multidomain, add a flag for inner layouts to know and react
            if ($this->isMultidomain($component, $props)) {
                // $this->add_general_prop($props, 'is-multidomain', true);
                $this->appendProp($component, $props, 'class', 'pop-multidomain');
            }
        }

        parent::initModelProps($component, $props);
    }
}
