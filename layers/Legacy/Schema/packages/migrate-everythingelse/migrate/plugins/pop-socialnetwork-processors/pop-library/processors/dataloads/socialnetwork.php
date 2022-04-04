<?php
use PoP\ComponentModel\ModuleProcessors\DataloadingConstants;
use PoP\ComponentModel\State\ApplicationState;
use PoPCMSSchema\CustomPosts\TypeResolvers\ObjectType\CustomPostObjectTypeResolver;
use PoPCMSSchema\PostTags\TypeResolvers\ObjectType\PostTagObjectTypeResolver;
use PoPCMSSchema\Users\TypeResolvers\ObjectType\UserObjectTypeResolver;
use PoPCMSSchema\UserState\CheckpointSets\UserStateCheckpointSets;

class PoP_Module_Processor_FunctionsDataloads extends PoP_Module_Processor_DataloadsBase
{
    public final const MODULE_DATALOAD_FOLLOWSUSERS = 'dataload-followsusers';
    public final const MODULE_DATALOAD_RECOMMENDSPOSTS = 'dataload-recommendsposts';
    public final const MODULE_DATALOAD_SUBSCRIBESTOTAGS = 'dataload-subscribestotags';
    public final const MODULE_DATALOAD_UPVOTESPOSTS = 'dataload-upvotesposts';
    public final const MODULE_DATALOAD_DOWNVOTESPOSTS = 'dataload-downvotesposts';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_DATALOAD_FOLLOWSUSERS],
            [self::class, self::MODULE_DATALOAD_RECOMMENDSPOSTS],
            [self::class, self::MODULE_DATALOAD_SUBSCRIBESTOTAGS],
            [self::class, self::MODULE_DATALOAD_UPVOTESPOSTS],
            [self::class, self::MODULE_DATALOAD_DOWNVOTESPOSTS],
        );
    }

    // function getDataaccessCheckpointConfiguration(array $module, array &$props) {
    public function getDataAccessCheckpoints(array $module, array &$props): array
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_FOLLOWSUSERS:
            case self::MODULE_DATALOAD_RECOMMENDSPOSTS:
            case self::MODULE_DATALOAD_SUBSCRIBESTOTAGS:
            case self::MODULE_DATALOAD_UPVOTESPOSTS:
            case self::MODULE_DATALOAD_DOWNVOTESPOSTS:
                return $this->maybeOverrideCheckpoints(UserStateCheckpointSets::LOGGEDIN_DATAFROMSERVER);//PoP_UserLogin_SettingsProcessor_CheckpointHelper::getCheckpointConfiguration(UserStateCheckpointSets::LOGGEDIN_DATAFROMSERVER);
        }

        // return parent::getDataaccessCheckpointConfiguration($module, $props);
        return parent::getDataAccessCheckpoints($module, $props);
    }

    protected function addHeaddatasetmoduleDataProperties(&$ret, array $module, array &$props)
    {
        parent::addHeaddatasetmoduleDataProperties($ret, $module, $props);

        switch ($module[1]) {
            case self::MODULE_DATALOAD_FOLLOWSUSERS:
            case self::MODULE_DATALOAD_RECOMMENDSPOSTS:
            case self::MODULE_DATALOAD_SUBSCRIBESTOTAGS:
            case self::MODULE_DATALOAD_UPVOTESPOSTS:
            case self::MODULE_DATALOAD_DOWNVOTESPOSTS:
                // If the user is not logged in, then do not load the data
                if (!PoP_UserState_Utils::currentRouteRequiresUserState() || !\PoP\Root\App::getState('is-user-logged-in')) {
                    $ret[DataloadingConstants::SKIPDATALOAD] = true;
                }
                break;
        }
    }

    public function getImmutableHeaddatasetmoduleDataProperties(array $module, array &$props): array
    {
        $ret = parent::getImmutableHeaddatasetmoduleDataProperties($module, $props);

        switch ($module[1]) {
            case self::MODULE_DATALOAD_FOLLOWSUSERS:
            case self::MODULE_DATALOAD_RECOMMENDSPOSTS:
            case self::MODULE_DATALOAD_UPVOTESPOSTS:
            case self::MODULE_DATALOAD_DOWNVOTESPOSTS:
            case self::MODULE_DATALOAD_SUBSCRIBESTOTAGS:
                // Bring all of them and then don't bring anymore
                $ret[GD_DATALOAD_QUERYHANDLERPROPERTY_LIST_STOPFETCHING] = true;
                $ret[DataloadingConstants::QUERYARGS]['limit'] = -1;
                break;
        }

        return $ret;
    }

    protected function getInnerSubmodules(array $module): array
    {
        $ret = parent::getInnerSubmodules($module);

        $layouts = array(
            self::MODULE_DATALOAD_FOLLOWSUSERS => [PoP_Module_Processor_FunctionsContents::class, PoP_Module_Processor_FunctionsContents::MODULE_CONTENT_FOLLOWSUSERS],
            self::MODULE_DATALOAD_RECOMMENDSPOSTS => [PoP_Module_Processor_FunctionsContents::class, PoP_Module_Processor_FunctionsContents::MODULE_CONTENT_RECOMMENDSPOSTS],
            self::MODULE_DATALOAD_SUBSCRIBESTOTAGS => [PoP_Module_Processor_FunctionsContents::class, PoP_Module_Processor_FunctionsContents::MODULE_CONTENT_SUBSCRIBESTOTAGS],
            self::MODULE_DATALOAD_UPVOTESPOSTS => [PoP_Module_Processor_FunctionsContents::class, PoP_Module_Processor_FunctionsContents::MODULE_CONTENT_UPVOTESPOSTS],
            self::MODULE_DATALOAD_DOWNVOTESPOSTS => [PoP_Module_Processor_FunctionsContents::class, PoP_Module_Processor_FunctionsContents::MODULE_CONTENT_DOWNVOTESPOSTS],
        );
        if ($layout = $layouts[$module[1]] ?? null) {
            $ret[] = $layout;
        }

        return $ret;
    }

    public function getObjectIDOrIDs(array $module, array &$props, &$data_properties): string | int | array
    {
        // All of these modules require the user to be logged in
        if (!\PoP\Root\App::getState('is-user-logged-in')) {
            return [];
        }

        $metaKeys = [
            self::MODULE_DATALOAD_FOLLOWSUSERS => GD_METAKEY_PROFILE_FOLLOWSUSERS,
            self::MODULE_DATALOAD_RECOMMENDSPOSTS => GD_METAKEY_PROFILE_RECOMMENDSPOSTS,
            self::MODULE_DATALOAD_SUBSCRIBESTOTAGS => GD_METAKEY_PROFILE_SUBSCRIBESTOTAGS,
            self::MODULE_DATALOAD_UPVOTESPOSTS => GD_METAKEY_PROFILE_UPVOTESPOSTS,
            self::MODULE_DATALOAD_DOWNVOTESPOSTS => GD_METAKEY_PROFILE_DOWNVOTESPOSTS,
        ];

        if ($metaKey = $metaKeys[$module[1]] ?? null) {
            $userID = \PoP\Root\App::getState('current-user-id');
            return \PoPCMSSchema\UserMeta\Utils::getUserMeta($userID, $metaKey) ?? [];
        }

        return parent::getObjectIDOrIDs($module, $props, $data_properties);
    }

    public function getRelationalTypeResolver(array $module): ?\PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_FOLLOWSUSERS:
                return $this->instanceManager->getInstance(UserObjectTypeResolver::class);

            case self::MODULE_DATALOAD_UPVOTESPOSTS:
            case self::MODULE_DATALOAD_RECOMMENDSPOSTS:
            case self::MODULE_DATALOAD_DOWNVOTESPOSTS:
                return $this->instanceManager->getInstance(CustomPostObjectTypeResolver::class);

            case self::MODULE_DATALOAD_SUBSCRIBESTOTAGS:
                return $this->instanceManager->getInstance(PostTagObjectTypeResolver::class);
        }

        return parent::getRelationalTypeResolver($module);
    }

    public function initModelProps(array $module, array &$props): void
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_FOLLOWSUSERS:
            case self::MODULE_DATALOAD_RECOMMENDSPOSTS:
            case self::MODULE_DATALOAD_UPVOTESPOSTS:
            case self::MODULE_DATALOAD_DOWNVOTESPOSTS:
            case self::MODULE_DATALOAD_SUBSCRIBESTOTAGS:
                $this->appendProp($module, $props, 'class', 'hidden');
                break;
        }

        parent::initModelProps($module, $props);
    }
}



