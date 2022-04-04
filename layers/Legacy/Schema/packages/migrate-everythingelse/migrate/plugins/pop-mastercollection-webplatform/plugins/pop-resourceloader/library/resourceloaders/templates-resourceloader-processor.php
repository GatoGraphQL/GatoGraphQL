<?php

class PoP_CoreProcessors_TemplateResourceLoaderProcessor extends PoP_TemplateResourceLoaderProcessor
{
    public final const RESOURCE_DATALOAD = 'dataload';
    public final const RESOURCE_BUTTONGROUP = 'buttongroup';
    public final const RESOURCE_BUTTON = 'button';
    public final const RESOURCE_BUTTONINNER = 'buttoninner';
    public final const RESOURCE_WINDOW = 'window';
    public final const RESOURCE_OFFCANVAS = 'offcanvas';
    public final const RESOURCE_HTMLCODE = 'htmlcode';
    public final const RESOURCE_SCRIPTCODE = 'scriptcode';
    public final const RESOURCE_STYLECODE = 'stylecode';
    public final const RESOURCE_CONDITIONWRAPPER = 'conditionwrapper';
    public final const RESOURCE_CONTROL_ANCHOR = 'control_anchor';
    public final const RESOURCE_CONTROL_BUTTON = 'control_button';
    public final const RESOURCE_CONTROL_DROPDOWNBUTTON = 'control_dropdownbutton';
    public final const RESOURCE_CONTROL_RADIOBUTTON = 'control_radiobutton';
    public final const RESOURCE_CONTROLBUTTONGROUP = 'controlbuttongroup';
    public final const RESOURCE_CONTROLGROUP = 'controlgroup';
    public final const RESOURCE_DIVIDER = 'divider';
    public final const RESOURCE_FETCHMORE = 'fetchmore';
    public final const RESOURCE_HIDEIFEMPTY = 'hideifempty';
    public final const RESOURCE_FEEDBACKMESSAGE_INNER = 'feedbackmessage_inner';
    public final const RESOURCE_LATESTCOUNT = 'latestcount';
    public final const RESOURCE_LAYOUT_MAXHEIGHT = 'layout_maxheight';
    public final const RESOURCE_LAYOUT_CONTENT = 'layout_content';
    public final const RESOURCE_LAYOUT_LINKCONTENT = 'layout_linkcontent';
    public final const RESOURCE_LAYOUT_APPENDSCRIPT = 'layout_appendscript';
    public final const RESOURCE_LAYOUT_AUTHOR_CONTACT = 'layout_author_contact';
    public final const RESOURCE_LAYOUT_AUTHORCONTENT = 'layout_authorcontent';
    public final const RESOURCE_LAYOUT_CATEGORIES = 'layout_categories';
    public final const RESOURCE_LAYOUT_COMMENT = 'layout_comment';
    public final const RESOURCE_LAYOUT_EMBEDPREVIEW = 'layout_embedpreview';
    public final const RESOURCE_LAYOUT_INITJSDELAY = 'layout_initjsdelay';
    public final const RESOURCE_LAYOUT_FULLOBJECTTITLE = 'layout_fullobjecttitle';
    public final const RESOURCE_LAYOUT_FULLVIEW = 'layout_fullview';
    public final const RESOURCE_LAYOUT_FULLUSER = 'layout_fulluser';
    public final const RESOURCE_LAYOUT_LATESTCOUNTSCRIPT = 'layout_latestcountscript';
    public final const RESOURCE_LAYOUT_MARKER = 'layout_marker';
    public final const RESOURCE_LAYOUT_MENU_ANCHOR = 'layout_menu_anchor';
    public final const RESOURCE_LAYOUT_MENU_COLLAPSESEGMENTEDBUTTON = 'layout_menu_collapsesegmentedbutton';
    public final const RESOURCE_LAYOUT_MENU_DROPDOWN = 'layout_menu_dropdown';
    public final const RESOURCE_LAYOUT_MENU_DROPDOWNBUTTON = 'layout_menu_dropdownbutton';
    public final const RESOURCE_LAYOUT_MENU_INDENT = 'layout_menu_indent';
    public final const RESOURCE_LAYOUT_MENU_MULTITARGETINDENT = 'layout_menu_multitargetindent';
    public final const RESOURCE_LAYOUT_FEEDBACKMESSAGE = 'layout_feedbackmessage';
    public final const RESOURCE_LAYOUT_MULTIPLE = 'layout_multiple';
    public final const RESOURCE_LAYOUT_PAGETAB = 'layout_pagetab';
    public final const RESOURCE_LAYOUT_POPOVER = 'layout_popover';
    public final const RESOURCE_LAYOUT_POSTADDITIONAL_MULTILAYOUT_LABEL = 'layout_postadditional_multilayout_label';
    public final const RESOURCE_LAYOUT_POSTSTATUSDATE = 'layout_poststatusdate';
    public final const RESOURCE_LAYOUT_TAGINFO = 'layout_taginfo';
    public final const RESOURCE_LAYOUT_POSTTHUMB = 'layout_postthumb';
    public final const RESOURCE_LAYOUT_PREVIEWPOST = 'layout_previewpost';
    public final const RESOURCE_LAYOUT_PREVIEWUSER = 'layout_previewuser';
    public final const RESOURCE_LAYOUT_SCRIPTFRAME = 'layout_scriptframe';
    public final const RESOURCE_LAYOUT_SEGMENTEDBUTTON_LINK = 'layout_segmentedbutton_link';
    public final const RESOURCE_LAYOUT_STYLES = 'layout_styles';
    public final const RESOURCE_LAYOUT_SUBCOMPONENT = 'layout_subcomponent';
    public final const RESOURCE_LAYOUT_TAG = 'layout_tag';
    public final const RESOURCE_LAYOUT_USERPOSTINTERACTION = 'layout_userpostinteraction';
    public final const RESOURCE_LAYOUTPOST_AUTHORNAME = 'layoutpost_authorname';
    public final const RESOURCE_LAYOUTPOST_DATE = 'layoutpost_date';
    public final const RESOURCE_LAYOUTPOST_STATUS = 'layoutpost_status';
    public final const RESOURCE_LAYOUTPOST_TYPEAHEAD_COMPONENT = 'layoutpost_typeahead_component';
    public final const RESOURCE_LAYOUTPOST_CARD = 'layoutpost_card';
    public final const RESOURCE_LAYOUTSTATIC_TYPEAHEAD_COMPONENT = 'layoutstatic_typeahead_component';
    public final const RESOURCE_LAYOUTTAG_TYPEAHEAD_COMPONENT = 'layouttag_typeahead_component';
    public final const RESOURCE_LAYOUTTAG_MENTION_COMPONENT = 'layouttag_mention_component';
    public final const RESOURCE_LAYOUTUSER_MENTION_COMPONENT = 'layoutuser_mention_component';
    public final const RESOURCE_LAYOUTUSER_QUICKLINKS = 'layoutuser_quicklinks';
    public final const RESOURCE_LAYOUTUSER_TYPEAHEAD_COMPONENT = 'layoutuser_typeahead_component';
    public final const RESOURCE_LAYOUTUSER_CARD = 'layoutuser_card';
    public final const RESOURCE_LAYOUTCOMMENT_CARD = 'layoutcomment_card';
    public final const RESOURCE_MESSAGE = 'message';
    public final const RESOURCE_SCRIPT_APPENDCOMMENT = 'script_appendcomment';
    public final const RESOURCE_SCRIPT_LAZYLOADINGREMOVE = 'script_lazyloadingremove';
    public final const RESOURCE_LAYOUT_LAZYLOADINGSPINNER = 'layout_lazyloadingspinner';
    public final const RESOURCE_SCROLL = 'scroll';
    public final const RESOURCE_SCROLL_INNER = 'scroll_inner';
    public final const RESOURCE_SOCIALMEDIA = 'socialmedia';
    public final const RESOURCE_SOCIALMEDIA_ITEM = 'socialmedia_item';
    public final const RESOURCE_STATUS = 'status';
    public final const RESOURCE_SUBMENU = 'submenu';
    public final const RESOURCE_TABLE = 'table';
    public final const RESOURCE_TABLE_INNER = 'table_inner';
    public final const RESOURCE_VIEWCOMPONENT_BUTTON = 'viewcomponent_button';
    public final const RESOURCE_VIEWCOMPONENT_HEADER_COMMENTCLIPPED = 'viewcomponent_header_commentclipped';
    public final const RESOURCE_VIEWCOMPONENT_HEADER_COMMENTPOST = 'viewcomponent_header_commentpost';
    public final const RESOURCE_VIEWCOMPONENT_HEADER_POST = 'viewcomponent_header_post';
    public final const RESOURCE_VIEWCOMPONENT_HEADER_REPLYCOMMENT = 'viewcomponent_header_replycomment';
    public final const RESOURCE_VIEWCOMPONENT_HEADER_USER = 'viewcomponent_header_user';
    public final const RESOURCE_VIEWCOMPONENT_HEADER_TAG = 'viewcomponent_header_tag';
    public final const RESOURCE_WIDGET = 'widget';

