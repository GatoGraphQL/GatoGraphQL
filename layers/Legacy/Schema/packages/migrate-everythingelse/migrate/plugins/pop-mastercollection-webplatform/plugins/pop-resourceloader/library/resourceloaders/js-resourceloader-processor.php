<?php

class PoP_CoreProcessors_ResourceLoaderProcessor extends PoP_JSResourceLoaderProcessor
{
    public final const RESOURCE_DATERANGE = 'daterange';
    public final const RESOURCE_MULTISELECT = 'multiselect';
    public final const RESOURCE_DYNAMICMAXHEIGHT = 'dynamicmaxheight';
    public final const RESOURCE_PERFECTSCROLLBAR = 'perfectscrollbar';
    public final const RESOURCE_ADDEDITPOST = 'addeditpost';
    public final const RESOURCE_CONTROLS = 'controls';
    public final const RESOURCE_COOKIES = 'cookies';
    public final const RESOURCE_FUNCTIONS = 'functions';
    public final const RESOURCE_EXPAND = 'expand';
    public final const RESOURCE_INPUTFUNCTIONS = 'input-functions';
    public final const RESOURCE_EMBEDFUNCTIONS = 'embed-functions';
    public final const RESOURCE_PRINTFUNCTIONS = 'print-functions';
    public final const RESOURCE_DYNAMICRENDER = 'dynamic-render';
    public final const RESOURCE_DYNAMICRENDERURLFUNCTIONS = 'dynamic-render-urlfunctions';
    public final const RESOURCE_TARGETFUNCTIONS = 'target-functions';
    public final const RESOURCE_SOCIALMEDIA = 'socialmedia';
    public final const RESOURCE_EMBEDDABLE = 'embeddable';
    public final const RESOURCE_BLOCKDATAQUERY = 'block-dataquery';
    public final const RESOURCE_BLOCKDATAQUERYUSERSTATE = 'block-dataquery-userstate';
    public final const RESOURCE_BLOCKGROUPDATAQUERY = 'blockgroup-dataquery';
    public final const RESOURCE_MENUS = 'menus';
    public final const RESOURCE_DATASETCOUNT = 'dataset-count';
    public final const RESOURCE_LINKS = 'links';
    public final const RESOURCE_CLASSES = 'classes';
    public final const RESOURCE_SCROLLS = 'scrolls';
    public final const RESOURCE_ONLINEOFFLINE = 'onlineoffline';
    public final const RESOURCE_EVENTREACTIONS = 'event-reactions';
    public final const RESOURCE_EVENTREACTIONSUSERSTATE = 'event-reactions-userstate';
    public final const RESOURCE_FEEDBACKMESSAGE = 'feedback-message';
    public final const RESOURCE_MENTIONS = 'mentions';
    public final const RESOURCE_SYSTEM = 'system';
    public final const RESOURCE_TABS = 'tabs';
    public final const RESOURCE_WINDOWS = 'windows';
    public final const RESOURCE_APPSHELL = 'appshell';
    
