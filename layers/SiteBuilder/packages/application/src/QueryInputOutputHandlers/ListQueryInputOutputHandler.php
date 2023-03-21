<?php

declare(strict_types=1);

namespace PoP\Application\QueryInputOutputHandlers;

use PoP\Application\ComponentProcessors\DataloadingConstants;
use PoP\ComponentModel\Module as ComponentModelModule;
use PoP\ComponentModel\ModuleInfo as ComponentModelModuleInfo;
use PoP\ComponentModel\Constants\DataSources;
use PoP\ComponentModel\Constants\PaginationParams;
use PoP\Root\Feedback\FeedbackItemResolution;
use PoP\ComponentModel\QueryInputOutputHandlers\ListQueryInputOutputHandler as UpstreamListQueryInputOutputHandler;
use PoP\LooseContracts\NameResolverInterface;
use PoP\Root\App;
use PoPCMSSchema\SchemaCommons\CMS\CMSServiceInterface;

class ListQueryInputOutputHandler extends UpstreamListQueryInputOutputHandler
{
    private ?CMSServiceInterface $cmsService = null;
    private ?NameResolverInterface $nameResolver = null;

    final public function setCMSService(CMSServiceInterface $cmsService): void
    {
        $this->cmsService = $cmsService;
    }
    final protected function getCMSService(): CMSServiceInterface
    {
        /** @var CMSServiceInterface */
        return $this->cmsService ??= $this->instanceManager->getInstance(CMSServiceInterface::class);
    }
    final public function setNameResolver(NameResolverInterface $nameResolver): void
    {
        $this->nameResolver = $nameResolver;
    }
    final protected function getNameResolver(): NameResolverInterface
    {
        /** @var NameResolverInterface */
        return $this->nameResolver ??= $this->instanceManager->getInstance(NameResolverInterface::class);
    }

    protected function getLimit(): int
    {
        return $this->getCMSService()->getOption($this->getNameResolver()->getName('popcms:option:limit'));
    }

    /**
     * @return array<string,mixed>
     * @param array<string,mixed> $data_properties
     * @param string|int|array<string|int> $objectIDOrIDs
     * @param array<string,mixed>|null $executed
     */
    public function getQueryState(array $data_properties, ?FeedbackItemResolution $dataaccess_checkpoint_validation, ?FeedbackItemResolution $actionexecution_checkpoint_validation, ?array $executed, string|int|array $objectIDOrIDs): array
    {
        $ret = parent::getQueryState($data_properties, $dataaccess_checkpoint_validation, $actionexecution_checkpoint_validation, $executed, $objectIDOrIDs);

        // Needed to loadLatest, to know from what time to get results
        if (isset($data_properties[DataloadingConstants::DATASOURCE]) && $data_properties[DataloadingConstants::DATASOURCE] === DataSources::MUTABLEONREQUEST) {
            /** @var ComponentModelModuleInfo */
            $moduleInfo = App::getModule(ComponentModelModule::class)->getInfo();
            $ret[GD_URLPARAM_TIMESTAMP] = $moduleInfo->getTime();
        }

        // If it is lazy load, no need to calculate pagenumber / stop-fetching / etc
        if (($data_properties[DataloadingConstants::LAZYLOAD] ?? null) || ($data_properties[DataloadingConstants::EXTERNALLOAD] ?? null) || (isset($data_properties[DataloadingConstants::DATASOURCE]) && $data_properties[DataloadingConstants::DATASOURCE] !== DataSources::MUTABLEONREQUEST) || (App::getState('loading-latest'))) {
            return $ret;
        }

        // If data is not to be loaded, then "stop-fetching" as to not show the Load More button
        if ($data_properties[DataloadingConstants::SKIPDATALOAD] ?? null) {
            $ret[GD_URLPARAM_STOPFETCHING] = true;
            return $ret;
        }

        $ret[GD_URLPARAM_STOPFETCHING] = Utils::stopFetching($objectIDOrIDs, $data_properties);

        return $ret;
    }

    /**
     * @return array<string,mixed>
     * @param array<string,mixed> $data_properties
     * @param string|int|array<string|int> $objectIDOrIDs
     * @param array<string,mixed>|null $executed
     */
    public function getQueryParams(array $data_properties, ?FeedbackItemResolution $dataaccess_checkpoint_validation, ?FeedbackItemResolution $actionexecution_checkpoint_validation, ?array $executed, string|int|array $objectIDOrIDs): array
    {
        $ret = parent::getQueryParams($data_properties, $dataaccess_checkpoint_validation, $actionexecution_checkpoint_validation, $executed, $objectIDOrIDs);

        // If data is not to be loaded, then "stop-fetching" as to not show the Load More button
        if (($data_properties[DataloadingConstants::SKIPDATALOAD] ?? null) || (isset($data_properties[DataloadingConstants::DATASOURCE]) && $data_properties[DataloadingConstants::DATASOURCE] !== DataSources::MUTABLEONREQUEST)) {
            return $ret;
        }

        $query_args = $data_properties[DataloadingConstants::QUERYARGS];

        if ($limit = $query_args[PaginationParams::LIMIT]) {
            $ret[PaginationParams::LIMIT] = $limit;
        }

        $pagenumber = $query_args[PaginationParams::PAGE_NUMBER];
        if (!Utils::stopFetching($objectIDOrIDs, $data_properties)) {
            // When loading latest, we need to return the same $pagenumber as we got, because it must not alter the params
            $nextpagenumber = (App::hasState('loading-latest') && App::getState('loading-latest')) ? $pagenumber : $pagenumber + 1;
        }
        $ret[PaginationParams::PAGE_NUMBER] = $nextpagenumber;

        return $ret;
    }
}