    public function getResourcesToProcess()
    {
        return array(
            [self::class, self::RESOURCE_DATALOAD],
            [self::class, self::RESOURCE_BUTTONGROUP],
            [self::class, self::RESOURCE_BUTTON],
            [self::class, self::RESOURCE_BUTTONINNER],
            [self::class, self::RESOURCE_WINDOW],
            [self::class, self::RESOURCE_OFFCANVAS],
            [self::class, self::RESOURCE_HTMLCODE],
            [self::class, self::RESOURCE_SCRIPTCODE],
            [self::class, self::RESOURCE_STYLECODE],
            [self::class, self::RESOURCE_CONDITIONWRAPPER],
            [self::class, self::RESOURCE_CONTROL_ANCHOR],
            [self::class, self::RESOURCE_CONTROL_BUTTON],
            [self::class, self::RESOURCE_CONTROL_DROPDOWNBUTTON],
            [self::class, self::RESOURCE_CONTROL_RADIOBUTTON],
            [self::class, self::RESOURCE_CONTROLBUTTONGROUP],
            [self::class, self::RESOURCE_CONTROLGROUP],
            [self::class, self::RESOURCE_DIVIDER],
            [self::class, self::RESOURCE_FETCHMORE],
            [self::class, self::RESOURCE_HIDEIFEMPTY],
            [self::class, self::RESOURCE_LATESTCOUNT],
            [self::class, self::RESOURCE_LAYOUT_MAXHEIGHT],
            [self::class, self::RESOURCE_LAYOUT_CONTENT],
            [self::class, self::RESOURCE_LAYOUT_LINKCONTENT],
            [self::class, self::RESOURCE_LAYOUT_APPENDSCRIPT],
            [self::class, self::RESOURCE_LAYOUT_AUTHOR_CONTACT],
            [self::class, self::RESOURCE_LAYOUT_AUTHORCONTENT],
            [self::class, self::RESOURCE_LAYOUT_CATEGORIES],
            [self::class, self::RESOURCE_LAYOUT_COMMENT],
            [self::class, self::RESOURCE_LAYOUT_EMBEDPREVIEW],
            [self::class, self::RESOURCE_LAYOUT_INITJSDELAY],
            [self::class, self::RESOURCE_LAYOUT_FULLOBJECTTITLE],
            [self::class, self::RESOURCE_LAYOUT_FULLVIEW],
            [self::class, self::RESOURCE_LAYOUT_FULLUSER],
            [self::class, self::RESOURCE_LAYOUT_LATESTCOUNTSCRIPT],
            [self::class, self::RESOURCE_LAYOUT_MARKER],
            [self::class, self::RESOURCE_LAYOUT_MENU_ANCHOR],
            [self::class, self::RESOURCE_LAYOUT_MENU_COLLAPSESEGMENTEDBUTTON],
            [self::class, self::RESOURCE_LAYOUT_MENU_DROPDOWN],
            [self::class, self::RESOURCE_LAYOUT_MENU_DROPDOWNBUTTON],
            [self::class, self::RESOURCE_LAYOUT_MENU_INDENT],
            [self::class, self::RESOURCE_LAYOUT_MENU_MULTITARGETINDENT],
            [self::class, self::RESOURCE_LAYOUT_FEEDBACKMESSAGE],
            [self::class, self::RESOURCE_LAYOUT_MULTIPLE],
            [self::class, self::RESOURCE_LAYOUT_PAGETAB],
            [self::class, self::RESOURCE_LAYOUT_POPOVER],
            [self::class, self::RESOURCE_LAYOUT_POSTADDITIONAL_MULTILAYOUT_LABEL],
            [self::class, self::RESOURCE_LAYOUT_POSTSTATUSDATE],
            [self::class, self::RESOURCE_LAYOUT_TAGINFO],
            [self::class, self::RESOURCE_LAYOUT_POSTTHUMB],
            [self::class, self::RESOURCE_LAYOUT_PREVIEWPOST],
            [self::class, self::RESOURCE_LAYOUT_PREVIEWUSER],
            [self::class, self::RESOURCE_LAYOUT_SCRIPTFRAME],
            [self::class, self::RESOURCE_LAYOUT_SEGMENTEDBUTTON_LINK],
            [self::class, self::RESOURCE_LAYOUT_STYLES],
            [self::class, self::RESOURCE_LAYOUT_SUBCOMPONENT],
            [self::class, self::RESOURCE_LAYOUT_TAG],
            [self::class, self::RESOURCE_LAYOUT_USERPOSTINTERACTION],
            [self::class, self::RESOURCE_LAYOUTPOST_AUTHORNAME],
            [self::class, self::RESOURCE_LAYOUTPOST_DATE],
            [self::class, self::RESOURCE_LAYOUTPOST_STATUS],
            [self::class, self::RESOURCE_LAYOUTPOST_TYPEAHEAD_COMPONENT],
            [self::class, self::RESOURCE_LAYOUTPOST_CARD],
            [self::class, self::RESOURCE_LAYOUTSTATIC_TYPEAHEAD_COMPONENT],
            [self::class, self::RESOURCE_LAYOUTTAG_TYPEAHEAD_COMPONENT],
            [self::class, self::RESOURCE_LAYOUTTAG_MENTION_COMPONENT],
            [self::class, self::RESOURCE_LAYOUTUSER_MENTION_COMPONENT],
            [self::class, self::RESOURCE_LAYOUTUSER_QUICKLINKS],
            [self::class, self::RESOURCE_LAYOUTUSER_TYPEAHEAD_COMPONENT],
            [self::class, self::RESOURCE_LAYOUTUSER_CARD],
            [self::class, self::RESOURCE_LAYOUTCOMMENT_CARD],
            [self::class, self::RESOURCE_MESSAGE],
            [self::class, self::RESOURCE_FEEDBACKMESSAGE_INNER],
            // [self::class, self::RESOURCE_CHECKPOINTMESSAGE_INNER],
            [self::class, self::RESOURCE_SCRIPT_APPENDCOMMENT],
            [self::class, self::RESOURCE_SCRIPT_LAZYLOADINGREMOVE],
            [self::class, self::RESOURCE_LAYOUT_LAZYLOADINGSPINNER],
            [self::class, self::RESOURCE_SCROLL],
            [self::class, self::RESOURCE_SCROLL_INNER],
            [self::class, self::RESOURCE_SOCIALMEDIA],
            [self::class, self::RESOURCE_SOCIALMEDIA_ITEM],
            [self::class, self::RESOURCE_STATUS],
            [self::class, self::RESOURCE_SUBMENU],
            [self::class, self::RESOURCE_TABLE],
            [self::class, self::RESOURCE_TABLE_INNER],
            [self::class, self::RESOURCE_VIEWCOMPONENT_BUTTON],
            [self::class, self::RESOURCE_VIEWCOMPONENT_HEADER_COMMENTCLIPPED],
            [self::class, self::RESOURCE_VIEWCOMPONENT_HEADER_COMMENTPOST],
            [self::class, self::RESOURCE_VIEWCOMPONENT_HEADER_POST],
            [self::class, self::RESOURCE_VIEWCOMPONENT_HEADER_REPLYCOMMENT],
            [self::class, self::RESOURCE_VIEWCOMPONENT_HEADER_USER],
            [self::class, self::RESOURCE_VIEWCOMPONENT_HEADER_TAG],
            [self::class, self::RESOURCE_WIDGET],
        );
    }

