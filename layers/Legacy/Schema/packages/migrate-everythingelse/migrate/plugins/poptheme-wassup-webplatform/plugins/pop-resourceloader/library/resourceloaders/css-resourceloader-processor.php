<?php

class PoPTheme_Wassup_CSSResourceLoaderProcessor extends PoP_CSSResourceLoaderProcessor
{
    public final const RESOURCE_CSS_PAGESECTIONGROUP = 'css-pagesectiongroup';
    public final const RESOURCE_CSS_THEMEWASSUP = 'css-theme-wassup';
    public final const RESOURCE_CSS_THEMEWASSUPBOOTSTRAP = 'css-themebootstrap';
    public final const RESOURCE_CSS_THEMEWASSUPTYPEAHEADBOOTSTRAP = 'css-themetypeaheadbootstrap';
    public final const RESOURCE_CSS_GROUPHOMEWELCOME = 'css-group-home-welcome';
    public final const RESOURCE_CSS_COLLAPSEHOMETOP = 'css-collapse-hometop';
    public final const RESOURCE_CSS_QUICKLINKGROUPS = 'css-quicklinkgroups';
    public final const RESOURCE_CSS_DATERANGEPICKER = 'css-daterangepicker';
    public final const RESOURCE_CSS_SKELETONSCREEN = 'css-skeletonscreen';
    public final const RESOURCE_CSS_BLOCKCAROUSEL = 'css-blockcarousel';
    public final const RESOURCE_CSS_FETCHMORE = 'css-fetchmore';
    public final const RESOURCE_CSS_GROUPAUTHOR = 'css-group-author';
    public final const RESOURCE_CSS_GROUPAUTHORSECTIONS = 'css-group-authorsections';
    public final const RESOURCE_CSS_BLOCK = 'css-block';
    public final const RESOURCE_CSS_FUNCTIONALBLOCK = 'css-functionalblock';
    public final const RESOURCE_CSS_FUNCTIONBUTTON = 'css-functionbutton';
    public final const RESOURCE_CSS_SOCIALMEDIA = 'css-socialmedia';
    public final const RESOURCE_CSS_FORMMYPREFERENCES = 'css-form-mypreferences';
    public final const RESOURCE_CSS_BLOCKCOMMENTS = 'css-block-comments';
    public final const RESOURCE_CSS_FRAMEADDCOMMENTS = 'css-frame-addcomments';
    public final const RESOURCE_CSS_SIDESECTIONSMENU = 'css-side-sections-menu';
    public final const RESOURCE_CSS_LITTLEGUY = 'css-littleguy';
    public final const RESOURCE_CSS_SPEECHBUBBLE = 'css-speechbubble';
    public final const RESOURCE_CSS_FEATUREDIMAGE = 'css-featuredimage';
    public final const RESOURCE_CSS_HOMEMESSAGE = 'css-homemessage';
    public final const RESOURCE_CSS_BLOCKNOTIFICATIONS = 'css-block-notifications';
    public final const RESOURCE_CSS_SCROLLNOTIFICATIONS = 'css-scroll-notifications';
    public final const RESOURCE_CSS_WIDGET = 'css-widget';
    public final const RESOURCE_CSS_DYNAMICMAXHEIGHT = 'css-dynamicmaxheight';
    public final const RESOURCE_CSS_STRUCTURE = 'css-structure';
    public final const RESOURCE_CSS_LAYOUT = 'css-layout';
    public final const RESOURCE_CSS_SECTIONLAYOUT = 'css-sectionlayout';