    public function getResourcesToProcess()
    {
        return [
            [self::class, self::RESOURCE_DATERANGE],
            [self::class, self::RESOURCE_MULTISELECT],
            [self::class, self::RESOURCE_DYNAMICMAXHEIGHT],
            [self::class, self::RESOURCE_PERFECTSCROLLBAR],
            [self::class, self::RESOURCE_ADDEDITPOST],
            [self::class, self::RESOURCE_CONTROLS],
            [self::class, self::RESOURCE_COOKIES],
            [self::class, self::RESOURCE_FUNCTIONS],
            [self::class, self::RESOURCE_EXPAND],
            [self::class, self::RESOURCE_INPUTFUNCTIONS],
            [self::class, self::RESOURCE_EMBEDFUNCTIONS],
            [self::class, self::RESOURCE_PRINTFUNCTIONS],
            [self::class, self::RESOURCE_DYNAMICRENDER],
            [self::class, self::RESOURCE_DYNAMICRENDERURLFUNCTIONS],
            [self::class, self::RESOURCE_TARGETFUNCTIONS],
            [self::class, self::RESOURCE_SOCIALMEDIA],
            [self::class, self::RESOURCE_EMBEDDABLE],
            [self::class, self::RESOURCE_BLOCKDATAQUERY],
            [self::class, self::RESOURCE_BLOCKDATAQUERYUSERSTATE],
            [self::class, self::RESOURCE_BLOCKGROUPDATAQUERY],
            [self::class, self::RESOURCE_MENUS],
            [self::class, self::RESOURCE_DATASETCOUNT],
            [self::class, self::RESOURCE_LINKS],
            [self::class, self::RESOURCE_CLASSES],
            [self::class, self::RESOURCE_SCROLLS],
            [self::class, self::RESOURCE_ONLINEOFFLINE],
            [self::class, self::RESOURCE_EVENTREACTIONS],
            [self::class, self::RESOURCE_EVENTREACTIONSUSERSTATE],
            [self::class, self::RESOURCE_FEEDBACKMESSAGE],
            [self::class, self::RESOURCE_MENTIONS],
            [self::class, self::RESOURCE_SYSTEM],
            [self::class, self::RESOURCE_TABS],
            [self::class, self::RESOURCE_WINDOWS],
            [self::class, self::RESOURCE_APPSHELL],
        ];
    }
    
    public function getFilename(array $resource)
    {
        $filenames = array(
            self::RESOURCE_DATERANGE => 'daterange',
            self::RESOURCE_MULTISELECT => 'multiselect',
            self::RESOURCE_DYNAMICMAXHEIGHT => 'dynamicmaxheight',
            self::RESOURCE_PERFECTSCROLLBAR => 'perfectscrollbar',
            self::RESOURCE_ADDEDITPOST => 'addeditpost',
            self::RESOURCE_CONTROLS => 'controls',
            self::RESOURCE_COOKIES => 'cookies',
            self::RESOURCE_FUNCTIONS => 'functions',
            self::RESOURCE_EXPAND => 'expand',
            self::RESOURCE_INPUTFUNCTIONS => 'input-functions',
            self::RESOURCE_EMBEDFUNCTIONS => 'embed-functions',
            self::RESOURCE_PRINTFUNCTIONS => 'print-functions',
            self::RESOURCE_DYNAMICRENDER => 'dynamic-render',
            self::RESOURCE_DYNAMICRENDERURLFUNCTIONS => 'dynamic-render-urlfunctions',
            self::RESOURCE_TARGETFUNCTIONS => 'target-functions',
            self::RESOURCE_SOCIALMEDIA => 'socialmedia',
            self::RESOURCE_EMBEDDABLE => 'embeddable',
            self::RESOURCE_BLOCKDATAQUERY => 'block-dataquery',
            self::RESOURCE_BLOCKDATAQUERYUSERSTATE => 'block-dataquery-userstate',
            self::RESOURCE_BLOCKGROUPDATAQUERY => 'blockgroup-dataquery',
            self::RESOURCE_MENUS => 'menus',
            self::RESOURCE_DATASETCOUNT => 'dataset-count',
            self::RESOURCE_LINKS => 'links',
            self::RESOURCE_CLASSES => 'classes',
            self::RESOURCE_SCROLLS => 'scrolls',
            self::RESOURCE_ONLINEOFFLINE => 'onlineoffline',
            self::RESOURCE_EVENTREACTIONS => 'event-reactions',
            self::RESOURCE_EVENTREACTIONSUSERSTATE => 'event-reactions-userstate',
            self::RESOURCE_FEEDBACKMESSAGE => 'feedback-message',
            self::RESOURCE_MENTIONS => 'mentions',
            self::RESOURCE_SYSTEM => 'system',
            self::RESOURCE_TABS => 'tabs',
            self::RESOURCE_WINDOWS => 'windows',
            self::RESOURCE_APPSHELL => 'appshell',
        );
        if ($filename = $filenames[$resource[1]]) {
            return $filename;
        }

        return parent::getFilename($resource);
    }
    
    public function getVersion(array $resource)
    {
        return POP_MASTERCOLLECTIONWEBPLATFORM_VERSION;
    }
    