    public function getTemplate(array $resource)
    {
        $templates = array(
            self::RESOURCE_DATALOAD => POP_TEMPLATE_DATALOAD,
            self::RESOURCE_BUTTONGROUP => POP_TEMPLATE_BUTTONGROUP,
            self::RESOURCE_BUTTON => POP_TEMPLATE_BUTTON,
            self::RESOURCE_BUTTONINNER => POP_TEMPLATE_BUTTONINNER,
            self::RESOURCE_WINDOW => POP_TEMPLATE_WINDOW,
            self::RESOURCE_OFFCANVAS => POP_TEMPLATE_OFFCANVAS,
            self::RESOURCE_HTMLCODE => POP_TEMPLATE_HTMLCODE,
            self::RESOURCE_SCRIPTCODE => POP_TEMPLATE_SCRIPTCODE,
            self::RESOURCE_STYLECODE => POP_TEMPLATE_STYLECODE,
            self::RESOURCE_CONDITIONWRAPPER => POP_TEMPLATE_CONDITIONWRAPPER,
            self::RESOURCE_CONTROL_ANCHOR => POP_TEMPLATE_CONTROL_ANCHOR,
            self::RESOURCE_CONTROL_BUTTON => POP_TEMPLATE_CONTROL_BUTTON,
            self::RESOURCE_CONTROL_DROPDOWNBUTTON => POP_TEMPLATE_CONTROL_DROPDOWNBUTTON,
            self::RESOURCE_CONTROL_RADIOBUTTON => POP_TEMPLATE_CONTROL_RADIOBUTTON,
            self::RESOURCE_CONTROLBUTTONGROUP => POP_TEMPLATE_CONTROLBUTTONGROUP,
            self::RESOURCE_CONTROLGROUP => POP_TEMPLATE_CONTROLGROUP,
            self::RESOURCE_DIVIDER => POP_TEMPLATE_DIVIDER,
            self::RESOURCE_FETCHMORE => POP_TEMPLATE_FETCHMORE,
            self::RESOURCE_HIDEIFEMPTY => POP_TEMPLATE_HIDEIFEMPTY,
            self::RESOURCE_FEEDBACKMESSAGE_INNER => POP_TEMPLATE_FEEDBACKMESSAGE_INNER,
            // self::RESOURCE_CHECKPOINTMESSAGE_INNER => POP_TEMPLATE_CHECKPOINTMESSAGE_INNER,
            self::RESOURCE_LATESTCOUNT => POP_TEMPLATE_LATESTCOUNT,
            self::RESOURCE_LAYOUT_MAXHEIGHT => POP_TEMPLATE_LAYOUT_MAXHEIGHT,
            self::RESOURCE_LAYOUT_CONTENT => POP_TEMPLATE_LAYOUT_CONTENT,
            self::RESOURCE_LAYOUT_LINKCONTENT => POP_TEMPLATE_LAYOUT_LINKCONTENT,
            self::RESOURCE_LAYOUT_APPENDSCRIPT => POP_TEMPLATE_LAYOUT_APPENDSCRIPT,
            self::RESOURCE_LAYOUT_AUTHOR_CONTACT => POP_TEMPLATE_LAYOUT_AUTHOR_CONTACT,
            self::RESOURCE_LAYOUT_AUTHORCONTENT => POP_TEMPLATE_LAYOUT_AUTHORCONTENT,
            self::RESOURCE_LAYOUT_CATEGORIES => POP_TEMPLATE_LAYOUT_CATEGORIES,
            self::RESOURCE_LAYOUT_COMMENT => POP_TEMPLATE_LAYOUT_COMMENT,
            self::RESOURCE_LAYOUT_EMBEDPREVIEW => POP_TEMPLATE_LAYOUT_EMBEDPREVIEW,
            self::RESOURCE_LAYOUT_INITJSDELAY => POP_TEMPLATE_LAYOUT_INITJSDELAY,
            self::RESOURCE_LAYOUT_FULLOBJECTTITLE => POP_TEMPLATE_LAYOUT_FULLOBJECTTITLE,
            self::RESOURCE_LAYOUT_FULLVIEW => POP_TEMPLATE_LAYOUT_FULLVIEW,
            self::RESOURCE_LAYOUT_FULLUSER => POP_TEMPLATE_LAYOUT_FULLUSER,
            self::RESOURCE_LAYOUT_LATESTCOUNTSCRIPT => POP_TEMPLATE_LAYOUT_LATESTCOUNTSCRIPT,
            self::RESOURCE_LAYOUT_MARKER => POP_TEMPLATE_LAYOUT_MARKER,
            self::RESOURCE_LAYOUT_MENU_ANCHOR => POP_TEMPLATE_LAYOUT_MENU_ANCHOR,
            self::RESOURCE_LAYOUT_MENU_COLLAPSESEGMENTEDBUTTON => POP_TEMPLATE_LAYOUT_MENU_COLLAPSESEGMENTEDBUTTON,
            self::RESOURCE_LAYOUT_MENU_DROPDOWN => POP_TEMPLATE_LAYOUT_MENU_DROPDOWN,
            self::RESOURCE_LAYOUT_MENU_DROPDOWNBUTTON => POP_TEMPLATE_LAYOUT_MENU_DROPDOWNBUTTON,
            self::RESOURCE_LAYOUT_MENU_INDENT => POP_TEMPLATE_LAYOUT_MENU_INDENT,
            self::RESOURCE_LAYOUT_MENU_MULTITARGETINDENT => POP_TEMPLATE_LAYOUT_MENU_MULTITARGETINDENT,
            self::RESOURCE_LAYOUT_FEEDBACKMESSAGE => POP_TEMPLATE_LAYOUT_FEEDBACKMESSAGE,
            self::RESOURCE_LAYOUT_MULTIPLE => POP_TEMPLATE_LAYOUT_MULTIPLE,
            self::RESOURCE_LAYOUT_PAGETAB => POP_TEMPLATE_LAYOUT_PAGETAB,
            self::RESOURCE_LAYOUT_POPOVER => POP_TEMPLATE_LAYOUT_POPOVER,
            self::RESOURCE_LAYOUT_POSTADDITIONAL_MULTILAYOUT_LABEL => POP_TEMPLATE_LAYOUT_POSTADDITIONAL_MULTILAYOUT_LABEL,
            self::RESOURCE_LAYOUT_POSTSTATUSDATE => POP_TEMPLATE_LAYOUT_POSTSTATUSDATE,
            self::RESOURCE_LAYOUT_TAGINFO => POP_TEMPLATE_LAYOUT_TAGINFO,
            self::RESOURCE_LAYOUT_POSTTHUMB => POP_TEMPLATE_LAYOUT_POSTTHUMB,
            self::RESOURCE_LAYOUT_PREVIEWPOST => POP_TEMPLATE_LAYOUT_PREVIEWPOST,
            self::RESOURCE_LAYOUT_PREVIEWUSER => POP_TEMPLATE_LAYOUT_PREVIEWUSER,
            self::RESOURCE_LAYOUT_SCRIPTFRAME => POP_TEMPLATE_LAYOUT_SCRIPTFRAME,
            self::RESOURCE_LAYOUT_SEGMENTEDBUTTON_LINK => POP_TEMPLATE_LAYOUT_SEGMENTEDBUTTON_LINK,
            self::RESOURCE_LAYOUT_STYLES => POP_TEMPLATE_LAYOUT_STYLES,
            self::RESOURCE_LAYOUT_SUBCOMPONENT => POP_TEMPLATE_LAYOUT_SUBCOMPONENT,
            self::RESOURCE_LAYOUT_TAG => POP_TEMPLATE_LAYOUT_TAG,
            self::RESOURCE_LAYOUT_USERPOSTINTERACTION => POP_TEMPLATE_LAYOUT_USERPOSTINTERACTION,
            self::RESOURCE_LAYOUTPOST_AUTHORNAME => POP_TEMPLATE_LAYOUTPOST_AUTHORNAME,
            self::RESOURCE_LAYOUTPOST_DATE => POP_TEMPLATE_LAYOUTPOST_DATE,
            self::RESOURCE_LAYOUTPOST_STATUS => POP_TEMPLATE_LAYOUTPOST_STATUS,
            self::RESOURCE_LAYOUTPOST_TYPEAHEAD_COMPONENT => POP_TEMPLATE_LAYOUTPOST_TYPEAHEAD_COMPONENT,
            self::RESOURCE_LAYOUTPOST_CARD => POP_TEMPLATE_LAYOUTPOST_CARD,
            self::RESOURCE_LAYOUTSTATIC_TYPEAHEAD_COMPONENT => POP_TEMPLATE_LAYOUTSTATIC_TYPEAHEAD_COMPONENT,
            self::RESOURCE_LAYOUTTAG_TYPEAHEAD_COMPONENT => POP_TEMPLATE_LAYOUTTAG_TYPEAHEAD_COMPONENT,
            self::RESOURCE_LAYOUTTAG_MENTION_COMPONENT => POP_TEMPLATE_LAYOUTTAG_MENTION_COMPONENT,
            self::RESOURCE_LAYOUTUSER_MENTION_COMPONENT => POP_TEMPLATE_LAYOUTUSER_MENTION_COMPONENT,
            self::RESOURCE_LAYOUTUSER_QUICKLINKS => POP_TEMPLATE_LAYOUTUSER_QUICKLINKS,
            self::RESOURCE_LAYOUTUSER_TYPEAHEAD_COMPONENT => POP_TEMPLATE_LAYOUTUSER_TYPEAHEAD_COMPONENT,
            self::RESOURCE_LAYOUTUSER_CARD => POP_TEMPLATE_LAYOUTUSER_CARD,
            self::RESOURCE_LAYOUTCOMMENT_CARD => POP_TEMPLATE_LAYOUTCOMMENT_CARD,
            self::RESOURCE_MESSAGE => POP_TEMPLATE_MESSAGE,
            self::RESOURCE_SCRIPT_APPENDCOMMENT => POP_TEMPLATE_SCRIPT_APPENDCOMMENT,
            self::RESOURCE_SCRIPT_LAZYLOADINGREMOVE => POP_TEMPLATE_SCRIPT_LAZYLOADINGREMOVE,
            self::RESOURCE_LAYOUT_LAZYLOADINGSPINNER => POP_TEMPLATE_LAYOUT_LAZYLOADINGSPINNER,
            self::RESOURCE_SCROLL => POP_TEMPLATE_SCROLL,
            self::RESOURCE_SCROLL_INNER => POP_TEMPLATE_SCROLL_INNER,
            self::RESOURCE_SOCIALMEDIA => POP_TEMPLATE_SOCIALMEDIA,
            self::RESOURCE_SOCIALMEDIA_ITEM => POP_TEMPLATE_SOCIALMEDIA_ITEM,
            self::RESOURCE_STATUS => POP_TEMPLATE_STATUS,
            self::RESOURCE_SUBMENU => POP_TEMPLATE_SUBMENU,
            self::RESOURCE_TABLE => POP_TEMPLATE_TABLE,
            self::RESOURCE_TABLE_INNER => POP_TEMPLATE_TABLE_INNER,
            self::RESOURCE_VIEWCOMPONENT_BUTTON => POP_TEMPLATE_VIEWCOMPONENT_BUTTON,
            self::RESOURCE_VIEWCOMPONENT_HEADER_COMMENTCLIPPED => POP_TEMPLATE_VIEWCOMPONENT_HEADER_COMMENTCLIPPED,
            self::RESOURCE_VIEWCOMPONENT_HEADER_COMMENTPOST => POP_TEMPLATE_VIEWCOMPONENT_HEADER_COMMENTPOST,
            self::RESOURCE_VIEWCOMPONENT_HEADER_POST => POP_TEMPLATE_VIEWCOMPONENT_HEADER_POST,
            self::RESOURCE_VIEWCOMPONENT_HEADER_REPLYCOMMENT => POP_TEMPLATE_VIEWCOMPONENT_HEADER_REPLYCOMMENT,
            self::RESOURCE_VIEWCOMPONENT_HEADER_USER => POP_TEMPLATE_VIEWCOMPONENT_HEADER_USER,
            self::RESOURCE_VIEWCOMPONENT_HEADER_TAG => POP_TEMPLATE_VIEWCOMPONENT_HEADER_TAG,
            self::RESOURCE_WIDGET => POP_TEMPLATE_WIDGET,
        );
        return $templates[$resource[1]];
    }