    public function getResourcesToProcess()
    {
        return [
            [self::class, self::RESOURCE_CSS_PAGESECTIONGROUP],
            [self::class, self::RESOURCE_CSS_THEMEWASSUP],
            [self::class, self::RESOURCE_CSS_THEMEWASSUPBOOTSTRAP],
            [self::class, self::RESOURCE_CSS_THEMEWASSUPTYPEAHEADBOOTSTRAP],
            [self::class, self::RESOURCE_CSS_GROUPHOMEWELCOME],
            [self::class, self::RESOURCE_CSS_COLLAPSEHOMETOP],
            [self::class, self::RESOURCE_CSS_QUICKLINKGROUPS],
            [self::class, self::RESOURCE_CSS_DATERANGEPICKER],
            [self::class, self::RESOURCE_CSS_SKELETONSCREEN],
            [self::class, self::RESOURCE_CSS_BLOCKCAROUSEL],
            [self::class, self::RESOURCE_CSS_FETCHMORE],
            [self::class, self::RESOURCE_CSS_GROUPAUTHOR],
            [self::class, self::RESOURCE_CSS_GROUPAUTHORSECTIONS],
            [self::class, self::RESOURCE_CSS_BLOCK],
            [self::class, self::RESOURCE_CSS_FUNCTIONALBLOCK],
            [self::class, self::RESOURCE_CSS_FUNCTIONBUTTON],
            [self::class, self::RESOURCE_CSS_SOCIALMEDIA],
            [self::class, self::RESOURCE_CSS_FORMMYPREFERENCES],
            [self::class, self::RESOURCE_CSS_BLOCKCOMMENTS],
            [self::class, self::RESOURCE_CSS_FRAMEADDCOMMENTS],
            [self::class, self::RESOURCE_CSS_SIDESECTIONSMENU],
            [self::class, self::RESOURCE_CSS_LITTLEGUY],
            [self::class, self::RESOURCE_CSS_SPEECHBUBBLE],
            [self::class, self::RESOURCE_CSS_FEATUREDIMAGE],
            [self::class, self::RESOURCE_CSS_HOMEMESSAGE],
            [self::class, self::RESOURCE_CSS_BLOCKNOTIFICATIONS],
            [self::class, self::RESOURCE_CSS_SCROLLNOTIFICATIONS],
            [self::class, self::RESOURCE_CSS_WIDGET],
            [self::class, self::RESOURCE_CSS_DYNAMICMAXHEIGHT],
            [self::class, self::RESOURCE_CSS_STRUCTURE],
            [self::class, self::RESOURCE_CSS_LAYOUT],
            [self::class, self::RESOURCE_CSS_SECTIONLAYOUT],
        ];
    }
    
    public function getFilename(array $resource)
    {
        $filenames = array(
            self::RESOURCE_CSS_PAGESECTIONGROUP => 'pagesection-group',
            self::RESOURCE_CSS_THEMEWASSUP => 'style',
            self::RESOURCE_CSS_THEMEWASSUPBOOTSTRAP => 'custom.bootstrap',
            self::RESOURCE_CSS_THEMEWASSUPTYPEAHEADBOOTSTRAP => 'typeahead.js-bootstrap',
            self::RESOURCE_CSS_GROUPHOMEWELCOME => 'blockgroup-home-welcome',
            self::RESOURCE_CSS_COLLAPSEHOMETOP => 'collapse-hometop',
            self::RESOURCE_CSS_QUICKLINKGROUPS => 'quicklinkgroups',
            self::RESOURCE_CSS_DATERANGEPICKER => 'daterangepicker',
            self::RESOURCE_CSS_SKELETONSCREEN => 'skeleton-screen',
            self::RESOURCE_CSS_BLOCKCAROUSEL => 'block-carousel',
            self::RESOURCE_CSS_FETCHMORE => 'fetchmore',
            self::RESOURCE_CSS_GROUPAUTHOR => 'blockgroup-author',
            self::RESOURCE_CSS_GROUPAUTHORSECTIONS => 'blockgroup-authorsections',
            self::RESOURCE_CSS_BLOCK => 'block',
            self::RESOURCE_CSS_FUNCTIONALBLOCK => 'functionalblock',
            self::RESOURCE_CSS_FUNCTIONBUTTON => 'functionbutton',
            self::RESOURCE_CSS_SOCIALMEDIA => 'socialmedia',
            self::RESOURCE_CSS_FORMMYPREFERENCES => 'form-mypreferences',
            self::RESOURCE_CSS_BLOCKCOMMENTS => 'block-comments',
            self::RESOURCE_CSS_FRAMEADDCOMMENTS => 'frame-addcomments',
            self::RESOURCE_CSS_SIDESECTIONSMENU => 'side-sections-menu',
            self::RESOURCE_CSS_LITTLEGUY => 'littleguy',
            self::RESOURCE_CSS_SPEECHBUBBLE => 'speechbubble',
            self::RESOURCE_CSS_FEATUREDIMAGE => 'featuredimage',
            self::RESOURCE_CSS_HOMEMESSAGE => 'homemessage',
            self::RESOURCE_CSS_BLOCKNOTIFICATIONS => 'block-notifications',
            self::RESOURCE_CSS_SCROLLNOTIFICATIONS => 'scroll-notifications',
            self::RESOURCE_CSS_WIDGET => 'widget',
            self::RESOURCE_CSS_DYNAMICMAXHEIGHT => 'dynamicmaxheight',
            self::RESOURCE_CSS_STRUCTURE => 'structure',
            self::RESOURCE_CSS_LAYOUT => 'layout',
            self::RESOURCE_CSS_SECTIONLAYOUT => 'section-layout',
        );
        if ($filename = $filenames[$resource[1]]) {
            return $filename;
        }

        return parent::getFilename($resource);
    }
    