    public function getDir(array $resource)
    {
        $subpath = PoP_WebPlatform_ServerUtils::useMinifiedResources() ? 'dist/' : '';
        switch ($resource[1]) {
            case self::RESOURCE_DATERANGE:
            case self::RESOURCE_MULTISELECT:
            case self::RESOURCE_DYNAMICMAXHEIGHT:
            case self::RESOURCE_PERFECTSCROLLBAR:
                return POP_MASTERCOLLECTIONWEBPLATFORM_DIR.'/js/'.$subpath.'libraries/3rdparties';
        }
    
        return POP_MASTERCOLLECTIONWEBPLATFORM_DIR.'/js/'.$subpath.'libraries';
    }
    
    public function getAssetPath(array $resource)
    {
        switch ($resource[1]) {
            case self::RESOURCE_DATERANGE:
            case self::RESOURCE_MULTISELECT:
            case self::RESOURCE_DYNAMICMAXHEIGHT:
            case self::RESOURCE_PERFECTSCROLLBAR:
                return POP_MASTERCOLLECTIONWEBPLATFORM_DIR.'/js/libraries/3rdparties/'.$this->getFilename($resource).'.js';
        }

        return POP_MASTERCOLLECTIONWEBPLATFORM_DIR.'/js/libraries/'.$this->getFilename($resource).'.js';
    }
    
    public function getPath(array $resource)
    {
        $afterpath = '';
        switch ($resource[1]) {
            case self::RESOURCE_DATERANGE:
            case self::RESOURCE_MULTISELECT:
            case self::RESOURCE_DYNAMICMAXHEIGHT:
            case self::RESOURCE_PERFECTSCROLLBAR:
                $afterpath = '/3rdparties';
                break;
        }

        $subpath = PoP_WebPlatform_ServerUtils::useMinifiedResources() ? 'dist/' : '';
        return POP_MASTERCOLLECTIONWEBPLATFORM_URL.'/js/'.$subpath.'libraries'.$afterpath;
    }

    public function getJsobjects(array $resource)
    {
        $objects = array(
            self::RESOURCE_DATERANGE => array(
                'DateRange',
            ),
            self::RESOURCE_MULTISELECT => array(
                'Multiselect',
            ),
            self::RESOURCE_DYNAMICMAXHEIGHT => array(
                'DynamicMaxHeight',
            ),
            self::RESOURCE_PERFECTSCROLLBAR => array(
                'PerfectScrollbar',
            ),
            self::RESOURCE_ADDEDITPOST => array(
                'AddEditPost',
            ),
            self::RESOURCE_CONTROLS => array(
                'Controls',
            ),
            self::RESOURCE_COOKIES => array(
                'Cookies',
            ),
            self::RESOURCE_FUNCTIONS => array(
                'Functions',
            ),
            self::RESOURCE_EXPAND => array(
                'Expand',
            ),
            self::RESOURCE_INPUTFUNCTIONS => array(
                'InputFunctions',
            ),
            self::RESOURCE_EMBEDFUNCTIONS => array(
                'EmbedFunctions',
            ),
            self::RESOURCE_PRINTFUNCTIONS => array(
                'PrintFunctions',
            ),
            self::RESOURCE_DYNAMICRENDER => array(
                'DynamicRender',
            ),
            self::RESOURCE_DYNAMICRENDERURLFUNCTIONS => array(
                'DynamicRenderURLFunctions',
            ),
            self::RESOURCE_TARGETFUNCTIONS => array(
                'TargetFunctions',
            ),
            self::RESOURCE_SOCIALMEDIA => array(
                'SocialMedia',
            ),
            self::RESOURCE_EMBEDDABLE => array(
                'Embeddable',
            ),
            self::RESOURCE_BLOCKDATAQUERY => array(
                'BlockDataQuery',
            ),
            self::RESOURCE_BLOCKDATAQUERYUSERSTATE => array(
                'BlockDataQueryUserState',
            ),
            self::RESOURCE_BLOCKGROUPDATAQUERY => array(
                'BlockGroupDataQuery',
            ),
            self::RESOURCE_MENUS => array(
                'Menus',
            ),
            self::RESOURCE_DATASETCOUNT => array(
                'DatasetCount',
            ),
            self::RESOURCE_LINKS => array(
                'Links',
            ),
            self::RESOURCE_CLASSES => array(
                'Classes',
            ),
            self::RESOURCE_SCROLLS => array(
                'Scrolls',
            ),
            self::RESOURCE_ONLINEOFFLINE => array(
                'OnlineOffline',
            ),
            self::RESOURCE_EVENTREACTIONS => array(
                'EventReactions',
            ),
            self::RESOURCE_EVENTREACTIONSUSERSTATE => array(
                'EventReactionsUserState',
            ),
            self::RESOURCE_FEEDBACKMESSAGE => array(
                'FeedbackMessage',
            ),
            self::RESOURCE_MENTIONS => array(
                'Mentions',
            ),
            self::RESOURCE_SYSTEM => array(
                'System',
            ),
            self::RESOURCE_TABS => array(
                'Tabs',
            ),
            self::RESOURCE_WINDOWS => array(
                'Window',
            ),
            self::RESOURCE_APPSHELL => array(
                'AppShell',
            ),
        );
        if ($object = $objects[$resource[1]]) {
            return $object;
        }

        return parent::getJsobjects($resource);
    }
    