    public function getVersion(array $resource)
    {
        return POP_MASTERCOLLECTIONWEBPLATFORM_VERSION;
    }

    public function getPath(array $resource)
    {
        return POP_MASTERCOLLECTIONWEBPLATFORM_URL.'/js/dist/templates';
    }

    public function getDir(array $resource)
    {
        return POP_MASTERCOLLECTIONWEBPLATFORM_DIR.'/js/dist/templates';
    }

    public function getGlobalscopeMethodCalls(array $resource)
    {
        switch ($resource[1]) {
            case self::RESOURCE_LAYOUT_INITJSDELAY:
                return array(
                    'Manager' => array(
                        'getBlock',
                    ),
                );
        }

        return parent::getGlobalscopeMethodCalls($resource);
    }

    public function getDependencies(array $resource)
    {
        $dependencies = parent::getDependencies($resource);

        switch ($resource[1]) {
            case self::RESOURCE_LAYOUT_LATESTCOUNTSCRIPT:
                $dependencies[] = [PoP_CoreProcessors_HandlebarsHelpersJSResourceLoaderProcessor::class, PoP_CoreProcessors_HandlebarsHelpersJSResourceLoaderProcessor::RESOURCE_HANDLEBARSHELPERS_LATESTCOUNT];
                break;

            case self::RESOURCE_LAYOUT_FEEDBACKMESSAGE:
                $dependencies[] = [PoP_CoreProcessors_HandlebarsHelpersJSResourceLoaderProcessor::class, PoP_CoreProcessors_HandlebarsHelpersJSResourceLoaderProcessor::RESOURCE_HANDLEBARSHELPERS_FEEDBACKMESSAGE];
                break;

            case self::RESOURCE_LAYOUT_MULTIPLE:
            case self::RESOURCE_LAYOUT_POSTADDITIONAL_MULTILAYOUT_LABEL:
                $dependencies[] = [PoP_CoreProcessors_HandlebarsHelpersJSResourceLoaderProcessor::class, PoP_CoreProcessors_HandlebarsHelpersJSResourceLoaderProcessor::RESOURCE_HANDLEBARSHELPERS_MULTILAYOUT];
                break;

            case self::RESOURCE_BUTTON:
                $dependencies[] = [PoP_CoreProcessors_HandlebarsHelpersJSResourceLoaderProcessor::class, PoP_CoreProcessors_HandlebarsHelpersJSResourceLoaderProcessor::RESOURCE_HANDLEBARSHELPERS_OPERATORS];
                break;

            case self::RESOURCE_BUTTONGROUP:
            case self::RESOURCE_SUBMENU:
            case self::RESOURCE_LAYOUT_MENU_COLLAPSESEGMENTEDBUTTON:
            case self::RESOURCE_LAYOUT_APPENDSCRIPT:
            case self::RESOURCE_LAYOUT_MENU_ANCHOR:
            case self::RESOURCE_LAYOUT_MENU_DROPDOWN:
            case self::RESOURCE_LAYOUT_MENU_DROPDOWNBUTTON:
            case self::RESOURCE_LAYOUT_MENU_INDENT:
            case self::RESOURCE_LAYOUT_MENU_MULTITARGETINDENT:
            case self::RESOURCE_LAYOUT_FULLVIEW:
            case self::RESOURCE_LAYOUT_FULLUSER:
            case self::RESOURCE_LAYOUT_PREVIEWPOST:
            case self::RESOURCE_SCRIPT_APPENDCOMMENT:
                $dependencies[] = [PoP_CoreProcessors_HandlebarsHelpersJSResourceLoaderProcessor::class, PoP_CoreProcessors_HandlebarsHelpersJSResourceLoaderProcessor::RESOURCE_HANDLEBARSHELPERS_COMPARE];
                break;

            case self::RESOURCE_LAYOUT_AUTHORCONTENT:
            case self::RESOURCE_LAYOUT_CONTENT:
                $dependencies[] = [PoP_CoreProcessors_HandlebarsHelpersJSResourceLoaderProcessor::class, PoP_CoreProcessors_HandlebarsHelpersJSResourceLoaderProcessor::RESOURCE_HANDLEBARSHELPERS_SHOWMORE];
                break;

            case self::RESOURCE_LAYOUTPOST_STATUS:
                $dependencies[] = [PoP_CoreProcessors_HandlebarsHelpersJSResourceLoaderProcessor::class, PoP_CoreProcessors_HandlebarsHelpersJSResourceLoaderProcessor::RESOURCE_HANDLEBARSHELPERS_STATUS];
                break;

            case self::RESOURCE_LAYOUT_CATEGORIES:
                $dependencies[] = [PoP_CoreProcessors_HandlebarsHelpersJSResourceLoaderProcessor::class, PoP_CoreProcessors_HandlebarsHelpersJSResourceLoaderProcessor::RESOURCE_HANDLEBARSHELPERS_LABELS];
                break;

            case self::RESOURCE_LAYOUT_SUBCOMPONENT:
                $dependencies[] = [PoP_CoreProcessors_HandlebarsHelpersJSResourceLoaderProcessor::class, PoP_CoreProcessors_HandlebarsHelpersJSResourceLoaderProcessor::RESOURCE_HANDLEBARSHELPERS_ARRAYS];
                break;

            case self::RESOURCE_SCROLL_INNER:
                $dependencies[] = [PoP_CoreProcessors_HandlebarsHelpersJSResourceLoaderProcessor::class, PoP_CoreProcessors_HandlebarsHelpersJSResourceLoaderProcessor::RESOURCE_HANDLEBARSHELPERS_MOD];
                break;
        }

        switch ($resource[1]) {
            case self::RESOURCE_LAYOUT_PREVIEWPOST:
                $dependencies[] = [PoP_CoreProcessors_HandlebarsHelpersJSResourceLoaderProcessor::class, PoP_CoreProcessors_HandlebarsHelpersJSResourceLoaderProcessor::RESOURCE_HANDLEBARSHELPERS_DATE];
                break;
        }

        return $dependencies;
    }
}


