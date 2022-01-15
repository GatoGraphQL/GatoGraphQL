<?php

declare(strict_types=1);

namespace PoP\Application\QueryInputOutputHandlers;

use PoP\Application\ModuleProcessors\DataloadingConstants;
use PoP\ComponentModel\Component as ComponentModelComponent;
use PoP\ComponentModel\ComponentInfo as ComponentModelComponentInfo;
use PoP\ComponentModel\Constants\DataSources;
use PoP\ComponentModel\Constants\PaginationParams;
use PoP\ComponentModel\Constants\Params;
use PoP\ComponentModel\QueryInputOutputHandlers\ListQueryInputOutputHandler as UpstreamListQueryInputOutputHandler;
use PoPSchema\SchemaCommons\CMS\CMSServiceInterface;
use PoP\LooseContracts\NameResolverInterface;
use PoP\Root\App;

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
        return $this->cmsService ??= $this->instanceManager->getInstance(CMSServiceInterface::class);
    }
    final public function setNameResolver(NameResolverInterface $nameResolver): void
    {
        $this->nameResolver = $nameResolver;
    }
    final protected function getNameResolver(): NameResolverInterface
    {
        return $this->nameResolver ??= $this->instanceManager->getInstance(NameResolverInterface::class);
    }

    protected function getLimit()
    {
        return $this->getCMSService()->getOption($this->getNameResolver()->getName('popcms:option:limit'));
    }

    public function getQueryState($data_properties, $dataaccess_checkpoint_validation, $actionexecution_checkpoint_validation, $executed, $dbObjectIDOrIDs): array
    {
        $ret = parent::getQueryState($data_properties, $dataaccess_checkpoint_validation, $actionexecution_checkpoint_validation, $executed, $dbObjectIDOrIDs);

        // Needed to loadLatest, to know from what time to get results
        if (isset($data_properties[DataloadingConstants::DATASOURCE]) && $data_properties[DataloadingConstants::DATASOURCE] == DataSources::MUTABLEONREQUEST) {
            /** @var ComponentModelComponentInfo */
            $componentInfo = App::getComponent(ComponentModelComponent::class)->getInfo();
            $ret[GD_URLPARAM_TIMESTAMP] = $componentInfo->getTime();
        }

        // If it is lazy load, no need to calculate pagenumber / stop-fetching / etc
        if (($data_properties[DataloadingConstants::LAZYLOAD] ?? null) || ($data_properties[DataloadingConstants::EXTERNALLOAD] ?? null) || (isset($data_properties[DataloadingConstants::DATASOURCE]) && $data_properties[DataloadingConstants::DATASOURCE] != DataSources::MUTABLEONREQUEST) || (App::getState('loading-latest'))) {
            return $ret;
        }

        // If data is not to be loaded, then "stop-fetching" as to not show the Load More button
        if ($data_properties[DataloadingConstants::SKIPDATALOAD] ?? null) {
            $ret[GD_URLPARAM_STOPFETCHING] = true;
            return $ret;
        }

        $ret[GD_URLPARAM_STOPFETCHING] = Utils::stopFetching($dbObjectIDOrIDs, $data_properties);

        return $ret;
    }

    public function getQueryParams($data_properties, $dataaccess_checkpoint_validation, $actionexecution_checkpoint_validation, $executed, $dbObjectIDOrIDs): array
    {
        $ret = parent::getQueryParams($data_properties, $dataaccess_checkpoint_validation, $actionexecution_checkpoint_validation, $executed, $dbObjectIDOrIDs);

        // If data is not to be loaded, then "stop-fetching" as to not show the Load More button
        if (($data_properties[DataloadingConstants::SKIPDATALOAD] ?? null) || (isset($data_properties[DataloadingConstants::DATASOURCE]) && $data_properties[DataloadingConstants::DATASOURCE] != DataSources::MUTABLEONREQUEST)) {
            return $ret;
        }

        $query_args = $data_properties[DataloadingConstants::QUERYARGS];

        if ($limit = $query_args[PaginationParams::LIMIT]) {
            $ret[PaginationParams::LIMIT] = $limit;
        }

        $pagenumber = $query_args[PaginationParams::PAGE_NUMBER];
        if (!Utils::stopFetching($dbObjectIDOrIDs, $data_properties)) {
            // When loading latest, we need to return the same $pagenumber as we got, because it must not alter the params
            $nextpagenumber = (App::hasState('loading-latest') && App::getState('loading-latest')) ? $pagenumber : $pagenumber + 1;
        }
        $ret[PaginationParams::PAGE_NUMBER] = $nextpagenumber;

        return $ret;
    }

    // function getUniquetodomainQuerystate($data_properties, $dataaccess_checkpoint_validation, $actionexecution_checkpoint_validation, $executed, $dbobjectids) {

    //     $ret = parent::getUniquetodomainQuerystate($data_properties, $dataaccess_checkpoint_validation, $actionexecution_checkpoint_validation, $executed, $dbobjectids);

    //     // Needed to loadLatest, to know from what time to get results
    //      /** @var ComponentModelComponentInfo */
    //      $componentInfo = App::getComponent(ComponentModelComponent::class)->getInfo();
    //     $ret[GD_URLPARAM_TIMESTAMP] = $componentInfo->getTime();

    //     // If data is not to be loaded, then "stop-fetching" as to not show the Load More button
    //     if ($data_properties[DataloadingConstants::SKIPDATALOAD] ?? null) {

    //         $ret[GD_URLPARAM_STOPFETCHING] = true;
    //         return $ret;
    //     }

    //     // If it is lazy load, no need to calculate pagenumber / stop-fetching / etc
    //     if ($data_properties[DataloadingConstants::LAZYLOAD] ?? null) {

    //         return $ret;
    //     }

    //     // If loading static data, then that's it
    //     if ($data_properties[DataloadingConstants::DATASOURCE] != \PoP\ComponentModel\Constants\DataSources::MUTABLEONREQUEST) {

    //         return $ret;
    //     }

    //     $query_args = $data_properties[DataloadingConstants::QUERYARGS];
    //     $pagenumber = $query_args[\PoP\ComponentModel\Constants\PaginationParams::PAGE_NUMBER];
    //     $stop_loading = Utils::stopFetching($dbobjectids, $data_properties);

    //     $ret[GD_URLPARAM_STOPFETCHING] = $stop_loading;

    //     // When loading latest, we need to return the same $pagenumber as we got, because it must not alter the params
    //     $nextpaged = \PoP\Root\App::getState('loading-latest') ? $pagenumber : $pagenumber + 1;
    //     $ret[ParamConstants::PARAMS][\PoP\ComponentModel\Constants\PaginationParams::PAGE_NUMBER] = $stop_loading ? '' : $nextpaged;

    //     // Do not send this value back when doing loadLatest, or it will mess up the original structure loading
    //     // Doing 'unset' as to also take it out if an ancestor class (eg: GD_DataLoad_BlockQueryInputOutputHandler) has set it
    //     if (\PoP\Root\App::hasState('loading-latest') && \PoP\Root\App::getState('loading-latest')) {

    //         unset($ret[GD_URLPARAM_STOPFETCHING]);
    //     }

    //     return $ret;
    // }

    // function getSharedbydomainsQuerystate($data_properties, $dataaccess_checkpoint_validation, $actionexecution_checkpoint_validation, $executed, $dbobjectids) {

    //     $ret = parent::getSharedbydomainsQuerystate($data_properties, $dataaccess_checkpoint_validation, $actionexecution_checkpoint_validation, $executed, $dbobjectids);

    //     $query_args = $data_properties[DataloadingConstants::QUERYARGS];

    //     $limit = $query_args[\PoP\ComponentModel\Constants\PaginationParams::LIMIT];
    //     $ret[ParamConstants::PARAMS][\PoP\ComponentModel\Constants\PaginationParams::LIMIT] = $limit;

    //     return $ret;
    // }

    // function getDatafeedback($data_properties, $dataaccess_checkpoint_validation, $actionexecution_checkpoint_validation, $executed, $dbobjectids) {

    //     $ret = parent::getDatafeedback($data_properties, $dataaccess_checkpoint_validation, $actionexecution_checkpoint_validation, $executed, $dbobjectids);

    //     $query_args = $data_properties[DataloadingConstants::QUERYARGS];

    //     $limit = $query_args[\PoP\ComponentModel\Constants\PaginationParams::LIMIT];
    //     $ret[ParamConstants::PARAMS][\PoP\ComponentModel\Constants\PaginationParams::LIMIT] = $limit;

    //     // If it is lazy load, no need to calculate show-msg / pagenumber / stop-fetching / etc
    //     if ($data_properties[DataloadingConstants::LAZYLOAD]) {

    //         return $ret;
    //     }

    //     $pagenumber = $query_args[\PoP\ComponentModel\Constants\PaginationParams::PAGE_NUMBER];

    //     // Print feedback messages always, if none then an empty array
    //     $msgs = array();

    //     // Show error message if no items, but only if the checkpoint did not fail
    //     $checkpoint_failed = GeneralUtils::isError($dataaccess_checkpoint_validation);
    //     if (!$checkpoint_failed) {
    //         if (empty($dbobjectids)) {

    //             // Do not show the message when doing loadLatest
    //             if (!\PoP\Root\App::getState('loading-latest')) {

    //                 // If pagenumber < 2 => There are no results at all
    //                 $msgs[] = array(
    //                     'codes' => array(
    //                         ($pagenumber < 2) ? 'noresults' : 'nomore',
    //                     ),
    //                     GD_JS_CLASS => 'alert-warning',
    //                 );
    //             }
    //         }
    //     }
    //     $ret['msgs'] = $msgs;

    //     // stop-fetching is loaded twice: in the params and in the feedback. This is because we can't access the params from the .tmpl files
    //     // (the params object is created only when initializing JS => after rendering the html with Handlebars so it's not available by then)
    //     // and this value is needed in fetchmore.tmpl
    //     $stop_loading = Utils::stopFetching($dbobjectids, $data_properties);

    //     $ret[GD_URLPARAM_STOPFETCHING] = $stop_loading;

    //     // Add the Fetch more link for the Search Engine
    //     if (!$stop_loading && $data_properties[DataloadingConstants::SOURCE] ?? null) {

    //         $ret[POP_IOCONSTANT_QUERYNEXTURL] = add_query_arg(\PoP\ComponentModel\Constants\PaginationParams::PAGE_NUMBER, $pagenumber+1, $data_properties[DataloadingConstants::SOURCE]);
    //     }

    //     // Do not send this value back when doing loadLatest, or it will mess up the original structure loading
    //     // Doing 'unset' as to also take it out if an ancestor class (eg: GD_DataLoad_BlockQueryInputOutputHandler) has set it
    //     if (\PoP\Root\App::hasState('loading-latest') && \PoP\Root\App::getState('loading-latest')) {

    //         unset($ret[GD_URLPARAM_STOPFETCHING]);
    //     }

    //     return $ret;
    // }
}