    public function getDependencies(array $resource)
    {
        $dependencies = parent::getDependencies($resource);
    
        switch ($resource[1]) {
            case self::RESOURCE_DATERANGE:
                $dependencies[] = [PoP_CoreProcessors_Bootstrap_VendorJSResourceLoaderProcessor::class, PoP_CoreProcessors_Bootstrap_VendorJSResourceLoaderProcessor::RESOURCE_EXTERNAL_DATERANGEPICKER];
                $dependencies[] = [PoP_BaseCollectionWebPlatform_VendorJSResourceLoaderProcessor::class, PoP_BaseCollectionWebPlatform_VendorJSResourceLoaderProcessor::RESOURCE_EXTERNAL_MOMENT];
                break;
                
            case self::RESOURCE_MULTISELECT:
                $dependencies[] = [PoP_CoreProcessors_Bootstrap_VendorJSResourceLoaderProcessor::class, PoP_CoreProcessors_Bootstrap_VendorJSResourceLoaderProcessor::RESOURCE_EXTERNAL_BOOTSTRAPMULTISELECT];
                
                // Allow to hook extra CSS styles
                if ($extra_dependencies = \PoP\Root\App::applyFilters('PoP_CoreProcessors_Bootstrap_ResourceLoaderProcessor:dependencies:multiselect', array())) {
                    $dependencies = array_merge(
                        $dependencies,
                        $extra_dependencies
                    );
                }
                break;
                
            case self::RESOURCE_DYNAMICMAXHEIGHT:
                $dependencies[] = [PoP_CoreProcessors_VendorJSResourceLoaderProcessor::class, PoP_CoreProcessors_VendorJSResourceLoaderProcessor::RESOURCE_EXTERNAL_DYNAMICMAXHEIGHT];
                break;
                                
            case self::RESOURCE_PERFECTSCROLLBAR:
                $dependencies[] = [PoP_CoreProcessors_VendorJSResourceLoaderProcessor::class, PoP_CoreProcessors_VendorJSResourceLoaderProcessor::RESOURCE_EXTERNAL_PERFECTSCROLLBAR];
                break;
                
            case self::RESOURCE_COOKIES:
            case self::RESOURCE_TABS:
                $dependencies[] = [PoP_CoreProcessors_VendorJSResourceLoaderProcessor::class, PoP_CoreProcessors_VendorJSResourceLoaderProcessor::RESOURCE_EXTERNAL_JQUERYCOOKIE];
                break;

            case self::RESOURCE_EMBEDDABLE:
                $dependencies[] = [PoP_CoreProcessors_VendorJSResourceLoaderProcessor::class, PoP_CoreProcessors_VendorJSResourceLoaderProcessor::RESOURCE_EXTERNAL_FULLSCREEN];
                break;
        }

        return $dependencies;
    }
}