    public function getReferencedFiles(array $resource)
    {
        $referenced_files = parent::getReferencedFiles($resource);

        switch ($resource[1]) {
            case self::RESOURCE_CSS_THEMEWASSUP:
                $referenced_files[] = '../fonts/Rockwell.eot';
                $referenced_files[] = '../fonts/Rockwell.svg';
                $referenced_files[] = '../fonts/Rockwell.ttf';
                $referenced_files[] = '../fonts/Rockwell.woff';
                $referenced_files[] = '../fonts/Rockwell.woff2';
                break;
        }

        return $referenced_files;
    }
    
    public function getHandle(array $resource)
    {
    
        // Other resources depend on the theme being called "poptheme-wassup"
        $handles = array(
            self::RESOURCE_CSS_THEMEWASSUP => 'poptheme-wassup',
        );
        if ($handle = $handles[$resource[1]]) {
            return $handle;
        }

        return parent::getHandle($resource);
    }
    
    public function getVersion(array $resource)
    {
        return POPTHEME_WASSUPWEBPLATFORM_VERSION;
    }
    
    public function getDir(array $resource)
    {
        $subpath = PoP_WebPlatform_ServerUtils::useMinifiedResources() ? 'dist/' : '';
        switch ($resource[1]) {
            case self::RESOURCE_CSS_PAGESECTIONGROUP:
            case self::RESOURCE_CSS_THEMEWASSUP:
            case self::RESOURCE_CSS_THEMEWASSUPBOOTSTRAP:
            case self::RESOURCE_CSS_THEMEWASSUPTYPEAHEADBOOTSTRAP:
                return POPTHEME_WASSUPWEBPLATFORM_DIR.'/css/'.$subpath.'libraries';
        }

        return POPTHEME_WASSUPWEBPLATFORM_DIR.'/css/'.$subpath.'templates';
    }
    
    // function getAssetPath(array $resource) {

    //     switch ($resource[1]) {

    //         case self::RESOURCE_CSS_PAGESECTIONGROUP:
    //         case self::RESOURCE_CSS_THEMEWASSUP:
    //         case self::RESOURCE_CSS_THEMEWASSUPBOOTSTRAP:
    //         case self::RESOURCE_CSS_THEMEWASSUPTYPEAHEADBOOTSTRAP:

    //             return POPTHEME_WASSUPWEBPLATFORM_DIR.'/css/libraries/'.$this->getFilename($resource).'.css';
    //     }

    //     return POPTHEME_WASSUPWEBPLATFORM_DIR.'/css/templates/'.$this->getFilename($resource).'.css';
    // }
    
    public function getPath(array $resource)
    {
        $subpath = PoP_WebPlatform_ServerUtils::useMinifiedResources() ? 'dist/' : '';
        switch ($resource[1]) {
            case self::RESOURCE_CSS_PAGESECTIONGROUP:
            case self::RESOURCE_CSS_THEMEWASSUP:
            case self::RESOURCE_CSS_THEMEWASSUPBOOTSTRAP:
            case self::RESOURCE_CSS_THEMEWASSUPTYPEAHEADBOOTSTRAP:
                return POPTHEME_WASSUPWEBPLATFORM_URL.'/css/'.$subpath.'libraries';
        }

        return POPTHEME_WASSUPWEBPLATFORM_URL.'/css/'.$subpath.'templates';
    }
    
    public function getDecoratedResources(array $resource)
    {
        $decorated = parent::getDecoratedResources($resource);
    
        switch ($resource[1]) {
            case self::RESOURCE_CSS_THEMEWASSUPBOOTSTRAP:
                $decorated[] = [PoP_BootstrapWebPlatform_VendorCSSResourceLoaderProcessor::class, PoP_BootstrapWebPlatform_VendorCSSResourceLoaderProcessor::RESOURCE_EXTERNAL_CSS_BOOTSTRAP];
                break;

            case self::RESOURCE_CSS_THEMEWASSUPTYPEAHEADBOOTSTRAP:
                $decorated[] = [PoP_CoreProcessors_VendorJSResourceLoaderProcessor::class, PoP_CoreProcessors_VendorJSResourceLoaderProcessor::RESOURCE_EXTERNAL_TYPEAHEAD];
                break;

            case self::RESOURCE_CSS_SECTIONLAYOUT:
                $decorated[] = [self::class, self::RESOURCE_CSS_LAYOUT];
                break;
        }

        return $decorated;
    }
}


