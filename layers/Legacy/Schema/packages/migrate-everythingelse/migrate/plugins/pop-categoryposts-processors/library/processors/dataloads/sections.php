<?php
use PoPCMSSchema\Posts\TypeResolvers\ObjectType\PostObjectTypeResolver;
use PoPCMSSchema\Tags\Routing\RequestNature as TagRequestNature;
use PoPCMSSchema\Users\Routing\RequestNature as UserRequestNature;

class CPP_Module_Processor_SectionDataloads extends PoP_Module_Processor_SectionDataloadsBase
{
    public final const MODULE_DATALOAD_CATEGORYPOSTS00_TYPEAHEAD = 'dataload-categoryposts00-typeahead';
    public final const MODULE_DATALOAD_CATEGORYPOSTS01_TYPEAHEAD = 'dataload-categoryposts01-typeahead';
    public final const MODULE_DATALOAD_CATEGORYPOSTS02_TYPEAHEAD = 'dataload-categoryposts02-typeahead';
    public final const MODULE_DATALOAD_CATEGORYPOSTS03_TYPEAHEAD = 'dataload-categoryposts03-typeahead';
    public final const MODULE_DATALOAD_CATEGORYPOSTS04_TYPEAHEAD = 'dataload-categoryposts04-typeahead';
    public final const MODULE_DATALOAD_CATEGORYPOSTS05_TYPEAHEAD = 'dataload-categoryposts05-typeahead';
    public final const MODULE_DATALOAD_CATEGORYPOSTS06_TYPEAHEAD = 'dataload-categoryposts06-typeahead';
    public final const MODULE_DATALOAD_CATEGORYPOSTS07_TYPEAHEAD = 'dataload-categoryposts07-typeahead';
    public final const MODULE_DATALOAD_CATEGORYPOSTS08_TYPEAHEAD = 'dataload-categoryposts08-typeahead';
    public final const MODULE_DATALOAD_CATEGORYPOSTS09_TYPEAHEAD = 'dataload-categoryposts09-typeahead';
    public final const MODULE_DATALOAD_CATEGORYPOSTS10_TYPEAHEAD = 'dataload-categoryposts10-typeahead';
    public final const MODULE_DATALOAD_CATEGORYPOSTS11_TYPEAHEAD = 'dataload-categoryposts11-typeahead';
    public final const MODULE_DATALOAD_CATEGORYPOSTS12_TYPEAHEAD = 'dataload-categoryposts12-typeahead';
    public final const MODULE_DATALOAD_CATEGORYPOSTS13_TYPEAHEAD = 'dataload-categoryposts13-typeahead';
    public final const MODULE_DATALOAD_CATEGORYPOSTS14_TYPEAHEAD = 'dataload-categoryposts14-typeahead';
    public final const MODULE_DATALOAD_CATEGORYPOSTS15_TYPEAHEAD = 'dataload-categoryposts15-typeahead';
    public final const MODULE_DATALOAD_CATEGORYPOSTS16_TYPEAHEAD = 'dataload-categoryposts16-typeahead';
    public final const MODULE_DATALOAD_CATEGORYPOSTS17_TYPEAHEAD = 'dataload-categoryposts17-typeahead';
    public final const MODULE_DATALOAD_CATEGORYPOSTS18_TYPEAHEAD = 'dataload-categoryposts18-typeahead';
    public final const MODULE_DATALOAD_CATEGORYPOSTS19_TYPEAHEAD = 'dataload-categoryposts19-typeahead';
    public final const MODULE_DATALOAD_CATEGORYPOSTS00_SCROLL_NAVIGATOR = 'dataload-categoryposts00-scroll-navigator';
    public final const MODULE_DATALOAD_CATEGORYPOSTS01_SCROLL_NAVIGATOR = 'dataload-categoryposts01-scroll-navigator';
    public final const MODULE_DATALOAD_CATEGORYPOSTS02_SCROLL_NAVIGATOR = 'dataload-categoryposts02-scroll-navigator';
    public final const MODULE_DATALOAD_CATEGORYPOSTS03_SCROLL_NAVIGATOR = 'dataload-categoryposts03-scroll-navigator';
    public final const MODULE_DATALOAD_CATEGORYPOSTS04_SCROLL_NAVIGATOR = 'dataload-categoryposts04-scroll-navigator';
    public final const MODULE_DATALOAD_CATEGORYPOSTS05_SCROLL_NAVIGATOR = 'dataload-categoryposts05-scroll-navigator';
    public final const MODULE_DATALOAD_CATEGORYPOSTS06_SCROLL_NAVIGATOR = 'dataload-categoryposts06-scroll-navigator';
    public final const MODULE_DATALOAD_CATEGORYPOSTS07_SCROLL_NAVIGATOR = 'dataload-categoryposts07-scroll-navigator';
    public final const MODULE_DATALOAD_CATEGORYPOSTS08_SCROLL_NAVIGATOR = 'dataload-categoryposts08-scroll-navigator';
    public final const MODULE_DATALOAD_CATEGORYPOSTS09_SCROLL_NAVIGATOR = 'dataload-categoryposts09-scroll-navigator';
    public final const MODULE_DATALOAD_CATEGORYPOSTS10_SCROLL_NAVIGATOR = 'dataload-categoryposts10-scroll-navigator';
    public final const MODULE_DATALOAD_CATEGORYPOSTS11_SCROLL_NAVIGATOR = 'dataload-categoryposts11-scroll-navigator';
    public final const MODULE_DATALOAD_CATEGORYPOSTS12_SCROLL_NAVIGATOR = 'dataload-categoryposts12-scroll-navigator';
    public final const MODULE_DATALOAD_CATEGORYPOSTS13_SCROLL_NAVIGATOR = 'dataload-categoryposts13-scroll-navigator';
    public final const MODULE_DATALOAD_CATEGORYPOSTS14_SCROLL_NAVIGATOR = 'dataload-categoryposts14-scroll-navigator';
    public final const MODULE_DATALOAD_CATEGORYPOSTS15_SCROLL_NAVIGATOR = 'dataload-categoryposts15-scroll-navigator';
    public final const MODULE_DATALOAD_CATEGORYPOSTS16_SCROLL_NAVIGATOR = 'dataload-categoryposts16-scroll-navigator';
    public final const MODULE_DATALOAD_CATEGORYPOSTS17_SCROLL_NAVIGATOR = 'dataload-categoryposts17-scroll-navigator';
    public final const MODULE_DATALOAD_CATEGORYPOSTS18_SCROLL_NAVIGATOR = 'dataload-categoryposts18-scroll-navigator';
    public final const MODULE_DATALOAD_CATEGORYPOSTS19_SCROLL_NAVIGATOR = 'dataload-categoryposts19-scroll-navigator';
    public final const MODULE_DATALOAD_CATEGORYPOSTS00_SCROLL_ADDONS = 'dataload-categoryposts00-scroll-addons';
    public final const MODULE_DATALOAD_CATEGORYPOSTS01_SCROLL_ADDONS = 'dataload-categoryposts01-scroll-addons';
    public final const MODULE_DATALOAD_CATEGORYPOSTS02_SCROLL_ADDONS = 'dataload-categoryposts02-scroll-addons';
    public final const MODULE_DATALOAD_CATEGORYPOSTS03_SCROLL_ADDONS = 'dataload-categoryposts03-scroll-addons';
    public final const MODULE_DATALOAD_CATEGORYPOSTS04_SCROLL_ADDONS = 'dataload-categoryposts04-scroll-addons';
    public final const MODULE_DATALOAD_CATEGORYPOSTS05_SCROLL_ADDONS = 'dataload-categoryposts05-scroll-addons';
    public final const MODULE_DATALOAD_CATEGORYPOSTS06_SCROLL_ADDONS = 'dataload-categoryposts06-scroll-addons';
    public final const MODULE_DATALOAD_CATEGORYPOSTS07_SCROLL_ADDONS = 'dataload-categoryposts07-scroll-addons';
    public final const MODULE_DATALOAD_CATEGORYPOSTS08_SCROLL_ADDONS = 'dataload-categoryposts08-scroll-addons';
    public final const MODULE_DATALOAD_CATEGORYPOSTS09_SCROLL_ADDONS = 'dataload-categoryposts09-scroll-addons';
    public final const MODULE_DATALOAD_CATEGORYPOSTS10_SCROLL_ADDONS = 'dataload-categoryposts10-scroll-addons';
    public final const MODULE_DATALOAD_CATEGORYPOSTS11_SCROLL_ADDONS = 'dataload-categoryposts11-scroll-addons';
    public final const MODULE_DATALOAD_CATEGORYPOSTS12_SCROLL_ADDONS = 'dataload-categoryposts12-scroll-addons';
    public final const MODULE_DATALOAD_CATEGORYPOSTS13_SCROLL_ADDONS = 'dataload-categoryposts13-scroll-addons';
    public final const MODULE_DATALOAD_CATEGORYPOSTS14_SCROLL_ADDONS = 'dataload-categoryposts14-scroll-addons';
    public final const MODULE_DATALOAD_CATEGORYPOSTS15_SCROLL_ADDONS = 'dataload-categoryposts15-scroll-addons';
    public final const MODULE_DATALOAD_CATEGORYPOSTS16_SCROLL_ADDONS = 'dataload-categoryposts16-scroll-addons';
    public final const MODULE_DATALOAD_CATEGORYPOSTS17_SCROLL_ADDONS = 'dataload-categoryposts17-scroll-addons';
    public final const MODULE_DATALOAD_CATEGORYPOSTS18_SCROLL_ADDONS = 'dataload-categoryposts18-scroll-addons';
    public final const MODULE_DATALOAD_CATEGORYPOSTS19_SCROLL_ADDONS = 'dataload-categoryposts19-scroll-addons';
    public final const MODULE_DATALOAD_CATEGORYPOSTS00_SCROLL_DETAILS = 'dataload-categoryposts00-scroll-details';
    public final const MODULE_DATALOAD_CATEGORYPOSTS01_SCROLL_DETAILS = 'dataload-categoryposts01-scroll-details';
    public final const MODULE_DATALOAD_CATEGORYPOSTS02_SCROLL_DETAILS = 'dataload-categoryposts02-scroll-details';
    public final const MODULE_DATALOAD_CATEGORYPOSTS03_SCROLL_DETAILS = 'dataload-categoryposts03-scroll-details';
    public final const MODULE_DATALOAD_CATEGORYPOSTS04_SCROLL_DETAILS = 'dataload-categoryposts04-scroll-details';
    public final const MODULE_DATALOAD_CATEGORYPOSTS05_SCROLL_DETAILS = 'dataload-categoryposts05-scroll-details';
    public final const MODULE_DATALOAD_CATEGORYPOSTS06_SCROLL_DETAILS = 'dataload-categoryposts06-scroll-details';
    public final const MODULE_DATALOAD_CATEGORYPOSTS07_SCROLL_DETAILS = 'dataload-categoryposts07-scroll-details';
    public final const MODULE_DATALOAD_CATEGORYPOSTS08_SCROLL_DETAILS = 'dataload-categoryposts08-scroll-details';
    public final const MODULE_DATALOAD_CATEGORYPOSTS09_SCROLL_DETAILS = 'dataload-categoryposts09-scroll-details';
    public final const MODULE_DATALOAD_CATEGORYPOSTS10_SCROLL_DETAILS = 'dataload-categoryposts10-scroll-details';
    public final const MODULE_DATALOAD_CATEGORYPOSTS11_SCROLL_DETAILS = 'dataload-categoryposts11-scroll-details';
    public final const MODULE_DATALOAD_CATEGORYPOSTS12_SCROLL_DETAILS = 'dataload-categoryposts12-scroll-details';
    public final const MODULE_DATALOAD_CATEGORYPOSTS13_SCROLL_DETAILS = 'dataload-categoryposts13-scroll-details';
    public final const MODULE_DATALOAD_CATEGORYPOSTS14_SCROLL_DETAILS = 'dataload-categoryposts14-scroll-details';
    public final const MODULE_DATALOAD_CATEGORYPOSTS15_SCROLL_DETAILS = 'dataload-categoryposts15-scroll-details';
    public final const MODULE_DATALOAD_CATEGORYPOSTS16_SCROLL_DETAILS = 'dataload-categoryposts16-scroll-details';
    public final const MODULE_DATALOAD_CATEGORYPOSTS17_SCROLL_DETAILS = 'dataload-categoryposts17-scroll-details';
    public final const MODULE_DATALOAD_CATEGORYPOSTS18_SCROLL_DETAILS = 'dataload-categoryposts18-scroll-details';
    public final const MODULE_DATALOAD_CATEGORYPOSTS19_SCROLL_DETAILS = 'dataload-categoryposts19-scroll-details';
    public final const MODULE_DATALOAD_AUTHORCATEGORYPOSTS00_SCROLL_DETAILS = 'dataload-authorcategoryposts00-scroll-details';
    public final const MODULE_DATALOAD_AUTHORCATEGORYPOSTS01_SCROLL_DETAILS = 'dataload-authorcategoryposts01-scroll-details';
    public final const MODULE_DATALOAD_AUTHORCATEGORYPOSTS02_SCROLL_DETAILS = 'dataload-authorcategoryposts02-scroll-details';
    public final const MODULE_DATALOAD_AUTHORCATEGORYPOSTS03_SCROLL_DETAILS = 'dataload-authorcategoryposts03-scroll-details';
    public final const MODULE_DATALOAD_AUTHORCATEGORYPOSTS04_SCROLL_DETAILS = 'dataload-authorcategoryposts04-scroll-details';
    public final const MODULE_DATALOAD_AUTHORCATEGORYPOSTS05_SCROLL_DETAILS = 'dataload-authorcategoryposts05-scroll-details';
    public final const MODULE_DATALOAD_AUTHORCATEGORYPOSTS06_SCROLL_DETAILS = 'dataload-authorcategoryposts06-scroll-details';
    public final const MODULE_DATALOAD_AUTHORCATEGORYPOSTS07_SCROLL_DETAILS = 'dataload-authorcategoryposts07-scroll-details';
    public final const MODULE_DATALOAD_AUTHORCATEGORYPOSTS08_SCROLL_DETAILS = 'dataload-authorcategoryposts08-scroll-details';
    public final const MODULE_DATALOAD_AUTHORCATEGORYPOSTS09_SCROLL_DETAILS = 'dataload-authorcategoryposts09-scroll-details';
    public final const MODULE_DATALOAD_AUTHORCATEGORYPOSTS10_SCROLL_DETAILS = 'dataload-authorcategoryposts10-scroll-details';
    public final const MODULE_DATALOAD_AUTHORCATEGORYPOSTS11_SCROLL_DETAILS = 'dataload-authorcategoryposts11-scroll-details';
    public final const MODULE_DATALOAD_AUTHORCATEGORYPOSTS12_SCROLL_DETAILS = 'dataload-authorcategoryposts12-scroll-details';
    public final const MODULE_DATALOAD_AUTHORCATEGORYPOSTS13_SCROLL_DETAILS = 'dataload-authorcategoryposts13-scroll-details';
    public final const MODULE_DATALOAD_AUTHORCATEGORYPOSTS14_SCROLL_DETAILS = 'dataload-authorcategoryposts14-scroll-details';
    public final const MODULE_DATALOAD_AUTHORCATEGORYPOSTS15_SCROLL_DETAILS = 'dataload-authorcategoryposts15-scroll-details';
    public final const MODULE_DATALOAD_AUTHORCATEGORYPOSTS16_SCROLL_DETAILS = 'dataload-authorcategoryposts16-scroll-details';
    public final const MODULE_DATALOAD_AUTHORCATEGORYPOSTS17_SCROLL_DETAILS = 'dataload-authorcategoryposts17-scroll-details';
    public final const MODULE_DATALOAD_AUTHORCATEGORYPOSTS18_SCROLL_DETAILS = 'dataload-authorcategoryposts18-scroll-details';
    public final const MODULE_DATALOAD_AUTHORCATEGORYPOSTS19_SCROLL_DETAILS = 'dataload-authorcategoryposts19-scroll-details';
    public final const MODULE_DATALOAD_TAGCATEGORYPOSTS00_SCROLL_DETAILS = 'dataload-tagcategoryposts00-scroll-details';
    public final const MODULE_DATALOAD_TAGCATEGORYPOSTS01_SCROLL_DETAILS = 'dataload-tagcategoryposts01-scroll-details';
    public final const MODULE_DATALOAD_TAGCATEGORYPOSTS02_SCROLL_DETAILS = 'dataload-tagcategoryposts02-scroll-details';
    public final const MODULE_DATALOAD_TAGCATEGORYPOSTS03_SCROLL_DETAILS = 'dataload-tagcategoryposts03-scroll-details';
    public final const MODULE_DATALOAD_TAGCATEGORYPOSTS04_SCROLL_DETAILS = 'dataload-tagcategoryposts04-scroll-details';
    public final const MODULE_DATALOAD_TAGCATEGORYPOSTS05_SCROLL_DETAILS = 'dataload-tagcategoryposts05-scroll-details';
    public final const MODULE_DATALOAD_TAGCATEGORYPOSTS06_SCROLL_DETAILS = 'dataload-tagcategoryposts06-scroll-details';
    public final const MODULE_DATALOAD_TAGCATEGORYPOSTS07_SCROLL_DETAILS = 'dataload-tagcategoryposts07-scroll-details';
    public final const MODULE_DATALOAD_TAGCATEGORYPOSTS08_SCROLL_DETAILS = 'dataload-tagcategoryposts08-scroll-details';
    public final const MODULE_DATALOAD_TAGCATEGORYPOSTS09_SCROLL_DETAILS = 'dataload-tagcategoryposts09-scroll-details';
    public final const MODULE_DATALOAD_TAGCATEGORYPOSTS10_SCROLL_DETAILS = 'dataload-tagcategoryposts10-scroll-details';
    public final const MODULE_DATALOAD_TAGCATEGORYPOSTS11_SCROLL_DETAILS = 'dataload-tagcategoryposts11-scroll-details';
    public final const MODULE_DATALOAD_TAGCATEGORYPOSTS12_SCROLL_DETAILS = 'dataload-tagcategoryposts12-scroll-details';
    public final const MODULE_DATALOAD_TAGCATEGORYPOSTS13_SCROLL_DETAILS = 'dataload-tagcategoryposts13-scroll-details';
    public final const MODULE_DATALOAD_TAGCATEGORYPOSTS14_SCROLL_DETAILS = 'dataload-tagcategoryposts14-scroll-details';
    public final const MODULE_DATALOAD_TAGCATEGORYPOSTS15_SCROLL_DETAILS = 'dataload-tagcategoryposts15-scroll-details';
    public final const MODULE_DATALOAD_TAGCATEGORYPOSTS16_SCROLL_DETAILS = 'dataload-tagcategoryposts16-scroll-details';
    public final const MODULE_DATALOAD_TAGCATEGORYPOSTS17_SCROLL_DETAILS = 'dataload-tagcategoryposts17-scroll-details';
    public final const MODULE_DATALOAD_TAGCATEGORYPOSTS18_SCROLL_DETAILS = 'dataload-tagcategoryposts18-scroll-details';
    public final const MODULE_DATALOAD_TAGCATEGORYPOSTS19_SCROLL_DETAILS = 'dataload-tagcategoryposts19-scroll-details';
    public final const MODULE_DATALOAD_CATEGORYPOSTS00_SCROLL_SIMPLEVIEW = 'dataload-categoryposts00-scroll-simpleview';
    public final const MODULE_DATALOAD_CATEGORYPOSTS01_SCROLL_SIMPLEVIEW = 'dataload-categoryposts01-scroll-simpleview';
    public final const MODULE_DATALOAD_CATEGORYPOSTS02_SCROLL_SIMPLEVIEW = 'dataload-categoryposts02-scroll-simpleview';
    public final const MODULE_DATALOAD_CATEGORYPOSTS03_SCROLL_SIMPLEVIEW = 'dataload-categoryposts03-scroll-simpleview';
    public final const MODULE_DATALOAD_CATEGORYPOSTS04_SCROLL_SIMPLEVIEW = 'dataload-categoryposts04-scroll-simpleview';
    public final const MODULE_DATALOAD_CATEGORYPOSTS05_SCROLL_SIMPLEVIEW = 'dataload-categoryposts05-scroll-simpleview';
    public final const MODULE_DATALOAD_CATEGORYPOSTS06_SCROLL_SIMPLEVIEW = 'dataload-categoryposts06-scroll-simpleview';
    public final const MODULE_DATALOAD_CATEGORYPOSTS07_SCROLL_SIMPLEVIEW = 'dataload-categoryposts07-scroll-simpleview';
    public final const MODULE_DATALOAD_CATEGORYPOSTS08_SCROLL_SIMPLEVIEW = 'dataload-categoryposts08-scroll-simpleview';
    public final const MODULE_DATALOAD_CATEGORYPOSTS09_SCROLL_SIMPLEVIEW = 'dataload-categoryposts09-scroll-simpleview';
    public final const MODULE_DATALOAD_CATEGORYPOSTS10_SCROLL_SIMPLEVIEW = 'dataload-categoryposts10-scroll-simpleview';
    public final const MODULE_DATALOAD_CATEGORYPOSTS11_SCROLL_SIMPLEVIEW = 'dataload-categoryposts11-scroll-simpleview';
    public final const MODULE_DATALOAD_CATEGORYPOSTS12_SCROLL_SIMPLEVIEW = 'dataload-categoryposts12-scroll-simpleview';
    public final const MODULE_DATALOAD_CATEGORYPOSTS13_SCROLL_SIMPLEVIEW = 'dataload-categoryposts13-scroll-simpleview';
    public final const MODULE_DATALOAD_CATEGORYPOSTS14_SCROLL_SIMPLEVIEW = 'dataload-categoryposts14-scroll-simpleview';
    public final const MODULE_DATALOAD_CATEGORYPOSTS15_SCROLL_SIMPLEVIEW = 'dataload-categoryposts15-scroll-simpleview';
    public final const MODULE_DATALOAD_CATEGORYPOSTS16_SCROLL_SIMPLEVIEW = 'dataload-categoryposts16-scroll-simpleview';
    public final const MODULE_DATALOAD_CATEGORYPOSTS17_SCROLL_SIMPLEVIEW = 'dataload-categoryposts17-scroll-simpleview';
    public final const MODULE_DATALOAD_CATEGORYPOSTS18_SCROLL_SIMPLEVIEW = 'dataload-categoryposts18-scroll-simpleview';
    public final const MODULE_DATALOAD_CATEGORYPOSTS19_SCROLL_SIMPLEVIEW = 'dataload-categoryposts19-scroll-simpleview';
    public final const MODULE_DATALOAD_AUTHORCATEGORYPOSTS00_SCROLL_SIMPLEVIEW = 'dataload-authorcategoryposts00-scroll-simpleview';
    public final const MODULE_DATALOAD_AUTHORCATEGORYPOSTS01_SCROLL_SIMPLEVIEW = 'dataload-authorcategoryposts01-scroll-simpleview';
    public final const MODULE_DATALOAD_AUTHORCATEGORYPOSTS02_SCROLL_SIMPLEVIEW = 'dataload-authorcategoryposts02-scroll-simpleview';
    public final const MODULE_DATALOAD_AUTHORCATEGORYPOSTS03_SCROLL_SIMPLEVIEW = 'dataload-authorcategoryposts03-scroll-simpleview';
    public final const MODULE_DATALOAD_AUTHORCATEGORYPOSTS04_SCROLL_SIMPLEVIEW = 'dataload-authorcategoryposts04-scroll-simpleview';
    public final const MODULE_DATALOAD_AUTHORCATEGORYPOSTS05_SCROLL_SIMPLEVIEW = 'dataload-authorcategoryposts05-scroll-simpleview';
    public final const MODULE_DATALOAD_AUTHORCATEGORYPOSTS06_SCROLL_SIMPLEVIEW = 'dataload-authorcategoryposts06-scroll-simpleview';
    public final const MODULE_DATALOAD_AUTHORCATEGORYPOSTS07_SCROLL_SIMPLEVIEW = 'dataload-authorcategoryposts07-scroll-simpleview';
    public final const MODULE_DATALOAD_AUTHORCATEGORYPOSTS08_SCROLL_SIMPLEVIEW = 'dataload-authorcategoryposts08-scroll-simpleview';
    public final const MODULE_DATALOAD_AUTHORCATEGORYPOSTS09_SCROLL_SIMPLEVIEW = 'dataload-authorcategoryposts09-scroll-simpleview';
    public final const MODULE_DATALOAD_AUTHORCATEGORYPOSTS10_SCROLL_SIMPLEVIEW = 'dataload-authorcategoryposts10-scroll-simpleview';
    public final const MODULE_DATALOAD_AUTHORCATEGORYPOSTS11_SCROLL_SIMPLEVIEW = 'dataload-authorcategoryposts11-scroll-simpleview';
    public final const MODULE_DATALOAD_AUTHORCATEGORYPOSTS12_SCROLL_SIMPLEVIEW = 'dataload-authorcategoryposts12-scroll-simpleview';
    public final const MODULE_DATALOAD_AUTHORCATEGORYPOSTS13_SCROLL_SIMPLEVIEW = 'dataload-authorcategoryposts13-scroll-simpleview';
    public final const MODULE_DATALOAD_AUTHORCATEGORYPOSTS14_SCROLL_SIMPLEVIEW = 'dataload-authorcategoryposts14-scroll-simpleview';
    public final const MODULE_DATALOAD_AUTHORCATEGORYPOSTS15_SCROLL_SIMPLEVIEW = 'dataload-authorcategoryposts15-scroll-simpleview';
    public final const MODULE_DATALOAD_AUTHORCATEGORYPOSTS16_SCROLL_SIMPLEVIEW = 'dataload-authorcategoryposts16-scroll-simpleview';
    public final const MODULE_DATALOAD_AUTHORCATEGORYPOSTS17_SCROLL_SIMPLEVIEW = 'dataload-authorcategoryposts17-scroll-simpleview';
    public final const MODULE_DATALOAD_AUTHORCATEGORYPOSTS18_SCROLL_SIMPLEVIEW = 'dataload-authorcategoryposts18-scroll-simpleview';
    public final const MODULE_DATALOAD_AUTHORCATEGORYPOSTS19_SCROLL_SIMPLEVIEW = 'dataload-authorcategoryposts19-scroll-simpleview';
    public final const MODULE_DATALOAD_TAGCATEGORYPOSTS00_SCROLL_SIMPLEVIEW = 'dataload-tagcategoryposts00-scroll-simpleview';
    public final const MODULE_DATALOAD_TAGCATEGORYPOSTS01_SCROLL_SIMPLEVIEW = 'dataload-tagcategoryposts01-scroll-simpleview';
    public final const MODULE_DATALOAD_TAGCATEGORYPOSTS02_SCROLL_SIMPLEVIEW = 'dataload-tagcategoryposts02-scroll-simpleview';
    public final const MODULE_DATALOAD_TAGCATEGORYPOSTS03_SCROLL_SIMPLEVIEW = 'dataload-tagcategoryposts03-scroll-simpleview';
    public final const MODULE_DATALOAD_TAGCATEGORYPOSTS04_SCROLL_SIMPLEVIEW = 'dataload-tagcategoryposts04-scroll-simpleview';
    public final const MODULE_DATALOAD_TAGCATEGORYPOSTS05_SCROLL_SIMPLEVIEW = 'dataload-tagcategoryposts05-scroll-simpleview';
    public final const MODULE_DATALOAD_TAGCATEGORYPOSTS06_SCROLL_SIMPLEVIEW = 'dataload-tagcategoryposts06-scroll-simpleview';
    public final const MODULE_DATALOAD_TAGCATEGORYPOSTS07_SCROLL_SIMPLEVIEW = 'dataload-tagcategoryposts07-scroll-simpleview';
    public final const MODULE_DATALOAD_TAGCATEGORYPOSTS08_SCROLL_SIMPLEVIEW = 'dataload-tagcategoryposts08-scroll-simpleview';
    public final const MODULE_DATALOAD_TAGCATEGORYPOSTS09_SCROLL_SIMPLEVIEW = 'dataload-tagcategoryposts09-scroll-simpleview';
    public final const MODULE_DATALOAD_TAGCATEGORYPOSTS10_SCROLL_SIMPLEVIEW = 'dataload-tagcategoryposts10-scroll-simpleview';
    public final const MODULE_DATALOAD_TAGCATEGORYPOSTS11_SCROLL_SIMPLEVIEW = 'dataload-tagcategoryposts11-scroll-simpleview';
    public final const MODULE_DATALOAD_TAGCATEGORYPOSTS12_SCROLL_SIMPLEVIEW = 'dataload-tagcategoryposts12-scroll-simpleview';
    public final const MODULE_DATALOAD_TAGCATEGORYPOSTS13_SCROLL_SIMPLEVIEW = 'dataload-tagcategoryposts13-scroll-simpleview';
    public final const MODULE_DATALOAD_TAGCATEGORYPOSTS14_SCROLL_SIMPLEVIEW = 'dataload-tagcategoryposts14-scroll-simpleview';
    public final const MODULE_DATALOAD_TAGCATEGORYPOSTS15_SCROLL_SIMPLEVIEW = 'dataload-tagcategoryposts15-scroll-simpleview';
    public final const MODULE_DATALOAD_TAGCATEGORYPOSTS16_SCROLL_SIMPLEVIEW = 'dataload-tagcategoryposts16-scroll-simpleview';
    public final const MODULE_DATALOAD_TAGCATEGORYPOSTS17_SCROLL_SIMPLEVIEW = 'dataload-tagcategoryposts17-scroll-simpleview';
    public final const MODULE_DATALOAD_TAGCATEGORYPOSTS18_SCROLL_SIMPLEVIEW = 'dataload-tagcategoryposts18-scroll-simpleview';
    public final const MODULE_DATALOAD_TAGCATEGORYPOSTS19_SCROLL_SIMPLEVIEW = 'dataload-tagcategoryposts19-scroll-simpleview';
    public final const MODULE_DATALOAD_CATEGORYPOSTS00_SCROLL_FULLVIEW = 'dataload-categoryposts00-scroll-fullview';
    public final const MODULE_DATALOAD_CATEGORYPOSTS01_SCROLL_FULLVIEW = 'dataload-categoryposts01-scroll-fullview';
    public final const MODULE_DATALOAD_CATEGORYPOSTS02_SCROLL_FULLVIEW = 'dataload-categoryposts02-scroll-fullview';
    public final const MODULE_DATALOAD_CATEGORYPOSTS03_SCROLL_FULLVIEW = 'dataload-categoryposts03-scroll-fullview';
    public final const MODULE_DATALOAD_CATEGORYPOSTS04_SCROLL_FULLVIEW = 'dataload-categoryposts04-scroll-fullview';
    public final const MODULE_DATALOAD_CATEGORYPOSTS05_SCROLL_FULLVIEW = 'dataload-categoryposts05-scroll-fullview';
    public final const MODULE_DATALOAD_CATEGORYPOSTS06_SCROLL_FULLVIEW = 'dataload-categoryposts06-scroll-fullview';
    public final const MODULE_DATALOAD_CATEGORYPOSTS07_SCROLL_FULLVIEW = 'dataload-categoryposts07-scroll-fullview';
    public final const MODULE_DATALOAD_CATEGORYPOSTS08_SCROLL_FULLVIEW = 'dataload-categoryposts08-scroll-fullview';
    public final const MODULE_DATALOAD_CATEGORYPOSTS09_SCROLL_FULLVIEW = 'dataload-categoryposts09-scroll-fullview';
    public final const MODULE_DATALOAD_CATEGORYPOSTS10_SCROLL_FULLVIEW = 'dataload-categoryposts10-scroll-fullview';
    public final const MODULE_DATALOAD_CATEGORYPOSTS11_SCROLL_FULLVIEW = 'dataload-categoryposts11-scroll-fullview';
    public final const MODULE_DATALOAD_CATEGORYPOSTS12_SCROLL_FULLVIEW = 'dataload-categoryposts12-scroll-fullview';
    public final const MODULE_DATALOAD_CATEGORYPOSTS13_SCROLL_FULLVIEW = 'dataload-categoryposts13-scroll-fullview';
    public final const MODULE_DATALOAD_CATEGORYPOSTS14_SCROLL_FULLVIEW = 'dataload-categoryposts14-scroll-fullview';
    public final const MODULE_DATALOAD_CATEGORYPOSTS15_SCROLL_FULLVIEW = 'dataload-categoryposts15-scroll-fullview';
    public final const MODULE_DATALOAD_CATEGORYPOSTS16_SCROLL_FULLVIEW = 'dataload-categoryposts16-scroll-fullview';
    public final const MODULE_DATALOAD_CATEGORYPOSTS17_SCROLL_FULLVIEW = 'dataload-categoryposts17-scroll-fullview';
    public final const MODULE_DATALOAD_CATEGORYPOSTS18_SCROLL_FULLVIEW = 'dataload-categoryposts18-scroll-fullview';
    public final const MODULE_DATALOAD_CATEGORYPOSTS19_SCROLL_FULLVIEW = 'dataload-categoryposts19-scroll-fullview';
    public final const MODULE_DATALOAD_AUTHORCATEGORYPOSTS00_SCROLL_FULLVIEW = 'dataload-authorcategoryposts00-scroll-fullview';
    public final const MODULE_DATALOAD_AUTHORCATEGORYPOSTS01_SCROLL_FULLVIEW = 'dataload-authorcategoryposts01-scroll-fullview';
    public final const MODULE_DATALOAD_AUTHORCATEGORYPOSTS02_SCROLL_FULLVIEW = 'dataload-authorcategoryposts02-scroll-fullview';
    public final const MODULE_DATALOAD_AUTHORCATEGORYPOSTS03_SCROLL_FULLVIEW = 'dataload-authorcategoryposts03-scroll-fullview';
    public final const MODULE_DATALOAD_AUTHORCATEGORYPOSTS04_SCROLL_FULLVIEW = 'dataload-authorcategoryposts04-scroll-fullview';
    public final const MODULE_DATALOAD_AUTHORCATEGORYPOSTS05_SCROLL_FULLVIEW = 'dataload-authorcategoryposts05-scroll-fullview';
    public final const MODULE_DATALOAD_AUTHORCATEGORYPOSTS06_SCROLL_FULLVIEW = 'dataload-authorcategoryposts06-scroll-fullview';
    public final const MODULE_DATALOAD_AUTHORCATEGORYPOSTS07_SCROLL_FULLVIEW = 'dataload-authorcategoryposts07-scroll-fullview';
    public final const MODULE_DATALOAD_AUTHORCATEGORYPOSTS08_SCROLL_FULLVIEW = 'dataload-authorcategoryposts08-scroll-fullview';
    public final const MODULE_DATALOAD_AUTHORCATEGORYPOSTS09_SCROLL_FULLVIEW = 'dataload-authorcategoryposts09-scroll-fullview';
    public final const MODULE_DATALOAD_AUTHORCATEGORYPOSTS10_SCROLL_FULLVIEW = 'dataload-authorcategoryposts10-scroll-fullview';
    public final const MODULE_DATALOAD_AUTHORCATEGORYPOSTS11_SCROLL_FULLVIEW = 'dataload-authorcategoryposts11-scroll-fullview';
    public final const MODULE_DATALOAD_AUTHORCATEGORYPOSTS12_SCROLL_FULLVIEW = 'dataload-authorcategoryposts12-scroll-fullview';
    public final const MODULE_DATALOAD_AUTHORCATEGORYPOSTS13_SCROLL_FULLVIEW = 'dataload-authorcategoryposts13-scroll-fullview';
    public final const MODULE_DATALOAD_AUTHORCATEGORYPOSTS14_SCROLL_FULLVIEW = 'dataload-authorcategoryposts14-scroll-fullview';
    public final const MODULE_DATALOAD_AUTHORCATEGORYPOSTS15_SCROLL_FULLVIEW = 'dataload-authorcategoryposts15-scroll-fullview';
    public final const MODULE_DATALOAD_AUTHORCATEGORYPOSTS16_SCROLL_FULLVIEW = 'dataload-authorcategoryposts16-scroll-fullview';
    public final const MODULE_DATALOAD_AUTHORCATEGORYPOSTS17_SCROLL_FULLVIEW = 'dataload-authorcategoryposts17-scroll-fullview';
    public final const MODULE_DATALOAD_AUTHORCATEGORYPOSTS18_SCROLL_FULLVIEW = 'dataload-authorcategoryposts18-scroll-fullview';
    public final const MODULE_DATALOAD_AUTHORCATEGORYPOSTS19_SCROLL_FULLVIEW = 'dataload-authorcategoryposts19-scroll-fullview';
    public final const MODULE_DATALOAD_TAGCATEGORYPOSTS00_SCROLL_FULLVIEW = 'dataload-tagcategoryposts00-scroll-fullview';
    public final const MODULE_DATALOAD_TAGCATEGORYPOSTS01_SCROLL_FULLVIEW = 'dataload-tagcategoryposts01-scroll-fullview';
    public final const MODULE_DATALOAD_TAGCATEGORYPOSTS02_SCROLL_FULLVIEW = 'dataload-tagcategoryposts02-scroll-fullview';
    public final const MODULE_DATALOAD_TAGCATEGORYPOSTS03_SCROLL_FULLVIEW = 'dataload-tagcategoryposts03-scroll-fullview';
    public final const MODULE_DATALOAD_TAGCATEGORYPOSTS04_SCROLL_FULLVIEW = 'dataload-tagcategoryposts04-scroll-fullview';
    public final const MODULE_DATALOAD_TAGCATEGORYPOSTS05_SCROLL_FULLVIEW = 'dataload-tagcategoryposts05-scroll-fullview';
    public final const MODULE_DATALOAD_TAGCATEGORYPOSTS06_SCROLL_FULLVIEW = 'dataload-tagcategoryposts06-scroll-fullview';
    public final const MODULE_DATALOAD_TAGCATEGORYPOSTS07_SCROLL_FULLVIEW = 'dataload-tagcategoryposts07-scroll-fullview';
    public final const MODULE_DATALOAD_TAGCATEGORYPOSTS08_SCROLL_FULLVIEW = 'dataload-tagcategoryposts08-scroll-fullview';
    public final const MODULE_DATALOAD_TAGCATEGORYPOSTS09_SCROLL_FULLVIEW = 'dataload-tagcategoryposts09-scroll-fullview';
    public final const MODULE_DATALOAD_TAGCATEGORYPOSTS10_SCROLL_FULLVIEW = 'dataload-tagcategoryposts10-scroll-fullview';
    public final const MODULE_DATALOAD_TAGCATEGORYPOSTS11_SCROLL_FULLVIEW = 'dataload-tagcategoryposts11-scroll-fullview';
    public final const MODULE_DATALOAD_TAGCATEGORYPOSTS12_SCROLL_FULLVIEW = 'dataload-tagcategoryposts12-scroll-fullview';
    public final const MODULE_DATALOAD_TAGCATEGORYPOSTS13_SCROLL_FULLVIEW = 'dataload-tagcategoryposts13-scroll-fullview';
    public final const MODULE_DATALOAD_TAGCATEGORYPOSTS14_SCROLL_FULLVIEW = 'dataload-tagcategoryposts14-scroll-fullview';
    public final const MODULE_DATALOAD_TAGCATEGORYPOSTS15_SCROLL_FULLVIEW = 'dataload-tagcategoryposts15-scroll-fullview';
    public final const MODULE_DATALOAD_TAGCATEGORYPOSTS16_SCROLL_FULLVIEW = 'dataload-tagcategoryposts16-scroll-fullview';
    public final const MODULE_DATALOAD_TAGCATEGORYPOSTS17_SCROLL_FULLVIEW = 'dataload-tagcategoryposts17-scroll-fullview';
    public final const MODULE_DATALOAD_TAGCATEGORYPOSTS18_SCROLL_FULLVIEW = 'dataload-tagcategoryposts18-scroll-fullview';
    public final const MODULE_DATALOAD_TAGCATEGORYPOSTS19_SCROLL_FULLVIEW = 'dataload-tagcategoryposts19-scroll-fullview';
    public final const MODULE_DATALOAD_CATEGORYPOSTS00_SCROLL_THUMBNAIL = 'dataload-categoryposts00-scroll-thumbnail';
    public final const MODULE_DATALOAD_CATEGORYPOSTS01_SCROLL_THUMBNAIL = 'dataload-categoryposts01-scroll-thumbnail';
    public final const MODULE_DATALOAD_CATEGORYPOSTS02_SCROLL_THUMBNAIL = 'dataload-categoryposts02-scroll-thumbnail';
    public final const MODULE_DATALOAD_CATEGORYPOSTS03_SCROLL_THUMBNAIL = 'dataload-categoryposts03-scroll-thumbnail';
    public final const MODULE_DATALOAD_CATEGORYPOSTS04_SCROLL_THUMBNAIL = 'dataload-categoryposts04-scroll-thumbnail';
    public final const MODULE_DATALOAD_CATEGORYPOSTS05_SCROLL_THUMBNAIL = 'dataload-categoryposts05-scroll-thumbnail';
    public final const MODULE_DATALOAD_CATEGORYPOSTS06_SCROLL_THUMBNAIL = 'dataload-categoryposts06-scroll-thumbnail';
    public final const MODULE_DATALOAD_CATEGORYPOSTS07_SCROLL_THUMBNAIL = 'dataload-categoryposts07-scroll-thumbnail';
    public final const MODULE_DATALOAD_CATEGORYPOSTS08_SCROLL_THUMBNAIL = 'dataload-categoryposts08-scroll-thumbnail';
    public final const MODULE_DATALOAD_CATEGORYPOSTS09_SCROLL_THUMBNAIL = 'dataload-categoryposts09-scroll-thumbnail';
    public final const MODULE_DATALOAD_CATEGORYPOSTS10_SCROLL_THUMBNAIL = 'dataload-categoryposts10-scroll-thumbnail';
    public final const MODULE_DATALOAD_CATEGORYPOSTS11_SCROLL_THUMBNAIL = 'dataload-categoryposts11-scroll-thumbnail';
    public final const MODULE_DATALOAD_CATEGORYPOSTS12_SCROLL_THUMBNAIL = 'dataload-categoryposts12-scroll-thumbnail';
    public final const MODULE_DATALOAD_CATEGORYPOSTS13_SCROLL_THUMBNAIL = 'dataload-categoryposts13-scroll-thumbnail';
    public final const MODULE_DATALOAD_CATEGORYPOSTS14_SCROLL_THUMBNAIL = 'dataload-categoryposts14-scroll-thumbnail';
    public final const MODULE_DATALOAD_CATEGORYPOSTS15_SCROLL_THUMBNAIL = 'dataload-categoryposts15-scroll-thumbnail';
    public final const MODULE_DATALOAD_CATEGORYPOSTS16_SCROLL_THUMBNAIL = 'dataload-categoryposts16-scroll-thumbnail';
    public final const MODULE_DATALOAD_CATEGORYPOSTS17_SCROLL_THUMBNAIL = 'dataload-categoryposts17-scroll-thumbnail';
    public final const MODULE_DATALOAD_CATEGORYPOSTS18_SCROLL_THUMBNAIL = 'dataload-categoryposts18-scroll-thumbnail';
    public final const MODULE_DATALOAD_CATEGORYPOSTS19_SCROLL_THUMBNAIL = 'dataload-categoryposts19-scroll-thumbnail';
    public final const MODULE_DATALOAD_AUTHORCATEGORYPOSTS00_SCROLL_THUMBNAIL = 'dataload-authorcategoryposts00-scroll-thumbnail';
    public final const MODULE_DATALOAD_AUTHORCATEGORYPOSTS01_SCROLL_THUMBNAIL = 'dataload-authorcategoryposts01-scroll-thumbnail';
    public final const MODULE_DATALOAD_AUTHORCATEGORYPOSTS02_SCROLL_THUMBNAIL = 'dataload-authorcategoryposts02-scroll-thumbnail';
    public final const MODULE_DATALOAD_AUTHORCATEGORYPOSTS03_SCROLL_THUMBNAIL = 'dataload-authorcategoryposts03-scroll-thumbnail';
    public final const MODULE_DATALOAD_AUTHORCATEGORYPOSTS04_SCROLL_THUMBNAIL = 'dataload-authorcategoryposts04-scroll-thumbnail';
    public final const MODULE_DATALOAD_AUTHORCATEGORYPOSTS05_SCROLL_THUMBNAIL = 'dataload-authorcategoryposts05-scroll-thumbnail';
    public final const MODULE_DATALOAD_AUTHORCATEGORYPOSTS06_SCROLL_THUMBNAIL = 'dataload-authorcategoryposts06-scroll-thumbnail';
    public final const MODULE_DATALOAD_AUTHORCATEGORYPOSTS07_SCROLL_THUMBNAIL = 'dataload-authorcategoryposts07-scroll-thumbnail';
    public final const MODULE_DATALOAD_AUTHORCATEGORYPOSTS08_SCROLL_THUMBNAIL = 'dataload-authorcategoryposts08-scroll-thumbnail';
    public final const MODULE_DATALOAD_AUTHORCATEGORYPOSTS09_SCROLL_THUMBNAIL = 'dataload-authorcategoryposts09-scroll-thumbnail';
    public final const MODULE_DATALOAD_AUTHORCATEGORYPOSTS10_SCROLL_THUMBNAIL = 'dataload-authorcategoryposts10-scroll-thumbnail';
    public final const MODULE_DATALOAD_AUTHORCATEGORYPOSTS11_SCROLL_THUMBNAIL = 'dataload-authorcategoryposts11-scroll-thumbnail';
    public final const MODULE_DATALOAD_AUTHORCATEGORYPOSTS12_SCROLL_THUMBNAIL = 'dataload-authorcategoryposts12-scroll-thumbnail';
    public final const MODULE_DATALOAD_AUTHORCATEGORYPOSTS13_SCROLL_THUMBNAIL = 'dataload-authorcategoryposts13-scroll-thumbnail';
    public final const MODULE_DATALOAD_AUTHORCATEGORYPOSTS14_SCROLL_THUMBNAIL = 'dataload-authorcategoryposts14-scroll-thumbnail';
    public final const MODULE_DATALOAD_AUTHORCATEGORYPOSTS15_SCROLL_THUMBNAIL = 'dataload-authorcategoryposts15-scroll-thumbnail';
    public final const MODULE_DATALOAD_AUTHORCATEGORYPOSTS16_SCROLL_THUMBNAIL = 'dataload-authorcategoryposts16-scroll-thumbnail';
    public final const MODULE_DATALOAD_AUTHORCATEGORYPOSTS17_SCROLL_THUMBNAIL = 'dataload-authorcategoryposts17-scroll-thumbnail';
    public final const MODULE_DATALOAD_AUTHORCATEGORYPOSTS18_SCROLL_THUMBNAIL = 'dataload-authorcategoryposts18-scroll-thumbnail';
    public final const MODULE_DATALOAD_AUTHORCATEGORYPOSTS19_SCROLL_THUMBNAIL = 'dataload-authorcategoryposts19-scroll-thumbnail';
    public final const MODULE_DATALOAD_TAGCATEGORYPOSTS00_SCROLL_THUMBNAIL = 'dataload-tagcategoryposts00-scroll-thumbnail';
    public final const MODULE_DATALOAD_TAGCATEGORYPOSTS01_SCROLL_THUMBNAIL = 'dataload-tagcategoryposts01-scroll-thumbnail';
    public final const MODULE_DATALOAD_TAGCATEGORYPOSTS02_SCROLL_THUMBNAIL = 'dataload-tagcategoryposts02-scroll-thumbnail';
    public final const MODULE_DATALOAD_TAGCATEGORYPOSTS03_SCROLL_THUMBNAIL = 'dataload-tagcategoryposts03-scroll-thumbnail';
    public final const MODULE_DATALOAD_TAGCATEGORYPOSTS04_SCROLL_THUMBNAIL = 'dataload-tagcategoryposts04-scroll-thumbnail';
    public final const MODULE_DATALOAD_TAGCATEGORYPOSTS05_SCROLL_THUMBNAIL = 'dataload-tagcategoryposts05-scroll-thumbnail';
    public final const MODULE_DATALOAD_TAGCATEGORYPOSTS06_SCROLL_THUMBNAIL = 'dataload-tagcategoryposts06-scroll-thumbnail';
    public final const MODULE_DATALOAD_TAGCATEGORYPOSTS07_SCROLL_THUMBNAIL = 'dataload-tagcategoryposts07-scroll-thumbnail';
    public final const MODULE_DATALOAD_TAGCATEGORYPOSTS08_SCROLL_THUMBNAIL = 'dataload-tagcategoryposts08-scroll-thumbnail';
    public final const MODULE_DATALOAD_TAGCATEGORYPOSTS09_SCROLL_THUMBNAIL = 'dataload-tagcategoryposts09-scroll-thumbnail';
    public final const MODULE_DATALOAD_TAGCATEGORYPOSTS10_SCROLL_THUMBNAIL = 'dataload-tagcategoryposts10-scroll-thumbnail';
    public final const MODULE_DATALOAD_TAGCATEGORYPOSTS11_SCROLL_THUMBNAIL = 'dataload-tagcategoryposts11-scroll-thumbnail';
    public final const MODULE_DATALOAD_TAGCATEGORYPOSTS12_SCROLL_THUMBNAIL = 'dataload-tagcategoryposts12-scroll-thumbnail';
    public final const MODULE_DATALOAD_TAGCATEGORYPOSTS13_SCROLL_THUMBNAIL = 'dataload-tagcategoryposts13-scroll-thumbnail';
    public final const MODULE_DATALOAD_TAGCATEGORYPOSTS14_SCROLL_THUMBNAIL = 'dataload-tagcategoryposts14-scroll-thumbnail';
    public final const MODULE_DATALOAD_TAGCATEGORYPOSTS15_SCROLL_THUMBNAIL = 'dataload-tagcategoryposts15-scroll-thumbnail';
    public final const MODULE_DATALOAD_TAGCATEGORYPOSTS16_SCROLL_THUMBNAIL = 'dataload-tagcategoryposts16-scroll-thumbnail';
    public final const MODULE_DATALOAD_TAGCATEGORYPOSTS17_SCROLL_THUMBNAIL = 'dataload-tagcategoryposts17-scroll-thumbnail';
    public final const MODULE_DATALOAD_TAGCATEGORYPOSTS18_SCROLL_THUMBNAIL = 'dataload-tagcategoryposts18-scroll-thumbnail';
    public final const MODULE_DATALOAD_TAGCATEGORYPOSTS19_SCROLL_THUMBNAIL = 'dataload-tagcategoryposts19-scroll-thumbnail';
    public final const MODULE_DATALOAD_CATEGORYPOSTS00_SCROLL_LIST = 'dataload-categoryposts00-scroll-list';
    public final const MODULE_DATALOAD_CATEGORYPOSTS01_SCROLL_LIST = 'dataload-categoryposts01-scroll-list';
    public final const MODULE_DATALOAD_CATEGORYPOSTS02_SCROLL_LIST = 'dataload-categoryposts02-scroll-list';
    public final const MODULE_DATALOAD_CATEGORYPOSTS03_SCROLL_LIST = 'dataload-categoryposts03-scroll-list';
    public final const MODULE_DATALOAD_CATEGORYPOSTS04_SCROLL_LIST = 'dataload-categoryposts04-scroll-list';
    public final const MODULE_DATALOAD_CATEGORYPOSTS05_SCROLL_LIST = 'dataload-categoryposts05-scroll-list';
    public final const MODULE_DATALOAD_CATEGORYPOSTS06_SCROLL_LIST = 'dataload-categoryposts06-scroll-list';
    public final const MODULE_DATALOAD_CATEGORYPOSTS07_SCROLL_LIST = 'dataload-categoryposts07-scroll-list';
    public final const MODULE_DATALOAD_CATEGORYPOSTS08_SCROLL_LIST = 'dataload-categoryposts08-scroll-list';
    public final const MODULE_DATALOAD_CATEGORYPOSTS09_SCROLL_LIST = 'dataload-categoryposts09-scroll-list';
    public final const MODULE_DATALOAD_CATEGORYPOSTS10_SCROLL_LIST = 'dataload-categoryposts10-scroll-list';
    public final const MODULE_DATALOAD_CATEGORYPOSTS11_SCROLL_LIST = 'dataload-categoryposts11-scroll-list';
    public final const MODULE_DATALOAD_CATEGORYPOSTS12_SCROLL_LIST = 'dataload-categoryposts12-scroll-list';
    public final const MODULE_DATALOAD_CATEGORYPOSTS13_SCROLL_LIST = 'dataload-categoryposts13-scroll-list';
    public final const MODULE_DATALOAD_CATEGORYPOSTS14_SCROLL_LIST = 'dataload-categoryposts14-scroll-list';
    public final const MODULE_DATALOAD_CATEGORYPOSTS15_SCROLL_LIST = 'dataload-categoryposts15-scroll-list';
    public final const MODULE_DATALOAD_CATEGORYPOSTS16_SCROLL_LIST = 'dataload-categoryposts16-scroll-list';
    public final const MODULE_DATALOAD_CATEGORYPOSTS17_SCROLL_LIST = 'dataload-categoryposts17-scroll-list';
    public final const MODULE_DATALOAD_CATEGORYPOSTS18_SCROLL_LIST = 'dataload-categoryposts18-scroll-list';
    public final const MODULE_DATALOAD_CATEGORYPOSTS19_SCROLL_LIST = 'dataload-categoryposts19-scroll-list';
    public final const MODULE_DATALOAD_AUTHORCATEGORYPOSTS00_SCROLL_LIST = 'dataload-authorcategoryposts00-scroll-list';
    public final const MODULE_DATALOAD_AUTHORCATEGORYPOSTS01_SCROLL_LIST = 'dataload-authorcategoryposts01-scroll-list';
    public final const MODULE_DATALOAD_AUTHORCATEGORYPOSTS02_SCROLL_LIST = 'dataload-authorcategoryposts02-scroll-list';
    public final const MODULE_DATALOAD_AUTHORCATEGORYPOSTS03_SCROLL_LIST = 'dataload-authorcategoryposts03-scroll-list';
    public final const MODULE_DATALOAD_AUTHORCATEGORYPOSTS04_SCROLL_LIST = 'dataload-authorcategoryposts04-scroll-list';
    public final const MODULE_DATALOAD_AUTHORCATEGORYPOSTS05_SCROLL_LIST = 'dataload-authorcategoryposts05-scroll-list';
    public final const MODULE_DATALOAD_AUTHORCATEGORYPOSTS06_SCROLL_LIST = 'dataload-authorcategoryposts06-scroll-list';
    public final const MODULE_DATALOAD_AUTHORCATEGORYPOSTS07_SCROLL_LIST = 'dataload-authorcategoryposts07-scroll-list';
    public final const MODULE_DATALOAD_AUTHORCATEGORYPOSTS08_SCROLL_LIST = 'dataload-authorcategoryposts08-scroll-list';
    public final const MODULE_DATALOAD_AUTHORCATEGORYPOSTS09_SCROLL_LIST = 'dataload-authorcategoryposts09-scroll-list';
    public final const MODULE_DATALOAD_AUTHORCATEGORYPOSTS10_SCROLL_LIST = 'dataload-authorcategoryposts10-scroll-list';
    public final const MODULE_DATALOAD_AUTHORCATEGORYPOSTS11_SCROLL_LIST = 'dataload-authorcategoryposts11-scroll-list';
    public final const MODULE_DATALOAD_AUTHORCATEGORYPOSTS12_SCROLL_LIST = 'dataload-authorcategoryposts12-scroll-list';
    public final const MODULE_DATALOAD_AUTHORCATEGORYPOSTS13_SCROLL_LIST = 'dataload-authorcategoryposts13-scroll-list';
    public final const MODULE_DATALOAD_AUTHORCATEGORYPOSTS14_SCROLL_LIST = 'dataload-authorcategoryposts14-scroll-list';
    public final const MODULE_DATALOAD_AUTHORCATEGORYPOSTS15_SCROLL_LIST = 'dataload-authorcategoryposts15-scroll-list';
    public final const MODULE_DATALOAD_AUTHORCATEGORYPOSTS16_SCROLL_LIST = 'dataload-authorcategoryposts16-scroll-list';
    public final const MODULE_DATALOAD_AUTHORCATEGORYPOSTS17_SCROLL_LIST = 'dataload-authorcategoryposts17-scroll-list';
    public final const MODULE_DATALOAD_AUTHORCATEGORYPOSTS18_SCROLL_LIST = 'dataload-authorcategoryposts18-scroll-list';
    public final const MODULE_DATALOAD_AUTHORCATEGORYPOSTS19_SCROLL_LIST = 'dataload-authorcategoryposts19-scroll-list';
    public final const MODULE_DATALOAD_TAGCATEGORYPOSTS00_SCROLL_LIST = 'dataload-tagcategoryposts00-scroll-list';
    public final const MODULE_DATALOAD_TAGCATEGORYPOSTS01_SCROLL_LIST = 'dataload-tagcategoryposts01-scroll-list';
    public final const MODULE_DATALOAD_TAGCATEGORYPOSTS02_SCROLL_LIST = 'dataload-tagcategoryposts02-scroll-list';
    public final const MODULE_DATALOAD_TAGCATEGORYPOSTS03_SCROLL_LIST = 'dataload-tagcategoryposts03-scroll-list';
    public final const MODULE_DATALOAD_TAGCATEGORYPOSTS04_SCROLL_LIST = 'dataload-tagcategoryposts04-scroll-list';
    public final const MODULE_DATALOAD_TAGCATEGORYPOSTS05_SCROLL_LIST = 'dataload-tagcategoryposts05-scroll-list';
    public final const MODULE_DATALOAD_TAGCATEGORYPOSTS06_SCROLL_LIST = 'dataload-tagcategoryposts06-scroll-list';
    public final const MODULE_DATALOAD_TAGCATEGORYPOSTS07_SCROLL_LIST = 'dataload-tagcategoryposts07-scroll-list';
    public final const MODULE_DATALOAD_TAGCATEGORYPOSTS08_SCROLL_LIST = 'dataload-tagcategoryposts08-scroll-list';
    public final const MODULE_DATALOAD_TAGCATEGORYPOSTS09_SCROLL_LIST = 'dataload-tagcategoryposts09-scroll-list';
    public final const MODULE_DATALOAD_TAGCATEGORYPOSTS10_SCROLL_LIST = 'dataload-tagcategoryposts10-scroll-list';
    public final const MODULE_DATALOAD_TAGCATEGORYPOSTS11_SCROLL_LIST = 'dataload-tagcategoryposts11-scroll-list';
    public final const MODULE_DATALOAD_TAGCATEGORYPOSTS12_SCROLL_LIST = 'dataload-tagcategoryposts12-scroll-list';
    public final const MODULE_DATALOAD_TAGCATEGORYPOSTS13_SCROLL_LIST = 'dataload-tagcategoryposts13-scroll-list';
    public final const MODULE_DATALOAD_TAGCATEGORYPOSTS14_SCROLL_LIST = 'dataload-tagcategoryposts14-scroll-list';
    public final const MODULE_DATALOAD_TAGCATEGORYPOSTS15_SCROLL_LIST = 'dataload-tagcategoryposts15-scroll-list';
    public final const MODULE_DATALOAD_TAGCATEGORYPOSTS16_SCROLL_LIST = 'dataload-tagcategoryposts16-scroll-list';
    public final const MODULE_DATALOAD_TAGCATEGORYPOSTS17_SCROLL_LIST = 'dataload-tagcategoryposts17-scroll-list';
    public final const MODULE_DATALOAD_TAGCATEGORYPOSTS18_SCROLL_LIST = 'dataload-tagcategoryposts18-scroll-list';
    public final const MODULE_DATALOAD_TAGCATEGORYPOSTS19_SCROLL_LIST = 'dataload-tagcategoryposts19-scroll-list';
    public final const MODULE_DATALOAD_CATEGORYPOSTS00_SCROLL_LINE = 'dataload-categoryposts00-scroll-line';
    public final const MODULE_DATALOAD_CATEGORYPOSTS01_SCROLL_LINE = 'dataload-categoryposts01-scroll-line';
    public final const MODULE_DATALOAD_CATEGORYPOSTS02_SCROLL_LINE = 'dataload-categoryposts02-scroll-line';
    public final const MODULE_DATALOAD_CATEGORYPOSTS03_SCROLL_LINE = 'dataload-categoryposts03-scroll-line';
    public final const MODULE_DATALOAD_CATEGORYPOSTS04_SCROLL_LINE = 'dataload-categoryposts04-scroll-line';
    public final const MODULE_DATALOAD_CATEGORYPOSTS05_SCROLL_LINE = 'dataload-categoryposts05-scroll-line';
    public final const MODULE_DATALOAD_CATEGORYPOSTS06_SCROLL_LINE = 'dataload-categoryposts06-scroll-line';
    public final const MODULE_DATALOAD_CATEGORYPOSTS07_SCROLL_LINE = 'dataload-categoryposts07-scroll-line';
    public final const MODULE_DATALOAD_CATEGORYPOSTS08_SCROLL_LINE = 'dataload-categoryposts08-scroll-line';
    public final const MODULE_DATALOAD_CATEGORYPOSTS09_SCROLL_LINE = 'dataload-categoryposts09-scroll-line';
    public final const MODULE_DATALOAD_CATEGORYPOSTS10_SCROLL_LINE = 'dataload-categoryposts10-scroll-line';
    public final const MODULE_DATALOAD_CATEGORYPOSTS11_SCROLL_LINE = 'dataload-categoryposts11-scroll-line';
    public final const MODULE_DATALOAD_CATEGORYPOSTS12_SCROLL_LINE = 'dataload-categoryposts12-scroll-line';
    public final const MODULE_DATALOAD_CATEGORYPOSTS13_SCROLL_LINE = 'dataload-categoryposts13-scroll-line';
    public final const MODULE_DATALOAD_CATEGORYPOSTS14_SCROLL_LINE = 'dataload-categoryposts14-scroll-line';
    public final const MODULE_DATALOAD_CATEGORYPOSTS15_SCROLL_LINE = 'dataload-categoryposts15-scroll-line';
    public final const MODULE_DATALOAD_CATEGORYPOSTS16_SCROLL_LINE = 'dataload-categoryposts16-scroll-line';
    public final const MODULE_DATALOAD_CATEGORYPOSTS17_SCROLL_LINE = 'dataload-categoryposts17-scroll-line';
    public final const MODULE_DATALOAD_CATEGORYPOSTS18_SCROLL_LINE = 'dataload-categoryposts18-scroll-line';
    public final const MODULE_DATALOAD_CATEGORYPOSTS19_SCROLL_LINE = 'dataload-categoryposts19-scroll-line';
    public final const MODULE_DATALOAD_AUTHORCATEGORYPOSTS00_SCROLL_LINE = 'dataload-authorcategoryposts00-scroll-line';
    public final const MODULE_DATALOAD_AUTHORCATEGORYPOSTS01_SCROLL_LINE = 'dataload-authorcategoryposts01-scroll-line';
    public final const MODULE_DATALOAD_AUTHORCATEGORYPOSTS02_SCROLL_LINE = 'dataload-authorcategoryposts02-scroll-line';
    public final const MODULE_DATALOAD_AUTHORCATEGORYPOSTS03_SCROLL_LINE = 'dataload-authorcategoryposts03-scroll-line';
    public final const MODULE_DATALOAD_AUTHORCATEGORYPOSTS04_SCROLL_LINE = 'dataload-authorcategoryposts04-scroll-line';
    public final const MODULE_DATALOAD_AUTHORCATEGORYPOSTS05_SCROLL_LINE = 'dataload-authorcategoryposts05-scroll-line';
    public final const MODULE_DATALOAD_AUTHORCATEGORYPOSTS06_SCROLL_LINE = 'dataload-authorcategoryposts06-scroll-line';
    public final const MODULE_DATALOAD_AUTHORCATEGORYPOSTS07_SCROLL_LINE = 'dataload-authorcategoryposts07-scroll-line';
    public final const MODULE_DATALOAD_AUTHORCATEGORYPOSTS08_SCROLL_LINE = 'dataload-authorcategoryposts08-scroll-line';
    public final const MODULE_DATALOAD_AUTHORCATEGORYPOSTS09_SCROLL_LINE = 'dataload-authorcategoryposts09-scroll-line';
    public final const MODULE_DATALOAD_AUTHORCATEGORYPOSTS10_SCROLL_LINE = 'dataload-authorcategoryposts10-scroll-line';
    public final const MODULE_DATALOAD_AUTHORCATEGORYPOSTS11_SCROLL_LINE = 'dataload-authorcategoryposts11-scroll-line';
    public final const MODULE_DATALOAD_AUTHORCATEGORYPOSTS12_SCROLL_LINE = 'dataload-authorcategoryposts12-scroll-line';
    public final const MODULE_DATALOAD_AUTHORCATEGORYPOSTS13_SCROLL_LINE = 'dataload-authorcategoryposts13-scroll-line';
    public final const MODULE_DATALOAD_AUTHORCATEGORYPOSTS14_SCROLL_LINE = 'dataload-authorcategoryposts14-scroll-line';
    public final const MODULE_DATALOAD_AUTHORCATEGORYPOSTS15_SCROLL_LINE = 'dataload-authorcategoryposts15-scroll-line';
    public final const MODULE_DATALOAD_AUTHORCATEGORYPOSTS16_SCROLL_LINE = 'dataload-authorcategoryposts16-scroll-line';
    public final const MODULE_DATALOAD_AUTHORCATEGORYPOSTS17_SCROLL_LINE = 'dataload-authorcategoryposts17-scroll-line';
    public final const MODULE_DATALOAD_AUTHORCATEGORYPOSTS18_SCROLL_LINE = 'dataload-authorcategoryposts18-scroll-line';
    public final const MODULE_DATALOAD_AUTHORCATEGORYPOSTS19_SCROLL_LINE = 'dataload-authorcategoryposts19-scroll-line';
    public final const MODULE_DATALOAD_TAGCATEGORYPOSTS00_SCROLL_LINE = 'dataload-tagcategoryposts00-scroll-line';
    public final const MODULE_DATALOAD_TAGCATEGORYPOSTS01_SCROLL_LINE = 'dataload-tagcategoryposts01-scroll-line';
    public final const MODULE_DATALOAD_TAGCATEGORYPOSTS02_SCROLL_LINE = 'dataload-tagcategoryposts02-scroll-line';
    public final const MODULE_DATALOAD_TAGCATEGORYPOSTS03_SCROLL_LINE = 'dataload-tagcategoryposts03-scroll-line';
    public final const MODULE_DATALOAD_TAGCATEGORYPOSTS04_SCROLL_LINE = 'dataload-tagcategoryposts04-scroll-line';
    public final const MODULE_DATALOAD_TAGCATEGORYPOSTS05_SCROLL_LINE = 'dataload-tagcategoryposts05-scroll-line';
    public final const MODULE_DATALOAD_TAGCATEGORYPOSTS06_SCROLL_LINE = 'dataload-tagcategoryposts06-scroll-line';
    public final const MODULE_DATALOAD_TAGCATEGORYPOSTS07_SCROLL_LINE = 'dataload-tagcategoryposts07-scroll-line';
    public final const MODULE_DATALOAD_TAGCATEGORYPOSTS08_SCROLL_LINE = 'dataload-tagcategoryposts08-scroll-line';
    public final const MODULE_DATALOAD_TAGCATEGORYPOSTS09_SCROLL_LINE = 'dataload-tagcategoryposts09-scroll-line';
    public final const MODULE_DATALOAD_TAGCATEGORYPOSTS10_SCROLL_LINE = 'dataload-tagcategoryposts10-scroll-line';
    public final const MODULE_DATALOAD_TAGCATEGORYPOSTS11_SCROLL_LINE = 'dataload-tagcategoryposts11-scroll-line';
    public final const MODULE_DATALOAD_TAGCATEGORYPOSTS12_SCROLL_LINE = 'dataload-tagcategoryposts12-scroll-line';
    public final const MODULE_DATALOAD_TAGCATEGORYPOSTS13_SCROLL_LINE = 'dataload-tagcategoryposts13-scroll-line';
    public final const MODULE_DATALOAD_TAGCATEGORYPOSTS14_SCROLL_LINE = 'dataload-tagcategoryposts14-scroll-line';
    public final const MODULE_DATALOAD_TAGCATEGORYPOSTS15_SCROLL_LINE = 'dataload-tagcategoryposts15-scroll-line';
    public final const MODULE_DATALOAD_TAGCATEGORYPOSTS16_SCROLL_LINE = 'dataload-tagcategoryposts16-scroll-line';
    public final const MODULE_DATALOAD_TAGCATEGORYPOSTS17_SCROLL_LINE = 'dataload-tagcategoryposts17-scroll-line';
    public final const MODULE_DATALOAD_TAGCATEGORYPOSTS18_SCROLL_LINE = 'dataload-tagcategoryposts18-scroll-line';
    public final const MODULE_DATALOAD_TAGCATEGORYPOSTS19_SCROLL_LINE = 'dataload-tagcategoryposts19-scroll-line';
    public final const MODULE_DATALOAD_CATEGORYPOSTS00_CAROUSEL = 'dataload-categoryposts00-carousel';
    public final const MODULE_DATALOAD_CATEGORYPOSTS01_CAROUSEL = 'dataload-categoryposts01-carousel';
    public final const MODULE_DATALOAD_CATEGORYPOSTS02_CAROUSEL = 'dataload-categoryposts02-carousel';
    public final const MODULE_DATALOAD_CATEGORYPOSTS03_CAROUSEL = 'dataload-categoryposts03-carousel';
    public final const MODULE_DATALOAD_CATEGORYPOSTS04_CAROUSEL = 'dataload-categoryposts04-carousel';
    public final const MODULE_DATALOAD_CATEGORYPOSTS05_CAROUSEL = 'dataload-categoryposts05-carousel';
    public final const MODULE_DATALOAD_CATEGORYPOSTS06_CAROUSEL = 'dataload-categoryposts06-carousel';
    public final const MODULE_DATALOAD_CATEGORYPOSTS07_CAROUSEL = 'dataload-categoryposts07-carousel';
    public final const MODULE_DATALOAD_CATEGORYPOSTS08_CAROUSEL = 'dataload-categoryposts08-carousel';
    public final const MODULE_DATALOAD_CATEGORYPOSTS09_CAROUSEL = 'dataload-categoryposts09-carousel';
    public final const MODULE_DATALOAD_CATEGORYPOSTS10_CAROUSEL = 'dataload-categoryposts10-carousel';
    public final const MODULE_DATALOAD_CATEGORYPOSTS11_CAROUSEL = 'dataload-categoryposts11-carousel';
    public final const MODULE_DATALOAD_CATEGORYPOSTS12_CAROUSEL = 'dataload-categoryposts12-carousel';
    public final const MODULE_DATALOAD_CATEGORYPOSTS13_CAROUSEL = 'dataload-categoryposts13-carousel';
    public final const MODULE_DATALOAD_CATEGORYPOSTS14_CAROUSEL = 'dataload-categoryposts14-carousel';
    public final const MODULE_DATALOAD_CATEGORYPOSTS15_CAROUSEL = 'dataload-categoryposts15-carousel';
    public final const MODULE_DATALOAD_CATEGORYPOSTS16_CAROUSEL = 'dataload-categoryposts16-carousel';
    public final const MODULE_DATALOAD_CATEGORYPOSTS17_CAROUSEL = 'dataload-categoryposts17-carousel';
    public final const MODULE_DATALOAD_CATEGORYPOSTS18_CAROUSEL = 'dataload-categoryposts18-carousel';
    public final const MODULE_DATALOAD_CATEGORYPOSTS19_CAROUSEL = 'dataload-categoryposts19-carousel';
    public final const MODULE_DATALOAD_AUTHORCATEGORYPOSTS00_CAROUSEL = 'dataload-authorcategoryposts00-carousel';
    public final const MODULE_DATALOAD_AUTHORCATEGORYPOSTS01_CAROUSEL = 'dataload-authorcategoryposts01-carousel';
    public final const MODULE_DATALOAD_AUTHORCATEGORYPOSTS02_CAROUSEL = 'dataload-authorcategoryposts02-carousel';
    public final const MODULE_DATALOAD_AUTHORCATEGORYPOSTS03_CAROUSEL = 'dataload-authorcategoryposts03-carousel';
    public final const MODULE_DATALOAD_AUTHORCATEGORYPOSTS04_CAROUSEL = 'dataload-authorcategoryposts04-carousel';
    public final const MODULE_DATALOAD_AUTHORCATEGORYPOSTS05_CAROUSEL = 'dataload-authorcategoryposts05-carousel';
    public final const MODULE_DATALOAD_AUTHORCATEGORYPOSTS06_CAROUSEL = 'dataload-authorcategoryposts06-carousel';
    public final const MODULE_DATALOAD_AUTHORCATEGORYPOSTS07_CAROUSEL = 'dataload-authorcategoryposts07-carousel';
    public final const MODULE_DATALOAD_AUTHORCATEGORYPOSTS08_CAROUSEL = 'dataload-authorcategoryposts08-carousel';
    public final const MODULE_DATALOAD_AUTHORCATEGORYPOSTS09_CAROUSEL = 'dataload-authorcategoryposts09-carousel';
    public final const MODULE_DATALOAD_AUTHORCATEGORYPOSTS10_CAROUSEL = 'dataload-authorcategoryposts10-carousel';
    public final const MODULE_DATALOAD_AUTHORCATEGORYPOSTS11_CAROUSEL = 'dataload-authorcategoryposts11-carousel';
    public final const MODULE_DATALOAD_AUTHORCATEGORYPOSTS12_CAROUSEL = 'dataload-authorcategoryposts12-carousel';
    public final const MODULE_DATALOAD_AUTHORCATEGORYPOSTS13_CAROUSEL = 'dataload-authorcategoryposts13-carousel';
    public final const MODULE_DATALOAD_AUTHORCATEGORYPOSTS14_CAROUSEL = 'dataload-authorcategoryposts14-carousel';
    public final const MODULE_DATALOAD_AUTHORCATEGORYPOSTS15_CAROUSEL = 'dataload-authorcategoryposts15-carousel';
    public final const MODULE_DATALOAD_AUTHORCATEGORYPOSTS16_CAROUSEL = 'dataload-authorcategoryposts16-carousel';
    public final const MODULE_DATALOAD_AUTHORCATEGORYPOSTS17_CAROUSEL = 'dataload-authorcategoryposts17-carousel';
    public final const MODULE_DATALOAD_AUTHORCATEGORYPOSTS18_CAROUSEL = 'dataload-authorcategoryposts18-carousel';
    public final const MODULE_DATALOAD_AUTHORCATEGORYPOSTS19_CAROUSEL = 'dataload-authorcategoryposts19-carousel';
    public final const MODULE_DATALOAD_TAGCATEGORYPOSTS00_CAROUSEL = 'dataload-tagcategoryposts00-carousel';
    public final const MODULE_DATALOAD_TAGCATEGORYPOSTS01_CAROUSEL = 'dataload-tagcategoryposts01-carousel';
    public final const MODULE_DATALOAD_TAGCATEGORYPOSTS02_CAROUSEL = 'dataload-tagcategoryposts02-carousel';
    public final const MODULE_DATALOAD_TAGCATEGORYPOSTS03_CAROUSEL = 'dataload-tagcategoryposts03-carousel';
    public final const MODULE_DATALOAD_TAGCATEGORYPOSTS04_CAROUSEL = 'dataload-tagcategoryposts04-carousel';
    public final const MODULE_DATALOAD_TAGCATEGORYPOSTS05_CAROUSEL = 'dataload-tagcategoryposts05-carousel';
    public final const MODULE_DATALOAD_TAGCATEGORYPOSTS06_CAROUSEL = 'dataload-tagcategoryposts06-carousel';
    public final const MODULE_DATALOAD_TAGCATEGORYPOSTS07_CAROUSEL = 'dataload-tagcategoryposts07-carousel';
    public final const MODULE_DATALOAD_TAGCATEGORYPOSTS08_CAROUSEL = 'dataload-tagcategoryposts08-carousel';
    public final const MODULE_DATALOAD_TAGCATEGORYPOSTS09_CAROUSEL = 'dataload-tagcategoryposts09-carousel';
    public final const MODULE_DATALOAD_TAGCATEGORYPOSTS10_CAROUSEL = 'dataload-tagcategoryposts10-carousel';
    public final const MODULE_DATALOAD_TAGCATEGORYPOSTS11_CAROUSEL = 'dataload-tagcategoryposts11-carousel';
    public final const MODULE_DATALOAD_TAGCATEGORYPOSTS12_CAROUSEL = 'dataload-tagcategoryposts12-carousel';
    public final const MODULE_DATALOAD_TAGCATEGORYPOSTS13_CAROUSEL = 'dataload-tagcategoryposts13-carousel';
    public final const MODULE_DATALOAD_TAGCATEGORYPOSTS14_CAROUSEL = 'dataload-tagcategoryposts14-carousel';
    public final const MODULE_DATALOAD_TAGCATEGORYPOSTS15_CAROUSEL = 'dataload-tagcategoryposts15-carousel';
    public final const MODULE_DATALOAD_TAGCATEGORYPOSTS16_CAROUSEL = 'dataload-tagcategoryposts16-carousel';
    public final const MODULE_DATALOAD_TAGCATEGORYPOSTS17_CAROUSEL = 'dataload-tagcategoryposts17-carousel';
    public final const MODULE_DATALOAD_TAGCATEGORYPOSTS18_CAROUSEL = 'dataload-tagcategoryposts18-carousel';
    public final const MODULE_DATALOAD_TAGCATEGORYPOSTS19_CAROUSEL = 'dataload-tagcategoryposts19-carousel';
    public final const MODULE_DATALOAD_CATEGORYPOSTS00_CAROUSEL_CONTENT = 'dataload-categoryposts00-carousel-content';
    public final const MODULE_DATALOAD_CATEGORYPOSTS01_CAROUSEL_CONTENT = 'dataload-categoryposts01-carousel-content';
    public final const MODULE_DATALOAD_CATEGORYPOSTS02_CAROUSEL_CONTENT = 'dataload-categoryposts02-carousel-content';
    public final const MODULE_DATALOAD_CATEGORYPOSTS03_CAROUSEL_CONTENT = 'dataload-categoryposts03-carousel-content';
    public final const MODULE_DATALOAD_CATEGORYPOSTS04_CAROUSEL_CONTENT = 'dataload-categoryposts04-carousel-content';
    public final const MODULE_DATALOAD_CATEGORYPOSTS05_CAROUSEL_CONTENT = 'dataload-categoryposts05-carousel-content';
    public final const MODULE_DATALOAD_CATEGORYPOSTS06_CAROUSEL_CONTENT = 'dataload-categoryposts06-carousel-content';
    public final const MODULE_DATALOAD_CATEGORYPOSTS07_CAROUSEL_CONTENT = 'dataload-categoryposts07-carousel-content';
    public final const MODULE_DATALOAD_CATEGORYPOSTS08_CAROUSEL_CONTENT = 'dataload-categoryposts08-carousel-content';
    public final const MODULE_DATALOAD_CATEGORYPOSTS09_CAROUSEL_CONTENT = 'dataload-categoryposts09-carousel-content';
    public final const MODULE_DATALOAD_CATEGORYPOSTS10_CAROUSEL_CONTENT = 'dataload-categoryposts10-carousel-content';
    public final const MODULE_DATALOAD_CATEGORYPOSTS11_CAROUSEL_CONTENT = 'dataload-categoryposts11-carousel-content';
    public final const MODULE_DATALOAD_CATEGORYPOSTS12_CAROUSEL_CONTENT = 'dataload-categoryposts12-carousel-content';
    public final const MODULE_DATALOAD_CATEGORYPOSTS13_CAROUSEL_CONTENT = 'dataload-categoryposts13-carousel-content';
    public final const MODULE_DATALOAD_CATEGORYPOSTS14_CAROUSEL_CONTENT = 'dataload-categoryposts14-carousel-content';
    public final const MODULE_DATALOAD_CATEGORYPOSTS15_CAROUSEL_CONTENT = 'dataload-categoryposts15-carousel-content';
    public final const MODULE_DATALOAD_CATEGORYPOSTS16_CAROUSEL_CONTENT = 'dataload-categoryposts16-carousel-content';
    public final const MODULE_DATALOAD_CATEGORYPOSTS17_CAROUSEL_CONTENT = 'dataload-categoryposts17-carousel-content';
    public final const MODULE_DATALOAD_CATEGORYPOSTS18_CAROUSEL_CONTENT = 'dataload-categoryposts18-carousel-content';
    public final const MODULE_DATALOAD_CATEGORYPOSTS19_CAROUSEL_CONTENT = 'dataload-categoryposts19-carousel-content';
    public final const MODULE_DATALOAD_AUTHORCATEGORYPOSTS00_CAROUSEL_CONTENT = 'dataload-authorcategoryposts00-carousel-content';
    public final const MODULE_DATALOAD_AUTHORCATEGORYPOSTS01_CAROUSEL_CONTENT = 'dataload-authorcategoryposts01-carousel-content';
    public final const MODULE_DATALOAD_AUTHORCATEGORYPOSTS02_CAROUSEL_CONTENT = 'dataload-authorcategoryposts02-carousel-content';
    public final const MODULE_DATALOAD_AUTHORCATEGORYPOSTS03_CAROUSEL_CONTENT = 'dataload-authorcategoryposts03-carousel-content';
    public final const MODULE_DATALOAD_AUTHORCATEGORYPOSTS04_CAROUSEL_CONTENT = 'dataload-authorcategoryposts04-carousel-content';
    public final const MODULE_DATALOAD_AUTHORCATEGORYPOSTS05_CAROUSEL_CONTENT = 'dataload-authorcategoryposts05-carousel-content';
    public final const MODULE_DATALOAD_AUTHORCATEGORYPOSTS06_CAROUSEL_CONTENT = 'dataload-authorcategoryposts06-carousel-content';
    public final const MODULE_DATALOAD_AUTHORCATEGORYPOSTS07_CAROUSEL_CONTENT = 'dataload-authorcategoryposts07-carousel-content';
    public final const MODULE_DATALOAD_AUTHORCATEGORYPOSTS08_CAROUSEL_CONTENT = 'dataload-authorcategoryposts08-carousel-content';
    public final const MODULE_DATALOAD_AUTHORCATEGORYPOSTS09_CAROUSEL_CONTENT = 'dataload-authorcategoryposts09-carousel-content';
    public final const MODULE_DATALOAD_AUTHORCATEGORYPOSTS10_CAROUSEL_CONTENT = 'dataload-authorcategoryposts10-carousel-content';
    public final const MODULE_DATALOAD_AUTHORCATEGORYPOSTS11_CAROUSEL_CONTENT = 'dataload-authorcategoryposts11-carousel-content';
    public final const MODULE_DATALOAD_AUTHORCATEGORYPOSTS12_CAROUSEL_CONTENT = 'dataload-authorcategoryposts12-carousel-content';
    public final const MODULE_DATALOAD_AUTHORCATEGORYPOSTS13_CAROUSEL_CONTENT = 'dataload-authorcategoryposts13-carousel-content';
    public final const MODULE_DATALOAD_AUTHORCATEGORYPOSTS14_CAROUSEL_CONTENT = 'dataload-authorcategoryposts14-carousel-content';
    public final const MODULE_DATALOAD_AUTHORCATEGORYPOSTS15_CAROUSEL_CONTENT = 'dataload-authorcategoryposts15-carousel-content';
    public final const MODULE_DATALOAD_AUTHORCATEGORYPOSTS16_CAROUSEL_CONTENT = 'dataload-authorcategoryposts16-carousel-content';
    public final const MODULE_DATALOAD_AUTHORCATEGORYPOSTS17_CAROUSEL_CONTENT = 'dataload-authorcategoryposts17-carousel-content';
    public final const MODULE_DATALOAD_AUTHORCATEGORYPOSTS18_CAROUSEL_CONTENT = 'dataload-authorcategoryposts18-carousel-content';
    public final const MODULE_DATALOAD_AUTHORCATEGORYPOSTS19_CAROUSEL_CONTENT = 'dataload-authorcategoryposts19-carousel-content';
    public final const MODULE_DATALOAD_TAGCATEGORYPOSTS00_CAROUSEL_CONTENT = 'dataload-tagcategoryposts00-carousel-content';
    public final const MODULE_DATALOAD_TAGCATEGORYPOSTS01_CAROUSEL_CONTENT = 'dataload-tagcategoryposts01-carousel-content';
    public final const MODULE_DATALOAD_TAGCATEGORYPOSTS02_CAROUSEL_CONTENT = 'dataload-tagcategoryposts02-carousel-content';
    public final const MODULE_DATALOAD_TAGCATEGORYPOSTS03_CAROUSEL_CONTENT = 'dataload-tagcategoryposts03-carousel-content';
    public final const MODULE_DATALOAD_TAGCATEGORYPOSTS04_CAROUSEL_CONTENT = 'dataload-tagcategoryposts04-carousel-content';
    public final const MODULE_DATALOAD_TAGCATEGORYPOSTS05_CAROUSEL_CONTENT = 'dataload-tagcategoryposts05-carousel-content';
    public final const MODULE_DATALOAD_TAGCATEGORYPOSTS06_CAROUSEL_CONTENT = 'dataload-tagcategoryposts06-carousel-content';
    public final const MODULE_DATALOAD_TAGCATEGORYPOSTS07_CAROUSEL_CONTENT = 'dataload-tagcategoryposts07-carousel-content';
    public final const MODULE_DATALOAD_TAGCATEGORYPOSTS08_CAROUSEL_CONTENT = 'dataload-tagcategoryposts08-carousel-content';
    public final const MODULE_DATALOAD_TAGCATEGORYPOSTS09_CAROUSEL_CONTENT = 'dataload-tagcategoryposts09-carousel-content';
    public final const MODULE_DATALOAD_TAGCATEGORYPOSTS10_CAROUSEL_CONTENT = 'dataload-tagcategoryposts10-carousel-content';
    public final const MODULE_DATALOAD_TAGCATEGORYPOSTS11_CAROUSEL_CONTENT = 'dataload-tagcategoryposts11-carousel-content';
    public final const MODULE_DATALOAD_TAGCATEGORYPOSTS12_CAROUSEL_CONTENT = 'dataload-tagcategoryposts12-carousel-content';
    public final const MODULE_DATALOAD_TAGCATEGORYPOSTS13_CAROUSEL_CONTENT = 'dataload-tagcategoryposts13-carousel-content';
    public final const MODULE_DATALOAD_TAGCATEGORYPOSTS14_CAROUSEL_CONTENT = 'dataload-tagcategoryposts14-carousel-content';
    public final const MODULE_DATALOAD_TAGCATEGORYPOSTS15_CAROUSEL_CONTENT = 'dataload-tagcategoryposts15-carousel-content';
    public final const MODULE_DATALOAD_TAGCATEGORYPOSTS16_CAROUSEL_CONTENT = 'dataload-tagcategoryposts16-carousel-content';
    public final const MODULE_DATALOAD_TAGCATEGORYPOSTS17_CAROUSEL_CONTENT = 'dataload-tagcategoryposts17-carousel-content';
    public final const MODULE_DATALOAD_TAGCATEGORYPOSTS18_CAROUSEL_CONTENT = 'dataload-tagcategoryposts18-carousel-content';
    public final const MODULE_DATALOAD_TAGCATEGORYPOSTS19_CAROUSEL_CONTENT = 'dataload-tagcategoryposts19-carousel-content';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS00_TYPEAHEAD],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS01_TYPEAHEAD],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS02_TYPEAHEAD],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS03_TYPEAHEAD],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS04_TYPEAHEAD],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS05_TYPEAHEAD],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS06_TYPEAHEAD],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS07_TYPEAHEAD],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS08_TYPEAHEAD],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS09_TYPEAHEAD],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS10_TYPEAHEAD],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS11_TYPEAHEAD],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS12_TYPEAHEAD],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS13_TYPEAHEAD],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS14_TYPEAHEAD],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS15_TYPEAHEAD],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS16_TYPEAHEAD],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS17_TYPEAHEAD],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS18_TYPEAHEAD],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS19_TYPEAHEAD],

            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS00_SCROLL_NAVIGATOR],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS01_SCROLL_NAVIGATOR],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS02_SCROLL_NAVIGATOR],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS03_SCROLL_NAVIGATOR],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS04_SCROLL_NAVIGATOR],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS05_SCROLL_NAVIGATOR],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS06_SCROLL_NAVIGATOR],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS07_SCROLL_NAVIGATOR],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS08_SCROLL_NAVIGATOR],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS09_SCROLL_NAVIGATOR],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS10_SCROLL_NAVIGATOR],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS11_SCROLL_NAVIGATOR],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS12_SCROLL_NAVIGATOR],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS13_SCROLL_NAVIGATOR],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS14_SCROLL_NAVIGATOR],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS15_SCROLL_NAVIGATOR],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS16_SCROLL_NAVIGATOR],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS17_SCROLL_NAVIGATOR],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS18_SCROLL_NAVIGATOR],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS19_SCROLL_NAVIGATOR],

            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS00_SCROLL_ADDONS],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS01_SCROLL_ADDONS],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS02_SCROLL_ADDONS],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS03_SCROLL_ADDONS],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS04_SCROLL_ADDONS],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS05_SCROLL_ADDONS],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS06_SCROLL_ADDONS],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS07_SCROLL_ADDONS],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS08_SCROLL_ADDONS],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS09_SCROLL_ADDONS],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS10_SCROLL_ADDONS],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS11_SCROLL_ADDONS],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS12_SCROLL_ADDONS],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS13_SCROLL_ADDONS],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS14_SCROLL_ADDONS],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS15_SCROLL_ADDONS],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS16_SCROLL_ADDONS],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS17_SCROLL_ADDONS],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS18_SCROLL_ADDONS],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS19_SCROLL_ADDONS],

            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS00_SCROLL_DETAILS],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS01_SCROLL_DETAILS],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS02_SCROLL_DETAILS],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS03_SCROLL_DETAILS],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS04_SCROLL_DETAILS],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS05_SCROLL_DETAILS],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS06_SCROLL_DETAILS],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS07_SCROLL_DETAILS],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS08_SCROLL_DETAILS],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS09_SCROLL_DETAILS],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS10_SCROLL_DETAILS],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS11_SCROLL_DETAILS],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS12_SCROLL_DETAILS],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS13_SCROLL_DETAILS],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS14_SCROLL_DETAILS],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS15_SCROLL_DETAILS],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS16_SCROLL_DETAILS],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS17_SCROLL_DETAILS],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS18_SCROLL_DETAILS],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS19_SCROLL_DETAILS],

            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS00_SCROLL_SIMPLEVIEW],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS01_SCROLL_SIMPLEVIEW],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS02_SCROLL_SIMPLEVIEW],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS03_SCROLL_SIMPLEVIEW],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS04_SCROLL_SIMPLEVIEW],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS05_SCROLL_SIMPLEVIEW],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS06_SCROLL_SIMPLEVIEW],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS07_SCROLL_SIMPLEVIEW],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS08_SCROLL_SIMPLEVIEW],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS09_SCROLL_SIMPLEVIEW],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS10_SCROLL_SIMPLEVIEW],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS11_SCROLL_SIMPLEVIEW],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS12_SCROLL_SIMPLEVIEW],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS13_SCROLL_SIMPLEVIEW],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS14_SCROLL_SIMPLEVIEW],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS15_SCROLL_SIMPLEVIEW],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS16_SCROLL_SIMPLEVIEW],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS17_SCROLL_SIMPLEVIEW],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS18_SCROLL_SIMPLEVIEW],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS19_SCROLL_SIMPLEVIEW],

            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS00_SCROLL_FULLVIEW],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS01_SCROLL_FULLVIEW],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS02_SCROLL_FULLVIEW],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS03_SCROLL_FULLVIEW],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS04_SCROLL_FULLVIEW],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS05_SCROLL_FULLVIEW],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS06_SCROLL_FULLVIEW],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS07_SCROLL_FULLVIEW],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS08_SCROLL_FULLVIEW],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS09_SCROLL_FULLVIEW],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS10_SCROLL_FULLVIEW],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS11_SCROLL_FULLVIEW],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS12_SCROLL_FULLVIEW],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS13_SCROLL_FULLVIEW],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS14_SCROLL_FULLVIEW],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS15_SCROLL_FULLVIEW],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS16_SCROLL_FULLVIEW],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS17_SCROLL_FULLVIEW],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS18_SCROLL_FULLVIEW],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS19_SCROLL_FULLVIEW],

            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS00_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS01_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS02_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS03_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS04_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS05_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS06_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS07_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS08_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS09_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS10_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS11_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS12_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS13_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS14_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS15_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS16_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS17_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS18_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS19_SCROLL_THUMBNAIL],

            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS00_SCROLL_LIST],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS01_SCROLL_LIST],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS02_SCROLL_LIST],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS03_SCROLL_LIST],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS04_SCROLL_LIST],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS05_SCROLL_LIST],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS06_SCROLL_LIST],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS07_SCROLL_LIST],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS08_SCROLL_LIST],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS09_SCROLL_LIST],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS10_SCROLL_LIST],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS11_SCROLL_LIST],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS12_SCROLL_LIST],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS13_SCROLL_LIST],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS14_SCROLL_LIST],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS15_SCROLL_LIST],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS16_SCROLL_LIST],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS17_SCROLL_LIST],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS18_SCROLL_LIST],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS19_SCROLL_LIST],

            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS00_SCROLL_LINE],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS01_SCROLL_LINE],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS02_SCROLL_LINE],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS03_SCROLL_LINE],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS04_SCROLL_LINE],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS05_SCROLL_LINE],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS06_SCROLL_LINE],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS07_SCROLL_LINE],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS08_SCROLL_LINE],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS09_SCROLL_LINE],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS10_SCROLL_LINE],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS11_SCROLL_LINE],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS12_SCROLL_LINE],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS13_SCROLL_LINE],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS14_SCROLL_LINE],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS15_SCROLL_LINE],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS16_SCROLL_LINE],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS17_SCROLL_LINE],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS18_SCROLL_LINE],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS19_SCROLL_LINE],

            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS00_SCROLL_DETAILS],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS01_SCROLL_DETAILS],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS02_SCROLL_DETAILS],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS03_SCROLL_DETAILS],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS04_SCROLL_DETAILS],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS05_SCROLL_DETAILS],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS06_SCROLL_DETAILS],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS07_SCROLL_DETAILS],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS08_SCROLL_DETAILS],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS09_SCROLL_DETAILS],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS10_SCROLL_DETAILS],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS11_SCROLL_DETAILS],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS12_SCROLL_DETAILS],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS13_SCROLL_DETAILS],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS14_SCROLL_DETAILS],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS15_SCROLL_DETAILS],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS16_SCROLL_DETAILS],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS17_SCROLL_DETAILS],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS18_SCROLL_DETAILS],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS19_SCROLL_DETAILS],

            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS00_SCROLL_SIMPLEVIEW],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS01_SCROLL_SIMPLEVIEW],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS02_SCROLL_SIMPLEVIEW],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS03_SCROLL_SIMPLEVIEW],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS04_SCROLL_SIMPLEVIEW],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS05_SCROLL_SIMPLEVIEW],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS06_SCROLL_SIMPLEVIEW],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS07_SCROLL_SIMPLEVIEW],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS08_SCROLL_SIMPLEVIEW],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS09_SCROLL_SIMPLEVIEW],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS10_SCROLL_SIMPLEVIEW],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS11_SCROLL_SIMPLEVIEW],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS12_SCROLL_SIMPLEVIEW],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS13_SCROLL_SIMPLEVIEW],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS14_SCROLL_SIMPLEVIEW],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS15_SCROLL_SIMPLEVIEW],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS16_SCROLL_SIMPLEVIEW],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS17_SCROLL_SIMPLEVIEW],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS18_SCROLL_SIMPLEVIEW],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS19_SCROLL_SIMPLEVIEW],

            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS00_SCROLL_FULLVIEW],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS01_SCROLL_FULLVIEW],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS02_SCROLL_FULLVIEW],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS03_SCROLL_FULLVIEW],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS04_SCROLL_FULLVIEW],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS05_SCROLL_FULLVIEW],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS06_SCROLL_FULLVIEW],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS07_SCROLL_FULLVIEW],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS08_SCROLL_FULLVIEW],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS09_SCROLL_FULLVIEW],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS10_SCROLL_FULLVIEW],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS11_SCROLL_FULLVIEW],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS12_SCROLL_FULLVIEW],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS13_SCROLL_FULLVIEW],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS14_SCROLL_FULLVIEW],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS15_SCROLL_FULLVIEW],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS16_SCROLL_FULLVIEW],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS17_SCROLL_FULLVIEW],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS18_SCROLL_FULLVIEW],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS19_SCROLL_FULLVIEW],

            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS00_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS01_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS02_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS03_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS04_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS05_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS06_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS07_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS08_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS09_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS10_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS11_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS12_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS13_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS14_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS15_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS16_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS17_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS18_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS19_SCROLL_THUMBNAIL],

            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS00_SCROLL_LIST],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS01_SCROLL_LIST],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS02_SCROLL_LIST],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS03_SCROLL_LIST],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS04_SCROLL_LIST],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS05_SCROLL_LIST],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS06_SCROLL_LIST],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS07_SCROLL_LIST],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS08_SCROLL_LIST],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS09_SCROLL_LIST],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS10_SCROLL_LIST],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS11_SCROLL_LIST],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS12_SCROLL_LIST],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS13_SCROLL_LIST],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS14_SCROLL_LIST],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS15_SCROLL_LIST],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS16_SCROLL_LIST],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS17_SCROLL_LIST],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS18_SCROLL_LIST],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS19_SCROLL_LIST],

            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS00_SCROLL_LINE],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS01_SCROLL_LINE],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS02_SCROLL_LINE],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS03_SCROLL_LINE],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS04_SCROLL_LINE],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS05_SCROLL_LINE],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS06_SCROLL_LINE],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS07_SCROLL_LINE],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS08_SCROLL_LINE],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS09_SCROLL_LINE],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS10_SCROLL_LINE],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS11_SCROLL_LINE],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS12_SCROLL_LINE],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS13_SCROLL_LINE],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS14_SCROLL_LINE],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS15_SCROLL_LINE],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS16_SCROLL_LINE],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS17_SCROLL_LINE],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS18_SCROLL_LINE],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS19_SCROLL_LINE],

            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS00_SCROLL_DETAILS],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS01_SCROLL_DETAILS],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS02_SCROLL_DETAILS],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS03_SCROLL_DETAILS],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS04_SCROLL_DETAILS],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS05_SCROLL_DETAILS],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS06_SCROLL_DETAILS],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS07_SCROLL_DETAILS],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS08_SCROLL_DETAILS],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS09_SCROLL_DETAILS],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS10_SCROLL_DETAILS],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS11_SCROLL_DETAILS],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS12_SCROLL_DETAILS],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS13_SCROLL_DETAILS],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS14_SCROLL_DETAILS],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS15_SCROLL_DETAILS],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS16_SCROLL_DETAILS],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS17_SCROLL_DETAILS],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS18_SCROLL_DETAILS],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS19_SCROLL_DETAILS],

            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS00_SCROLL_SIMPLEVIEW],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS01_SCROLL_SIMPLEVIEW],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS02_SCROLL_SIMPLEVIEW],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS03_SCROLL_SIMPLEVIEW],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS04_SCROLL_SIMPLEVIEW],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS05_SCROLL_SIMPLEVIEW],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS06_SCROLL_SIMPLEVIEW],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS07_SCROLL_SIMPLEVIEW],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS08_SCROLL_SIMPLEVIEW],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS09_SCROLL_SIMPLEVIEW],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS10_SCROLL_SIMPLEVIEW],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS11_SCROLL_SIMPLEVIEW],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS12_SCROLL_SIMPLEVIEW],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS13_SCROLL_SIMPLEVIEW],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS14_SCROLL_SIMPLEVIEW],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS15_SCROLL_SIMPLEVIEW],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS16_SCROLL_SIMPLEVIEW],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS17_SCROLL_SIMPLEVIEW],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS18_SCROLL_SIMPLEVIEW],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS19_SCROLL_SIMPLEVIEW],

            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS00_SCROLL_FULLVIEW],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS01_SCROLL_FULLVIEW],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS02_SCROLL_FULLVIEW],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS03_SCROLL_FULLVIEW],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS04_SCROLL_FULLVIEW],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS05_SCROLL_FULLVIEW],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS06_SCROLL_FULLVIEW],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS07_SCROLL_FULLVIEW],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS08_SCROLL_FULLVIEW],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS09_SCROLL_FULLVIEW],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS10_SCROLL_FULLVIEW],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS11_SCROLL_FULLVIEW],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS12_SCROLL_FULLVIEW],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS13_SCROLL_FULLVIEW],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS14_SCROLL_FULLVIEW],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS15_SCROLL_FULLVIEW],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS16_SCROLL_FULLVIEW],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS17_SCROLL_FULLVIEW],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS18_SCROLL_FULLVIEW],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS19_SCROLL_FULLVIEW],

            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS00_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS01_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS02_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS03_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS04_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS05_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS06_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS07_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS08_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS09_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS10_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS11_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS12_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS13_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS14_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS15_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS16_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS17_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS18_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS19_SCROLL_THUMBNAIL],

            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS00_SCROLL_LIST],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS01_SCROLL_LIST],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS02_SCROLL_LIST],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS03_SCROLL_LIST],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS04_SCROLL_LIST],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS05_SCROLL_LIST],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS06_SCROLL_LIST],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS07_SCROLL_LIST],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS08_SCROLL_LIST],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS09_SCROLL_LIST],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS10_SCROLL_LIST],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS11_SCROLL_LIST],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS12_SCROLL_LIST],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS13_SCROLL_LIST],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS14_SCROLL_LIST],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS15_SCROLL_LIST],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS16_SCROLL_LIST],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS17_SCROLL_LIST],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS18_SCROLL_LIST],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS19_SCROLL_LIST],

            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS00_SCROLL_LINE],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS01_SCROLL_LINE],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS02_SCROLL_LINE],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS03_SCROLL_LINE],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS04_SCROLL_LINE],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS05_SCROLL_LINE],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS06_SCROLL_LINE],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS07_SCROLL_LINE],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS08_SCROLL_LINE],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS09_SCROLL_LINE],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS10_SCROLL_LINE],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS11_SCROLL_LINE],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS12_SCROLL_LINE],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS13_SCROLL_LINE],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS14_SCROLL_LINE],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS15_SCROLL_LINE],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS16_SCROLL_LINE],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS17_SCROLL_LINE],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS18_SCROLL_LINE],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS19_SCROLL_LINE],

            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS00_CAROUSEL],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS01_CAROUSEL],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS02_CAROUSEL],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS03_CAROUSEL],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS04_CAROUSEL],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS05_CAROUSEL],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS06_CAROUSEL],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS07_CAROUSEL],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS08_CAROUSEL],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS09_CAROUSEL],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS10_CAROUSEL],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS11_CAROUSEL],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS12_CAROUSEL],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS13_CAROUSEL],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS14_CAROUSEL],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS15_CAROUSEL],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS16_CAROUSEL],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS17_CAROUSEL],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS18_CAROUSEL],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS19_CAROUSEL],

            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS00_CAROUSEL_CONTENT],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS01_CAROUSEL_CONTENT],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS02_CAROUSEL_CONTENT],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS03_CAROUSEL_CONTENT],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS04_CAROUSEL_CONTENT],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS05_CAROUSEL_CONTENT],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS06_CAROUSEL_CONTENT],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS07_CAROUSEL_CONTENT],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS08_CAROUSEL_CONTENT],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS09_CAROUSEL_CONTENT],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS10_CAROUSEL_CONTENT],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS11_CAROUSEL_CONTENT],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS12_CAROUSEL_CONTENT],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS13_CAROUSEL_CONTENT],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS14_CAROUSEL_CONTENT],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS15_CAROUSEL_CONTENT],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS16_CAROUSEL_CONTENT],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS17_CAROUSEL_CONTENT],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS18_CAROUSEL_CONTENT],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS19_CAROUSEL_CONTENT],

            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS00_CAROUSEL],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS01_CAROUSEL],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS02_CAROUSEL],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS03_CAROUSEL],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS04_CAROUSEL],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS05_CAROUSEL],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS06_CAROUSEL],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS07_CAROUSEL],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS08_CAROUSEL],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS09_CAROUSEL],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS10_CAROUSEL],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS11_CAROUSEL],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS12_CAROUSEL],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS13_CAROUSEL],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS14_CAROUSEL],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS15_CAROUSEL],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS16_CAROUSEL],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS17_CAROUSEL],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS18_CAROUSEL],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS19_CAROUSEL],

            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS00_CAROUSEL_CONTENT],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS01_CAROUSEL_CONTENT],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS02_CAROUSEL_CONTENT],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS03_CAROUSEL_CONTENT],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS04_CAROUSEL_CONTENT],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS05_CAROUSEL_CONTENT],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS06_CAROUSEL_CONTENT],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS07_CAROUSEL_CONTENT],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS08_CAROUSEL_CONTENT],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS09_CAROUSEL_CONTENT],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS10_CAROUSEL_CONTENT],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS11_CAROUSEL_CONTENT],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS12_CAROUSEL_CONTENT],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS13_CAROUSEL_CONTENT],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS14_CAROUSEL_CONTENT],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS15_CAROUSEL_CONTENT],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS16_CAROUSEL_CONTENT],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS17_CAROUSEL_CONTENT],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS18_CAROUSEL_CONTENT],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS19_CAROUSEL_CONTENT],

            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS00_CAROUSEL],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS01_CAROUSEL],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS02_CAROUSEL],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS03_CAROUSEL],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS04_CAROUSEL],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS05_CAROUSEL],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS06_CAROUSEL],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS07_CAROUSEL],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS08_CAROUSEL],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS09_CAROUSEL],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS10_CAROUSEL],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS11_CAROUSEL],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS12_CAROUSEL],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS13_CAROUSEL],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS14_CAROUSEL],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS15_CAROUSEL],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS16_CAROUSEL],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS17_CAROUSEL],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS18_CAROUSEL],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS19_CAROUSEL],

            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS00_CAROUSEL_CONTENT],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS01_CAROUSEL_CONTENT],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS02_CAROUSEL_CONTENT],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS03_CAROUSEL_CONTENT],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS04_CAROUSEL_CONTENT],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS05_CAROUSEL_CONTENT],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS06_CAROUSEL_CONTENT],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS07_CAROUSEL_CONTENT],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS08_CAROUSEL_CONTENT],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS09_CAROUSEL_CONTENT],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS10_CAROUSEL_CONTENT],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS11_CAROUSEL_CONTENT],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS12_CAROUSEL_CONTENT],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS13_CAROUSEL_CONTENT],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS14_CAROUSEL_CONTENT],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS15_CAROUSEL_CONTENT],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS16_CAROUSEL_CONTENT],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS17_CAROUSEL_CONTENT],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS18_CAROUSEL_CONTENT],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS19_CAROUSEL_CONTENT],
        );
    }

    public function getRelevantRoute(array $componentVariation, array &$props): ?string
    {
        return match($componentVariation[1]) {
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS00_CAROUSEL => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS00,
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS00_SCROLL_DETAILS => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS00,
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS00_SCROLL_FULLVIEW => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS00,
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS00_SCROLL_LINE => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS00,
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS00_SCROLL_LIST => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS00,
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS00_SCROLL_SIMPLEVIEW => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS00,
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS00_SCROLL_THUMBNAIL => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS00,
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS01_CAROUSEL => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS01,
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS01_SCROLL_DETAILS => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS01,
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS01_SCROLL_FULLVIEW => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS01,
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS01_SCROLL_LINE => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS01,
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS01_SCROLL_LIST => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS01,
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS01_SCROLL_SIMPLEVIEW => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS01,
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS01_SCROLL_THUMBNAIL => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS01,
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS02_CAROUSEL => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS02,
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS02_SCROLL_DETAILS => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS02,
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS02_SCROLL_FULLVIEW => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS02,
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS02_SCROLL_LINE => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS02,
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS02_SCROLL_LIST => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS02,
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS02_SCROLL_SIMPLEVIEW => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS02,
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS02_SCROLL_THUMBNAIL => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS02,
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS03_CAROUSEL => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS03,
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS03_SCROLL_DETAILS => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS03,
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS03_SCROLL_FULLVIEW => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS03,
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS03_SCROLL_LINE => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS03,
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS03_SCROLL_LIST => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS03,
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS03_SCROLL_SIMPLEVIEW => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS03,
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS03_SCROLL_THUMBNAIL => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS03,
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS04_CAROUSEL => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS04,
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS04_SCROLL_DETAILS => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS04,
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS04_SCROLL_FULLVIEW => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS04,
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS04_SCROLL_LINE => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS04,
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS04_SCROLL_LIST => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS04,
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS04_SCROLL_SIMPLEVIEW => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS04,
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS04_SCROLL_THUMBNAIL => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS04,
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS05_CAROUSEL => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS05,
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS05_SCROLL_DETAILS => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS05,
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS05_SCROLL_FULLVIEW => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS05,
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS05_SCROLL_LINE => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS05,
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS05_SCROLL_LIST => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS05,
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS05_SCROLL_SIMPLEVIEW => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS05,
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS05_SCROLL_THUMBNAIL => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS05,
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS06_CAROUSEL => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS06,
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS06_SCROLL_DETAILS => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS06,
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS06_SCROLL_FULLVIEW => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS06,
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS06_SCROLL_LINE => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS06,
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS06_SCROLL_LIST => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS06,
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS06_SCROLL_SIMPLEVIEW => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS06,
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS06_SCROLL_THUMBNAIL => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS06,
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS07_CAROUSEL => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS07,
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS07_SCROLL_DETAILS => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS07,
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS07_SCROLL_FULLVIEW => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS07,
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS07_SCROLL_LINE => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS07,
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS07_SCROLL_LIST => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS07,
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS07_SCROLL_SIMPLEVIEW => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS07,
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS07_SCROLL_THUMBNAIL => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS07,
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS08_CAROUSEL => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS08,
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS08_SCROLL_DETAILS => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS08,
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS08_SCROLL_FULLVIEW => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS08,
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS08_SCROLL_LINE => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS08,
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS08_SCROLL_LIST => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS08,
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS08_SCROLL_SIMPLEVIEW => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS08,
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS08_SCROLL_THUMBNAIL => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS08,
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS09_CAROUSEL => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS09,
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS09_SCROLL_DETAILS => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS09,
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS09_SCROLL_FULLVIEW => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS09,
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS09_SCROLL_LINE => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS09,
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS09_SCROLL_LIST => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS09,
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS09_SCROLL_SIMPLEVIEW => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS09,
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS09_SCROLL_THUMBNAIL => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS09,
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS10_CAROUSEL => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS10,
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS10_SCROLL_DETAILS => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS10,
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS10_SCROLL_FULLVIEW => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS10,
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS10_SCROLL_LINE => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS10,
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS10_SCROLL_LIST => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS10,
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS10_SCROLL_SIMPLEVIEW => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS10,
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS10_SCROLL_THUMBNAIL => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS10,
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS11_CAROUSEL => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS11,
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS11_SCROLL_DETAILS => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS11,
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS11_SCROLL_FULLVIEW => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS11,
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS11_SCROLL_LINE => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS11,
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS11_SCROLL_LIST => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS11,
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS11_SCROLL_SIMPLEVIEW => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS11,
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS11_SCROLL_THUMBNAIL => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS11,
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS12_CAROUSEL => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS12,
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS12_SCROLL_DETAILS => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS12,
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS12_SCROLL_FULLVIEW => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS12,
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS12_SCROLL_LINE => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS12,
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS12_SCROLL_LIST => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS12,
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS12_SCROLL_SIMPLEVIEW => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS12,
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS12_SCROLL_THUMBNAIL => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS12,
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS13_CAROUSEL => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS13,
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS13_SCROLL_DETAILS => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS13,
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS13_SCROLL_FULLVIEW => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS13,
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS13_SCROLL_LINE => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS13,
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS13_SCROLL_LIST => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS13,
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS13_SCROLL_SIMPLEVIEW => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS13,
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS13_SCROLL_THUMBNAIL => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS13,
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS14_CAROUSEL => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS14,
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS14_SCROLL_DETAILS => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS14,
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS14_SCROLL_FULLVIEW => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS14,
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS14_SCROLL_LINE => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS14,
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS14_SCROLL_LIST => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS14,
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS14_SCROLL_SIMPLEVIEW => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS14,
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS14_SCROLL_THUMBNAIL => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS14,
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS15_CAROUSEL => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS15,
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS15_SCROLL_DETAILS => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS15,
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS15_SCROLL_FULLVIEW => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS15,
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS15_SCROLL_LINE => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS15,
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS15_SCROLL_LIST => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS15,
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS15_SCROLL_SIMPLEVIEW => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS15,
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS15_SCROLL_THUMBNAIL => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS15,
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS16_CAROUSEL => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS16,
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS16_SCROLL_DETAILS => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS16,
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS16_SCROLL_FULLVIEW => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS16,
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS16_SCROLL_LINE => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS16,
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS16_SCROLL_LIST => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS16,
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS16_SCROLL_SIMPLEVIEW => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS16,
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS16_SCROLL_THUMBNAIL => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS16,
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS17_CAROUSEL => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS17,
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS17_SCROLL_DETAILS => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS17,
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS17_SCROLL_FULLVIEW => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS17,
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS17_SCROLL_LINE => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS17,
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS17_SCROLL_LIST => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS17,
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS17_SCROLL_SIMPLEVIEW => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS17,
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS17_SCROLL_THUMBNAIL => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS17,
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS18_CAROUSEL => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS18,
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS18_SCROLL_DETAILS => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS18,
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS18_SCROLL_FULLVIEW => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS18,
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS18_SCROLL_LINE => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS18,
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS18_SCROLL_LIST => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS18,
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS18_SCROLL_SIMPLEVIEW => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS18,
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS18_SCROLL_THUMBNAIL => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS18,
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS19_CAROUSEL => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS19,
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS19_SCROLL_DETAILS => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS19,
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS19_SCROLL_FULLVIEW => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS19,
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS19_SCROLL_LINE => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS19,
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS19_SCROLL_LIST => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS19,
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS19_SCROLL_SIMPLEVIEW => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS19,
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS19_SCROLL_THUMBNAIL => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS19,
            self::MODULE_DATALOAD_CATEGORYPOSTS00_CAROUSEL => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS00,
            self::MODULE_DATALOAD_CATEGORYPOSTS00_CAROUSEL => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS00,
            self::MODULE_DATALOAD_CATEGORYPOSTS00_CAROUSEL_CONTENT => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS00,
            self::MODULE_DATALOAD_CATEGORYPOSTS00_CAROUSEL_CONTENT => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS00,
            self::MODULE_DATALOAD_CATEGORYPOSTS00_SCROLL_ADDONS => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS00,
            self::MODULE_DATALOAD_CATEGORYPOSTS00_SCROLL_DETAILS => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS00,
            self::MODULE_DATALOAD_CATEGORYPOSTS00_SCROLL_FULLVIEW => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS00,
            self::MODULE_DATALOAD_CATEGORYPOSTS00_SCROLL_LINE => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS00,
            self::MODULE_DATALOAD_CATEGORYPOSTS00_SCROLL_LIST => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS00,
            self::MODULE_DATALOAD_CATEGORYPOSTS00_SCROLL_NAVIGATOR => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS00,
            self::MODULE_DATALOAD_CATEGORYPOSTS00_SCROLL_SIMPLEVIEW => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS00,
            self::MODULE_DATALOAD_CATEGORYPOSTS00_SCROLL_THUMBNAIL => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS00,
            self::MODULE_DATALOAD_CATEGORYPOSTS00_TYPEAHEAD => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS00,
            self::MODULE_DATALOAD_CATEGORYPOSTS01_CAROUSEL => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS01,
            self::MODULE_DATALOAD_CATEGORYPOSTS01_CAROUSEL => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS01,
            self::MODULE_DATALOAD_CATEGORYPOSTS01_CAROUSEL_CONTENT => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS01,
            self::MODULE_DATALOAD_CATEGORYPOSTS01_CAROUSEL_CONTENT => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS01,
            self::MODULE_DATALOAD_CATEGORYPOSTS01_SCROLL_ADDONS => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS01,
            self::MODULE_DATALOAD_CATEGORYPOSTS01_SCROLL_DETAILS => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS01,
            self::MODULE_DATALOAD_CATEGORYPOSTS01_SCROLL_FULLVIEW => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS01,
            self::MODULE_DATALOAD_CATEGORYPOSTS01_SCROLL_LINE => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS01,
            self::MODULE_DATALOAD_CATEGORYPOSTS01_SCROLL_LIST => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS01,
            self::MODULE_DATALOAD_CATEGORYPOSTS01_SCROLL_NAVIGATOR => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS01,
            self::MODULE_DATALOAD_CATEGORYPOSTS01_SCROLL_SIMPLEVIEW => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS01,
            self::MODULE_DATALOAD_CATEGORYPOSTS01_SCROLL_THUMBNAIL => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS01,
            self::MODULE_DATALOAD_CATEGORYPOSTS01_TYPEAHEAD => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS01,
            self::MODULE_DATALOAD_CATEGORYPOSTS02_CAROUSEL => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS02,
            self::MODULE_DATALOAD_CATEGORYPOSTS02_CAROUSEL_CONTENT => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS02,
            self::MODULE_DATALOAD_CATEGORYPOSTS02_SCROLL_ADDONS => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS02,
            self::MODULE_DATALOAD_CATEGORYPOSTS02_SCROLL_DETAILS => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS02,
            self::MODULE_DATALOAD_CATEGORYPOSTS02_SCROLL_FULLVIEW => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS02,
            self::MODULE_DATALOAD_CATEGORYPOSTS02_SCROLL_LINE => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS02,
            self::MODULE_DATALOAD_CATEGORYPOSTS02_SCROLL_LIST => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS02,
            self::MODULE_DATALOAD_CATEGORYPOSTS02_SCROLL_NAVIGATOR => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS02,
            self::MODULE_DATALOAD_CATEGORYPOSTS02_SCROLL_SIMPLEVIEW => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS02,
            self::MODULE_DATALOAD_CATEGORYPOSTS02_SCROLL_THUMBNAIL => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS02,
            self::MODULE_DATALOAD_CATEGORYPOSTS02_TYPEAHEAD => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS02,
            self::MODULE_DATALOAD_CATEGORYPOSTS03_CAROUSEL => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS03,
            self::MODULE_DATALOAD_CATEGORYPOSTS03_CAROUSEL_CONTENT => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS03,
            self::MODULE_DATALOAD_CATEGORYPOSTS03_SCROLL_ADDONS => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS03,
            self::MODULE_DATALOAD_CATEGORYPOSTS03_SCROLL_DETAILS => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS03,
            self::MODULE_DATALOAD_CATEGORYPOSTS03_SCROLL_FULLVIEW => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS03,
            self::MODULE_DATALOAD_CATEGORYPOSTS03_SCROLL_LINE => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS03,
            self::MODULE_DATALOAD_CATEGORYPOSTS03_SCROLL_LIST => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS03,
            self::MODULE_DATALOAD_CATEGORYPOSTS03_SCROLL_NAVIGATOR => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS03,
            self::MODULE_DATALOAD_CATEGORYPOSTS03_SCROLL_SIMPLEVIEW => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS03,
            self::MODULE_DATALOAD_CATEGORYPOSTS03_SCROLL_THUMBNAIL => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS03,
            self::MODULE_DATALOAD_CATEGORYPOSTS03_TYPEAHEAD => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS03,
            self::MODULE_DATALOAD_CATEGORYPOSTS04_CAROUSEL => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS04,
            self::MODULE_DATALOAD_CATEGORYPOSTS04_CAROUSEL_CONTENT => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS04,
            self::MODULE_DATALOAD_CATEGORYPOSTS04_SCROLL_ADDONS => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS04,
            self::MODULE_DATALOAD_CATEGORYPOSTS04_SCROLL_DETAILS => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS04,
            self::MODULE_DATALOAD_CATEGORYPOSTS04_SCROLL_FULLVIEW => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS04,
            self::MODULE_DATALOAD_CATEGORYPOSTS04_SCROLL_LINE => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS04,
            self::MODULE_DATALOAD_CATEGORYPOSTS04_SCROLL_LIST => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS04,
            self::MODULE_DATALOAD_CATEGORYPOSTS04_SCROLL_NAVIGATOR => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS04,
            self::MODULE_DATALOAD_CATEGORYPOSTS04_SCROLL_SIMPLEVIEW => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS04,
            self::MODULE_DATALOAD_CATEGORYPOSTS04_SCROLL_THUMBNAIL => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS04,
            self::MODULE_DATALOAD_CATEGORYPOSTS04_TYPEAHEAD => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS04,
            self::MODULE_DATALOAD_CATEGORYPOSTS05_CAROUSEL => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS05,
            self::MODULE_DATALOAD_CATEGORYPOSTS05_CAROUSEL_CONTENT => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS05,
            self::MODULE_DATALOAD_CATEGORYPOSTS05_SCROLL_ADDONS => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS05,
            self::MODULE_DATALOAD_CATEGORYPOSTS05_SCROLL_DETAILS => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS05,
            self::MODULE_DATALOAD_CATEGORYPOSTS05_SCROLL_FULLVIEW => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS05,
            self::MODULE_DATALOAD_CATEGORYPOSTS05_SCROLL_LINE => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS05,
            self::MODULE_DATALOAD_CATEGORYPOSTS05_SCROLL_LIST => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS05,
            self::MODULE_DATALOAD_CATEGORYPOSTS05_SCROLL_NAVIGATOR => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS05,
            self::MODULE_DATALOAD_CATEGORYPOSTS05_SCROLL_SIMPLEVIEW => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS05,
            self::MODULE_DATALOAD_CATEGORYPOSTS05_SCROLL_THUMBNAIL => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS05,
            self::MODULE_DATALOAD_CATEGORYPOSTS05_TYPEAHEAD => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS05,
            self::MODULE_DATALOAD_CATEGORYPOSTS06_CAROUSEL => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS06,
            self::MODULE_DATALOAD_CATEGORYPOSTS06_CAROUSEL_CONTENT => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS06,
            self::MODULE_DATALOAD_CATEGORYPOSTS06_SCROLL_ADDONS => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS06,
            self::MODULE_DATALOAD_CATEGORYPOSTS06_SCROLL_DETAILS => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS06,
            self::MODULE_DATALOAD_CATEGORYPOSTS06_SCROLL_FULLVIEW => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS06,
            self::MODULE_DATALOAD_CATEGORYPOSTS06_SCROLL_LINE => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS06,
            self::MODULE_DATALOAD_CATEGORYPOSTS06_SCROLL_LIST => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS06,
            self::MODULE_DATALOAD_CATEGORYPOSTS06_SCROLL_NAVIGATOR => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS06,
            self::MODULE_DATALOAD_CATEGORYPOSTS06_SCROLL_SIMPLEVIEW => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS06,
            self::MODULE_DATALOAD_CATEGORYPOSTS06_SCROLL_THUMBNAIL => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS06,
            self::MODULE_DATALOAD_CATEGORYPOSTS06_TYPEAHEAD => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS06,
            self::MODULE_DATALOAD_CATEGORYPOSTS07_CAROUSEL => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS07,
            self::MODULE_DATALOAD_CATEGORYPOSTS07_CAROUSEL_CONTENT => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS07,
            self::MODULE_DATALOAD_CATEGORYPOSTS07_SCROLL_ADDONS => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS07,
            self::MODULE_DATALOAD_CATEGORYPOSTS07_SCROLL_DETAILS => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS07,
            self::MODULE_DATALOAD_CATEGORYPOSTS07_SCROLL_FULLVIEW => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS07,
            self::MODULE_DATALOAD_CATEGORYPOSTS07_SCROLL_LINE => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS07,
            self::MODULE_DATALOAD_CATEGORYPOSTS07_SCROLL_LIST => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS07,
            self::MODULE_DATALOAD_CATEGORYPOSTS07_SCROLL_NAVIGATOR => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS07,
            self::MODULE_DATALOAD_CATEGORYPOSTS07_SCROLL_SIMPLEVIEW => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS07,
            self::MODULE_DATALOAD_CATEGORYPOSTS07_SCROLL_THUMBNAIL => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS07,
            self::MODULE_DATALOAD_CATEGORYPOSTS07_TYPEAHEAD => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS07,
            self::MODULE_DATALOAD_CATEGORYPOSTS08_CAROUSEL => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS08,
            self::MODULE_DATALOAD_CATEGORYPOSTS08_CAROUSEL_CONTENT => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS08,
            self::MODULE_DATALOAD_CATEGORYPOSTS08_SCROLL_ADDONS => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS08,
            self::MODULE_DATALOAD_CATEGORYPOSTS08_SCROLL_DETAILS => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS08,
            self::MODULE_DATALOAD_CATEGORYPOSTS08_SCROLL_FULLVIEW => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS08,
            self::MODULE_DATALOAD_CATEGORYPOSTS08_SCROLL_LINE => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS08,
            self::MODULE_DATALOAD_CATEGORYPOSTS08_SCROLL_LIST => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS08,
            self::MODULE_DATALOAD_CATEGORYPOSTS08_SCROLL_NAVIGATOR => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS08,
            self::MODULE_DATALOAD_CATEGORYPOSTS08_SCROLL_SIMPLEVIEW => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS08,
            self::MODULE_DATALOAD_CATEGORYPOSTS08_SCROLL_THUMBNAIL => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS08,
            self::MODULE_DATALOAD_CATEGORYPOSTS08_TYPEAHEAD => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS08,
            self::MODULE_DATALOAD_CATEGORYPOSTS09_CAROUSEL => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS09,
            self::MODULE_DATALOAD_CATEGORYPOSTS09_CAROUSEL_CONTENT => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS09,
            self::MODULE_DATALOAD_CATEGORYPOSTS09_SCROLL_ADDONS => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS09,
            self::MODULE_DATALOAD_CATEGORYPOSTS09_SCROLL_DETAILS => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS09,
            self::MODULE_DATALOAD_CATEGORYPOSTS09_SCROLL_FULLVIEW => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS09,
            self::MODULE_DATALOAD_CATEGORYPOSTS09_SCROLL_LINE => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS09,
            self::MODULE_DATALOAD_CATEGORYPOSTS09_SCROLL_LIST => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS09,
            self::MODULE_DATALOAD_CATEGORYPOSTS09_SCROLL_NAVIGATOR => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS09,
            self::MODULE_DATALOAD_CATEGORYPOSTS09_SCROLL_SIMPLEVIEW => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS09,
            self::MODULE_DATALOAD_CATEGORYPOSTS09_SCROLL_THUMBNAIL => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS09,
            self::MODULE_DATALOAD_CATEGORYPOSTS09_TYPEAHEAD => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS09,
            self::MODULE_DATALOAD_CATEGORYPOSTS10_SCROLL_ADDONS => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS10,
            self::MODULE_DATALOAD_CATEGORYPOSTS10_SCROLL_DETAILS => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS10,
            self::MODULE_DATALOAD_CATEGORYPOSTS10_SCROLL_FULLVIEW => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS10,
            self::MODULE_DATALOAD_CATEGORYPOSTS10_SCROLL_LINE => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS10,
            self::MODULE_DATALOAD_CATEGORYPOSTS10_SCROLL_LIST => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS10,
            self::MODULE_DATALOAD_CATEGORYPOSTS10_SCROLL_NAVIGATOR => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS10,
            self::MODULE_DATALOAD_CATEGORYPOSTS10_SCROLL_SIMPLEVIEW => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS10,
            self::MODULE_DATALOAD_CATEGORYPOSTS10_SCROLL_THUMBNAIL => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS10,
            self::MODULE_DATALOAD_CATEGORYPOSTS10_TYPEAHEAD => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS10,
            self::MODULE_DATALOAD_CATEGORYPOSTS11_SCROLL_ADDONS => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS11,
            self::MODULE_DATALOAD_CATEGORYPOSTS11_SCROLL_DETAILS => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS11,
            self::MODULE_DATALOAD_CATEGORYPOSTS11_SCROLL_FULLVIEW => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS11,
            self::MODULE_DATALOAD_CATEGORYPOSTS11_SCROLL_LINE => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS11,
            self::MODULE_DATALOAD_CATEGORYPOSTS11_SCROLL_LIST => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS11,
            self::MODULE_DATALOAD_CATEGORYPOSTS11_SCROLL_NAVIGATOR => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS11,
            self::MODULE_DATALOAD_CATEGORYPOSTS11_SCROLL_SIMPLEVIEW => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS11,
            self::MODULE_DATALOAD_CATEGORYPOSTS11_SCROLL_THUMBNAIL => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS11,
            self::MODULE_DATALOAD_CATEGORYPOSTS11_TYPEAHEAD => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS11,
            self::MODULE_DATALOAD_CATEGORYPOSTS12_CAROUSEL => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS12,
            self::MODULE_DATALOAD_CATEGORYPOSTS12_CAROUSEL_CONTENT => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS12,
            self::MODULE_DATALOAD_CATEGORYPOSTS12_SCROLL_ADDONS => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS12,
            self::MODULE_DATALOAD_CATEGORYPOSTS12_SCROLL_DETAILS => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS12,
            self::MODULE_DATALOAD_CATEGORYPOSTS12_SCROLL_FULLVIEW => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS12,
            self::MODULE_DATALOAD_CATEGORYPOSTS12_SCROLL_LINE => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS12,
            self::MODULE_DATALOAD_CATEGORYPOSTS12_SCROLL_LIST => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS12,
            self::MODULE_DATALOAD_CATEGORYPOSTS12_SCROLL_NAVIGATOR => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS12,
            self::MODULE_DATALOAD_CATEGORYPOSTS12_SCROLL_SIMPLEVIEW => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS12,
            self::MODULE_DATALOAD_CATEGORYPOSTS12_SCROLL_THUMBNAIL => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS12,
            self::MODULE_DATALOAD_CATEGORYPOSTS12_TYPEAHEAD => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS12,
            self::MODULE_DATALOAD_CATEGORYPOSTS13_CAROUSEL => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS13,
            self::MODULE_DATALOAD_CATEGORYPOSTS13_CAROUSEL_CONTENT => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS13,
            self::MODULE_DATALOAD_CATEGORYPOSTS13_SCROLL_ADDONS => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS13,
            self::MODULE_DATALOAD_CATEGORYPOSTS13_SCROLL_DETAILS => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS13,
            self::MODULE_DATALOAD_CATEGORYPOSTS13_SCROLL_FULLVIEW => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS13,
            self::MODULE_DATALOAD_CATEGORYPOSTS13_SCROLL_LINE => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS13,
            self::MODULE_DATALOAD_CATEGORYPOSTS13_SCROLL_LIST => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS13,
            self::MODULE_DATALOAD_CATEGORYPOSTS13_SCROLL_NAVIGATOR => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS13,
            self::MODULE_DATALOAD_CATEGORYPOSTS13_SCROLL_SIMPLEVIEW => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS13,
            self::MODULE_DATALOAD_CATEGORYPOSTS13_SCROLL_THUMBNAIL => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS13,
            self::MODULE_DATALOAD_CATEGORYPOSTS13_TYPEAHEAD => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS13,
            self::MODULE_DATALOAD_CATEGORYPOSTS14_CAROUSEL => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS14,
            self::MODULE_DATALOAD_CATEGORYPOSTS14_CAROUSEL_CONTENT => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS14,
            self::MODULE_DATALOAD_CATEGORYPOSTS14_SCROLL_ADDONS => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS14,
            self::MODULE_DATALOAD_CATEGORYPOSTS14_SCROLL_DETAILS => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS14,
            self::MODULE_DATALOAD_CATEGORYPOSTS14_SCROLL_FULLVIEW => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS14,
            self::MODULE_DATALOAD_CATEGORYPOSTS14_SCROLL_LINE => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS14,
            self::MODULE_DATALOAD_CATEGORYPOSTS14_SCROLL_LIST => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS14,
            self::MODULE_DATALOAD_CATEGORYPOSTS14_SCROLL_NAVIGATOR => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS14,
            self::MODULE_DATALOAD_CATEGORYPOSTS14_SCROLL_SIMPLEVIEW => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS14,
            self::MODULE_DATALOAD_CATEGORYPOSTS14_SCROLL_THUMBNAIL => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS14,
            self::MODULE_DATALOAD_CATEGORYPOSTS14_TYPEAHEAD => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS14,
            self::MODULE_DATALOAD_CATEGORYPOSTS15_CAROUSEL => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS15,
            self::MODULE_DATALOAD_CATEGORYPOSTS15_CAROUSEL_CONTENT => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS15,
            self::MODULE_DATALOAD_CATEGORYPOSTS15_SCROLL_ADDONS => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS15,
            self::MODULE_DATALOAD_CATEGORYPOSTS15_SCROLL_DETAILS => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS15,
            self::MODULE_DATALOAD_CATEGORYPOSTS15_SCROLL_FULLVIEW => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS15,
            self::MODULE_DATALOAD_CATEGORYPOSTS15_SCROLL_LINE => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS15,
            self::MODULE_DATALOAD_CATEGORYPOSTS15_SCROLL_LIST => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS15,
            self::MODULE_DATALOAD_CATEGORYPOSTS15_SCROLL_NAVIGATOR => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS15,
            self::MODULE_DATALOAD_CATEGORYPOSTS15_SCROLL_SIMPLEVIEW => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS15,
            self::MODULE_DATALOAD_CATEGORYPOSTS15_SCROLL_THUMBNAIL => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS15,
            self::MODULE_DATALOAD_CATEGORYPOSTS15_TYPEAHEAD => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS15,
            self::MODULE_DATALOAD_CATEGORYPOSTS16_CAROUSEL => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS16,
            self::MODULE_DATALOAD_CATEGORYPOSTS16_CAROUSEL_CONTENT => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS16,
            self::MODULE_DATALOAD_CATEGORYPOSTS16_SCROLL_ADDONS => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS16,
            self::MODULE_DATALOAD_CATEGORYPOSTS16_SCROLL_DETAILS => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS16,
            self::MODULE_DATALOAD_CATEGORYPOSTS16_SCROLL_FULLVIEW => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS16,
            self::MODULE_DATALOAD_CATEGORYPOSTS16_SCROLL_LINE => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS16,
            self::MODULE_DATALOAD_CATEGORYPOSTS16_SCROLL_LIST => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS16,
            self::MODULE_DATALOAD_CATEGORYPOSTS16_SCROLL_NAVIGATOR => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS16,
            self::MODULE_DATALOAD_CATEGORYPOSTS16_SCROLL_SIMPLEVIEW => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS16,
            self::MODULE_DATALOAD_CATEGORYPOSTS16_SCROLL_THUMBNAIL => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS16,
            self::MODULE_DATALOAD_CATEGORYPOSTS16_TYPEAHEAD => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS16,
            self::MODULE_DATALOAD_CATEGORYPOSTS17_CAROUSEL => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS17,
            self::MODULE_DATALOAD_CATEGORYPOSTS17_CAROUSEL_CONTENT => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS17,
            self::MODULE_DATALOAD_CATEGORYPOSTS17_SCROLL_ADDONS => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS17,
            self::MODULE_DATALOAD_CATEGORYPOSTS17_SCROLL_DETAILS => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS17,
            self::MODULE_DATALOAD_CATEGORYPOSTS17_SCROLL_FULLVIEW => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS17,
            self::MODULE_DATALOAD_CATEGORYPOSTS17_SCROLL_LINE => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS17,
            self::MODULE_DATALOAD_CATEGORYPOSTS17_SCROLL_LIST => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS17,
            self::MODULE_DATALOAD_CATEGORYPOSTS17_SCROLL_NAVIGATOR => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS17,
            self::MODULE_DATALOAD_CATEGORYPOSTS17_SCROLL_SIMPLEVIEW => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS17,
            self::MODULE_DATALOAD_CATEGORYPOSTS17_SCROLL_THUMBNAIL => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS17,
            self::MODULE_DATALOAD_CATEGORYPOSTS17_TYPEAHEAD => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS17,
            self::MODULE_DATALOAD_CATEGORYPOSTS18_CAROUSEL => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS18,
            self::MODULE_DATALOAD_CATEGORYPOSTS18_CAROUSEL_CONTENT => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS18,
            self::MODULE_DATALOAD_CATEGORYPOSTS18_SCROLL_ADDONS => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS18,
            self::MODULE_DATALOAD_CATEGORYPOSTS18_SCROLL_DETAILS => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS18,
            self::MODULE_DATALOAD_CATEGORYPOSTS18_SCROLL_FULLVIEW => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS18,
            self::MODULE_DATALOAD_CATEGORYPOSTS18_SCROLL_LINE => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS18,
            self::MODULE_DATALOAD_CATEGORYPOSTS18_SCROLL_LIST => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS18,
            self::MODULE_DATALOAD_CATEGORYPOSTS18_SCROLL_NAVIGATOR => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS18,
            self::MODULE_DATALOAD_CATEGORYPOSTS18_SCROLL_SIMPLEVIEW => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS18,
            self::MODULE_DATALOAD_CATEGORYPOSTS18_SCROLL_THUMBNAIL => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS18,
            self::MODULE_DATALOAD_CATEGORYPOSTS18_TYPEAHEAD => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS18,
            self::MODULE_DATALOAD_CATEGORYPOSTS19_CAROUSEL => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS19,
            self::MODULE_DATALOAD_CATEGORYPOSTS19_CAROUSEL_CONTENT => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS19,
            self::MODULE_DATALOAD_CATEGORYPOSTS19_SCROLL_ADDONS => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS19,
            self::MODULE_DATALOAD_CATEGORYPOSTS19_SCROLL_DETAILS => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS19,
            self::MODULE_DATALOAD_CATEGORYPOSTS19_SCROLL_FULLVIEW => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS19,
            self::MODULE_DATALOAD_CATEGORYPOSTS19_SCROLL_LINE => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS19,
            self::MODULE_DATALOAD_CATEGORYPOSTS19_SCROLL_LIST => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS19,
            self::MODULE_DATALOAD_CATEGORYPOSTS19_SCROLL_NAVIGATOR => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS19,
            self::MODULE_DATALOAD_CATEGORYPOSTS19_SCROLL_SIMPLEVIEW => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS19,
            self::MODULE_DATALOAD_CATEGORYPOSTS19_SCROLL_THUMBNAIL => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS19,
            self::MODULE_DATALOAD_CATEGORYPOSTS19_TYPEAHEAD => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS19,
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS00_CAROUSEL => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS00,
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS00_SCROLL_DETAILS => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS00,
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS00_SCROLL_FULLVIEW => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS00,
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS00_SCROLL_LINE => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS00,
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS00_SCROLL_LIST => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS00,
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS00_SCROLL_SIMPLEVIEW => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS00,
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS00_SCROLL_THUMBNAIL => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS00,
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS01_CAROUSEL => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS01,
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS01_SCROLL_DETAILS => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS01,
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS01_SCROLL_FULLVIEW => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS01,
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS01_SCROLL_LINE => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS01,
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS01_SCROLL_LIST => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS01,
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS01_SCROLL_SIMPLEVIEW => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS01,
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS01_SCROLL_THUMBNAIL => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS01,
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS02_CAROUSEL => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS02,
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS02_SCROLL_DETAILS => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS02,
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS02_SCROLL_FULLVIEW => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS02,
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS02_SCROLL_LINE => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS02,
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS02_SCROLL_LIST => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS02,
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS02_SCROLL_SIMPLEVIEW => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS02,
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS02_SCROLL_THUMBNAIL => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS02,
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS03_CAROUSEL => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS03,
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS03_SCROLL_DETAILS => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS03,
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS03_SCROLL_FULLVIEW => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS03,
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS03_SCROLL_LINE => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS03,
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS03_SCROLL_LIST => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS03,
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS03_SCROLL_SIMPLEVIEW => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS03,
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS03_SCROLL_THUMBNAIL => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS03,
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS04_CAROUSEL => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS04,
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS04_SCROLL_DETAILS => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS04,
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS04_SCROLL_FULLVIEW => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS04,
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS04_SCROLL_LINE => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS04,
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS04_SCROLL_LIST => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS04,
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS04_SCROLL_SIMPLEVIEW => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS04,
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS04_SCROLL_THUMBNAIL => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS04,
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS05_CAROUSEL => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS05,
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS05_SCROLL_DETAILS => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS05,
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS05_SCROLL_FULLVIEW => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS05,
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS05_SCROLL_LINE => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS05,
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS05_SCROLL_LIST => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS05,
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS05_SCROLL_SIMPLEVIEW => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS05,
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS05_SCROLL_THUMBNAIL => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS05,
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS06_CAROUSEL => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS06,
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS06_SCROLL_DETAILS => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS06,
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS06_SCROLL_FULLVIEW => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS06,
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS06_SCROLL_LINE => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS06,
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS06_SCROLL_LIST => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS06,
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS06_SCROLL_SIMPLEVIEW => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS06,
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS06_SCROLL_THUMBNAIL => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS06,
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS07_CAROUSEL => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS07,
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS07_SCROLL_DETAILS => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS07,
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS07_SCROLL_FULLVIEW => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS07,
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS07_SCROLL_LINE => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS07,
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS07_SCROLL_LIST => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS07,
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS07_SCROLL_SIMPLEVIEW => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS07,
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS07_SCROLL_THUMBNAIL => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS07,
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS08_CAROUSEL => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS08,
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS08_SCROLL_DETAILS => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS08,
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS08_SCROLL_FULLVIEW => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS08,
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS08_SCROLL_LINE => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS08,
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS08_SCROLL_LIST => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS08,
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS08_SCROLL_SIMPLEVIEW => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS08,
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS08_SCROLL_THUMBNAIL => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS08,
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS09_CAROUSEL => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS09,
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS09_SCROLL_DETAILS => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS09,
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS09_SCROLL_FULLVIEW => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS09,
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS09_SCROLL_LINE => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS09,
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS09_SCROLL_LIST => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS09,
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS09_SCROLL_SIMPLEVIEW => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS09,
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS09_SCROLL_THUMBNAIL => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS09,
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS10_CAROUSEL => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS10,
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS10_SCROLL_DETAILS => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS10,
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS10_SCROLL_FULLVIEW => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS10,
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS10_SCROLL_LINE => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS10,
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS10_SCROLL_LIST => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS10,
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS10_SCROLL_SIMPLEVIEW => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS10,
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS10_SCROLL_THUMBNAIL => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS10,
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS11_CAROUSEL => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS11,
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS11_SCROLL_DETAILS => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS11,
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS11_SCROLL_FULLVIEW => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS11,
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS11_SCROLL_LINE => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS11,
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS11_SCROLL_LIST => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS11,
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS11_SCROLL_SIMPLEVIEW => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS11,
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS11_SCROLL_THUMBNAIL => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS11,
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS12_CAROUSEL => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS12,
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS12_SCROLL_DETAILS => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS12,
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS12_SCROLL_FULLVIEW => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS12,
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS12_SCROLL_LINE => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS12,
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS12_SCROLL_LIST => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS12,
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS12_SCROLL_SIMPLEVIEW => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS12,
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS12_SCROLL_THUMBNAIL => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS12,
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS13_CAROUSEL => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS13,
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS13_SCROLL_DETAILS => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS13,
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS13_SCROLL_FULLVIEW => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS13,
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS13_SCROLL_LINE => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS13,
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS13_SCROLL_LIST => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS13,
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS13_SCROLL_SIMPLEVIEW => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS13,
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS13_SCROLL_THUMBNAIL => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS13,
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS14_CAROUSEL => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS14,
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS14_SCROLL_DETAILS => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS14,
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS14_SCROLL_FULLVIEW => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS14,
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS14_SCROLL_LINE => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS14,
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS14_SCROLL_LIST => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS14,
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS14_SCROLL_SIMPLEVIEW => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS14,
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS14_SCROLL_THUMBNAIL => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS14,
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS15_CAROUSEL => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS15,
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS15_SCROLL_DETAILS => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS15,
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS15_SCROLL_FULLVIEW => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS15,
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS15_SCROLL_LINE => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS15,
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS15_SCROLL_LIST => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS15,
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS15_SCROLL_SIMPLEVIEW => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS15,
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS15_SCROLL_THUMBNAIL => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS15,
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS16_CAROUSEL => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS16,
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS16_SCROLL_DETAILS => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS16,
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS16_SCROLL_FULLVIEW => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS16,
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS16_SCROLL_LINE => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS16,
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS16_SCROLL_LIST => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS16,
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS16_SCROLL_SIMPLEVIEW => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS16,
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS16_SCROLL_THUMBNAIL => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS16,
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS17_CAROUSEL => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS17,
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS17_SCROLL_DETAILS => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS17,
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS17_SCROLL_FULLVIEW => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS17,
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS17_SCROLL_LINE => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS17,
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS17_SCROLL_LIST => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS17,
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS17_SCROLL_SIMPLEVIEW => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS17,
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS17_SCROLL_THUMBNAIL => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS17,
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS18_CAROUSEL => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS18,
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS18_SCROLL_DETAILS => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS18,
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS18_SCROLL_FULLVIEW => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS18,
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS18_SCROLL_LINE => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS18,
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS18_SCROLL_LIST => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS18,
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS18_SCROLL_SIMPLEVIEW => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS18,
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS18_SCROLL_THUMBNAIL => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS18,
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS19_CAROUSEL => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS19,
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS19_SCROLL_DETAILS => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS19,
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS19_SCROLL_FULLVIEW => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS19,
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS19_SCROLL_LINE => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS19,
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS19_SCROLL_LIST => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS19,
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS19_SCROLL_SIMPLEVIEW => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS19,
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS19_SCROLL_THUMBNAIL => POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS19,
            default => parent::getRelevantRoute($componentVariation, $props),
        };
    }

    public function getInnerSubmodule(array $componentVariation)
    {
        $inner_modules = array(

            /*********************************************
         * Typeaheads
         *********************************************/
            // Straight to the layout
            self::MODULE_DATALOAD_CATEGORYPOSTS00_TYPEAHEAD => [PoP_Module_Processor_PostTypeaheadComponentLayouts::class, PoP_Module_Processor_PostTypeaheadComponentLayouts::MODULE_LAYOUTPOST_TYPEAHEAD_COMPONENT],
            self::MODULE_DATALOAD_CATEGORYPOSTS01_TYPEAHEAD => [PoP_Module_Processor_PostTypeaheadComponentLayouts::class, PoP_Module_Processor_PostTypeaheadComponentLayouts::MODULE_LAYOUTPOST_TYPEAHEAD_COMPONENT],
            self::MODULE_DATALOAD_CATEGORYPOSTS02_TYPEAHEAD => [PoP_Module_Processor_PostTypeaheadComponentLayouts::class, PoP_Module_Processor_PostTypeaheadComponentLayouts::MODULE_LAYOUTPOST_TYPEAHEAD_COMPONENT],
            self::MODULE_DATALOAD_CATEGORYPOSTS03_TYPEAHEAD => [PoP_Module_Processor_PostTypeaheadComponentLayouts::class, PoP_Module_Processor_PostTypeaheadComponentLayouts::MODULE_LAYOUTPOST_TYPEAHEAD_COMPONENT],
            self::MODULE_DATALOAD_CATEGORYPOSTS04_TYPEAHEAD => [PoP_Module_Processor_PostTypeaheadComponentLayouts::class, PoP_Module_Processor_PostTypeaheadComponentLayouts::MODULE_LAYOUTPOST_TYPEAHEAD_COMPONENT],
            self::MODULE_DATALOAD_CATEGORYPOSTS05_TYPEAHEAD => [PoP_Module_Processor_PostTypeaheadComponentLayouts::class, PoP_Module_Processor_PostTypeaheadComponentLayouts::MODULE_LAYOUTPOST_TYPEAHEAD_COMPONENT],
            self::MODULE_DATALOAD_CATEGORYPOSTS06_TYPEAHEAD => [PoP_Module_Processor_PostTypeaheadComponentLayouts::class, PoP_Module_Processor_PostTypeaheadComponentLayouts::MODULE_LAYOUTPOST_TYPEAHEAD_COMPONENT],
            self::MODULE_DATALOAD_CATEGORYPOSTS07_TYPEAHEAD => [PoP_Module_Processor_PostTypeaheadComponentLayouts::class, PoP_Module_Processor_PostTypeaheadComponentLayouts::MODULE_LAYOUTPOST_TYPEAHEAD_COMPONENT],
            self::MODULE_DATALOAD_CATEGORYPOSTS08_TYPEAHEAD => [PoP_Module_Processor_PostTypeaheadComponentLayouts::class, PoP_Module_Processor_PostTypeaheadComponentLayouts::MODULE_LAYOUTPOST_TYPEAHEAD_COMPONENT],
            self::MODULE_DATALOAD_CATEGORYPOSTS09_TYPEAHEAD => [PoP_Module_Processor_PostTypeaheadComponentLayouts::class, PoP_Module_Processor_PostTypeaheadComponentLayouts::MODULE_LAYOUTPOST_TYPEAHEAD_COMPONENT],
            self::MODULE_DATALOAD_CATEGORYPOSTS10_TYPEAHEAD => [PoP_Module_Processor_PostTypeaheadComponentLayouts::class, PoP_Module_Processor_PostTypeaheadComponentLayouts::MODULE_LAYOUTPOST_TYPEAHEAD_COMPONENT],
            self::MODULE_DATALOAD_CATEGORYPOSTS11_TYPEAHEAD => [PoP_Module_Processor_PostTypeaheadComponentLayouts::class, PoP_Module_Processor_PostTypeaheadComponentLayouts::MODULE_LAYOUTPOST_TYPEAHEAD_COMPONENT],
            self::MODULE_DATALOAD_CATEGORYPOSTS12_TYPEAHEAD => [PoP_Module_Processor_PostTypeaheadComponentLayouts::class, PoP_Module_Processor_PostTypeaheadComponentLayouts::MODULE_LAYOUTPOST_TYPEAHEAD_COMPONENT],
            self::MODULE_DATALOAD_CATEGORYPOSTS13_TYPEAHEAD => [PoP_Module_Processor_PostTypeaheadComponentLayouts::class, PoP_Module_Processor_PostTypeaheadComponentLayouts::MODULE_LAYOUTPOST_TYPEAHEAD_COMPONENT],
            self::MODULE_DATALOAD_CATEGORYPOSTS14_TYPEAHEAD => [PoP_Module_Processor_PostTypeaheadComponentLayouts::class, PoP_Module_Processor_PostTypeaheadComponentLayouts::MODULE_LAYOUTPOST_TYPEAHEAD_COMPONENT],
            self::MODULE_DATALOAD_CATEGORYPOSTS15_TYPEAHEAD => [PoP_Module_Processor_PostTypeaheadComponentLayouts::class, PoP_Module_Processor_PostTypeaheadComponentLayouts::MODULE_LAYOUTPOST_TYPEAHEAD_COMPONENT],
            self::MODULE_DATALOAD_CATEGORYPOSTS16_TYPEAHEAD => [PoP_Module_Processor_PostTypeaheadComponentLayouts::class, PoP_Module_Processor_PostTypeaheadComponentLayouts::MODULE_LAYOUTPOST_TYPEAHEAD_COMPONENT],
            self::MODULE_DATALOAD_CATEGORYPOSTS17_TYPEAHEAD => [PoP_Module_Processor_PostTypeaheadComponentLayouts::class, PoP_Module_Processor_PostTypeaheadComponentLayouts::MODULE_LAYOUTPOST_TYPEAHEAD_COMPONENT],
            self::MODULE_DATALOAD_CATEGORYPOSTS18_TYPEAHEAD => [PoP_Module_Processor_PostTypeaheadComponentLayouts::class, PoP_Module_Processor_PostTypeaheadComponentLayouts::MODULE_LAYOUTPOST_TYPEAHEAD_COMPONENT],
            self::MODULE_DATALOAD_CATEGORYPOSTS19_TYPEAHEAD => [PoP_Module_Processor_PostTypeaheadComponentLayouts::class, PoP_Module_Processor_PostTypeaheadComponentLayouts::MODULE_LAYOUTPOST_TYPEAHEAD_COMPONENT],

            /*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*
        * Common blocks (Home/Page/Author/Single)
        *-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*/

            self::MODULE_DATALOAD_CATEGORYPOSTS00_SCROLL_NAVIGATOR => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_NAVIGATOR],
            self::MODULE_DATALOAD_CATEGORYPOSTS01_SCROLL_NAVIGATOR => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_NAVIGATOR],
            self::MODULE_DATALOAD_CATEGORYPOSTS02_SCROLL_NAVIGATOR => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_NAVIGATOR],
            self::MODULE_DATALOAD_CATEGORYPOSTS03_SCROLL_NAVIGATOR => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_NAVIGATOR],
            self::MODULE_DATALOAD_CATEGORYPOSTS04_SCROLL_NAVIGATOR => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_NAVIGATOR],
            self::MODULE_DATALOAD_CATEGORYPOSTS05_SCROLL_NAVIGATOR => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_NAVIGATOR],
            self::MODULE_DATALOAD_CATEGORYPOSTS06_SCROLL_NAVIGATOR => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_NAVIGATOR],
            self::MODULE_DATALOAD_CATEGORYPOSTS07_SCROLL_NAVIGATOR => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_NAVIGATOR],
            self::MODULE_DATALOAD_CATEGORYPOSTS08_SCROLL_NAVIGATOR => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_NAVIGATOR],
            self::MODULE_DATALOAD_CATEGORYPOSTS09_SCROLL_NAVIGATOR => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_NAVIGATOR],
            self::MODULE_DATALOAD_CATEGORYPOSTS10_SCROLL_NAVIGATOR => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_NAVIGATOR],
            self::MODULE_DATALOAD_CATEGORYPOSTS11_SCROLL_NAVIGATOR => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_NAVIGATOR],
            self::MODULE_DATALOAD_CATEGORYPOSTS12_SCROLL_NAVIGATOR => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_NAVIGATOR],
            self::MODULE_DATALOAD_CATEGORYPOSTS13_SCROLL_NAVIGATOR => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_NAVIGATOR],
            self::MODULE_DATALOAD_CATEGORYPOSTS14_SCROLL_NAVIGATOR => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_NAVIGATOR],
            self::MODULE_DATALOAD_CATEGORYPOSTS15_SCROLL_NAVIGATOR => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_NAVIGATOR],
            self::MODULE_DATALOAD_CATEGORYPOSTS16_SCROLL_NAVIGATOR => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_NAVIGATOR],
            self::MODULE_DATALOAD_CATEGORYPOSTS17_SCROLL_NAVIGATOR => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_NAVIGATOR],
            self::MODULE_DATALOAD_CATEGORYPOSTS18_SCROLL_NAVIGATOR => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_NAVIGATOR],
            self::MODULE_DATALOAD_CATEGORYPOSTS19_SCROLL_NAVIGATOR => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_NAVIGATOR],

            self::MODULE_DATALOAD_CATEGORYPOSTS00_SCROLL_ADDONS => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_ADDONS],
            self::MODULE_DATALOAD_CATEGORYPOSTS01_SCROLL_ADDONS => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_ADDONS],
            self::MODULE_DATALOAD_CATEGORYPOSTS02_SCROLL_ADDONS => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_ADDONS],
            self::MODULE_DATALOAD_CATEGORYPOSTS03_SCROLL_ADDONS => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_ADDONS],
            self::MODULE_DATALOAD_CATEGORYPOSTS04_SCROLL_ADDONS => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_ADDONS],
            self::MODULE_DATALOAD_CATEGORYPOSTS05_SCROLL_ADDONS => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_ADDONS],
            self::MODULE_DATALOAD_CATEGORYPOSTS06_SCROLL_ADDONS => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_ADDONS],
            self::MODULE_DATALOAD_CATEGORYPOSTS07_SCROLL_ADDONS => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_ADDONS],
            self::MODULE_DATALOAD_CATEGORYPOSTS08_SCROLL_ADDONS => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_ADDONS],
            self::MODULE_DATALOAD_CATEGORYPOSTS09_SCROLL_ADDONS => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_ADDONS],
            self::MODULE_DATALOAD_CATEGORYPOSTS10_SCROLL_ADDONS => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_ADDONS],
            self::MODULE_DATALOAD_CATEGORYPOSTS11_SCROLL_ADDONS => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_ADDONS],
            self::MODULE_DATALOAD_CATEGORYPOSTS12_SCROLL_ADDONS => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_ADDONS],
            self::MODULE_DATALOAD_CATEGORYPOSTS13_SCROLL_ADDONS => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_ADDONS],
            self::MODULE_DATALOAD_CATEGORYPOSTS14_SCROLL_ADDONS => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_ADDONS],
            self::MODULE_DATALOAD_CATEGORYPOSTS15_SCROLL_ADDONS => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_ADDONS],
            self::MODULE_DATALOAD_CATEGORYPOSTS16_SCROLL_ADDONS => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_ADDONS],
            self::MODULE_DATALOAD_CATEGORYPOSTS17_SCROLL_ADDONS => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_ADDONS],
            self::MODULE_DATALOAD_CATEGORYPOSTS18_SCROLL_ADDONS => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_ADDONS],
            self::MODULE_DATALOAD_CATEGORYPOSTS19_SCROLL_ADDONS => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_ADDONS],

            /*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*
        * Home/Page blocks
        *-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*/

            self::MODULE_DATALOAD_CATEGORYPOSTS00_SCROLL_DETAILS => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_DETAILS],
            self::MODULE_DATALOAD_CATEGORYPOSTS01_SCROLL_DETAILS => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_DETAILS],
            self::MODULE_DATALOAD_CATEGORYPOSTS02_SCROLL_DETAILS => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_DETAILS],
            self::MODULE_DATALOAD_CATEGORYPOSTS03_SCROLL_DETAILS => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_DETAILS],
            self::MODULE_DATALOAD_CATEGORYPOSTS04_SCROLL_DETAILS => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_DETAILS],
            self::MODULE_DATALOAD_CATEGORYPOSTS05_SCROLL_DETAILS => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_DETAILS],
            self::MODULE_DATALOAD_CATEGORYPOSTS06_SCROLL_DETAILS => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_DETAILS],
            self::MODULE_DATALOAD_CATEGORYPOSTS07_SCROLL_DETAILS => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_DETAILS],
            self::MODULE_DATALOAD_CATEGORYPOSTS08_SCROLL_DETAILS => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_DETAILS],
            self::MODULE_DATALOAD_CATEGORYPOSTS09_SCROLL_DETAILS => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_DETAILS],
            self::MODULE_DATALOAD_CATEGORYPOSTS10_SCROLL_DETAILS => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_DETAILS],
            self::MODULE_DATALOAD_CATEGORYPOSTS11_SCROLL_DETAILS => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_DETAILS],
            self::MODULE_DATALOAD_CATEGORYPOSTS12_SCROLL_DETAILS => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_DETAILS],
            self::MODULE_DATALOAD_CATEGORYPOSTS13_SCROLL_DETAILS => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_DETAILS],
            self::MODULE_DATALOAD_CATEGORYPOSTS14_SCROLL_DETAILS => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_DETAILS],
            self::MODULE_DATALOAD_CATEGORYPOSTS15_SCROLL_DETAILS => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_DETAILS],
            self::MODULE_DATALOAD_CATEGORYPOSTS16_SCROLL_DETAILS => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_DETAILS],
            self::MODULE_DATALOAD_CATEGORYPOSTS17_SCROLL_DETAILS => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_DETAILS],
            self::MODULE_DATALOAD_CATEGORYPOSTS18_SCROLL_DETAILS => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_DETAILS],
            self::MODULE_DATALOAD_CATEGORYPOSTS19_SCROLL_DETAILS => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_DETAILS],

            self::MODULE_DATALOAD_CATEGORYPOSTS00_SCROLL_SIMPLEVIEW => [PoP_CategoryPosts_Module_Processor_Scrolls::class, PoP_CategoryPosts_Module_Processor_Scrolls::MODULE_SCROLL_CATEGORYPOSTS00_SIMPLEVIEW],
            self::MODULE_DATALOAD_CATEGORYPOSTS01_SCROLL_SIMPLEVIEW => [PoP_CategoryPosts_Module_Processor_Scrolls::class, PoP_CategoryPosts_Module_Processor_Scrolls::MODULE_SCROLL_CATEGORYPOSTS01_SIMPLEVIEW],
            self::MODULE_DATALOAD_CATEGORYPOSTS02_SCROLL_SIMPLEVIEW => [PoP_CategoryPosts_Module_Processor_Scrolls::class, PoP_CategoryPosts_Module_Processor_Scrolls::MODULE_SCROLL_CATEGORYPOSTS02_SIMPLEVIEW],
            self::MODULE_DATALOAD_CATEGORYPOSTS03_SCROLL_SIMPLEVIEW => [PoP_CategoryPosts_Module_Processor_Scrolls::class, PoP_CategoryPosts_Module_Processor_Scrolls::MODULE_SCROLL_CATEGORYPOSTS03_SIMPLEVIEW],
            self::MODULE_DATALOAD_CATEGORYPOSTS04_SCROLL_SIMPLEVIEW => [PoP_CategoryPosts_Module_Processor_Scrolls::class, PoP_CategoryPosts_Module_Processor_Scrolls::MODULE_SCROLL_CATEGORYPOSTS04_SIMPLEVIEW],
            self::MODULE_DATALOAD_CATEGORYPOSTS05_SCROLL_SIMPLEVIEW => [PoP_CategoryPosts_Module_Processor_Scrolls::class, PoP_CategoryPosts_Module_Processor_Scrolls::MODULE_SCROLL_CATEGORYPOSTS05_SIMPLEVIEW],
            self::MODULE_DATALOAD_CATEGORYPOSTS06_SCROLL_SIMPLEVIEW => [PoP_CategoryPosts_Module_Processor_Scrolls::class, PoP_CategoryPosts_Module_Processor_Scrolls::MODULE_SCROLL_CATEGORYPOSTS06_SIMPLEVIEW],
            self::MODULE_DATALOAD_CATEGORYPOSTS07_SCROLL_SIMPLEVIEW => [PoP_CategoryPosts_Module_Processor_Scrolls::class, PoP_CategoryPosts_Module_Processor_Scrolls::MODULE_SCROLL_CATEGORYPOSTS07_SIMPLEVIEW],
            self::MODULE_DATALOAD_CATEGORYPOSTS08_SCROLL_SIMPLEVIEW => [PoP_CategoryPosts_Module_Processor_Scrolls::class, PoP_CategoryPosts_Module_Processor_Scrolls::MODULE_SCROLL_CATEGORYPOSTS08_SIMPLEVIEW],
            self::MODULE_DATALOAD_CATEGORYPOSTS09_SCROLL_SIMPLEVIEW => [PoP_CategoryPosts_Module_Processor_Scrolls::class, PoP_CategoryPosts_Module_Processor_Scrolls::MODULE_SCROLL_CATEGORYPOSTS09_SIMPLEVIEW],
            self::MODULE_DATALOAD_CATEGORYPOSTS10_SCROLL_SIMPLEVIEW => [PoP_CategoryPosts_Module_Processor_Scrolls::class, PoP_CategoryPosts_Module_Processor_Scrolls::MODULE_SCROLL_CATEGORYPOSTS10_SIMPLEVIEW],
            self::MODULE_DATALOAD_CATEGORYPOSTS11_SCROLL_SIMPLEVIEW => [PoP_CategoryPosts_Module_Processor_Scrolls::class, PoP_CategoryPosts_Module_Processor_Scrolls::MODULE_SCROLL_CATEGORYPOSTS11_SIMPLEVIEW],
            self::MODULE_DATALOAD_CATEGORYPOSTS12_SCROLL_SIMPLEVIEW => [PoP_CategoryPosts_Module_Processor_Scrolls::class, PoP_CategoryPosts_Module_Processor_Scrolls::MODULE_SCROLL_CATEGORYPOSTS12_SIMPLEVIEW],
            self::MODULE_DATALOAD_CATEGORYPOSTS13_SCROLL_SIMPLEVIEW => [PoP_CategoryPosts_Module_Processor_Scrolls::class, PoP_CategoryPosts_Module_Processor_Scrolls::MODULE_SCROLL_CATEGORYPOSTS13_SIMPLEVIEW],
            self::MODULE_DATALOAD_CATEGORYPOSTS14_SCROLL_SIMPLEVIEW => [PoP_CategoryPosts_Module_Processor_Scrolls::class, PoP_CategoryPosts_Module_Processor_Scrolls::MODULE_SCROLL_CATEGORYPOSTS14_SIMPLEVIEW],
            self::MODULE_DATALOAD_CATEGORYPOSTS15_SCROLL_SIMPLEVIEW => [PoP_CategoryPosts_Module_Processor_Scrolls::class, PoP_CategoryPosts_Module_Processor_Scrolls::MODULE_SCROLL_CATEGORYPOSTS15_SIMPLEVIEW],
            self::MODULE_DATALOAD_CATEGORYPOSTS16_SCROLL_SIMPLEVIEW => [PoP_CategoryPosts_Module_Processor_Scrolls::class, PoP_CategoryPosts_Module_Processor_Scrolls::MODULE_SCROLL_CATEGORYPOSTS16_SIMPLEVIEW],
            self::MODULE_DATALOAD_CATEGORYPOSTS17_SCROLL_SIMPLEVIEW => [PoP_CategoryPosts_Module_Processor_Scrolls::class, PoP_CategoryPosts_Module_Processor_Scrolls::MODULE_SCROLL_CATEGORYPOSTS17_SIMPLEVIEW],
            self::MODULE_DATALOAD_CATEGORYPOSTS18_SCROLL_SIMPLEVIEW => [PoP_CategoryPosts_Module_Processor_Scrolls::class, PoP_CategoryPosts_Module_Processor_Scrolls::MODULE_SCROLL_CATEGORYPOSTS18_SIMPLEVIEW],
            self::MODULE_DATALOAD_CATEGORYPOSTS19_SCROLL_SIMPLEVIEW => [PoP_CategoryPosts_Module_Processor_Scrolls::class, PoP_CategoryPosts_Module_Processor_Scrolls::MODULE_SCROLL_CATEGORYPOSTS19_SIMPLEVIEW],

            self::MODULE_DATALOAD_CATEGORYPOSTS00_SCROLL_FULLVIEW => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_FULLVIEW],
            self::MODULE_DATALOAD_CATEGORYPOSTS01_SCROLL_FULLVIEW => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_FULLVIEW],
            self::MODULE_DATALOAD_CATEGORYPOSTS02_SCROLL_FULLVIEW => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_FULLVIEW],
            self::MODULE_DATALOAD_CATEGORYPOSTS03_SCROLL_FULLVIEW => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_FULLVIEW],
            self::MODULE_DATALOAD_CATEGORYPOSTS04_SCROLL_FULLVIEW => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_FULLVIEW],
            self::MODULE_DATALOAD_CATEGORYPOSTS05_SCROLL_FULLVIEW => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_FULLVIEW],
            self::MODULE_DATALOAD_CATEGORYPOSTS06_SCROLL_FULLVIEW => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_FULLVIEW],
            self::MODULE_DATALOAD_CATEGORYPOSTS07_SCROLL_FULLVIEW => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_FULLVIEW],
            self::MODULE_DATALOAD_CATEGORYPOSTS08_SCROLL_FULLVIEW => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_FULLVIEW],
            self::MODULE_DATALOAD_CATEGORYPOSTS09_SCROLL_FULLVIEW => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_FULLVIEW],
            self::MODULE_DATALOAD_CATEGORYPOSTS10_SCROLL_FULLVIEW => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_FULLVIEW],
            self::MODULE_DATALOAD_CATEGORYPOSTS11_SCROLL_FULLVIEW => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_FULLVIEW],
            self::MODULE_DATALOAD_CATEGORYPOSTS12_SCROLL_FULLVIEW => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_FULLVIEW],
            self::MODULE_DATALOAD_CATEGORYPOSTS13_SCROLL_FULLVIEW => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_FULLVIEW],
            self::MODULE_DATALOAD_CATEGORYPOSTS14_SCROLL_FULLVIEW => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_FULLVIEW],
            self::MODULE_DATALOAD_CATEGORYPOSTS15_SCROLL_FULLVIEW => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_FULLVIEW],
            self::MODULE_DATALOAD_CATEGORYPOSTS16_SCROLL_FULLVIEW => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_FULLVIEW],
            self::MODULE_DATALOAD_CATEGORYPOSTS17_SCROLL_FULLVIEW => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_FULLVIEW],
            self::MODULE_DATALOAD_CATEGORYPOSTS18_SCROLL_FULLVIEW => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_FULLVIEW],
            self::MODULE_DATALOAD_CATEGORYPOSTS19_SCROLL_FULLVIEW => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_FULLVIEW],

            self::MODULE_DATALOAD_CATEGORYPOSTS00_SCROLL_THUMBNAIL => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_THUMBNAIL],
            self::MODULE_DATALOAD_CATEGORYPOSTS01_SCROLL_THUMBNAIL => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_THUMBNAIL],
            self::MODULE_DATALOAD_CATEGORYPOSTS02_SCROLL_THUMBNAIL => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_THUMBNAIL],
            self::MODULE_DATALOAD_CATEGORYPOSTS03_SCROLL_THUMBNAIL => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_THUMBNAIL],
            self::MODULE_DATALOAD_CATEGORYPOSTS04_SCROLL_THUMBNAIL => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_THUMBNAIL],
            self::MODULE_DATALOAD_CATEGORYPOSTS05_SCROLL_THUMBNAIL => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_THUMBNAIL],
            self::MODULE_DATALOAD_CATEGORYPOSTS06_SCROLL_THUMBNAIL => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_THUMBNAIL],
            self::MODULE_DATALOAD_CATEGORYPOSTS07_SCROLL_THUMBNAIL => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_THUMBNAIL],
            self::MODULE_DATALOAD_CATEGORYPOSTS08_SCROLL_THUMBNAIL => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_THUMBNAIL],
            self::MODULE_DATALOAD_CATEGORYPOSTS09_SCROLL_THUMBNAIL => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_THUMBNAIL],
            self::MODULE_DATALOAD_CATEGORYPOSTS10_SCROLL_THUMBNAIL => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_THUMBNAIL],
            self::MODULE_DATALOAD_CATEGORYPOSTS11_SCROLL_THUMBNAIL => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_THUMBNAIL],
            self::MODULE_DATALOAD_CATEGORYPOSTS12_SCROLL_THUMBNAIL => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_THUMBNAIL],
            self::MODULE_DATALOAD_CATEGORYPOSTS13_SCROLL_THUMBNAIL => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_THUMBNAIL],
            self::MODULE_DATALOAD_CATEGORYPOSTS14_SCROLL_THUMBNAIL => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_THUMBNAIL],
            self::MODULE_DATALOAD_CATEGORYPOSTS15_SCROLL_THUMBNAIL => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_THUMBNAIL],
            self::MODULE_DATALOAD_CATEGORYPOSTS16_SCROLL_THUMBNAIL => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_THUMBNAIL],
            self::MODULE_DATALOAD_CATEGORYPOSTS17_SCROLL_THUMBNAIL => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_THUMBNAIL],
            self::MODULE_DATALOAD_CATEGORYPOSTS18_SCROLL_THUMBNAIL => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_THUMBNAIL],
            self::MODULE_DATALOAD_CATEGORYPOSTS19_SCROLL_THUMBNAIL => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_THUMBNAIL],

            self::MODULE_DATALOAD_CATEGORYPOSTS00_SCROLL_LIST => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_LIST],
            self::MODULE_DATALOAD_CATEGORYPOSTS01_SCROLL_LIST => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_LIST],
            self::MODULE_DATALOAD_CATEGORYPOSTS02_SCROLL_LIST => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_LIST],
            self::MODULE_DATALOAD_CATEGORYPOSTS03_SCROLL_LIST => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_LIST],
            self::MODULE_DATALOAD_CATEGORYPOSTS04_SCROLL_LIST => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_LIST],
            self::MODULE_DATALOAD_CATEGORYPOSTS05_SCROLL_LIST => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_LIST],
            self::MODULE_DATALOAD_CATEGORYPOSTS06_SCROLL_LIST => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_LIST],
            self::MODULE_DATALOAD_CATEGORYPOSTS07_SCROLL_LIST => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_LIST],
            self::MODULE_DATALOAD_CATEGORYPOSTS08_SCROLL_LIST => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_LIST],
            self::MODULE_DATALOAD_CATEGORYPOSTS09_SCROLL_LIST => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_LIST],
            self::MODULE_DATALOAD_CATEGORYPOSTS10_SCROLL_LIST => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_LIST],
            self::MODULE_DATALOAD_CATEGORYPOSTS11_SCROLL_LIST => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_LIST],
            self::MODULE_DATALOAD_CATEGORYPOSTS12_SCROLL_LIST => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_LIST],
            self::MODULE_DATALOAD_CATEGORYPOSTS13_SCROLL_LIST => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_LIST],
            self::MODULE_DATALOAD_CATEGORYPOSTS14_SCROLL_LIST => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_LIST],
            self::MODULE_DATALOAD_CATEGORYPOSTS15_SCROLL_LIST => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_LIST],
            self::MODULE_DATALOAD_CATEGORYPOSTS16_SCROLL_LIST => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_LIST],
            self::MODULE_DATALOAD_CATEGORYPOSTS17_SCROLL_LIST => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_LIST],
            self::MODULE_DATALOAD_CATEGORYPOSTS18_SCROLL_LIST => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_LIST],
            self::MODULE_DATALOAD_CATEGORYPOSTS19_SCROLL_LIST => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_LIST],

            self::MODULE_DATALOAD_CATEGORYPOSTS00_SCROLL_LINE => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_LINE],
            self::MODULE_DATALOAD_CATEGORYPOSTS01_SCROLL_LINE => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_LINE],
            self::MODULE_DATALOAD_CATEGORYPOSTS02_SCROLL_LINE => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_LINE],
            self::MODULE_DATALOAD_CATEGORYPOSTS03_SCROLL_LINE => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_LINE],
            self::MODULE_DATALOAD_CATEGORYPOSTS04_SCROLL_LINE => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_LINE],
            self::MODULE_DATALOAD_CATEGORYPOSTS05_SCROLL_LINE => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_LINE],
            self::MODULE_DATALOAD_CATEGORYPOSTS06_SCROLL_LINE => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_LINE],
            self::MODULE_DATALOAD_CATEGORYPOSTS07_SCROLL_LINE => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_LINE],
            self::MODULE_DATALOAD_CATEGORYPOSTS08_SCROLL_LINE => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_LINE],
            self::MODULE_DATALOAD_CATEGORYPOSTS09_SCROLL_LINE => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_LINE],
            self::MODULE_DATALOAD_CATEGORYPOSTS10_SCROLL_LINE => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_LINE],
            self::MODULE_DATALOAD_CATEGORYPOSTS11_SCROLL_LINE => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_LINE],
            self::MODULE_DATALOAD_CATEGORYPOSTS12_SCROLL_LINE => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_LINE],
            self::MODULE_DATALOAD_CATEGORYPOSTS13_SCROLL_LINE => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_LINE],
            self::MODULE_DATALOAD_CATEGORYPOSTS14_SCROLL_LINE => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_LINE],
            self::MODULE_DATALOAD_CATEGORYPOSTS15_SCROLL_LINE => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_LINE],
            self::MODULE_DATALOAD_CATEGORYPOSTS16_SCROLL_LINE => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_LINE],
            self::MODULE_DATALOAD_CATEGORYPOSTS17_SCROLL_LINE => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_LINE],
            self::MODULE_DATALOAD_CATEGORYPOSTS18_SCROLL_LINE => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_LINE],
            self::MODULE_DATALOAD_CATEGORYPOSTS19_SCROLL_LINE => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_LINE],

            /*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*
        * Author blocks
        *-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*/

            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS00_SCROLL_DETAILS => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_DETAILS],
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS01_SCROLL_DETAILS => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_DETAILS],
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS02_SCROLL_DETAILS => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_DETAILS],
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS03_SCROLL_DETAILS => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_DETAILS],
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS04_SCROLL_DETAILS => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_DETAILS],
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS05_SCROLL_DETAILS => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_DETAILS],
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS06_SCROLL_DETAILS => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_DETAILS],
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS07_SCROLL_DETAILS => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_DETAILS],
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS08_SCROLL_DETAILS => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_DETAILS],
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS09_SCROLL_DETAILS => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_DETAILS],
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS10_SCROLL_DETAILS => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_DETAILS],
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS11_SCROLL_DETAILS => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_DETAILS],
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS12_SCROLL_DETAILS => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_DETAILS],
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS13_SCROLL_DETAILS => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_DETAILS],
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS14_SCROLL_DETAILS => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_DETAILS],
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS15_SCROLL_DETAILS => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_DETAILS],
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS16_SCROLL_DETAILS => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_DETAILS],
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS17_SCROLL_DETAILS => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_DETAILS],
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS18_SCROLL_DETAILS => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_DETAILS],
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS19_SCROLL_DETAILS => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_DETAILS],

            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS00_SCROLL_SIMPLEVIEW => [PoP_CategoryPosts_Module_Processor_Scrolls::class, PoP_CategoryPosts_Module_Processor_Scrolls::MODULE_SCROLL_CATEGORYPOSTS00_SIMPLEVIEW],
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS01_SCROLL_SIMPLEVIEW => [PoP_CategoryPosts_Module_Processor_Scrolls::class, PoP_CategoryPosts_Module_Processor_Scrolls::MODULE_SCROLL_CATEGORYPOSTS01_SIMPLEVIEW],
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS02_SCROLL_SIMPLEVIEW => [PoP_CategoryPosts_Module_Processor_Scrolls::class, PoP_CategoryPosts_Module_Processor_Scrolls::MODULE_SCROLL_CATEGORYPOSTS02_SIMPLEVIEW],
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS03_SCROLL_SIMPLEVIEW => [PoP_CategoryPosts_Module_Processor_Scrolls::class, PoP_CategoryPosts_Module_Processor_Scrolls::MODULE_SCROLL_CATEGORYPOSTS03_SIMPLEVIEW],
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS04_SCROLL_SIMPLEVIEW => [PoP_CategoryPosts_Module_Processor_Scrolls::class, PoP_CategoryPosts_Module_Processor_Scrolls::MODULE_SCROLL_CATEGORYPOSTS04_SIMPLEVIEW],
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS05_SCROLL_SIMPLEVIEW => [PoP_CategoryPosts_Module_Processor_Scrolls::class, PoP_CategoryPosts_Module_Processor_Scrolls::MODULE_SCROLL_CATEGORYPOSTS05_SIMPLEVIEW],
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS06_SCROLL_SIMPLEVIEW => [PoP_CategoryPosts_Module_Processor_Scrolls::class, PoP_CategoryPosts_Module_Processor_Scrolls::MODULE_SCROLL_CATEGORYPOSTS06_SIMPLEVIEW],
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS07_SCROLL_SIMPLEVIEW => [PoP_CategoryPosts_Module_Processor_Scrolls::class, PoP_CategoryPosts_Module_Processor_Scrolls::MODULE_SCROLL_CATEGORYPOSTS07_SIMPLEVIEW],
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS08_SCROLL_SIMPLEVIEW => [PoP_CategoryPosts_Module_Processor_Scrolls::class, PoP_CategoryPosts_Module_Processor_Scrolls::MODULE_SCROLL_CATEGORYPOSTS08_SIMPLEVIEW],
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS09_SCROLL_SIMPLEVIEW => [PoP_CategoryPosts_Module_Processor_Scrolls::class, PoP_CategoryPosts_Module_Processor_Scrolls::MODULE_SCROLL_CATEGORYPOSTS09_SIMPLEVIEW],
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS10_SCROLL_SIMPLEVIEW => [PoP_CategoryPosts_Module_Processor_Scrolls::class, PoP_CategoryPosts_Module_Processor_Scrolls::MODULE_SCROLL_CATEGORYPOSTS10_SIMPLEVIEW],
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS11_SCROLL_SIMPLEVIEW => [PoP_CategoryPosts_Module_Processor_Scrolls::class, PoP_CategoryPosts_Module_Processor_Scrolls::MODULE_SCROLL_CATEGORYPOSTS11_SIMPLEVIEW],
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS12_SCROLL_SIMPLEVIEW => [PoP_CategoryPosts_Module_Processor_Scrolls::class, PoP_CategoryPosts_Module_Processor_Scrolls::MODULE_SCROLL_CATEGORYPOSTS12_SIMPLEVIEW],
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS13_SCROLL_SIMPLEVIEW => [PoP_CategoryPosts_Module_Processor_Scrolls::class, PoP_CategoryPosts_Module_Processor_Scrolls::MODULE_SCROLL_CATEGORYPOSTS13_SIMPLEVIEW],
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS14_SCROLL_SIMPLEVIEW => [PoP_CategoryPosts_Module_Processor_Scrolls::class, PoP_CategoryPosts_Module_Processor_Scrolls::MODULE_SCROLL_CATEGORYPOSTS14_SIMPLEVIEW],
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS15_SCROLL_SIMPLEVIEW => [PoP_CategoryPosts_Module_Processor_Scrolls::class, PoP_CategoryPosts_Module_Processor_Scrolls::MODULE_SCROLL_CATEGORYPOSTS15_SIMPLEVIEW],
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS16_SCROLL_SIMPLEVIEW => [PoP_CategoryPosts_Module_Processor_Scrolls::class, PoP_CategoryPosts_Module_Processor_Scrolls::MODULE_SCROLL_CATEGORYPOSTS16_SIMPLEVIEW],
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS17_SCROLL_SIMPLEVIEW => [PoP_CategoryPosts_Module_Processor_Scrolls::class, PoP_CategoryPosts_Module_Processor_Scrolls::MODULE_SCROLL_CATEGORYPOSTS17_SIMPLEVIEW],
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS18_SCROLL_SIMPLEVIEW => [PoP_CategoryPosts_Module_Processor_Scrolls::class, PoP_CategoryPosts_Module_Processor_Scrolls::MODULE_SCROLL_CATEGORYPOSTS18_SIMPLEVIEW],
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS19_SCROLL_SIMPLEVIEW => [PoP_CategoryPosts_Module_Processor_Scrolls::class, PoP_CategoryPosts_Module_Processor_Scrolls::MODULE_SCROLL_CATEGORYPOSTS19_SIMPLEVIEW],

            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS00_SCROLL_FULLVIEW => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_AUTHORPOSTS_FULLVIEW],
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS01_SCROLL_FULLVIEW => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_AUTHORPOSTS_FULLVIEW],
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS02_SCROLL_FULLVIEW => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_AUTHORPOSTS_FULLVIEW],
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS03_SCROLL_FULLVIEW => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_AUTHORPOSTS_FULLVIEW],
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS04_SCROLL_FULLVIEW => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_AUTHORPOSTS_FULLVIEW],
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS05_SCROLL_FULLVIEW => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_AUTHORPOSTS_FULLVIEW],
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS06_SCROLL_FULLVIEW => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_AUTHORPOSTS_FULLVIEW],
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS07_SCROLL_FULLVIEW => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_AUTHORPOSTS_FULLVIEW],
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS08_SCROLL_FULLVIEW => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_AUTHORPOSTS_FULLVIEW],
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS09_SCROLL_FULLVIEW => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_AUTHORPOSTS_FULLVIEW],
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS10_SCROLL_FULLVIEW => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_AUTHORPOSTS_FULLVIEW],
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS11_SCROLL_FULLVIEW => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_AUTHORPOSTS_FULLVIEW],
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS12_SCROLL_FULLVIEW => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_AUTHORPOSTS_FULLVIEW],
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS13_SCROLL_FULLVIEW => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_AUTHORPOSTS_FULLVIEW],
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS14_SCROLL_FULLVIEW => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_AUTHORPOSTS_FULLVIEW],
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS15_SCROLL_FULLVIEW => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_AUTHORPOSTS_FULLVIEW],
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS16_SCROLL_FULLVIEW => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_AUTHORPOSTS_FULLVIEW],
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS17_SCROLL_FULLVIEW => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_AUTHORPOSTS_FULLVIEW],
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS18_SCROLL_FULLVIEW => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_AUTHORPOSTS_FULLVIEW],
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS19_SCROLL_FULLVIEW => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_AUTHORPOSTS_FULLVIEW],

            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS00_SCROLL_THUMBNAIL => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_THUMBNAIL],
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS01_SCROLL_THUMBNAIL => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_THUMBNAIL],
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS02_SCROLL_THUMBNAIL => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_THUMBNAIL],
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS03_SCROLL_THUMBNAIL => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_THUMBNAIL],
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS04_SCROLL_THUMBNAIL => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_THUMBNAIL],
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS05_SCROLL_THUMBNAIL => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_THUMBNAIL],
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS06_SCROLL_THUMBNAIL => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_THUMBNAIL],
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS07_SCROLL_THUMBNAIL => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_THUMBNAIL],
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS08_SCROLL_THUMBNAIL => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_THUMBNAIL],
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS09_SCROLL_THUMBNAIL => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_THUMBNAIL],
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS10_SCROLL_THUMBNAIL => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_THUMBNAIL],
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS11_SCROLL_THUMBNAIL => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_THUMBNAIL],
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS12_SCROLL_THUMBNAIL => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_THUMBNAIL],
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS13_SCROLL_THUMBNAIL => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_THUMBNAIL],
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS14_SCROLL_THUMBNAIL => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_THUMBNAIL],
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS15_SCROLL_THUMBNAIL => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_THUMBNAIL],
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS16_SCROLL_THUMBNAIL => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_THUMBNAIL],
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS17_SCROLL_THUMBNAIL => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_THUMBNAIL],
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS18_SCROLL_THUMBNAIL => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_THUMBNAIL],
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS19_SCROLL_THUMBNAIL => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_THUMBNAIL],

            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS00_SCROLL_LIST => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_LIST],
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS01_SCROLL_LIST => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_LIST],
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS02_SCROLL_LIST => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_LIST],
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS03_SCROLL_LIST => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_LIST],
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS04_SCROLL_LIST => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_LIST],
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS05_SCROLL_LIST => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_LIST],
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS06_SCROLL_LIST => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_LIST],
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS07_SCROLL_LIST => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_LIST],
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS08_SCROLL_LIST => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_LIST],
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS09_SCROLL_LIST => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_LIST],
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS10_SCROLL_LIST => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_LIST],
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS11_SCROLL_LIST => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_LIST],
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS12_SCROLL_LIST => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_LIST],
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS13_SCROLL_LIST => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_LIST],
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS14_SCROLL_LIST => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_LIST],
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS15_SCROLL_LIST => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_LIST],
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS16_SCROLL_LIST => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_LIST],
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS17_SCROLL_LIST => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_LIST],
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS18_SCROLL_LIST => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_LIST],
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS19_SCROLL_LIST => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_LIST],

            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS00_SCROLL_LINE => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_LINE],
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS01_SCROLL_LINE => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_LINE],
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS02_SCROLL_LINE => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_LINE],
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS03_SCROLL_LINE => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_LINE],
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS04_SCROLL_LINE => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_LINE],
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS05_SCROLL_LINE => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_LINE],
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS06_SCROLL_LINE => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_LINE],
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS07_SCROLL_LINE => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_LINE],
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS08_SCROLL_LINE => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_LINE],
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS09_SCROLL_LINE => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_LINE],
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS10_SCROLL_LINE => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_LINE],
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS11_SCROLL_LINE => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_LINE],
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS12_SCROLL_LINE => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_LINE],
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS13_SCROLL_LINE => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_LINE],
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS14_SCROLL_LINE => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_LINE],
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS15_SCROLL_LINE => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_LINE],
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS16_SCROLL_LINE => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_LINE],
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS17_SCROLL_LINE => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_LINE],
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS18_SCROLL_LINE => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_LINE],
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS19_SCROLL_LINE => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_LINE],

            /*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*
        * Tat blocks
        *-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*/

            self::MODULE_DATALOAD_TAGCATEGORYPOSTS00_SCROLL_DETAILS => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_DETAILS],
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS01_SCROLL_DETAILS => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_DETAILS],
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS02_SCROLL_DETAILS => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_DETAILS],
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS03_SCROLL_DETAILS => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_DETAILS],
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS04_SCROLL_DETAILS => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_DETAILS],
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS05_SCROLL_DETAILS => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_DETAILS],
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS06_SCROLL_DETAILS => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_DETAILS],
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS07_SCROLL_DETAILS => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_DETAILS],
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS08_SCROLL_DETAILS => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_DETAILS],
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS09_SCROLL_DETAILS => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_DETAILS],
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS10_SCROLL_DETAILS => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_DETAILS],
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS11_SCROLL_DETAILS => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_DETAILS],
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS12_SCROLL_DETAILS => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_DETAILS],
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS13_SCROLL_DETAILS => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_DETAILS],
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS14_SCROLL_DETAILS => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_DETAILS],
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS15_SCROLL_DETAILS => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_DETAILS],
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS16_SCROLL_DETAILS => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_DETAILS],
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS17_SCROLL_DETAILS => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_DETAILS],
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS18_SCROLL_DETAILS => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_DETAILS],
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS19_SCROLL_DETAILS => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_DETAILS],

            self::MODULE_DATALOAD_TAGCATEGORYPOSTS00_SCROLL_SIMPLEVIEW => [PoP_CategoryPosts_Module_Processor_Scrolls::class, PoP_CategoryPosts_Module_Processor_Scrolls::MODULE_SCROLL_CATEGORYPOSTS00_SIMPLEVIEW],
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS01_SCROLL_SIMPLEVIEW => [PoP_CategoryPosts_Module_Processor_Scrolls::class, PoP_CategoryPosts_Module_Processor_Scrolls::MODULE_SCROLL_CATEGORYPOSTS01_SIMPLEVIEW],
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS02_SCROLL_SIMPLEVIEW => [PoP_CategoryPosts_Module_Processor_Scrolls::class, PoP_CategoryPosts_Module_Processor_Scrolls::MODULE_SCROLL_CATEGORYPOSTS02_SIMPLEVIEW],
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS03_SCROLL_SIMPLEVIEW => [PoP_CategoryPosts_Module_Processor_Scrolls::class, PoP_CategoryPosts_Module_Processor_Scrolls::MODULE_SCROLL_CATEGORYPOSTS03_SIMPLEVIEW],
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS04_SCROLL_SIMPLEVIEW => [PoP_CategoryPosts_Module_Processor_Scrolls::class, PoP_CategoryPosts_Module_Processor_Scrolls::MODULE_SCROLL_CATEGORYPOSTS04_SIMPLEVIEW],
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS05_SCROLL_SIMPLEVIEW => [PoP_CategoryPosts_Module_Processor_Scrolls::class, PoP_CategoryPosts_Module_Processor_Scrolls::MODULE_SCROLL_CATEGORYPOSTS05_SIMPLEVIEW],
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS06_SCROLL_SIMPLEVIEW => [PoP_CategoryPosts_Module_Processor_Scrolls::class, PoP_CategoryPosts_Module_Processor_Scrolls::MODULE_SCROLL_CATEGORYPOSTS06_SIMPLEVIEW],
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS07_SCROLL_SIMPLEVIEW => [PoP_CategoryPosts_Module_Processor_Scrolls::class, PoP_CategoryPosts_Module_Processor_Scrolls::MODULE_SCROLL_CATEGORYPOSTS07_SIMPLEVIEW],
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS08_SCROLL_SIMPLEVIEW => [PoP_CategoryPosts_Module_Processor_Scrolls::class, PoP_CategoryPosts_Module_Processor_Scrolls::MODULE_SCROLL_CATEGORYPOSTS08_SIMPLEVIEW],
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS09_SCROLL_SIMPLEVIEW => [PoP_CategoryPosts_Module_Processor_Scrolls::class, PoP_CategoryPosts_Module_Processor_Scrolls::MODULE_SCROLL_CATEGORYPOSTS09_SIMPLEVIEW],
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS10_SCROLL_SIMPLEVIEW => [PoP_CategoryPosts_Module_Processor_Scrolls::class, PoP_CategoryPosts_Module_Processor_Scrolls::MODULE_SCROLL_CATEGORYPOSTS10_SIMPLEVIEW],
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS11_SCROLL_SIMPLEVIEW => [PoP_CategoryPosts_Module_Processor_Scrolls::class, PoP_CategoryPosts_Module_Processor_Scrolls::MODULE_SCROLL_CATEGORYPOSTS11_SIMPLEVIEW],
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS12_SCROLL_SIMPLEVIEW => [PoP_CategoryPosts_Module_Processor_Scrolls::class, PoP_CategoryPosts_Module_Processor_Scrolls::MODULE_SCROLL_CATEGORYPOSTS12_SIMPLEVIEW],
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS13_SCROLL_SIMPLEVIEW => [PoP_CategoryPosts_Module_Processor_Scrolls::class, PoP_CategoryPosts_Module_Processor_Scrolls::MODULE_SCROLL_CATEGORYPOSTS13_SIMPLEVIEW],
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS14_SCROLL_SIMPLEVIEW => [PoP_CategoryPosts_Module_Processor_Scrolls::class, PoP_CategoryPosts_Module_Processor_Scrolls::MODULE_SCROLL_CATEGORYPOSTS14_SIMPLEVIEW],
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS15_SCROLL_SIMPLEVIEW => [PoP_CategoryPosts_Module_Processor_Scrolls::class, PoP_CategoryPosts_Module_Processor_Scrolls::MODULE_SCROLL_CATEGORYPOSTS15_SIMPLEVIEW],
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS16_SCROLL_SIMPLEVIEW => [PoP_CategoryPosts_Module_Processor_Scrolls::class, PoP_CategoryPosts_Module_Processor_Scrolls::MODULE_SCROLL_CATEGORYPOSTS16_SIMPLEVIEW],
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS17_SCROLL_SIMPLEVIEW => [PoP_CategoryPosts_Module_Processor_Scrolls::class, PoP_CategoryPosts_Module_Processor_Scrolls::MODULE_SCROLL_CATEGORYPOSTS17_SIMPLEVIEW],
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS18_SCROLL_SIMPLEVIEW => [PoP_CategoryPosts_Module_Processor_Scrolls::class, PoP_CategoryPosts_Module_Processor_Scrolls::MODULE_SCROLL_CATEGORYPOSTS18_SIMPLEVIEW],
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS19_SCROLL_SIMPLEVIEW => [PoP_CategoryPosts_Module_Processor_Scrolls::class, PoP_CategoryPosts_Module_Processor_Scrolls::MODULE_SCROLL_CATEGORYPOSTS19_SIMPLEVIEW],

            self::MODULE_DATALOAD_TAGCATEGORYPOSTS00_SCROLL_FULLVIEW => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_FULLVIEW],
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS01_SCROLL_FULLVIEW => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_FULLVIEW],
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS02_SCROLL_FULLVIEW => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_FULLVIEW],
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS03_SCROLL_FULLVIEW => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_FULLVIEW],
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS04_SCROLL_FULLVIEW => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_FULLVIEW],
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS05_SCROLL_FULLVIEW => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_FULLVIEW],
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS06_SCROLL_FULLVIEW => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_FULLVIEW],
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS07_SCROLL_FULLVIEW => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_FULLVIEW],
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS08_SCROLL_FULLVIEW => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_FULLVIEW],
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS09_SCROLL_FULLVIEW => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_FULLVIEW],
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS10_SCROLL_FULLVIEW => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_FULLVIEW],
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS11_SCROLL_FULLVIEW => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_FULLVIEW],
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS12_SCROLL_FULLVIEW => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_FULLVIEW],
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS13_SCROLL_FULLVIEW => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_FULLVIEW],
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS14_SCROLL_FULLVIEW => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_FULLVIEW],
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS15_SCROLL_FULLVIEW => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_FULLVIEW],
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS16_SCROLL_FULLVIEW => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_FULLVIEW],
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS17_SCROLL_FULLVIEW => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_FULLVIEW],
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS18_SCROLL_FULLVIEW => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_FULLVIEW],
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS19_SCROLL_FULLVIEW => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_FULLVIEW],

            self::MODULE_DATALOAD_TAGCATEGORYPOSTS00_SCROLL_THUMBNAIL => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_THUMBNAIL],
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS01_SCROLL_THUMBNAIL => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_THUMBNAIL],
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS02_SCROLL_THUMBNAIL => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_THUMBNAIL],
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS03_SCROLL_THUMBNAIL => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_THUMBNAIL],
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS04_SCROLL_THUMBNAIL => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_THUMBNAIL],
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS05_SCROLL_THUMBNAIL => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_THUMBNAIL],
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS06_SCROLL_THUMBNAIL => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_THUMBNAIL],
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS07_SCROLL_THUMBNAIL => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_THUMBNAIL],
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS08_SCROLL_THUMBNAIL => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_THUMBNAIL],
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS09_SCROLL_THUMBNAIL => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_THUMBNAIL],
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS10_SCROLL_THUMBNAIL => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_THUMBNAIL],
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS11_SCROLL_THUMBNAIL => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_THUMBNAIL],
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS12_SCROLL_THUMBNAIL => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_THUMBNAIL],
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS13_SCROLL_THUMBNAIL => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_THUMBNAIL],
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS14_SCROLL_THUMBNAIL => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_THUMBNAIL],
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS15_SCROLL_THUMBNAIL => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_THUMBNAIL],
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS16_SCROLL_THUMBNAIL => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_THUMBNAIL],
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS17_SCROLL_THUMBNAIL => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_THUMBNAIL],
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS18_SCROLL_THUMBNAIL => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_THUMBNAIL],
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS19_SCROLL_THUMBNAIL => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_THUMBNAIL],

            self::MODULE_DATALOAD_TAGCATEGORYPOSTS00_SCROLL_LIST => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_LIST],
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS01_SCROLL_LIST => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_LIST],
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS02_SCROLL_LIST => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_LIST],
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS03_SCROLL_LIST => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_LIST],
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS04_SCROLL_LIST => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_LIST],
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS05_SCROLL_LIST => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_LIST],
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS06_SCROLL_LIST => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_LIST],
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS07_SCROLL_LIST => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_LIST],
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS08_SCROLL_LIST => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_LIST],
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS09_SCROLL_LIST => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_LIST],
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS10_SCROLL_LIST => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_LIST],
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS11_SCROLL_LIST => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_LIST],
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS12_SCROLL_LIST => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_LIST],
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS13_SCROLL_LIST => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_LIST],
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS14_SCROLL_LIST => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_LIST],
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS15_SCROLL_LIST => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_LIST],
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS16_SCROLL_LIST => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_LIST],
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS17_SCROLL_LIST => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_LIST],
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS18_SCROLL_LIST => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_LIST],
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS19_SCROLL_LIST => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_LIST],

            self::MODULE_DATALOAD_TAGCATEGORYPOSTS00_SCROLL_LINE => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_LINE],
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS01_SCROLL_LINE => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_LINE],
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS02_SCROLL_LINE => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_LINE],
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS03_SCROLL_LINE => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_LINE],
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS04_SCROLL_LINE => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_LINE],
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS05_SCROLL_LINE => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_LINE],
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS06_SCROLL_LINE => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_LINE],
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS07_SCROLL_LINE => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_LINE],
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS08_SCROLL_LINE => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_LINE],
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS09_SCROLL_LINE => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_LINE],
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS10_SCROLL_LINE => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_LINE],
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS11_SCROLL_LINE => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_LINE],
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS12_SCROLL_LINE => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_LINE],
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS13_SCROLL_LINE => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_LINE],
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS14_SCROLL_LINE => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_LINE],
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS15_SCROLL_LINE => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_LINE],
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS16_SCROLL_LINE => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_LINE],
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS17_SCROLL_LINE => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_LINE],
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS18_SCROLL_LINE => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_LINE],
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS19_SCROLL_LINE => [PoP_Module_Processor_CustomScrolls::class, PoP_Module_Processor_CustomScrolls::MODULE_SCROLL_POSTS_LINE],

            /*********************************************
         * Carousels
         *********************************************/
            self::MODULE_DATALOAD_CATEGORYPOSTS00_CAROUSEL => [CPP_Module_Processor_Carousels::class, CPP_Module_Processor_Carousels::MODULE_CAROUSEL_CATEGORYPOSTS00],
            self::MODULE_DATALOAD_CATEGORYPOSTS01_CAROUSEL => [CPP_Module_Processor_Carousels::class, CPP_Module_Processor_Carousels::MODULE_CAROUSEL_CATEGORYPOSTS01],
            self::MODULE_DATALOAD_CATEGORYPOSTS02_CAROUSEL => [CPP_Module_Processor_Carousels::class, CPP_Module_Processor_Carousels::MODULE_CAROUSEL_CATEGORYPOSTS02],
            self::MODULE_DATALOAD_CATEGORYPOSTS03_CAROUSEL => [CPP_Module_Processor_Carousels::class, CPP_Module_Processor_Carousels::MODULE_CAROUSEL_CATEGORYPOSTS03],
            self::MODULE_DATALOAD_CATEGORYPOSTS04_CAROUSEL => [CPP_Module_Processor_Carousels::class, CPP_Module_Processor_Carousels::MODULE_CAROUSEL_CATEGORYPOSTS04],
            self::MODULE_DATALOAD_CATEGORYPOSTS05_CAROUSEL => [CPP_Module_Processor_Carousels::class, CPP_Module_Processor_Carousels::MODULE_CAROUSEL_CATEGORYPOSTS05],
            self::MODULE_DATALOAD_CATEGORYPOSTS06_CAROUSEL => [CPP_Module_Processor_Carousels::class, CPP_Module_Processor_Carousels::MODULE_CAROUSEL_CATEGORYPOSTS06],
            self::MODULE_DATALOAD_CATEGORYPOSTS07_CAROUSEL => [CPP_Module_Processor_Carousels::class, CPP_Module_Processor_Carousels::MODULE_CAROUSEL_CATEGORYPOSTS07],
            self::MODULE_DATALOAD_CATEGORYPOSTS08_CAROUSEL => [CPP_Module_Processor_Carousels::class, CPP_Module_Processor_Carousels::MODULE_CAROUSEL_CATEGORYPOSTS08],
            self::MODULE_DATALOAD_CATEGORYPOSTS09_CAROUSEL => [CPP_Module_Processor_Carousels::class, CPP_Module_Processor_Carousels::MODULE_CAROUSEL_CATEGORYPOSTS09],
            self::MODULE_DATALOAD_CATEGORYPOSTS10_CAROUSEL => [CPP_Module_Processor_Carousels::class, CPP_Module_Processor_Carousels::MODULE_CAROUSEL_CATEGORYPOSTS10],
            self::MODULE_DATALOAD_CATEGORYPOSTS11_CAROUSEL => [CPP_Module_Processor_Carousels::class, CPP_Module_Processor_Carousels::MODULE_CAROUSEL_CATEGORYPOSTS11],
            self::MODULE_DATALOAD_CATEGORYPOSTS12_CAROUSEL => [CPP_Module_Processor_Carousels::class, CPP_Module_Processor_Carousels::MODULE_CAROUSEL_CATEGORYPOSTS12],
            self::MODULE_DATALOAD_CATEGORYPOSTS13_CAROUSEL => [CPP_Module_Processor_Carousels::class, CPP_Module_Processor_Carousels::MODULE_CAROUSEL_CATEGORYPOSTS13],
            self::MODULE_DATALOAD_CATEGORYPOSTS14_CAROUSEL => [CPP_Module_Processor_Carousels::class, CPP_Module_Processor_Carousels::MODULE_CAROUSEL_CATEGORYPOSTS14],
            self::MODULE_DATALOAD_CATEGORYPOSTS15_CAROUSEL => [CPP_Module_Processor_Carousels::class, CPP_Module_Processor_Carousels::MODULE_CAROUSEL_CATEGORYPOSTS15],
            self::MODULE_DATALOAD_CATEGORYPOSTS16_CAROUSEL => [CPP_Module_Processor_Carousels::class, CPP_Module_Processor_Carousels::MODULE_CAROUSEL_CATEGORYPOSTS16],
            self::MODULE_DATALOAD_CATEGORYPOSTS17_CAROUSEL => [CPP_Module_Processor_Carousels::class, CPP_Module_Processor_Carousels::MODULE_CAROUSEL_CATEGORYPOSTS17],
            self::MODULE_DATALOAD_CATEGORYPOSTS18_CAROUSEL => [CPP_Module_Processor_Carousels::class, CPP_Module_Processor_Carousels::MODULE_CAROUSEL_CATEGORYPOSTS18],
            self::MODULE_DATALOAD_CATEGORYPOSTS19_CAROUSEL => [CPP_Module_Processor_Carousels::class, CPP_Module_Processor_Carousels::MODULE_CAROUSEL_CATEGORYPOSTS19],

            self::MODULE_DATALOAD_CATEGORYPOSTS00_CAROUSEL_CONTENT => [CPP_Module_Processor_Carousels::class, CPP_Module_Processor_Carousels::MODULE_CAROUSEL_CATEGORYPOSTS00_CONTENT],
            self::MODULE_DATALOAD_CATEGORYPOSTS01_CAROUSEL_CONTENT => [CPP_Module_Processor_Carousels::class, CPP_Module_Processor_Carousels::MODULE_CAROUSEL_CATEGORYPOSTS01_CONTENT],
            self::MODULE_DATALOAD_CATEGORYPOSTS02_CAROUSEL_CONTENT => [CPP_Module_Processor_Carousels::class, CPP_Module_Processor_Carousels::MODULE_CAROUSEL_CATEGORYPOSTS02_CONTENT],
            self::MODULE_DATALOAD_CATEGORYPOSTS03_CAROUSEL_CONTENT => [CPP_Module_Processor_Carousels::class, CPP_Module_Processor_Carousels::MODULE_CAROUSEL_CATEGORYPOSTS03_CONTENT],
            self::MODULE_DATALOAD_CATEGORYPOSTS04_CAROUSEL_CONTENT => [CPP_Module_Processor_Carousels::class, CPP_Module_Processor_Carousels::MODULE_CAROUSEL_CATEGORYPOSTS04_CONTENT],
            self::MODULE_DATALOAD_CATEGORYPOSTS05_CAROUSEL_CONTENT => [CPP_Module_Processor_Carousels::class, CPP_Module_Processor_Carousels::MODULE_CAROUSEL_CATEGORYPOSTS05_CONTENT],
            self::MODULE_DATALOAD_CATEGORYPOSTS06_CAROUSEL_CONTENT => [CPP_Module_Processor_Carousels::class, CPP_Module_Processor_Carousels::MODULE_CAROUSEL_CATEGORYPOSTS06_CONTENT],
            self::MODULE_DATALOAD_CATEGORYPOSTS07_CAROUSEL_CONTENT => [CPP_Module_Processor_Carousels::class, CPP_Module_Processor_Carousels::MODULE_CAROUSEL_CATEGORYPOSTS07_CONTENT],
            self::MODULE_DATALOAD_CATEGORYPOSTS08_CAROUSEL_CONTENT => [CPP_Module_Processor_Carousels::class, CPP_Module_Processor_Carousels::MODULE_CAROUSEL_CATEGORYPOSTS08_CONTENT],
            self::MODULE_DATALOAD_CATEGORYPOSTS09_CAROUSEL_CONTENT => [CPP_Module_Processor_Carousels::class, CPP_Module_Processor_Carousels::MODULE_CAROUSEL_CATEGORYPOSTS09_CONTENT],
            self::MODULE_DATALOAD_CATEGORYPOSTS10_CAROUSEL_CONTENT => [CPP_Module_Processor_Carousels::class, CPP_Module_Processor_Carousels::MODULE_CAROUSEL_CATEGORYPOSTS10_CONTENT],
            self::MODULE_DATALOAD_CATEGORYPOSTS11_CAROUSEL_CONTENT => [CPP_Module_Processor_Carousels::class, CPP_Module_Processor_Carousels::MODULE_CAROUSEL_CATEGORYPOSTS11_CONTENT],
            self::MODULE_DATALOAD_CATEGORYPOSTS12_CAROUSEL_CONTENT => [CPP_Module_Processor_Carousels::class, CPP_Module_Processor_Carousels::MODULE_CAROUSEL_CATEGORYPOSTS12_CONTENT],
            self::MODULE_DATALOAD_CATEGORYPOSTS13_CAROUSEL_CONTENT => [CPP_Module_Processor_Carousels::class, CPP_Module_Processor_Carousels::MODULE_CAROUSEL_CATEGORYPOSTS13_CONTENT],
            self::MODULE_DATALOAD_CATEGORYPOSTS14_CAROUSEL_CONTENT => [CPP_Module_Processor_Carousels::class, CPP_Module_Processor_Carousels::MODULE_CAROUSEL_CATEGORYPOSTS14_CONTENT],
            self::MODULE_DATALOAD_CATEGORYPOSTS15_CAROUSEL_CONTENT => [CPP_Module_Processor_Carousels::class, CPP_Module_Processor_Carousels::MODULE_CAROUSEL_CATEGORYPOSTS15_CONTENT],
            self::MODULE_DATALOAD_CATEGORYPOSTS16_CAROUSEL_CONTENT => [CPP_Module_Processor_Carousels::class, CPP_Module_Processor_Carousels::MODULE_CAROUSEL_CATEGORYPOSTS16_CONTENT],
            self::MODULE_DATALOAD_CATEGORYPOSTS17_CAROUSEL_CONTENT => [CPP_Module_Processor_Carousels::class, CPP_Module_Processor_Carousels::MODULE_CAROUSEL_CATEGORYPOSTS17_CONTENT],
            self::MODULE_DATALOAD_CATEGORYPOSTS18_CAROUSEL_CONTENT => [CPP_Module_Processor_Carousels::class, CPP_Module_Processor_Carousels::MODULE_CAROUSEL_CATEGORYPOSTS18_CONTENT],
            self::MODULE_DATALOAD_CATEGORYPOSTS19_CAROUSEL_CONTENT => [CPP_Module_Processor_Carousels::class, CPP_Module_Processor_Carousels::MODULE_CAROUSEL_CATEGORYPOSTS19_CONTENT],

            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS00_CAROUSEL => [CPP_Module_Processor_Carousels::class, CPP_Module_Processor_Carousels::MODULE_CAROUSEL_AUTHORCATEGORYPOSTS00],
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS01_CAROUSEL => [CPP_Module_Processor_Carousels::class, CPP_Module_Processor_Carousels::MODULE_CAROUSEL_AUTHORCATEGORYPOSTS01],
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS02_CAROUSEL => [CPP_Module_Processor_Carousels::class, CPP_Module_Processor_Carousels::MODULE_CAROUSEL_AUTHORCATEGORYPOSTS02],
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS03_CAROUSEL => [CPP_Module_Processor_Carousels::class, CPP_Module_Processor_Carousels::MODULE_CAROUSEL_AUTHORCATEGORYPOSTS03],
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS04_CAROUSEL => [CPP_Module_Processor_Carousels::class, CPP_Module_Processor_Carousels::MODULE_CAROUSEL_AUTHORCATEGORYPOSTS04],
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS05_CAROUSEL => [CPP_Module_Processor_Carousels::class, CPP_Module_Processor_Carousels::MODULE_CAROUSEL_AUTHORCATEGORYPOSTS05],
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS06_CAROUSEL => [CPP_Module_Processor_Carousels::class, CPP_Module_Processor_Carousels::MODULE_CAROUSEL_AUTHORCATEGORYPOSTS06],
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS07_CAROUSEL => [CPP_Module_Processor_Carousels::class, CPP_Module_Processor_Carousels::MODULE_CAROUSEL_AUTHORCATEGORYPOSTS07],
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS08_CAROUSEL => [CPP_Module_Processor_Carousels::class, CPP_Module_Processor_Carousels::MODULE_CAROUSEL_AUTHORCATEGORYPOSTS08],
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS09_CAROUSEL => [CPP_Module_Processor_Carousels::class, CPP_Module_Processor_Carousels::MODULE_CAROUSEL_AUTHORCATEGORYPOSTS09],
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS10_CAROUSEL => [CPP_Module_Processor_Carousels::class, CPP_Module_Processor_Carousels::MODULE_CAROUSEL_AUTHORCATEGORYPOSTS10],
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS11_CAROUSEL => [CPP_Module_Processor_Carousels::class, CPP_Module_Processor_Carousels::MODULE_CAROUSEL_AUTHORCATEGORYPOSTS11],
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS12_CAROUSEL => [CPP_Module_Processor_Carousels::class, CPP_Module_Processor_Carousels::MODULE_CAROUSEL_AUTHORCATEGORYPOSTS12],
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS13_CAROUSEL => [CPP_Module_Processor_Carousels::class, CPP_Module_Processor_Carousels::MODULE_CAROUSEL_AUTHORCATEGORYPOSTS13],
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS14_CAROUSEL => [CPP_Module_Processor_Carousels::class, CPP_Module_Processor_Carousels::MODULE_CAROUSEL_AUTHORCATEGORYPOSTS14],
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS15_CAROUSEL => [CPP_Module_Processor_Carousels::class, CPP_Module_Processor_Carousels::MODULE_CAROUSEL_AUTHORCATEGORYPOSTS15],
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS16_CAROUSEL => [CPP_Module_Processor_Carousels::class, CPP_Module_Processor_Carousels::MODULE_CAROUSEL_AUTHORCATEGORYPOSTS16],
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS17_CAROUSEL => [CPP_Module_Processor_Carousels::class, CPP_Module_Processor_Carousels::MODULE_CAROUSEL_AUTHORCATEGORYPOSTS17],
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS18_CAROUSEL => [CPP_Module_Processor_Carousels::class, CPP_Module_Processor_Carousels::MODULE_CAROUSEL_AUTHORCATEGORYPOSTS18],
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS19_CAROUSEL => [CPP_Module_Processor_Carousels::class, CPP_Module_Processor_Carousels::MODULE_CAROUSEL_AUTHORCATEGORYPOSTS19],

            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS00_CAROUSEL_CONTENT => [CPP_Module_Processor_Carousels::class, CPP_Module_Processor_Carousels::MODULE_CAROUSEL_AUTHORCATEGORYPOSTS00_CONTENT],
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS01_CAROUSEL_CONTENT => [CPP_Module_Processor_Carousels::class, CPP_Module_Processor_Carousels::MODULE_CAROUSEL_AUTHORCATEGORYPOSTS01_CONTENT],
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS02_CAROUSEL_CONTENT => [CPP_Module_Processor_Carousels::class, CPP_Module_Processor_Carousels::MODULE_CAROUSEL_AUTHORCATEGORYPOSTS02_CONTENT],
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS03_CAROUSEL_CONTENT => [CPP_Module_Processor_Carousels::class, CPP_Module_Processor_Carousels::MODULE_CAROUSEL_AUTHORCATEGORYPOSTS03_CONTENT],
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS04_CAROUSEL_CONTENT => [CPP_Module_Processor_Carousels::class, CPP_Module_Processor_Carousels::MODULE_CAROUSEL_AUTHORCATEGORYPOSTS04_CONTENT],
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS05_CAROUSEL_CONTENT => [CPP_Module_Processor_Carousels::class, CPP_Module_Processor_Carousels::MODULE_CAROUSEL_AUTHORCATEGORYPOSTS05_CONTENT],
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS06_CAROUSEL_CONTENT => [CPP_Module_Processor_Carousels::class, CPP_Module_Processor_Carousels::MODULE_CAROUSEL_AUTHORCATEGORYPOSTS06_CONTENT],
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS07_CAROUSEL_CONTENT => [CPP_Module_Processor_Carousels::class, CPP_Module_Processor_Carousels::MODULE_CAROUSEL_AUTHORCATEGORYPOSTS07_CONTENT],
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS08_CAROUSEL_CONTENT => [CPP_Module_Processor_Carousels::class, CPP_Module_Processor_Carousels::MODULE_CAROUSEL_AUTHORCATEGORYPOSTS08_CONTENT],
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS09_CAROUSEL_CONTENT => [CPP_Module_Processor_Carousels::class, CPP_Module_Processor_Carousels::MODULE_CAROUSEL_AUTHORCATEGORYPOSTS09_CONTENT],
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS10_CAROUSEL_CONTENT => [CPP_Module_Processor_Carousels::class, CPP_Module_Processor_Carousels::MODULE_CAROUSEL_AUTHORCATEGORYPOSTS10_CONTENT],
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS11_CAROUSEL_CONTENT => [CPP_Module_Processor_Carousels::class, CPP_Module_Processor_Carousels::MODULE_CAROUSEL_AUTHORCATEGORYPOSTS11_CONTENT],
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS12_CAROUSEL_CONTENT => [CPP_Module_Processor_Carousels::class, CPP_Module_Processor_Carousels::MODULE_CAROUSEL_AUTHORCATEGORYPOSTS12_CONTENT],
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS13_CAROUSEL_CONTENT => [CPP_Module_Processor_Carousels::class, CPP_Module_Processor_Carousels::MODULE_CAROUSEL_AUTHORCATEGORYPOSTS13_CONTENT],
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS14_CAROUSEL_CONTENT => [CPP_Module_Processor_Carousels::class, CPP_Module_Processor_Carousels::MODULE_CAROUSEL_AUTHORCATEGORYPOSTS14_CONTENT],
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS15_CAROUSEL_CONTENT => [CPP_Module_Processor_Carousels::class, CPP_Module_Processor_Carousels::MODULE_CAROUSEL_AUTHORCATEGORYPOSTS15_CONTENT],
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS16_CAROUSEL_CONTENT => [CPP_Module_Processor_Carousels::class, CPP_Module_Processor_Carousels::MODULE_CAROUSEL_AUTHORCATEGORYPOSTS16_CONTENT],
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS17_CAROUSEL_CONTENT => [CPP_Module_Processor_Carousels::class, CPP_Module_Processor_Carousels::MODULE_CAROUSEL_AUTHORCATEGORYPOSTS17_CONTENT],
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS18_CAROUSEL_CONTENT => [CPP_Module_Processor_Carousels::class, CPP_Module_Processor_Carousels::MODULE_CAROUSEL_AUTHORCATEGORYPOSTS18_CONTENT],
            self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS19_CAROUSEL_CONTENT => [CPP_Module_Processor_Carousels::class, CPP_Module_Processor_Carousels::MODULE_CAROUSEL_AUTHORCATEGORYPOSTS19_CONTENT],

            self::MODULE_DATALOAD_TAGCATEGORYPOSTS00_CAROUSEL => [CPP_Module_Processor_Carousels::class, CPP_Module_Processor_Carousels::MODULE_CAROUSEL_TAGCATEGORYPOSTS00],
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS01_CAROUSEL => [CPP_Module_Processor_Carousels::class, CPP_Module_Processor_Carousels::MODULE_CAROUSEL_TAGCATEGORYPOSTS01],
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS02_CAROUSEL => [CPP_Module_Processor_Carousels::class, CPP_Module_Processor_Carousels::MODULE_CAROUSEL_TAGCATEGORYPOSTS02],
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS03_CAROUSEL => [CPP_Module_Processor_Carousels::class, CPP_Module_Processor_Carousels::MODULE_CAROUSEL_TAGCATEGORYPOSTS03],
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS04_CAROUSEL => [CPP_Module_Processor_Carousels::class, CPP_Module_Processor_Carousels::MODULE_CAROUSEL_TAGCATEGORYPOSTS04],
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS05_CAROUSEL => [CPP_Module_Processor_Carousels::class, CPP_Module_Processor_Carousels::MODULE_CAROUSEL_TAGCATEGORYPOSTS05],
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS06_CAROUSEL => [CPP_Module_Processor_Carousels::class, CPP_Module_Processor_Carousels::MODULE_CAROUSEL_TAGCATEGORYPOSTS06],
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS07_CAROUSEL => [CPP_Module_Processor_Carousels::class, CPP_Module_Processor_Carousels::MODULE_CAROUSEL_TAGCATEGORYPOSTS07],
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS08_CAROUSEL => [CPP_Module_Processor_Carousels::class, CPP_Module_Processor_Carousels::MODULE_CAROUSEL_TAGCATEGORYPOSTS08],
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS09_CAROUSEL => [CPP_Module_Processor_Carousels::class, CPP_Module_Processor_Carousels::MODULE_CAROUSEL_TAGCATEGORYPOSTS09],
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS10_CAROUSEL => [CPP_Module_Processor_Carousels::class, CPP_Module_Processor_Carousels::MODULE_CAROUSEL_TAGCATEGORYPOSTS10],
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS11_CAROUSEL => [CPP_Module_Processor_Carousels::class, CPP_Module_Processor_Carousels::MODULE_CAROUSEL_TAGCATEGORYPOSTS11],
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS12_CAROUSEL => [CPP_Module_Processor_Carousels::class, CPP_Module_Processor_Carousels::MODULE_CAROUSEL_TAGCATEGORYPOSTS12],
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS13_CAROUSEL => [CPP_Module_Processor_Carousels::class, CPP_Module_Processor_Carousels::MODULE_CAROUSEL_TAGCATEGORYPOSTS13],
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS14_CAROUSEL => [CPP_Module_Processor_Carousels::class, CPP_Module_Processor_Carousels::MODULE_CAROUSEL_TAGCATEGORYPOSTS14],
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS15_CAROUSEL => [CPP_Module_Processor_Carousels::class, CPP_Module_Processor_Carousels::MODULE_CAROUSEL_TAGCATEGORYPOSTS15],
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS16_CAROUSEL => [CPP_Module_Processor_Carousels::class, CPP_Module_Processor_Carousels::MODULE_CAROUSEL_TAGCATEGORYPOSTS16],
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS17_CAROUSEL => [CPP_Module_Processor_Carousels::class, CPP_Module_Processor_Carousels::MODULE_CAROUSEL_TAGCATEGORYPOSTS17],
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS18_CAROUSEL => [CPP_Module_Processor_Carousels::class, CPP_Module_Processor_Carousels::MODULE_CAROUSEL_TAGCATEGORYPOSTS18],
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS19_CAROUSEL => [CPP_Module_Processor_Carousels::class, CPP_Module_Processor_Carousels::MODULE_CAROUSEL_TAGCATEGORYPOSTS19],

            self::MODULE_DATALOAD_TAGCATEGORYPOSTS00_CAROUSEL_CONTENT => [CPP_Module_Processor_Carousels::class, CPP_Module_Processor_Carousels::MODULE_CAROUSEL_TAGCATEGORYPOSTS00_CONTENT],
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS01_CAROUSEL_CONTENT => [CPP_Module_Processor_Carousels::class, CPP_Module_Processor_Carousels::MODULE_CAROUSEL_TAGCATEGORYPOSTS01_CONTENT],
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS02_CAROUSEL_CONTENT => [CPP_Module_Processor_Carousels::class, CPP_Module_Processor_Carousels::MODULE_CAROUSEL_TAGCATEGORYPOSTS02_CONTENT],
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS03_CAROUSEL_CONTENT => [CPP_Module_Processor_Carousels::class, CPP_Module_Processor_Carousels::MODULE_CAROUSEL_TAGCATEGORYPOSTS03_CONTENT],
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS04_CAROUSEL_CONTENT => [CPP_Module_Processor_Carousels::class, CPP_Module_Processor_Carousels::MODULE_CAROUSEL_TAGCATEGORYPOSTS04_CONTENT],
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS05_CAROUSEL_CONTENT => [CPP_Module_Processor_Carousels::class, CPP_Module_Processor_Carousels::MODULE_CAROUSEL_TAGCATEGORYPOSTS05_CONTENT],
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS06_CAROUSEL_CONTENT => [CPP_Module_Processor_Carousels::class, CPP_Module_Processor_Carousels::MODULE_CAROUSEL_TAGCATEGORYPOSTS06_CONTENT],
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS07_CAROUSEL_CONTENT => [CPP_Module_Processor_Carousels::class, CPP_Module_Processor_Carousels::MODULE_CAROUSEL_TAGCATEGORYPOSTS07_CONTENT],
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS08_CAROUSEL_CONTENT => [CPP_Module_Processor_Carousels::class, CPP_Module_Processor_Carousels::MODULE_CAROUSEL_TAGCATEGORYPOSTS08_CONTENT],
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS09_CAROUSEL_CONTENT => [CPP_Module_Processor_Carousels::class, CPP_Module_Processor_Carousels::MODULE_CAROUSEL_TAGCATEGORYPOSTS09_CONTENT],
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS10_CAROUSEL_CONTENT => [CPP_Module_Processor_Carousels::class, CPP_Module_Processor_Carousels::MODULE_CAROUSEL_TAGCATEGORYPOSTS10_CONTENT],
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS11_CAROUSEL_CONTENT => [CPP_Module_Processor_Carousels::class, CPP_Module_Processor_Carousels::MODULE_CAROUSEL_TAGCATEGORYPOSTS11_CONTENT],
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS12_CAROUSEL_CONTENT => [CPP_Module_Processor_Carousels::class, CPP_Module_Processor_Carousels::MODULE_CAROUSEL_TAGCATEGORYPOSTS12_CONTENT],
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS13_CAROUSEL_CONTENT => [CPP_Module_Processor_Carousels::class, CPP_Module_Processor_Carousels::MODULE_CAROUSEL_TAGCATEGORYPOSTS13_CONTENT],
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS14_CAROUSEL_CONTENT => [CPP_Module_Processor_Carousels::class, CPP_Module_Processor_Carousels::MODULE_CAROUSEL_TAGCATEGORYPOSTS14_CONTENT],
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS15_CAROUSEL_CONTENT => [CPP_Module_Processor_Carousels::class, CPP_Module_Processor_Carousels::MODULE_CAROUSEL_TAGCATEGORYPOSTS15_CONTENT],
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS16_CAROUSEL_CONTENT => [CPP_Module_Processor_Carousels::class, CPP_Module_Processor_Carousels::MODULE_CAROUSEL_TAGCATEGORYPOSTS16_CONTENT],
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS17_CAROUSEL_CONTENT => [CPP_Module_Processor_Carousels::class, CPP_Module_Processor_Carousels::MODULE_CAROUSEL_TAGCATEGORYPOSTS17_CONTENT],
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS18_CAROUSEL_CONTENT => [CPP_Module_Processor_Carousels::class, CPP_Module_Processor_Carousels::MODULE_CAROUSEL_TAGCATEGORYPOSTS18_CONTENT],
            self::MODULE_DATALOAD_TAGCATEGORYPOSTS19_CAROUSEL_CONTENT => [CPP_Module_Processor_Carousels::class, CPP_Module_Processor_Carousels::MODULE_CAROUSEL_TAGCATEGORYPOSTS19_CONTENT],
        );

        return $inner_modules[$componentVariation[1]] ?? null;
    }

    public function getFilterSubmodule(array $componentVariation): ?array
    {
        switch ($componentVariation[1]) {
            case self::MODULE_DATALOAD_CATEGORYPOSTS00_TYPEAHEAD:
            case self::MODULE_DATALOAD_CATEGORYPOSTS01_TYPEAHEAD:
            case self::MODULE_DATALOAD_CATEGORYPOSTS02_TYPEAHEAD:
            case self::MODULE_DATALOAD_CATEGORYPOSTS03_TYPEAHEAD:
            case self::MODULE_DATALOAD_CATEGORYPOSTS04_TYPEAHEAD:
            case self::MODULE_DATALOAD_CATEGORYPOSTS05_TYPEAHEAD:
            case self::MODULE_DATALOAD_CATEGORYPOSTS06_TYPEAHEAD:
            case self::MODULE_DATALOAD_CATEGORYPOSTS07_TYPEAHEAD:
            case self::MODULE_DATALOAD_CATEGORYPOSTS08_TYPEAHEAD:
            case self::MODULE_DATALOAD_CATEGORYPOSTS09_TYPEAHEAD:
            case self::MODULE_DATALOAD_CATEGORYPOSTS00_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_CATEGORYPOSTS01_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_CATEGORYPOSTS02_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_CATEGORYPOSTS03_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_CATEGORYPOSTS04_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_CATEGORYPOSTS05_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_CATEGORYPOSTS06_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_CATEGORYPOSTS07_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_CATEGORYPOSTS08_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_CATEGORYPOSTS09_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_CATEGORYPOSTS00_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_CATEGORYPOSTS01_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_CATEGORYPOSTS02_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_CATEGORYPOSTS03_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_CATEGORYPOSTS04_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_CATEGORYPOSTS05_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_CATEGORYPOSTS06_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_CATEGORYPOSTS07_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_CATEGORYPOSTS08_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_CATEGORYPOSTS09_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_CATEGORYPOSTS00_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_CATEGORYPOSTS01_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_CATEGORYPOSTS02_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_CATEGORYPOSTS03_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_CATEGORYPOSTS04_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_CATEGORYPOSTS05_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_CATEGORYPOSTS06_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_CATEGORYPOSTS07_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_CATEGORYPOSTS08_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_CATEGORYPOSTS09_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_CATEGORYPOSTS00_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_CATEGORYPOSTS01_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_CATEGORYPOSTS02_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_CATEGORYPOSTS03_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_CATEGORYPOSTS04_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_CATEGORYPOSTS05_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_CATEGORYPOSTS06_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_CATEGORYPOSTS07_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_CATEGORYPOSTS08_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_CATEGORYPOSTS09_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_CATEGORYPOSTS00_SCROLL_LIST:
            case self::MODULE_DATALOAD_CATEGORYPOSTS01_SCROLL_LIST:
            case self::MODULE_DATALOAD_CATEGORYPOSTS02_SCROLL_LIST:
            case self::MODULE_DATALOAD_CATEGORYPOSTS03_SCROLL_LIST:
            case self::MODULE_DATALOAD_CATEGORYPOSTS04_SCROLL_LIST:
            case self::MODULE_DATALOAD_CATEGORYPOSTS05_SCROLL_LIST:
            case self::MODULE_DATALOAD_CATEGORYPOSTS06_SCROLL_LIST:
            case self::MODULE_DATALOAD_CATEGORYPOSTS07_SCROLL_LIST:
            case self::MODULE_DATALOAD_CATEGORYPOSTS08_SCROLL_LIST:
            case self::MODULE_DATALOAD_CATEGORYPOSTS09_SCROLL_LIST:
            case self::MODULE_DATALOAD_CATEGORYPOSTS00_SCROLL_LINE:
            case self::MODULE_DATALOAD_CATEGORYPOSTS01_SCROLL_LINE:
            case self::MODULE_DATALOAD_CATEGORYPOSTS02_SCROLL_LINE:
            case self::MODULE_DATALOAD_CATEGORYPOSTS03_SCROLL_LINE:
            case self::MODULE_DATALOAD_CATEGORYPOSTS04_SCROLL_LINE:
            case self::MODULE_DATALOAD_CATEGORYPOSTS05_SCROLL_LINE:
            case self::MODULE_DATALOAD_CATEGORYPOSTS06_SCROLL_LINE:
            case self::MODULE_DATALOAD_CATEGORYPOSTS07_SCROLL_LINE:
            case self::MODULE_DATALOAD_CATEGORYPOSTS08_SCROLL_LINE:
            case self::MODULE_DATALOAD_CATEGORYPOSTS09_SCROLL_LINE:
            case self::MODULE_DATALOAD_CATEGORYPOSTS10_TYPEAHEAD:
            case self::MODULE_DATALOAD_CATEGORYPOSTS11_TYPEAHEAD:
            case self::MODULE_DATALOAD_CATEGORYPOSTS12_TYPEAHEAD:
            case self::MODULE_DATALOAD_CATEGORYPOSTS13_TYPEAHEAD:
            case self::MODULE_DATALOAD_CATEGORYPOSTS14_TYPEAHEAD:
            case self::MODULE_DATALOAD_CATEGORYPOSTS15_TYPEAHEAD:
            case self::MODULE_DATALOAD_CATEGORYPOSTS16_TYPEAHEAD:
            case self::MODULE_DATALOAD_CATEGORYPOSTS17_TYPEAHEAD:
            case self::MODULE_DATALOAD_CATEGORYPOSTS18_TYPEAHEAD:
            case self::MODULE_DATALOAD_CATEGORYPOSTS19_TYPEAHEAD:
            case self::MODULE_DATALOAD_CATEGORYPOSTS10_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_CATEGORYPOSTS11_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_CATEGORYPOSTS12_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_CATEGORYPOSTS13_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_CATEGORYPOSTS14_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_CATEGORYPOSTS15_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_CATEGORYPOSTS16_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_CATEGORYPOSTS17_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_CATEGORYPOSTS18_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_CATEGORYPOSTS19_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_CATEGORYPOSTS10_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_CATEGORYPOSTS11_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_CATEGORYPOSTS12_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_CATEGORYPOSTS13_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_CATEGORYPOSTS14_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_CATEGORYPOSTS15_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_CATEGORYPOSTS16_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_CATEGORYPOSTS17_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_CATEGORYPOSTS18_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_CATEGORYPOSTS19_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_CATEGORYPOSTS10_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_CATEGORYPOSTS11_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_CATEGORYPOSTS12_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_CATEGORYPOSTS13_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_CATEGORYPOSTS14_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_CATEGORYPOSTS15_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_CATEGORYPOSTS16_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_CATEGORYPOSTS17_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_CATEGORYPOSTS18_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_CATEGORYPOSTS19_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_CATEGORYPOSTS10_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_CATEGORYPOSTS11_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_CATEGORYPOSTS12_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_CATEGORYPOSTS13_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_CATEGORYPOSTS14_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_CATEGORYPOSTS15_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_CATEGORYPOSTS16_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_CATEGORYPOSTS17_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_CATEGORYPOSTS18_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_CATEGORYPOSTS19_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_CATEGORYPOSTS10_SCROLL_LIST:
            case self::MODULE_DATALOAD_CATEGORYPOSTS11_SCROLL_LIST:
            case self::MODULE_DATALOAD_CATEGORYPOSTS12_SCROLL_LIST:
            case self::MODULE_DATALOAD_CATEGORYPOSTS13_SCROLL_LIST:
            case self::MODULE_DATALOAD_CATEGORYPOSTS14_SCROLL_LIST:
            case self::MODULE_DATALOAD_CATEGORYPOSTS15_SCROLL_LIST:
            case self::MODULE_DATALOAD_CATEGORYPOSTS16_SCROLL_LIST:
            case self::MODULE_DATALOAD_CATEGORYPOSTS17_SCROLL_LIST:
            case self::MODULE_DATALOAD_CATEGORYPOSTS18_SCROLL_LIST:
            case self::MODULE_DATALOAD_CATEGORYPOSTS19_SCROLL_LIST:
            case self::MODULE_DATALOAD_CATEGORYPOSTS10_SCROLL_LINE:
            case self::MODULE_DATALOAD_CATEGORYPOSTS11_SCROLL_LINE:
            case self::MODULE_DATALOAD_CATEGORYPOSTS12_SCROLL_LINE:
            case self::MODULE_DATALOAD_CATEGORYPOSTS13_SCROLL_LINE:
            case self::MODULE_DATALOAD_CATEGORYPOSTS14_SCROLL_LINE:
            case self::MODULE_DATALOAD_CATEGORYPOSTS15_SCROLL_LINE:
            case self::MODULE_DATALOAD_CATEGORYPOSTS16_SCROLL_LINE:
            case self::MODULE_DATALOAD_CATEGORYPOSTS17_SCROLL_LINE:
            case self::MODULE_DATALOAD_CATEGORYPOSTS18_SCROLL_LINE:
            case self::MODULE_DATALOAD_CATEGORYPOSTS19_SCROLL_LINE:
                return [PoP_Module_Processor_CustomFilters::class, PoP_Module_Processor_CustomFilters::MODULE_FILTER_CATEGORYPOSTS];

            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS00_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS01_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS02_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS03_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS04_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS05_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS06_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS07_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS08_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS09_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS10_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS11_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS12_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS13_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS14_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS15_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS16_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS17_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS18_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS19_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS00_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS01_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS02_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS03_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS04_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS05_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS06_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS07_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS08_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS09_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS10_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS11_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS12_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS13_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS14_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS15_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS16_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS17_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS18_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS19_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS00_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS01_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS02_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS03_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS04_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS05_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS06_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS07_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS08_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS09_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS10_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS11_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS12_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS13_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS14_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS15_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS16_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS17_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS18_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS19_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS00_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS01_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS02_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS03_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS04_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS05_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS06_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS07_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS08_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS09_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS10_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS11_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS12_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS13_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS14_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS15_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS16_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS17_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS18_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS19_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS00_SCROLL_LIST:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS01_SCROLL_LIST:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS02_SCROLL_LIST:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS03_SCROLL_LIST:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS04_SCROLL_LIST:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS05_SCROLL_LIST:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS06_SCROLL_LIST:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS07_SCROLL_LIST:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS08_SCROLL_LIST:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS09_SCROLL_LIST:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS10_SCROLL_LIST:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS11_SCROLL_LIST:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS12_SCROLL_LIST:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS13_SCROLL_LIST:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS14_SCROLL_LIST:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS15_SCROLL_LIST:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS16_SCROLL_LIST:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS17_SCROLL_LIST:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS18_SCROLL_LIST:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS19_SCROLL_LIST:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS00_SCROLL_LINE:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS01_SCROLL_LINE:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS02_SCROLL_LINE:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS03_SCROLL_LINE:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS04_SCROLL_LINE:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS05_SCROLL_LINE:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS06_SCROLL_LINE:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS07_SCROLL_LINE:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS08_SCROLL_LINE:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS09_SCROLL_LINE:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS10_SCROLL_LINE:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS11_SCROLL_LINE:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS12_SCROLL_LINE:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS13_SCROLL_LINE:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS14_SCROLL_LINE:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS15_SCROLL_LINE:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS16_SCROLL_LINE:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS17_SCROLL_LINE:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS18_SCROLL_LINE:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS19_SCROLL_LINE:
                return [PoP_Module_Processor_CustomFilters::class, PoP_Module_Processor_CustomFilters::MODULE_FILTER_AUTHORCATEGORYPOSTS];

            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS00_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS01_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS02_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS03_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS04_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS05_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS06_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS07_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS08_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS09_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS10_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS11_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS12_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS13_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS14_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS15_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS16_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS17_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS18_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS19_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS00_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS01_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS02_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS03_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS04_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS05_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS06_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS07_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS08_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS19_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS10_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS11_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS12_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS13_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS14_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS15_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS16_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS17_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS18_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS19_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS00_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS01_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS02_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS03_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS04_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS05_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS06_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS07_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS08_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS09_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS10_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS11_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS12_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS13_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS14_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS15_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS16_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS17_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS18_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS19_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS00_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS01_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS02_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS03_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS04_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS05_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS06_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS07_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS08_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS09_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS10_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS11_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS12_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS13_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS14_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS15_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS16_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS17_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS18_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS19_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS00_SCROLL_LIST:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS01_SCROLL_LIST:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS02_SCROLL_LIST:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS03_SCROLL_LIST:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS04_SCROLL_LIST:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS05_SCROLL_LIST:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS06_SCROLL_LIST:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS07_SCROLL_LIST:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS08_SCROLL_LIST:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS09_SCROLL_LIST:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS10_SCROLL_LIST:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS11_SCROLL_LIST:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS12_SCROLL_LIST:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS13_SCROLL_LIST:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS14_SCROLL_LIST:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS15_SCROLL_LIST:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS16_SCROLL_LIST:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS17_SCROLL_LIST:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS18_SCROLL_LIST:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS19_SCROLL_LIST:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS00_SCROLL_LINE:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS01_SCROLL_LINE:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS02_SCROLL_LINE:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS03_SCROLL_LINE:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS04_SCROLL_LINE:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS05_SCROLL_LINE:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS06_SCROLL_LINE:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS07_SCROLL_LINE:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS08_SCROLL_LINE:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS09_SCROLL_LINE:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS10_SCROLL_LINE:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS11_SCROLL_LINE:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS12_SCROLL_LINE:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS13_SCROLL_LINE:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS14_SCROLL_LINE:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS15_SCROLL_LINE:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS16_SCROLL_LINE:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS17_SCROLL_LINE:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS18_SCROLL_LINE:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS19_SCROLL_LINE:
                return [PoP_Module_Processor_CustomFilters::class, PoP_Module_Processor_CustomFilters::MODULE_FILTER_TAGCATEGORYPOSTS];
        }

        return parent::getFilterSubmodule($componentVariation);
    }

    public function getFormat(array $componentVariation): ?string
    {

        // Add the format attr
        $details = array(
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS00_SCROLL_DETAILS],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS01_SCROLL_DETAILS],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS02_SCROLL_DETAILS],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS03_SCROLL_DETAILS],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS04_SCROLL_DETAILS],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS05_SCROLL_DETAILS],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS06_SCROLL_DETAILS],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS07_SCROLL_DETAILS],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS08_SCROLL_DETAILS],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS09_SCROLL_DETAILS],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS10_SCROLL_DETAILS],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS11_SCROLL_DETAILS],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS12_SCROLL_DETAILS],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS13_SCROLL_DETAILS],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS14_SCROLL_DETAILS],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS15_SCROLL_DETAILS],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS16_SCROLL_DETAILS],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS17_SCROLL_DETAILS],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS18_SCROLL_DETAILS],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS19_SCROLL_DETAILS],

            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS00_SCROLL_DETAILS],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS01_SCROLL_DETAILS],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS02_SCROLL_DETAILS],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS03_SCROLL_DETAILS],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS04_SCROLL_DETAILS],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS05_SCROLL_DETAILS],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS06_SCROLL_DETAILS],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS07_SCROLL_DETAILS],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS08_SCROLL_DETAILS],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS09_SCROLL_DETAILS],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS10_SCROLL_DETAILS],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS11_SCROLL_DETAILS],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS12_SCROLL_DETAILS],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS13_SCROLL_DETAILS],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS14_SCROLL_DETAILS],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS15_SCROLL_DETAILS],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS16_SCROLL_DETAILS],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS17_SCROLL_DETAILS],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS18_SCROLL_DETAILS],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS19_SCROLL_DETAILS],

            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS00_SCROLL_DETAILS],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS01_SCROLL_DETAILS],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS02_SCROLL_DETAILS],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS03_SCROLL_DETAILS],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS04_SCROLL_DETAILS],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS05_SCROLL_DETAILS],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS06_SCROLL_DETAILS],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS07_SCROLL_DETAILS],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS08_SCROLL_DETAILS],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS09_SCROLL_DETAILS],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS10_SCROLL_DETAILS],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS11_SCROLL_DETAILS],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS12_SCROLL_DETAILS],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS13_SCROLL_DETAILS],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS14_SCROLL_DETAILS],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS15_SCROLL_DETAILS],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS16_SCROLL_DETAILS],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS17_SCROLL_DETAILS],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS18_SCROLL_DETAILS],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS19_SCROLL_DETAILS],
        );
        $simpleviews = array(
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS00_SCROLL_SIMPLEVIEW],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS01_SCROLL_SIMPLEVIEW],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS02_SCROLL_SIMPLEVIEW],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS03_SCROLL_SIMPLEVIEW],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS04_SCROLL_SIMPLEVIEW],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS05_SCROLL_SIMPLEVIEW],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS06_SCROLL_SIMPLEVIEW],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS07_SCROLL_SIMPLEVIEW],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS08_SCROLL_SIMPLEVIEW],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS09_SCROLL_SIMPLEVIEW],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS10_SCROLL_SIMPLEVIEW],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS11_SCROLL_SIMPLEVIEW],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS12_SCROLL_SIMPLEVIEW],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS13_SCROLL_SIMPLEVIEW],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS14_SCROLL_SIMPLEVIEW],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS15_SCROLL_SIMPLEVIEW],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS16_SCROLL_SIMPLEVIEW],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS17_SCROLL_SIMPLEVIEW],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS18_SCROLL_SIMPLEVIEW],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS19_SCROLL_SIMPLEVIEW],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS00_SCROLL_SIMPLEVIEW],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS01_SCROLL_SIMPLEVIEW],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS02_SCROLL_SIMPLEVIEW],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS03_SCROLL_SIMPLEVIEW],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS04_SCROLL_SIMPLEVIEW],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS05_SCROLL_SIMPLEVIEW],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS06_SCROLL_SIMPLEVIEW],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS07_SCROLL_SIMPLEVIEW],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS08_SCROLL_SIMPLEVIEW],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS09_SCROLL_SIMPLEVIEW],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS10_SCROLL_SIMPLEVIEW],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS11_SCROLL_SIMPLEVIEW],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS12_SCROLL_SIMPLEVIEW],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS13_SCROLL_SIMPLEVIEW],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS14_SCROLL_SIMPLEVIEW],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS15_SCROLL_SIMPLEVIEW],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS16_SCROLL_SIMPLEVIEW],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS17_SCROLL_SIMPLEVIEW],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS18_SCROLL_SIMPLEVIEW],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS19_SCROLL_SIMPLEVIEW],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS00_SCROLL_SIMPLEVIEW],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS01_SCROLL_SIMPLEVIEW],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS02_SCROLL_SIMPLEVIEW],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS03_SCROLL_SIMPLEVIEW],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS04_SCROLL_SIMPLEVIEW],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS05_SCROLL_SIMPLEVIEW],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS06_SCROLL_SIMPLEVIEW],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS07_SCROLL_SIMPLEVIEW],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS08_SCROLL_SIMPLEVIEW],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS09_SCROLL_SIMPLEVIEW],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS10_SCROLL_SIMPLEVIEW],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS11_SCROLL_SIMPLEVIEW],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS12_SCROLL_SIMPLEVIEW],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS13_SCROLL_SIMPLEVIEW],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS14_SCROLL_SIMPLEVIEW],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS15_SCROLL_SIMPLEVIEW],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS16_SCROLL_SIMPLEVIEW],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS17_SCROLL_SIMPLEVIEW],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS18_SCROLL_SIMPLEVIEW],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS19_SCROLL_SIMPLEVIEW],
        );
        $fullviews = array(
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS00_SCROLL_FULLVIEW],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS01_SCROLL_FULLVIEW],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS02_SCROLL_FULLVIEW],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS03_SCROLL_FULLVIEW],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS04_SCROLL_FULLVIEW],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS05_SCROLL_FULLVIEW],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS06_SCROLL_FULLVIEW],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS07_SCROLL_FULLVIEW],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS08_SCROLL_FULLVIEW],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS09_SCROLL_FULLVIEW],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS10_SCROLL_FULLVIEW],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS11_SCROLL_FULLVIEW],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS12_SCROLL_FULLVIEW],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS13_SCROLL_FULLVIEW],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS14_SCROLL_FULLVIEW],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS15_SCROLL_FULLVIEW],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS16_SCROLL_FULLVIEW],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS17_SCROLL_FULLVIEW],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS18_SCROLL_FULLVIEW],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS19_SCROLL_FULLVIEW],

            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS00_SCROLL_FULLVIEW],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS01_SCROLL_FULLVIEW],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS02_SCROLL_FULLVIEW],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS03_SCROLL_FULLVIEW],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS04_SCROLL_FULLVIEW],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS05_SCROLL_FULLVIEW],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS06_SCROLL_FULLVIEW],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS07_SCROLL_FULLVIEW],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS08_SCROLL_FULLVIEW],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS09_SCROLL_FULLVIEW],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS10_SCROLL_FULLVIEW],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS11_SCROLL_FULLVIEW],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS12_SCROLL_FULLVIEW],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS13_SCROLL_FULLVIEW],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS14_SCROLL_FULLVIEW],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS15_SCROLL_FULLVIEW],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS16_SCROLL_FULLVIEW],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS17_SCROLL_FULLVIEW],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS18_SCROLL_FULLVIEW],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS19_SCROLL_FULLVIEW],

            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS00_SCROLL_FULLVIEW],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS01_SCROLL_FULLVIEW],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS02_SCROLL_FULLVIEW],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS03_SCROLL_FULLVIEW],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS04_SCROLL_FULLVIEW],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS05_SCROLL_FULLVIEW],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS06_SCROLL_FULLVIEW],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS07_SCROLL_FULLVIEW],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS08_SCROLL_FULLVIEW],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS09_SCROLL_FULLVIEW],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS10_SCROLL_FULLVIEW],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS11_SCROLL_FULLVIEW],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS12_SCROLL_FULLVIEW],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS13_SCROLL_FULLVIEW],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS14_SCROLL_FULLVIEW],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS15_SCROLL_FULLVIEW],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS16_SCROLL_FULLVIEW],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS17_SCROLL_FULLVIEW],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS18_SCROLL_FULLVIEW],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS19_SCROLL_FULLVIEW],
        );
        $thumbnails = array(
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS00_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS01_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS02_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS03_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS04_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS05_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS06_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS07_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS08_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS09_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS10_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS11_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS12_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS13_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS14_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS15_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS16_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS17_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS18_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS19_SCROLL_THUMBNAIL],

            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS00_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS01_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS02_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS03_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS04_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS05_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS06_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS07_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS08_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS09_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS10_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS11_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS12_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS13_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS14_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS15_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS16_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS17_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS18_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS19_SCROLL_THUMBNAIL],

            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS00_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS01_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS02_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS03_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS04_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS05_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS06_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS07_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS08_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS09_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS10_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS11_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS12_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS13_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS14_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS15_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS16_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS17_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS18_SCROLL_THUMBNAIL],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS19_SCROLL_THUMBNAIL],
        );
        $lists = array(
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS00_SCROLL_LIST],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS01_SCROLL_LIST],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS02_SCROLL_LIST],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS03_SCROLL_LIST],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS04_SCROLL_LIST],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS05_SCROLL_LIST],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS06_SCROLL_LIST],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS07_SCROLL_LIST],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS08_SCROLL_LIST],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS09_SCROLL_LIST],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS10_SCROLL_LIST],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS11_SCROLL_LIST],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS12_SCROLL_LIST],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS13_SCROLL_LIST],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS14_SCROLL_LIST],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS15_SCROLL_LIST],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS16_SCROLL_LIST],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS17_SCROLL_LIST],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS18_SCROLL_LIST],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS19_SCROLL_LIST],

            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS00_SCROLL_LIST],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS01_SCROLL_LIST],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS02_SCROLL_LIST],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS03_SCROLL_LIST],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS04_SCROLL_LIST],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS05_SCROLL_LIST],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS06_SCROLL_LIST],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS07_SCROLL_LIST],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS08_SCROLL_LIST],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS09_SCROLL_LIST],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS10_SCROLL_LIST],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS11_SCROLL_LIST],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS12_SCROLL_LIST],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS13_SCROLL_LIST],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS14_SCROLL_LIST],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS15_SCROLL_LIST],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS16_SCROLL_LIST],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS17_SCROLL_LIST],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS18_SCROLL_LIST],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS19_SCROLL_LIST],

            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS00_SCROLL_LIST],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS01_SCROLL_LIST],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS02_SCROLL_LIST],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS03_SCROLL_LIST],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS04_SCROLL_LIST],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS05_SCROLL_LIST],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS06_SCROLL_LIST],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS07_SCROLL_LIST],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS08_SCROLL_LIST],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS09_SCROLL_LIST],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS10_SCROLL_LIST],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS11_SCROLL_LIST],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS12_SCROLL_LIST],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS13_SCROLL_LIST],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS14_SCROLL_LIST],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS15_SCROLL_LIST],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS16_SCROLL_LIST],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS17_SCROLL_LIST],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS18_SCROLL_LIST],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS19_SCROLL_LIST],
        );
        $lines = array(
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS00_SCROLL_LINE],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS01_SCROLL_LINE],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS02_SCROLL_LINE],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS03_SCROLL_LINE],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS04_SCROLL_LINE],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS05_SCROLL_LINE],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS06_SCROLL_LINE],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS07_SCROLL_LINE],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS08_SCROLL_LINE],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS09_SCROLL_LINE],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS10_SCROLL_LINE],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS11_SCROLL_LINE],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS12_SCROLL_LINE],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS13_SCROLL_LINE],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS14_SCROLL_LINE],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS15_SCROLL_LINE],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS16_SCROLL_LINE],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS17_SCROLL_LINE],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS18_SCROLL_LINE],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS19_SCROLL_LINE],

            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS00_SCROLL_LINE],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS01_SCROLL_LINE],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS02_SCROLL_LINE],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS03_SCROLL_LINE],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS04_SCROLL_LINE],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS05_SCROLL_LINE],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS06_SCROLL_LINE],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS07_SCROLL_LINE],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS08_SCROLL_LINE],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS09_SCROLL_LINE],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS10_SCROLL_LINE],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS11_SCROLL_LINE],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS12_SCROLL_LINE],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS13_SCROLL_LINE],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS14_SCROLL_LINE],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS15_SCROLL_LINE],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS16_SCROLL_LINE],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS17_SCROLL_LINE],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS18_SCROLL_LINE],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS19_SCROLL_LINE],

            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS00_SCROLL_LINE],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS01_SCROLL_LINE],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS02_SCROLL_LINE],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS03_SCROLL_LINE],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS04_SCROLL_LINE],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS05_SCROLL_LINE],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS06_SCROLL_LINE],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS07_SCROLL_LINE],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS08_SCROLL_LINE],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS09_SCROLL_LINE],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS10_SCROLL_LINE],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS11_SCROLL_LINE],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS12_SCROLL_LINE],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS13_SCROLL_LINE],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS14_SCROLL_LINE],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS15_SCROLL_LINE],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS16_SCROLL_LINE],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS17_SCROLL_LINE],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS18_SCROLL_LINE],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS19_SCROLL_LINE],
        );
        $typeaheads = array(
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS00_TYPEAHEAD],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS01_TYPEAHEAD],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS02_TYPEAHEAD],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS03_TYPEAHEAD],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS04_TYPEAHEAD],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS05_TYPEAHEAD],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS06_TYPEAHEAD],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS07_TYPEAHEAD],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS08_TYPEAHEAD],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS09_TYPEAHEAD],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS10_TYPEAHEAD],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS11_TYPEAHEAD],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS12_TYPEAHEAD],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS13_TYPEAHEAD],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS14_TYPEAHEAD],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS15_TYPEAHEAD],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS16_TYPEAHEAD],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS17_TYPEAHEAD],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS18_TYPEAHEAD],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS19_TYPEAHEAD],
        );
        $carousels = array(
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS00_CAROUSEL],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS01_CAROUSEL],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS02_CAROUSEL],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS03_CAROUSEL],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS04_CAROUSEL],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS05_CAROUSEL],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS06_CAROUSEL],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS07_CAROUSEL],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS08_CAROUSEL],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS09_CAROUSEL],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS10_CAROUSEL],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS11_CAROUSEL],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS12_CAROUSEL],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS13_CAROUSEL],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS14_CAROUSEL],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS15_CAROUSEL],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS16_CAROUSEL],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS17_CAROUSEL],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS18_CAROUSEL],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS19_CAROUSEL],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS00_CAROUSEL],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS01_CAROUSEL],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS02_CAROUSEL],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS03_CAROUSEL],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS04_CAROUSEL],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS05_CAROUSEL],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS06_CAROUSEL],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS07_CAROUSEL],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS08_CAROUSEL],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS09_CAROUSEL],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS10_CAROUSEL],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS11_CAROUSEL],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS12_CAROUSEL],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS13_CAROUSEL],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS14_CAROUSEL],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS15_CAROUSEL],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS16_CAROUSEL],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS17_CAROUSEL],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS18_CAROUSEL],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS19_CAROUSEL],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS00_CAROUSEL],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS01_CAROUSEL],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS02_CAROUSEL],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS03_CAROUSEL],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS04_CAROUSEL],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS05_CAROUSEL],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS06_CAROUSEL],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS07_CAROUSEL],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS08_CAROUSEL],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS09_CAROUSEL],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS10_CAROUSEL],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS11_CAROUSEL],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS12_CAROUSEL],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS13_CAROUSEL],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS14_CAROUSEL],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS15_CAROUSEL],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS16_CAROUSEL],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS17_CAROUSEL],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS18_CAROUSEL],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS19_CAROUSEL],
        );
        $content_carousels = array(
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS00_CAROUSEL_CONTENT],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS01_CAROUSEL_CONTENT],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS02_CAROUSEL_CONTENT],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS03_CAROUSEL_CONTENT],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS04_CAROUSEL_CONTENT],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS05_CAROUSEL_CONTENT],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS06_CAROUSEL_CONTENT],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS07_CAROUSEL_CONTENT],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS08_CAROUSEL_CONTENT],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS09_CAROUSEL_CONTENT],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS10_CAROUSEL_CONTENT],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS11_CAROUSEL_CONTENT],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS12_CAROUSEL_CONTENT],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS13_CAROUSEL_CONTENT],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS14_CAROUSEL_CONTENT],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS15_CAROUSEL_CONTENT],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS16_CAROUSEL_CONTENT],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS17_CAROUSEL_CONTENT],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS18_CAROUSEL_CONTENT],
            [self::class, self::MODULE_DATALOAD_CATEGORYPOSTS19_CAROUSEL_CONTENT],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS00_CAROUSEL_CONTENT],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS01_CAROUSEL_CONTENT],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS02_CAROUSEL_CONTENT],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS03_CAROUSEL_CONTENT],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS04_CAROUSEL_CONTENT],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS05_CAROUSEL_CONTENT],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS06_CAROUSEL_CONTENT],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS07_CAROUSEL_CONTENT],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS08_CAROUSEL_CONTENT],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS09_CAROUSEL_CONTENT],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS10_CAROUSEL_CONTENT],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS11_CAROUSEL_CONTENT],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS12_CAROUSEL_CONTENT],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS13_CAROUSEL_CONTENT],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS14_CAROUSEL_CONTENT],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS15_CAROUSEL_CONTENT],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS16_CAROUSEL_CONTENT],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS17_CAROUSEL_CONTENT],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS18_CAROUSEL_CONTENT],
            [self::class, self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS19_CAROUSEL_CONTENT],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS00_CAROUSEL_CONTENT],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS01_CAROUSEL_CONTENT],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS02_CAROUSEL_CONTENT],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS03_CAROUSEL_CONTENT],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS04_CAROUSEL_CONTENT],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS05_CAROUSEL_CONTENT],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS06_CAROUSEL_CONTENT],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS07_CAROUSEL_CONTENT],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS08_CAROUSEL_CONTENT],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS09_CAROUSEL_CONTENT],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS10_CAROUSEL_CONTENT],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS11_CAROUSEL_CONTENT],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS12_CAROUSEL_CONTENT],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS13_CAROUSEL_CONTENT],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS14_CAROUSEL_CONTENT],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS15_CAROUSEL_CONTENT],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS16_CAROUSEL_CONTENT],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS17_CAROUSEL_CONTENT],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS18_CAROUSEL_CONTENT],
            [self::class, self::MODULE_DATALOAD_TAGCATEGORYPOSTS19_CAROUSEL_CONTENT],
        );
        if (in_array($componentVariation, $details)) {
            $format = POP_FORMAT_DETAILS;
        } elseif (in_array($componentVariation, $simpleviews)) {
            $format = POP_FORMAT_SIMPLEVIEW;
        } elseif (in_array($componentVariation, $fullviews)) {
            $format = POP_FORMAT_FULLVIEW;
        } elseif (in_array($componentVariation, $thumbnails)) {
            $format = POP_FORMAT_THUMBNAIL;
        } elseif (in_array($componentVariation, $lists)) {
            $format = POP_FORMAT_LIST;
        } elseif (in_array($componentVariation, $lines)) {
            $format = POP_FORMAT_LINE;
        } elseif (in_array($componentVariation, $carousels)) {
            $format = POP_FORMAT_CAROUSEL;
        } elseif (in_array($componentVariation, $content_carousels)) {
            $format = POP_FORMAT_CAROUSELCONTENT;
        } elseif (in_array($componentVariation, $typeaheads)) {
            $format = POP_FORMAT_TYPEAHEAD;
        }

        return $format ?? parent::getFormat($componentVariation);
    }

    // public function getNature(array $componentVariation)
    // {
    //     switch ($componentVariation[1]) {
    //         case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS00_SCROLL_DETAILS:
    //         case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS01_SCROLL_DETAILS:
    //         case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS02_SCROLL_DETAILS:
    //         case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS03_SCROLL_DETAILS:
    //         case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS04_SCROLL_DETAILS:
    //         case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS05_SCROLL_DETAILS:
    //         case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS06_SCROLL_DETAILS:
    //         case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS07_SCROLL_DETAILS:
    //         case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS08_SCROLL_DETAILS:
    //         case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS09_SCROLL_DETAILS:
    //         case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS10_SCROLL_DETAILS:
    //         case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS11_SCROLL_DETAILS:
    //         case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS12_SCROLL_DETAILS:
    //         case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS13_SCROLL_DETAILS:
    //         case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS14_SCROLL_DETAILS:
    //         case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS15_SCROLL_DETAILS:
    //         case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS16_SCROLL_DETAILS:
    //         case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS17_SCROLL_DETAILS:
    //         case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS18_SCROLL_DETAILS:
    //         case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS19_SCROLL_DETAILS:
    //         case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS00_SCROLL_SIMPLEVIEW:
    //         case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS01_SCROLL_SIMPLEVIEW:
    //         case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS02_SCROLL_SIMPLEVIEW:
    //         case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS03_SCROLL_SIMPLEVIEW:
    //         case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS04_SCROLL_SIMPLEVIEW:
    //         case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS05_SCROLL_SIMPLEVIEW:
    //         case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS06_SCROLL_SIMPLEVIEW:
    //         case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS07_SCROLL_SIMPLEVIEW:
    //         case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS08_SCROLL_SIMPLEVIEW:
    //         case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS09_SCROLL_SIMPLEVIEW:
    //         case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS10_SCROLL_SIMPLEVIEW:
    //         case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS11_SCROLL_SIMPLEVIEW:
    //         case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS12_SCROLL_SIMPLEVIEW:
    //         case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS13_SCROLL_SIMPLEVIEW:
    //         case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS14_SCROLL_SIMPLEVIEW:
    //         case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS15_SCROLL_SIMPLEVIEW:
    //         case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS16_SCROLL_SIMPLEVIEW:
    //         case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS17_SCROLL_SIMPLEVIEW:
    //         case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS18_SCROLL_SIMPLEVIEW:
    //         case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS19_SCROLL_SIMPLEVIEW:
    //         case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS00_SCROLL_FULLVIEW:
    //         case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS01_SCROLL_FULLVIEW:
    //         case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS02_SCROLL_FULLVIEW:
    //         case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS03_SCROLL_FULLVIEW:
    //         case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS04_SCROLL_FULLVIEW:
    //         case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS05_SCROLL_FULLVIEW:
    //         case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS06_SCROLL_FULLVIEW:
    //         case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS07_SCROLL_FULLVIEW:
    //         case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS08_SCROLL_FULLVIEW:
    //         case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS09_SCROLL_FULLVIEW:
    //         case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS10_SCROLL_FULLVIEW:
    //         case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS11_SCROLL_FULLVIEW:
    //         case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS12_SCROLL_FULLVIEW:
    //         case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS13_SCROLL_FULLVIEW:
    //         case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS14_SCROLL_FULLVIEW:
    //         case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS15_SCROLL_FULLVIEW:
    //         case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS16_SCROLL_FULLVIEW:
    //         case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS17_SCROLL_FULLVIEW:
    //         case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS18_SCROLL_FULLVIEW:
    //         case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS19_SCROLL_FULLVIEW:
    //         case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS00_SCROLL_THUMBNAIL:
    //         case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS01_SCROLL_THUMBNAIL:
    //         case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS02_SCROLL_THUMBNAIL:
    //         case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS03_SCROLL_THUMBNAIL:
    //         case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS04_SCROLL_THUMBNAIL:
    //         case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS05_SCROLL_THUMBNAIL:
    //         case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS06_SCROLL_THUMBNAIL:
    //         case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS07_SCROLL_THUMBNAIL:
    //         case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS08_SCROLL_THUMBNAIL:
    //         case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS09_SCROLL_THUMBNAIL:
    //         case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS10_SCROLL_THUMBNAIL:
    //         case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS11_SCROLL_THUMBNAIL:
    //         case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS12_SCROLL_THUMBNAIL:
    //         case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS13_SCROLL_THUMBNAIL:
    //         case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS14_SCROLL_THUMBNAIL:
    //         case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS15_SCROLL_THUMBNAIL:
    //         case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS16_SCROLL_THUMBNAIL:
    //         case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS17_SCROLL_THUMBNAIL:
    //         case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS18_SCROLL_THUMBNAIL:
    //         case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS19_SCROLL_THUMBNAIL:
    //         case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS00_SCROLL_LIST:
    //         case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS01_SCROLL_LIST:
    //         case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS02_SCROLL_LIST:
    //         case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS03_SCROLL_LIST:
    //         case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS04_SCROLL_LIST:
    //         case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS05_SCROLL_LIST:
    //         case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS06_SCROLL_LIST:
    //         case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS07_SCROLL_LIST:
    //         case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS08_SCROLL_LIST:
    //         case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS09_SCROLL_LIST:
    //         case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS10_SCROLL_LIST:
    //         case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS11_SCROLL_LIST:
    //         case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS12_SCROLL_LIST:
    //         case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS13_SCROLL_LIST:
    //         case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS14_SCROLL_LIST:
    //         case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS15_SCROLL_LIST:
    //         case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS16_SCROLL_LIST:
    //         case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS17_SCROLL_LIST:
    //         case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS18_SCROLL_LIST:
    //         case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS19_SCROLL_LIST:
    //         case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS00_SCROLL_LINE:
    //         case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS01_SCROLL_LINE:
    //         case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS02_SCROLL_LINE:
    //         case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS03_SCROLL_LINE:
    //         case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS04_SCROLL_LINE:
    //         case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS05_SCROLL_LINE:
    //         case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS06_SCROLL_LINE:
    //         case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS07_SCROLL_LINE:
    //         case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS08_SCROLL_LINE:
    //         case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS09_SCROLL_LINE:
    //         case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS10_SCROLL_LINE:
    //         case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS11_SCROLL_LINE:
    //         case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS12_SCROLL_LINE:
    //         case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS13_SCROLL_LINE:
    //         case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS14_SCROLL_LINE:
    //         case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS15_SCROLL_LINE:
    //         case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS16_SCROLL_LINE:
    //         case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS17_SCROLL_LINE:
    //         case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS18_SCROLL_LINE:
    //         case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS19_SCROLL_LINE:
    //         case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS00_CAROUSEL:
    //         case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS01_CAROUSEL:
    //         case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS02_CAROUSEL:
    //         case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS03_CAROUSEL:
    //         case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS04_CAROUSEL:
    //         case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS05_CAROUSEL:
    //         case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS06_CAROUSEL:
    //         case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS07_CAROUSEL:
    //         case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS08_CAROUSEL:
    //         case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS09_CAROUSEL:
    //         case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS10_CAROUSEL:
    //         case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS11_CAROUSEL:
    //         case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS12_CAROUSEL:
    //         case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS13_CAROUSEL:
    //         case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS14_CAROUSEL:
    //         case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS15_CAROUSEL:
    //         case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS16_CAROUSEL:
    //         case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS17_CAROUSEL:
    //         case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS18_CAROUSEL:
    //         case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS19_CAROUSEL:
    //         case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS00_CAROUSEL_CONTENT:
    //         case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS01_CAROUSEL_CONTENT:
    //         case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS02_CAROUSEL_CONTENT:
    //         case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS03_CAROUSEL_CONTENT:
    //         case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS04_CAROUSEL_CONTENT:
    //         case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS05_CAROUSEL_CONTENT:
    //         case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS06_CAROUSEL_CONTENT:
    //         case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS07_CAROUSEL_CONTENT:
    //         case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS08_CAROUSEL_CONTENT:
    //         case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS09_CAROUSEL_CONTENT:
    //         case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS10_CAROUSEL_CONTENT:
    //         case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS11_CAROUSEL_CONTENT:
    //         case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS12_CAROUSEL_CONTENT:
    //         case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS13_CAROUSEL_CONTENT:
    //         case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS14_CAROUSEL_CONTENT:
    //         case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS15_CAROUSEL_CONTENT:
    //         case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS16_CAROUSEL_CONTENT:
    //         case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS17_CAROUSEL_CONTENT:
    //         case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS18_CAROUSEL_CONTENT:
    //         case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS19_CAROUSEL_CONTENT:
    //             return UserRequestNature::USER;

    //         case self::MODULE_DATALOAD_TAGCATEGORYPOSTS00_SCROLL_DETAILS:
    //         case self::MODULE_DATALOAD_TAGCATEGORYPOSTS01_SCROLL_DETAILS:
    //         case self::MODULE_DATALOAD_TAGCATEGORYPOSTS02_SCROLL_DETAILS:
    //         case self::MODULE_DATALOAD_TAGCATEGORYPOSTS03_SCROLL_DETAILS:
    //         case self::MODULE_DATALOAD_TAGCATEGORYPOSTS04_SCROLL_DETAILS:
    //         case self::MODULE_DATALOAD_TAGCATEGORYPOSTS05_SCROLL_DETAILS:
    //         case self::MODULE_DATALOAD_TAGCATEGORYPOSTS06_SCROLL_DETAILS:
    //         case self::MODULE_DATALOAD_TAGCATEGORYPOSTS07_SCROLL_DETAILS:
    //         case self::MODULE_DATALOAD_TAGCATEGORYPOSTS08_SCROLL_DETAILS:
    //         case self::MODULE_DATALOAD_TAGCATEGORYPOSTS09_SCROLL_DETAILS:
    //         case self::MODULE_DATALOAD_TAGCATEGORYPOSTS10_SCROLL_DETAILS:
    //         case self::MODULE_DATALOAD_TAGCATEGORYPOSTS11_SCROLL_DETAILS:
    //         case self::MODULE_DATALOAD_TAGCATEGORYPOSTS12_SCROLL_DETAILS:
    //         case self::MODULE_DATALOAD_TAGCATEGORYPOSTS13_SCROLL_DETAILS:
    //         case self::MODULE_DATALOAD_TAGCATEGORYPOSTS14_SCROLL_DETAILS:
    //         case self::MODULE_DATALOAD_TAGCATEGORYPOSTS15_SCROLL_DETAILS:
    //         case self::MODULE_DATALOAD_TAGCATEGORYPOSTS16_SCROLL_DETAILS:
    //         case self::MODULE_DATALOAD_TAGCATEGORYPOSTS17_SCROLL_DETAILS:
    //         case self::MODULE_DATALOAD_TAGCATEGORYPOSTS18_SCROLL_DETAILS:
    //         case self::MODULE_DATALOAD_TAGCATEGORYPOSTS19_SCROLL_DETAILS:
    //         case self::MODULE_DATALOAD_TAGCATEGORYPOSTS00_SCROLL_SIMPLEVIEW:
    //         case self::MODULE_DATALOAD_TAGCATEGORYPOSTS01_SCROLL_SIMPLEVIEW:
    //         case self::MODULE_DATALOAD_TAGCATEGORYPOSTS02_SCROLL_SIMPLEVIEW:
    //         case self::MODULE_DATALOAD_TAGCATEGORYPOSTS03_SCROLL_SIMPLEVIEW:
    //         case self::MODULE_DATALOAD_TAGCATEGORYPOSTS04_SCROLL_SIMPLEVIEW:
    //         case self::MODULE_DATALOAD_TAGCATEGORYPOSTS05_SCROLL_SIMPLEVIEW:
    //         case self::MODULE_DATALOAD_TAGCATEGORYPOSTS06_SCROLL_SIMPLEVIEW:
    //         case self::MODULE_DATALOAD_TAGCATEGORYPOSTS07_SCROLL_SIMPLEVIEW:
    //         case self::MODULE_DATALOAD_TAGCATEGORYPOSTS08_SCROLL_SIMPLEVIEW:
    //         case self::MODULE_DATALOAD_TAGCATEGORYPOSTS09_SCROLL_SIMPLEVIEW:
    //         case self::MODULE_DATALOAD_TAGCATEGORYPOSTS10_SCROLL_SIMPLEVIEW:
    //         case self::MODULE_DATALOAD_TAGCATEGORYPOSTS11_SCROLL_SIMPLEVIEW:
    //         case self::MODULE_DATALOAD_TAGCATEGORYPOSTS12_SCROLL_SIMPLEVIEW:
    //         case self::MODULE_DATALOAD_TAGCATEGORYPOSTS13_SCROLL_SIMPLEVIEW:
    //         case self::MODULE_DATALOAD_TAGCATEGORYPOSTS14_SCROLL_SIMPLEVIEW:
    //         case self::MODULE_DATALOAD_TAGCATEGORYPOSTS15_SCROLL_SIMPLEVIEW:
    //         case self::MODULE_DATALOAD_TAGCATEGORYPOSTS16_SCROLL_SIMPLEVIEW:
    //         case self::MODULE_DATALOAD_TAGCATEGORYPOSTS17_SCROLL_SIMPLEVIEW:
    //         case self::MODULE_DATALOAD_TAGCATEGORYPOSTS18_SCROLL_SIMPLEVIEW:
    //         case self::MODULE_DATALOAD_TAGCATEGORYPOSTS19_SCROLL_SIMPLEVIEW:
    //         case self::MODULE_DATALOAD_TAGCATEGORYPOSTS00_SCROLL_FULLVIEW:
    //         case self::MODULE_DATALOAD_TAGCATEGORYPOSTS01_SCROLL_FULLVIEW:
    //         case self::MODULE_DATALOAD_TAGCATEGORYPOSTS02_SCROLL_FULLVIEW:
    //         case self::MODULE_DATALOAD_TAGCATEGORYPOSTS03_SCROLL_FULLVIEW:
    //         case self::MODULE_DATALOAD_TAGCATEGORYPOSTS04_SCROLL_FULLVIEW:
    //         case self::MODULE_DATALOAD_TAGCATEGORYPOSTS05_SCROLL_FULLVIEW:
    //         case self::MODULE_DATALOAD_TAGCATEGORYPOSTS06_SCROLL_FULLVIEW:
    //         case self::MODULE_DATALOAD_TAGCATEGORYPOSTS07_SCROLL_FULLVIEW:
    //         case self::MODULE_DATALOAD_TAGCATEGORYPOSTS08_SCROLL_FULLVIEW:
    //         case self::MODULE_DATALOAD_TAGCATEGORYPOSTS09_SCROLL_FULLVIEW:
    //         case self::MODULE_DATALOAD_TAGCATEGORYPOSTS10_SCROLL_FULLVIEW:
    //         case self::MODULE_DATALOAD_TAGCATEGORYPOSTS11_SCROLL_FULLVIEW:
    //         case self::MODULE_DATALOAD_TAGCATEGORYPOSTS12_SCROLL_FULLVIEW:
    //         case self::MODULE_DATALOAD_TAGCATEGORYPOSTS13_SCROLL_FULLVIEW:
    //         case self::MODULE_DATALOAD_TAGCATEGORYPOSTS14_SCROLL_FULLVIEW:
    //         case self::MODULE_DATALOAD_TAGCATEGORYPOSTS15_SCROLL_FULLVIEW:
    //         case self::MODULE_DATALOAD_TAGCATEGORYPOSTS16_SCROLL_FULLVIEW:
    //         case self::MODULE_DATALOAD_TAGCATEGORYPOSTS17_SCROLL_FULLVIEW:
    //         case self::MODULE_DATALOAD_TAGCATEGORYPOSTS18_SCROLL_FULLVIEW:
    //         case self::MODULE_DATALOAD_TAGCATEGORYPOSTS19_SCROLL_FULLVIEW:
    //         case self::MODULE_DATALOAD_TAGCATEGORYPOSTS00_SCROLL_THUMBNAIL:
    //         case self::MODULE_DATALOAD_TAGCATEGORYPOSTS01_SCROLL_THUMBNAIL:
    //         case self::MODULE_DATALOAD_TAGCATEGORYPOSTS02_SCROLL_THUMBNAIL:
    //         case self::MODULE_DATALOAD_TAGCATEGORYPOSTS03_SCROLL_THUMBNAIL:
    //         case self::MODULE_DATALOAD_TAGCATEGORYPOSTS04_SCROLL_THUMBNAIL:
    //         case self::MODULE_DATALOAD_TAGCATEGORYPOSTS05_SCROLL_THUMBNAIL:
    //         case self::MODULE_DATALOAD_TAGCATEGORYPOSTS06_SCROLL_THUMBNAIL:
    //         case self::MODULE_DATALOAD_TAGCATEGORYPOSTS07_SCROLL_THUMBNAIL:
    //         case self::MODULE_DATALOAD_TAGCATEGORYPOSTS08_SCROLL_THUMBNAIL:
    //         case self::MODULE_DATALOAD_TAGCATEGORYPOSTS09_SCROLL_THUMBNAIL:
    //         case self::MODULE_DATALOAD_TAGCATEGORYPOSTS10_SCROLL_THUMBNAIL:
    //         case self::MODULE_DATALOAD_TAGCATEGORYPOSTS11_SCROLL_THUMBNAIL:
    //         case self::MODULE_DATALOAD_TAGCATEGORYPOSTS12_SCROLL_THUMBNAIL:
    //         case self::MODULE_DATALOAD_TAGCATEGORYPOSTS13_SCROLL_THUMBNAIL:
    //         case self::MODULE_DATALOAD_TAGCATEGORYPOSTS14_SCROLL_THUMBNAIL:
    //         case self::MODULE_DATALOAD_TAGCATEGORYPOSTS15_SCROLL_THUMBNAIL:
    //         case self::MODULE_DATALOAD_TAGCATEGORYPOSTS16_SCROLL_THUMBNAIL:
    //         case self::MODULE_DATALOAD_TAGCATEGORYPOSTS17_SCROLL_THUMBNAIL:
    //         case self::MODULE_DATALOAD_TAGCATEGORYPOSTS18_SCROLL_THUMBNAIL:
    //         case self::MODULE_DATALOAD_TAGCATEGORYPOSTS19_SCROLL_THUMBNAIL:
    //         case self::MODULE_DATALOAD_TAGCATEGORYPOSTS00_SCROLL_LIST:
    //         case self::MODULE_DATALOAD_TAGCATEGORYPOSTS01_SCROLL_LIST:
    //         case self::MODULE_DATALOAD_TAGCATEGORYPOSTS02_SCROLL_LIST:
    //         case self::MODULE_DATALOAD_TAGCATEGORYPOSTS03_SCROLL_LIST:
    //         case self::MODULE_DATALOAD_TAGCATEGORYPOSTS04_SCROLL_LIST:
    //         case self::MODULE_DATALOAD_TAGCATEGORYPOSTS05_SCROLL_LIST:
    //         case self::MODULE_DATALOAD_TAGCATEGORYPOSTS06_SCROLL_LIST:
    //         case self::MODULE_DATALOAD_TAGCATEGORYPOSTS07_SCROLL_LIST:
    //         case self::MODULE_DATALOAD_TAGCATEGORYPOSTS08_SCROLL_LIST:
    //         case self::MODULE_DATALOAD_TAGCATEGORYPOSTS09_SCROLL_LIST:
    //         case self::MODULE_DATALOAD_TAGCATEGORYPOSTS10_SCROLL_LIST:
    //         case self::MODULE_DATALOAD_TAGCATEGORYPOSTS11_SCROLL_LIST:
    //         case self::MODULE_DATALOAD_TAGCATEGORYPOSTS12_SCROLL_LIST:
    //         case self::MODULE_DATALOAD_TAGCATEGORYPOSTS13_SCROLL_LIST:
    //         case self::MODULE_DATALOAD_TAGCATEGORYPOSTS14_SCROLL_LIST:
    //         case self::MODULE_DATALOAD_TAGCATEGORYPOSTS15_SCROLL_LIST:
    //         case self::MODULE_DATALOAD_TAGCATEGORYPOSTS16_SCROLL_LIST:
    //         case self::MODULE_DATALOAD_TAGCATEGORYPOSTS17_SCROLL_LIST:
    //         case self::MODULE_DATALOAD_TAGCATEGORYPOSTS18_SCROLL_LIST:
    //         case self::MODULE_DATALOAD_TAGCATEGORYPOSTS19_SCROLL_LIST:
    //         case self::MODULE_DATALOAD_TAGCATEGORYPOSTS00_SCROLL_LINE:
    //         case self::MODULE_DATALOAD_TAGCATEGORYPOSTS01_SCROLL_LINE:
    //         case self::MODULE_DATALOAD_TAGCATEGORYPOSTS02_SCROLL_LINE:
    //         case self::MODULE_DATALOAD_TAGCATEGORYPOSTS03_SCROLL_LINE:
    //         case self::MODULE_DATALOAD_TAGCATEGORYPOSTS04_SCROLL_LINE:
    //         case self::MODULE_DATALOAD_TAGCATEGORYPOSTS05_SCROLL_LINE:
    //         case self::MODULE_DATALOAD_TAGCATEGORYPOSTS06_SCROLL_LINE:
    //         case self::MODULE_DATALOAD_TAGCATEGORYPOSTS07_SCROLL_LINE:
    //         case self::MODULE_DATALOAD_TAGCATEGORYPOSTS08_SCROLL_LINE:
    //         case self::MODULE_DATALOAD_TAGCATEGORYPOSTS09_SCROLL_LINE:
    //         case self::MODULE_DATALOAD_TAGCATEGORYPOSTS10_SCROLL_LINE:
    //         case self::MODULE_DATALOAD_TAGCATEGORYPOSTS11_SCROLL_LINE:
    //         case self::MODULE_DATALOAD_TAGCATEGORYPOSTS12_SCROLL_LINE:
    //         case self::MODULE_DATALOAD_TAGCATEGORYPOSTS13_SCROLL_LINE:
    //         case self::MODULE_DATALOAD_TAGCATEGORYPOSTS14_SCROLL_LINE:
    //         case self::MODULE_DATALOAD_TAGCATEGORYPOSTS15_SCROLL_LINE:
    //         case self::MODULE_DATALOAD_TAGCATEGORYPOSTS16_SCROLL_LINE:
    //         case self::MODULE_DATALOAD_TAGCATEGORYPOSTS17_SCROLL_LINE:
    //         case self::MODULE_DATALOAD_TAGCATEGORYPOSTS18_SCROLL_LINE:
    //         case self::MODULE_DATALOAD_TAGCATEGORYPOSTS19_SCROLL_LINE:
    //         case self::MODULE_DATALOAD_TAGCATEGORYPOSTS00_CAROUSEL:
    //         case self::MODULE_DATALOAD_TAGCATEGORYPOSTS01_CAROUSEL:
    //         case self::MODULE_DATALOAD_TAGCATEGORYPOSTS02_CAROUSEL:
    //         case self::MODULE_DATALOAD_TAGCATEGORYPOSTS03_CAROUSEL:
    //         case self::MODULE_DATALOAD_TAGCATEGORYPOSTS04_CAROUSEL:
    //         case self::MODULE_DATALOAD_TAGCATEGORYPOSTS05_CAROUSEL:
    //         case self::MODULE_DATALOAD_TAGCATEGORYPOSTS06_CAROUSEL:
    //         case self::MODULE_DATALOAD_TAGCATEGORYPOSTS07_CAROUSEL:
    //         case self::MODULE_DATALOAD_TAGCATEGORYPOSTS08_CAROUSEL:
    //         case self::MODULE_DATALOAD_TAGCATEGORYPOSTS09_CAROUSEL:
    //         case self::MODULE_DATALOAD_TAGCATEGORYPOSTS10_CAROUSEL:
    //         case self::MODULE_DATALOAD_TAGCATEGORYPOSTS11_CAROUSEL:
    //         case self::MODULE_DATALOAD_TAGCATEGORYPOSTS12_CAROUSEL:
    //         case self::MODULE_DATALOAD_TAGCATEGORYPOSTS13_CAROUSEL:
    //         case self::MODULE_DATALOAD_TAGCATEGORYPOSTS14_CAROUSEL:
    //         case self::MODULE_DATALOAD_TAGCATEGORYPOSTS15_CAROUSEL:
    //         case self::MODULE_DATALOAD_TAGCATEGORYPOSTS16_CAROUSEL:
    //         case self::MODULE_DATALOAD_TAGCATEGORYPOSTS17_CAROUSEL:
    //         case self::MODULE_DATALOAD_TAGCATEGORYPOSTS18_CAROUSEL:
    //         case self::MODULE_DATALOAD_TAGCATEGORYPOSTS19_CAROUSEL:
    //         case self::MODULE_DATALOAD_TAGCATEGORYPOSTS00_CAROUSEL_CONTENT:
    //         case self::MODULE_DATALOAD_TAGCATEGORYPOSTS01_CAROUSEL_CONTENT:
    //         case self::MODULE_DATALOAD_TAGCATEGORYPOSTS02_CAROUSEL_CONTENT:
    //         case self::MODULE_DATALOAD_TAGCATEGORYPOSTS03_CAROUSEL_CONTENT:
    //         case self::MODULE_DATALOAD_TAGCATEGORYPOSTS04_CAROUSEL_CONTENT:
    //         case self::MODULE_DATALOAD_TAGCATEGORYPOSTS05_CAROUSEL_CONTENT:
    //         case self::MODULE_DATALOAD_TAGCATEGORYPOSTS06_CAROUSEL_CONTENT:
    //         case self::MODULE_DATALOAD_TAGCATEGORYPOSTS07_CAROUSEL_CONTENT:
    //         case self::MODULE_DATALOAD_TAGCATEGORYPOSTS08_CAROUSEL_CONTENT:
    //         case self::MODULE_DATALOAD_TAGCATEGORYPOSTS09_CAROUSEL_CONTENT:
    //         case self::MODULE_DATALOAD_TAGCATEGORYPOSTS10_CAROUSEL_CONTENT:
    //         case self::MODULE_DATALOAD_TAGCATEGORYPOSTS11_CAROUSEL_CONTENT:
    //         case self::MODULE_DATALOAD_TAGCATEGORYPOSTS12_CAROUSEL_CONTENT:
    //         case self::MODULE_DATALOAD_TAGCATEGORYPOSTS13_CAROUSEL_CONTENT:
    //         case self::MODULE_DATALOAD_TAGCATEGORYPOSTS14_CAROUSEL_CONTENT:
    //         case self::MODULE_DATALOAD_TAGCATEGORYPOSTS15_CAROUSEL_CONTENT:
    //         case self::MODULE_DATALOAD_TAGCATEGORYPOSTS16_CAROUSEL_CONTENT:
    //         case self::MODULE_DATALOAD_TAGCATEGORYPOSTS17_CAROUSEL_CONTENT:
    //         case self::MODULE_DATALOAD_TAGCATEGORYPOSTS18_CAROUSEL_CONTENT:
    //         case self::MODULE_DATALOAD_TAGCATEGORYPOSTS19_CAROUSEL_CONTENT:
    //             return TagRequestNature::TAG;
    //     }

    //     return parent::getNature($componentVariation);
    // }


    protected function getImmutableDataloadQueryArgs(array $componentVariation, array &$props): array
    {
        $ret = parent::getImmutableDataloadQueryArgs($componentVariation, $props);

        switch ($componentVariation[1]) {
            case self::MODULE_DATALOAD_CATEGORYPOSTS00_TYPEAHEAD:
            case self::MODULE_DATALOAD_CATEGORYPOSTS00_SCROLL_NAVIGATOR:
            case self::MODULE_DATALOAD_CATEGORYPOSTS00_SCROLL_ADDONS:
            case self::MODULE_DATALOAD_CATEGORYPOSTS00_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_CATEGORYPOSTS00_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_CATEGORYPOSTS00_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_CATEGORYPOSTS00_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_CATEGORYPOSTS00_SCROLL_LIST:
            case self::MODULE_DATALOAD_CATEGORYPOSTS00_SCROLL_LINE:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS00_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS00_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS00_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS00_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS00_SCROLL_LIST:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS00_SCROLL_LINE:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS00_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS00_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS00_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS00_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS00_SCROLL_LIST:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS00_SCROLL_LINE:
            case self::MODULE_DATALOAD_CATEGORYPOSTS00_CAROUSEL:
            case self::MODULE_DATALOAD_CATEGORYPOSTS00_CAROUSEL_CONTENT:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS00_CAROUSEL:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS00_CAROUSEL_CONTENT:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS00_CAROUSEL:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS00_CAROUSEL_CONTENT:
                $ret['categories'] = [POP_CATEGORYPOSTS_CAT_CATEGORYPOSTS00];
                break;

            case self::MODULE_DATALOAD_CATEGORYPOSTS10_TYPEAHEAD:
            case self::MODULE_DATALOAD_CATEGORYPOSTS10_SCROLL_NAVIGATOR:
            case self::MODULE_DATALOAD_CATEGORYPOSTS10_SCROLL_ADDONS:
            case self::MODULE_DATALOAD_CATEGORYPOSTS10_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_CATEGORYPOSTS10_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_CATEGORYPOSTS10_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_CATEGORYPOSTS10_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_CATEGORYPOSTS10_SCROLL_LIST:
            case self::MODULE_DATALOAD_CATEGORYPOSTS10_SCROLL_LINE:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS10_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS10_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS10_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS10_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS10_SCROLL_LIST:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS10_SCROLL_LINE:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS10_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS10_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS10_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS10_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS10_SCROLL_LIST:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS10_SCROLL_LINE:
            case self::MODULE_DATALOAD_CATEGORYPOSTS10_CAROUSEL:
            case self::MODULE_DATALOAD_CATEGORYPOSTS10_CAROUSEL_CONTENT:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS10_CAROUSEL:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS10_CAROUSEL_CONTENT:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS10_CAROUSEL:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS10_CAROUSEL_CONTENT:
                $ret['categories'] = [POP_CATEGORYPOSTS_CAT_CATEGORYPOSTS10];
                break;

            case self::MODULE_DATALOAD_CATEGORYPOSTS01_TYPEAHEAD:
            case self::MODULE_DATALOAD_CATEGORYPOSTS01_SCROLL_NAVIGATOR:
            case self::MODULE_DATALOAD_CATEGORYPOSTS01_SCROLL_ADDONS:
            case self::MODULE_DATALOAD_CATEGORYPOSTS01_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_CATEGORYPOSTS01_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_CATEGORYPOSTS01_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_CATEGORYPOSTS01_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_CATEGORYPOSTS01_SCROLL_LIST:
            case self::MODULE_DATALOAD_CATEGORYPOSTS01_SCROLL_LINE:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS01_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS01_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS01_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS01_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS01_SCROLL_LIST:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS01_SCROLL_LINE:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS01_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS01_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS01_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS01_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS01_SCROLL_LIST:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS01_SCROLL_LINE:
            case self::MODULE_DATALOAD_CATEGORYPOSTS01_CAROUSEL:
            case self::MODULE_DATALOAD_CATEGORYPOSTS01_CAROUSEL_CONTENT:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS01_CAROUSEL:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS01_CAROUSEL_CONTENT:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS01_CAROUSEL:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS01_CAROUSEL_CONTENT:
                $ret['categories'] = [POP_CATEGORYPOSTS_CAT_CATEGORYPOSTS01];
                break;

            case self::MODULE_DATALOAD_CATEGORYPOSTS11_TYPEAHEAD:
            case self::MODULE_DATALOAD_CATEGORYPOSTS11_SCROLL_NAVIGATOR:
            case self::MODULE_DATALOAD_CATEGORYPOSTS11_SCROLL_ADDONS:
            case self::MODULE_DATALOAD_CATEGORYPOSTS11_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_CATEGORYPOSTS11_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_CATEGORYPOSTS11_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_CATEGORYPOSTS11_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_CATEGORYPOSTS11_SCROLL_LIST:
            case self::MODULE_DATALOAD_CATEGORYPOSTS11_SCROLL_LINE:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS11_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS11_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS11_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS11_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS11_SCROLL_LIST:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS11_SCROLL_LINE:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS11_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS11_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS11_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS11_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS11_SCROLL_LIST:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS11_SCROLL_LINE:
            case self::MODULE_DATALOAD_CATEGORYPOSTS11_CAROUSEL:
            case self::MODULE_DATALOAD_CATEGORYPOSTS11_CAROUSEL_CONTENT:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS11_CAROUSEL:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS11_CAROUSEL_CONTENT:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS11_CAROUSEL:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS11_CAROUSEL_CONTENT:
                $ret['categories'] = [POP_CATEGORYPOSTS_CAT_CATEGORYPOSTS11];
                break;

            case self::MODULE_DATALOAD_CATEGORYPOSTS02_TYPEAHEAD:
            case self::MODULE_DATALOAD_CATEGORYPOSTS02_SCROLL_NAVIGATOR:
            case self::MODULE_DATALOAD_CATEGORYPOSTS02_SCROLL_ADDONS:
            case self::MODULE_DATALOAD_CATEGORYPOSTS02_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_CATEGORYPOSTS02_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_CATEGORYPOSTS02_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_CATEGORYPOSTS02_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_CATEGORYPOSTS02_SCROLL_LIST:
            case self::MODULE_DATALOAD_CATEGORYPOSTS02_SCROLL_LINE:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS02_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS02_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS02_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS02_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS02_SCROLL_LIST:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS02_SCROLL_LINE:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS02_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS02_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS02_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS02_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS02_SCROLL_LIST:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS02_SCROLL_LINE:
            case self::MODULE_DATALOAD_CATEGORYPOSTS02_CAROUSEL:
            case self::MODULE_DATALOAD_CATEGORYPOSTS02_CAROUSEL_CONTENT:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS02_CAROUSEL:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS02_CAROUSEL_CONTENT:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS02_CAROUSEL:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS02_CAROUSEL_CONTENT:
                $ret['categories'] = [POP_CATEGORYPOSTS_CAT_CATEGORYPOSTS02];
                break;

            case self::MODULE_DATALOAD_CATEGORYPOSTS12_TYPEAHEAD:
            case self::MODULE_DATALOAD_CATEGORYPOSTS12_SCROLL_NAVIGATOR:
            case self::MODULE_DATALOAD_CATEGORYPOSTS12_SCROLL_ADDONS:
            case self::MODULE_DATALOAD_CATEGORYPOSTS12_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_CATEGORYPOSTS12_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_CATEGORYPOSTS12_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_CATEGORYPOSTS12_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_CATEGORYPOSTS12_SCROLL_LIST:
            case self::MODULE_DATALOAD_CATEGORYPOSTS12_SCROLL_LINE:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS12_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS12_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS12_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS12_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS12_SCROLL_LIST:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS12_SCROLL_LINE:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS12_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS12_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS12_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS12_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS12_SCROLL_LIST:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS12_SCROLL_LINE:
            case self::MODULE_DATALOAD_CATEGORYPOSTS12_CAROUSEL:
            case self::MODULE_DATALOAD_CATEGORYPOSTS12_CAROUSEL_CONTENT:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS12_CAROUSEL:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS12_CAROUSEL_CONTENT:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS12_CAROUSEL:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS12_CAROUSEL_CONTENT:
                $ret['categories'] = [POP_CATEGORYPOSTS_CAT_CATEGORYPOSTS12];
                break;

            case self::MODULE_DATALOAD_CATEGORYPOSTS03_TYPEAHEAD:
            case self::MODULE_DATALOAD_CATEGORYPOSTS03_SCROLL_NAVIGATOR:
            case self::MODULE_DATALOAD_CATEGORYPOSTS03_SCROLL_ADDONS:
            case self::MODULE_DATALOAD_CATEGORYPOSTS03_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_CATEGORYPOSTS03_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_CATEGORYPOSTS03_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_CATEGORYPOSTS03_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_CATEGORYPOSTS03_SCROLL_LIST:
            case self::MODULE_DATALOAD_CATEGORYPOSTS03_SCROLL_LINE:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS03_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS03_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS03_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS03_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS03_SCROLL_LIST:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS03_SCROLL_LINE:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS03_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS03_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS03_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS03_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS03_SCROLL_LIST:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS03_SCROLL_LINE:
            case self::MODULE_DATALOAD_CATEGORYPOSTS03_CAROUSEL:
            case self::MODULE_DATALOAD_CATEGORYPOSTS03_CAROUSEL_CONTENT:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS03_CAROUSEL:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS03_CAROUSEL_CONTENT:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS03_CAROUSEL:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS03_CAROUSEL_CONTENT:
                $ret['categories'] = [POP_CATEGORYPOSTS_CAT_CATEGORYPOSTS03];
                break;

            case self::MODULE_DATALOAD_CATEGORYPOSTS13_TYPEAHEAD:
            case self::MODULE_DATALOAD_CATEGORYPOSTS13_SCROLL_NAVIGATOR:
            case self::MODULE_DATALOAD_CATEGORYPOSTS13_SCROLL_ADDONS:
            case self::MODULE_DATALOAD_CATEGORYPOSTS13_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_CATEGORYPOSTS13_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_CATEGORYPOSTS13_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_CATEGORYPOSTS13_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_CATEGORYPOSTS13_SCROLL_LIST:
            case self::MODULE_DATALOAD_CATEGORYPOSTS13_SCROLL_LINE:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS13_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS13_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS13_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS13_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS13_SCROLL_LIST:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS13_SCROLL_LINE:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS13_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS13_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS13_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS13_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS13_SCROLL_LIST:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS13_SCROLL_LINE:
            case self::MODULE_DATALOAD_CATEGORYPOSTS13_CAROUSEL:
            case self::MODULE_DATALOAD_CATEGORYPOSTS13_CAROUSEL_CONTENT:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS13_CAROUSEL:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS13_CAROUSEL_CONTENT:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS13_CAROUSEL:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS13_CAROUSEL_CONTENT:
                $ret['categories'] = [POP_CATEGORYPOSTS_CAT_CATEGORYPOSTS13];
                break;

            case self::MODULE_DATALOAD_CATEGORYPOSTS04_TYPEAHEAD:
            case self::MODULE_DATALOAD_CATEGORYPOSTS04_SCROLL_NAVIGATOR:
            case self::MODULE_DATALOAD_CATEGORYPOSTS04_SCROLL_ADDONS:
            case self::MODULE_DATALOAD_CATEGORYPOSTS04_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_CATEGORYPOSTS04_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_CATEGORYPOSTS04_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_CATEGORYPOSTS04_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_CATEGORYPOSTS04_SCROLL_LIST:
            case self::MODULE_DATALOAD_CATEGORYPOSTS04_SCROLL_LINE:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS04_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS04_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS04_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS04_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS04_SCROLL_LIST:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS04_SCROLL_LINE:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS04_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS04_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS04_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS04_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS04_SCROLL_LIST:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS04_SCROLL_LINE:
            case self::MODULE_DATALOAD_CATEGORYPOSTS04_CAROUSEL:
            case self::MODULE_DATALOAD_CATEGORYPOSTS04_CAROUSEL_CONTENT:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS04_CAROUSEL:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS04_CAROUSEL_CONTENT:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS04_CAROUSEL:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS04_CAROUSEL_CONTENT:
                $ret['categories'] = [POP_CATEGORYPOSTS_CAT_CATEGORYPOSTS04];
                break;

            case self::MODULE_DATALOAD_CATEGORYPOSTS14_TYPEAHEAD:
            case self::MODULE_DATALOAD_CATEGORYPOSTS14_SCROLL_NAVIGATOR:
            case self::MODULE_DATALOAD_CATEGORYPOSTS14_SCROLL_ADDONS:
            case self::MODULE_DATALOAD_CATEGORYPOSTS14_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_CATEGORYPOSTS14_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_CATEGORYPOSTS14_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_CATEGORYPOSTS14_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_CATEGORYPOSTS14_SCROLL_LIST:
            case self::MODULE_DATALOAD_CATEGORYPOSTS14_SCROLL_LINE:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS14_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS14_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS14_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS14_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS14_SCROLL_LIST:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS14_SCROLL_LINE:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS14_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS14_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS14_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS14_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS14_SCROLL_LIST:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS14_SCROLL_LINE:
            case self::MODULE_DATALOAD_CATEGORYPOSTS14_CAROUSEL:
            case self::MODULE_DATALOAD_CATEGORYPOSTS14_CAROUSEL_CONTENT:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS14_CAROUSEL:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS14_CAROUSEL_CONTENT:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS14_CAROUSEL:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS14_CAROUSEL_CONTENT:
                $ret['categories'] = [POP_CATEGORYPOSTS_CAT_CATEGORYPOSTS14];
                break;

            case self::MODULE_DATALOAD_CATEGORYPOSTS05_TYPEAHEAD:
            case self::MODULE_DATALOAD_CATEGORYPOSTS05_SCROLL_NAVIGATOR:
            case self::MODULE_DATALOAD_CATEGORYPOSTS05_SCROLL_ADDONS:
            case self::MODULE_DATALOAD_CATEGORYPOSTS05_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_CATEGORYPOSTS05_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_CATEGORYPOSTS05_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_CATEGORYPOSTS05_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_CATEGORYPOSTS05_SCROLL_LIST:
            case self::MODULE_DATALOAD_CATEGORYPOSTS05_SCROLL_LINE:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS05_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS05_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS05_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS05_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS05_SCROLL_LIST:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS05_SCROLL_LINE:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS05_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS05_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS05_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS05_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS05_SCROLL_LIST:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS05_SCROLL_LINE:
            case self::MODULE_DATALOAD_CATEGORYPOSTS05_CAROUSEL:
            case self::MODULE_DATALOAD_CATEGORYPOSTS05_CAROUSEL_CONTENT:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS05_CAROUSEL:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS05_CAROUSEL_CONTENT:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS05_CAROUSEL:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS05_CAROUSEL_CONTENT:
                $ret['categories'] = [POP_CATEGORYPOSTS_CAT_CATEGORYPOSTS05];
                break;

            case self::MODULE_DATALOAD_CATEGORYPOSTS15_TYPEAHEAD:
            case self::MODULE_DATALOAD_CATEGORYPOSTS15_SCROLL_NAVIGATOR:
            case self::MODULE_DATALOAD_CATEGORYPOSTS15_SCROLL_ADDONS:
            case self::MODULE_DATALOAD_CATEGORYPOSTS15_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_CATEGORYPOSTS15_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_CATEGORYPOSTS15_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_CATEGORYPOSTS15_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_CATEGORYPOSTS15_SCROLL_LIST:
            case self::MODULE_DATALOAD_CATEGORYPOSTS15_SCROLL_LINE:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS15_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS15_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS15_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS15_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS15_SCROLL_LIST:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS15_SCROLL_LINE:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS15_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS15_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS15_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS15_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS15_SCROLL_LIST:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS15_SCROLL_LINE:
            case self::MODULE_DATALOAD_CATEGORYPOSTS15_CAROUSEL:
            case self::MODULE_DATALOAD_CATEGORYPOSTS15_CAROUSEL_CONTENT:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS15_CAROUSEL:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS15_CAROUSEL_CONTENT:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS15_CAROUSEL:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS15_CAROUSEL_CONTENT:
                $ret['categories'] = [POP_CATEGORYPOSTS_CAT_CATEGORYPOSTS15];
                break;

            case self::MODULE_DATALOAD_CATEGORYPOSTS06_TYPEAHEAD:
            case self::MODULE_DATALOAD_CATEGORYPOSTS06_SCROLL_NAVIGATOR:
            case self::MODULE_DATALOAD_CATEGORYPOSTS06_SCROLL_ADDONS:
            case self::MODULE_DATALOAD_CATEGORYPOSTS06_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_CATEGORYPOSTS06_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_CATEGORYPOSTS06_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_CATEGORYPOSTS06_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_CATEGORYPOSTS06_SCROLL_LIST:
            case self::MODULE_DATALOAD_CATEGORYPOSTS06_SCROLL_LINE:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS06_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS06_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS06_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS06_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS06_SCROLL_LIST:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS06_SCROLL_LINE:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS06_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS06_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS06_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS06_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS06_SCROLL_LIST:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS06_SCROLL_LINE:
            case self::MODULE_DATALOAD_CATEGORYPOSTS06_CAROUSEL:
            case self::MODULE_DATALOAD_CATEGORYPOSTS06_CAROUSEL_CONTENT:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS06_CAROUSEL:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS06_CAROUSEL_CONTENT:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS06_CAROUSEL:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS06_CAROUSEL_CONTENT:
                $ret['categories'] = [POP_CATEGORYPOSTS_CAT_CATEGORYPOSTS06];
                break;

            case self::MODULE_DATALOAD_CATEGORYPOSTS16_TYPEAHEAD:
            case self::MODULE_DATALOAD_CATEGORYPOSTS16_SCROLL_NAVIGATOR:
            case self::MODULE_DATALOAD_CATEGORYPOSTS16_SCROLL_ADDONS:
            case self::MODULE_DATALOAD_CATEGORYPOSTS16_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_CATEGORYPOSTS16_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_CATEGORYPOSTS16_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_CATEGORYPOSTS16_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_CATEGORYPOSTS16_SCROLL_LIST:
            case self::MODULE_DATALOAD_CATEGORYPOSTS16_SCROLL_LINE:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS16_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS16_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS16_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS16_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS16_SCROLL_LIST:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS16_SCROLL_LINE:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS16_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS16_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS16_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS16_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS16_SCROLL_LIST:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS16_SCROLL_LINE:
            case self::MODULE_DATALOAD_CATEGORYPOSTS16_CAROUSEL:
            case self::MODULE_DATALOAD_CATEGORYPOSTS16_CAROUSEL_CONTENT:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS16_CAROUSEL:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS16_CAROUSEL_CONTENT:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS16_CAROUSEL:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS16_CAROUSEL_CONTENT:
                $ret['categories'] = [POP_CATEGORYPOSTS_CAT_CATEGORYPOSTS16];
                break;

            case self::MODULE_DATALOAD_CATEGORYPOSTS07_TYPEAHEAD:
            case self::MODULE_DATALOAD_CATEGORYPOSTS07_SCROLL_NAVIGATOR:
            case self::MODULE_DATALOAD_CATEGORYPOSTS07_SCROLL_ADDONS:
            case self::MODULE_DATALOAD_CATEGORYPOSTS07_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_CATEGORYPOSTS07_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_CATEGORYPOSTS07_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_CATEGORYPOSTS07_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_CATEGORYPOSTS07_SCROLL_LIST:
            case self::MODULE_DATALOAD_CATEGORYPOSTS07_SCROLL_LINE:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS07_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS07_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS07_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS07_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS07_SCROLL_LIST:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS07_SCROLL_LINE:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS07_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS07_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS07_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS07_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS07_SCROLL_LIST:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS07_SCROLL_LINE:
            case self::MODULE_DATALOAD_CATEGORYPOSTS07_CAROUSEL:
            case self::MODULE_DATALOAD_CATEGORYPOSTS07_CAROUSEL_CONTENT:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS07_CAROUSEL:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS07_CAROUSEL_CONTENT:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS07_CAROUSEL:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS07_CAROUSEL_CONTENT:
                $ret['categories'] = [POP_CATEGORYPOSTS_CAT_CATEGORYPOSTS07];
                break;

            case self::MODULE_DATALOAD_CATEGORYPOSTS17_TYPEAHEAD:
            case self::MODULE_DATALOAD_CATEGORYPOSTS17_SCROLL_NAVIGATOR:
            case self::MODULE_DATALOAD_CATEGORYPOSTS17_SCROLL_ADDONS:
            case self::MODULE_DATALOAD_CATEGORYPOSTS17_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_CATEGORYPOSTS17_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_CATEGORYPOSTS17_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_CATEGORYPOSTS17_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_CATEGORYPOSTS17_SCROLL_LIST:
            case self::MODULE_DATALOAD_CATEGORYPOSTS17_SCROLL_LINE:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS17_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS17_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS17_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS17_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS17_SCROLL_LIST:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS17_SCROLL_LINE:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS17_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS17_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS17_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS17_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS17_SCROLL_LIST:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS17_SCROLL_LINE:
            case self::MODULE_DATALOAD_CATEGORYPOSTS17_CAROUSEL:
            case self::MODULE_DATALOAD_CATEGORYPOSTS17_CAROUSEL_CONTENT:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS17_CAROUSEL:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS17_CAROUSEL_CONTENT:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS17_CAROUSEL:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS17_CAROUSEL_CONTENT:
                $ret['categories'] = [POP_CATEGORYPOSTS_CAT_CATEGORYPOSTS17];
                break;

            case self::MODULE_DATALOAD_CATEGORYPOSTS08_TYPEAHEAD:
            case self::MODULE_DATALOAD_CATEGORYPOSTS08_SCROLL_NAVIGATOR:
            case self::MODULE_DATALOAD_CATEGORYPOSTS08_SCROLL_ADDONS:
            case self::MODULE_DATALOAD_CATEGORYPOSTS08_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_CATEGORYPOSTS08_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_CATEGORYPOSTS08_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_CATEGORYPOSTS08_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_CATEGORYPOSTS08_SCROLL_LIST:
            case self::MODULE_DATALOAD_CATEGORYPOSTS08_SCROLL_LINE:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS08_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS08_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS08_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS08_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS08_SCROLL_LIST:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS08_SCROLL_LINE:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS08_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS08_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS08_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS08_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS08_SCROLL_LIST:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS08_SCROLL_LINE:
            case self::MODULE_DATALOAD_CATEGORYPOSTS08_CAROUSEL:
            case self::MODULE_DATALOAD_CATEGORYPOSTS08_CAROUSEL_CONTENT:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS08_CAROUSEL:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS08_CAROUSEL_CONTENT:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS08_CAROUSEL:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS08_CAROUSEL_CONTENT:
                $ret['categories'] = [POP_CATEGORYPOSTS_CAT_CATEGORYPOSTS08];
                break;

            case self::MODULE_DATALOAD_CATEGORYPOSTS18_TYPEAHEAD:
            case self::MODULE_DATALOAD_CATEGORYPOSTS18_SCROLL_NAVIGATOR:
            case self::MODULE_DATALOAD_CATEGORYPOSTS18_SCROLL_ADDONS:
            case self::MODULE_DATALOAD_CATEGORYPOSTS18_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_CATEGORYPOSTS18_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_CATEGORYPOSTS18_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_CATEGORYPOSTS18_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_CATEGORYPOSTS18_SCROLL_LIST:
            case self::MODULE_DATALOAD_CATEGORYPOSTS18_SCROLL_LINE:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS18_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS18_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS18_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS18_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS18_SCROLL_LIST:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS18_SCROLL_LINE:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS18_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS18_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS18_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS18_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS18_SCROLL_LIST:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS18_SCROLL_LINE:
            case self::MODULE_DATALOAD_CATEGORYPOSTS18_CAROUSEL:
            case self::MODULE_DATALOAD_CATEGORYPOSTS18_CAROUSEL_CONTENT:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS18_CAROUSEL:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS18_CAROUSEL_CONTENT:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS18_CAROUSEL:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS18_CAROUSEL_CONTENT:
                $ret['categories'] = [POP_CATEGORYPOSTS_CAT_CATEGORYPOSTS18];
                break;

            case self::MODULE_DATALOAD_CATEGORYPOSTS09_TYPEAHEAD:
            case self::MODULE_DATALOAD_CATEGORYPOSTS09_SCROLL_NAVIGATOR:
            case self::MODULE_DATALOAD_CATEGORYPOSTS09_SCROLL_ADDONS:
            case self::MODULE_DATALOAD_CATEGORYPOSTS09_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_CATEGORYPOSTS09_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_CATEGORYPOSTS09_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_CATEGORYPOSTS09_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_CATEGORYPOSTS09_SCROLL_LIST:
            case self::MODULE_DATALOAD_CATEGORYPOSTS09_SCROLL_LINE:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS09_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS09_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS09_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS09_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS09_SCROLL_LIST:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS09_SCROLL_LINE:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS09_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS09_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS09_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS09_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS09_SCROLL_LIST:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS09_SCROLL_LINE:
            case self::MODULE_DATALOAD_CATEGORYPOSTS09_CAROUSEL:
            case self::MODULE_DATALOAD_CATEGORYPOSTS09_CAROUSEL_CONTENT:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS09_CAROUSEL:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS09_CAROUSEL_CONTENT:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS09_CAROUSEL:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS09_CAROUSEL_CONTENT:
                $ret['categories'] = [POP_CATEGORYPOSTS_CAT_CATEGORYPOSTS09];
                break;

            case self::MODULE_DATALOAD_CATEGORYPOSTS19_TYPEAHEAD:
            case self::MODULE_DATALOAD_CATEGORYPOSTS19_SCROLL_NAVIGATOR:
            case self::MODULE_DATALOAD_CATEGORYPOSTS19_SCROLL_ADDONS:
            case self::MODULE_DATALOAD_CATEGORYPOSTS19_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_CATEGORYPOSTS19_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_CATEGORYPOSTS19_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_CATEGORYPOSTS19_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_CATEGORYPOSTS19_SCROLL_LIST:
            case self::MODULE_DATALOAD_CATEGORYPOSTS19_SCROLL_LINE:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS19_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS19_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS19_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS19_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS19_SCROLL_LIST:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS19_SCROLL_LINE:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS19_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS19_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS19_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS19_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS19_SCROLL_LIST:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS19_SCROLL_LINE:
            case self::MODULE_DATALOAD_CATEGORYPOSTS19_CAROUSEL:
            case self::MODULE_DATALOAD_CATEGORYPOSTS19_CAROUSEL_CONTENT:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS19_CAROUSEL:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS19_CAROUSEL_CONTENT:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS19_CAROUSEL:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS19_CAROUSEL_CONTENT:
                $ret['categories'] = [POP_CATEGORYPOSTS_CAT_CATEGORYPOSTS19];
                break;
        }

        return $ret;
    }

    protected function getMutableonrequestDataloadQueryArgs(array $componentVariation, array &$props): array
    {
        $ret = parent::getMutableonrequestDataloadQueryArgs($componentVariation, $props);

        switch ($componentVariation[1]) {
         // Filter by the Profile/Community
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS00_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS01_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS02_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS03_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS04_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS05_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS06_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS07_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS08_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS09_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS10_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS11_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS12_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS13_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS14_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS15_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS16_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS17_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS18_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS19_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS00_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS01_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS02_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS03_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS04_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS05_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS06_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS07_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS08_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS09_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS10_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS11_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS12_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS13_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS14_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS15_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS16_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS17_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS18_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS19_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS00_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS01_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS02_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS03_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS04_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS05_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS06_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS07_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS08_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS09_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS10_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS11_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS12_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS13_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS14_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS15_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS16_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS17_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS18_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS19_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS00_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS01_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS02_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS03_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS04_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS05_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS06_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS07_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS08_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS09_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS10_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS11_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS12_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS13_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS14_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS15_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS16_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS17_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS18_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS19_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS00_SCROLL_LIST:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS01_SCROLL_LIST:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS02_SCROLL_LIST:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS03_SCROLL_LIST:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS04_SCROLL_LIST:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS05_SCROLL_LIST:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS06_SCROLL_LIST:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS07_SCROLL_LIST:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS08_SCROLL_LIST:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS09_SCROLL_LIST:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS10_SCROLL_LIST:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS11_SCROLL_LIST:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS12_SCROLL_LIST:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS13_SCROLL_LIST:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS14_SCROLL_LIST:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS15_SCROLL_LIST:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS16_SCROLL_LIST:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS17_SCROLL_LIST:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS18_SCROLL_LIST:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS19_SCROLL_LIST:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS00_SCROLL_LINE:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS01_SCROLL_LINE:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS02_SCROLL_LINE:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS03_SCROLL_LINE:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS04_SCROLL_LINE:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS05_SCROLL_LINE:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS06_SCROLL_LINE:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS07_SCROLL_LINE:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS08_SCROLL_LINE:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS09_SCROLL_LINE:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS10_SCROLL_LINE:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS11_SCROLL_LINE:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS12_SCROLL_LINE:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS13_SCROLL_LINE:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS14_SCROLL_LINE:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS15_SCROLL_LINE:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS16_SCROLL_LINE:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS17_SCROLL_LINE:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS18_SCROLL_LINE:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS19_SCROLL_LINE:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS00_CAROUSEL:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS01_CAROUSEL:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS02_CAROUSEL:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS03_CAROUSEL:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS04_CAROUSEL:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS05_CAROUSEL:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS06_CAROUSEL:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS07_CAROUSEL:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS08_CAROUSEL:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS09_CAROUSEL:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS10_CAROUSEL:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS11_CAROUSEL:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS12_CAROUSEL:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS13_CAROUSEL:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS14_CAROUSEL:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS15_CAROUSEL:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS16_CAROUSEL:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS17_CAROUSEL:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS18_CAROUSEL:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS19_CAROUSEL:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS00_CAROUSEL_CONTENT:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS01_CAROUSEL_CONTENT:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS02_CAROUSEL_CONTENT:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS03_CAROUSEL_CONTENT:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS04_CAROUSEL_CONTENT:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS05_CAROUSEL_CONTENT:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS06_CAROUSEL_CONTENT:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS07_CAROUSEL_CONTENT:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS08_CAROUSEL_CONTENT:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS09_CAROUSEL_CONTENT:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS10_CAROUSEL_CONTENT:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS11_CAROUSEL_CONTENT:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS12_CAROUSEL_CONTENT:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS13_CAROUSEL_CONTENT:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS14_CAROUSEL_CONTENT:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS15_CAROUSEL_CONTENT:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS16_CAROUSEL_CONTENT:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS17_CAROUSEL_CONTENT:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS18_CAROUSEL_CONTENT:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS19_CAROUSEL_CONTENT:
                PoP_Module_Processor_CustomSectionBlocksUtils::addDataloadqueryargsAuthorcontent($ret);
                break;

            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS00_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS01_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS02_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS03_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS04_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS05_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS06_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS07_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS08_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS09_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS10_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS11_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS12_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS13_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS14_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS15_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS16_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS17_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS18_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS19_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS00_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS01_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS02_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS03_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS04_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS05_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS06_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS07_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS08_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS09_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS10_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS11_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS12_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS13_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS14_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS15_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS16_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS17_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS18_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS19_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS00_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS01_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS02_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS03_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS04_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS05_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS06_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS07_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS08_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS09_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS10_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS11_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS12_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS13_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS14_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS15_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS16_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS17_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS18_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS19_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS00_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS01_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS02_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS03_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS04_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS05_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS06_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS07_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS08_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS09_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS10_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS11_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS12_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS13_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS14_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS15_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS16_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS17_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS18_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS19_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS00_SCROLL_LIST:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS01_SCROLL_LIST:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS02_SCROLL_LIST:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS03_SCROLL_LIST:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS04_SCROLL_LIST:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS05_SCROLL_LIST:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS06_SCROLL_LIST:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS07_SCROLL_LIST:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS08_SCROLL_LIST:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS09_SCROLL_LIST:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS10_SCROLL_LIST:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS11_SCROLL_LIST:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS12_SCROLL_LIST:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS13_SCROLL_LIST:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS14_SCROLL_LIST:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS15_SCROLL_LIST:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS16_SCROLL_LIST:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS17_SCROLL_LIST:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS18_SCROLL_LIST:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS19_SCROLL_LIST:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS00_SCROLL_LINE:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS01_SCROLL_LINE:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS02_SCROLL_LINE:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS03_SCROLL_LINE:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS04_SCROLL_LINE:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS05_SCROLL_LINE:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS06_SCROLL_LINE:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS07_SCROLL_LINE:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS08_SCROLL_LINE:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS09_SCROLL_LINE:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS10_SCROLL_LINE:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS11_SCROLL_LINE:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS12_SCROLL_LINE:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS13_SCROLL_LINE:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS14_SCROLL_LINE:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS15_SCROLL_LINE:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS16_SCROLL_LINE:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS17_SCROLL_LINE:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS18_SCROLL_LINE:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS19_SCROLL_LINE:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS00_CAROUSEL:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS01_CAROUSEL:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS02_CAROUSEL:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS03_CAROUSEL:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS04_CAROUSEL:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS05_CAROUSEL:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS06_CAROUSEL:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS07_CAROUSEL:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS08_CAROUSEL:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS09_CAROUSEL:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS10_CAROUSEL:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS11_CAROUSEL:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS12_CAROUSEL:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS13_CAROUSEL:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS14_CAROUSEL:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS15_CAROUSEL:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS16_CAROUSEL:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS17_CAROUSEL:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS18_CAROUSEL:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS19_CAROUSEL:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS00_CAROUSEL_CONTENT:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS01_CAROUSEL_CONTENT:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS02_CAROUSEL_CONTENT:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS03_CAROUSEL_CONTENT:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS04_CAROUSEL_CONTENT:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS05_CAROUSEL_CONTENT:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS06_CAROUSEL_CONTENT:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS07_CAROUSEL_CONTENT:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS08_CAROUSEL_CONTENT:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS09_CAROUSEL_CONTENT:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS10_CAROUSEL_CONTENT:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS11_CAROUSEL_CONTENT:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS12_CAROUSEL_CONTENT:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS13_CAROUSEL_CONTENT:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS14_CAROUSEL_CONTENT:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS15_CAROUSEL_CONTENT:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS16_CAROUSEL_CONTENT:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS17_CAROUSEL_CONTENT:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS18_CAROUSEL_CONTENT:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS19_CAROUSEL_CONTENT:
                PoP_Module_Processor_CustomSectionBlocksUtils::addDataloadqueryargsTagcontent($ret);
                break;
        }

        return $ret;
    }

    public function getRelationalTypeResolver(array $componentVariation): ?\PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface
    {
        return $this->instanceManager->getInstance(PostObjectTypeResolver::class);
    }

    public function initModelProps(array $componentVariation, array &$props): void
    {
        switch ($componentVariation[1]) {
            case self::MODULE_DATALOAD_CATEGORYPOSTS00_SCROLL_NAVIGATOR:
            case self::MODULE_DATALOAD_CATEGORYPOSTS00_SCROLL_ADDONS:
            case self::MODULE_DATALOAD_CATEGORYPOSTS00_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_CATEGORYPOSTS00_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_CATEGORYPOSTS00_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_CATEGORYPOSTS00_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_CATEGORYPOSTS00_SCROLL_LIST:
            case self::MODULE_DATALOAD_CATEGORYPOSTS00_SCROLL_LINE:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS00_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS00_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS00_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS00_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS00_SCROLL_LIST:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS00_SCROLL_LINE:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS00_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS00_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS00_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS00_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS00_SCROLL_LIST:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS00_SCROLL_LINE:
            case self::MODULE_DATALOAD_CATEGORYPOSTS00_CAROUSEL:
            case self::MODULE_DATALOAD_CATEGORYPOSTS00_CAROUSEL_CONTENT:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS00_CAROUSEL:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS00_CAROUSEL_CONTENT:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS00_CAROUSEL:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS00_CAROUSEL_CONTENT:
                $cat = POP_CATEGORYPOSTS_CAT_CATEGORYPOSTS00;
                break;

            case self::MODULE_DATALOAD_CATEGORYPOSTS10_SCROLL_NAVIGATOR:
            case self::MODULE_DATALOAD_CATEGORYPOSTS10_SCROLL_ADDONS:
            case self::MODULE_DATALOAD_CATEGORYPOSTS10_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_CATEGORYPOSTS10_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_CATEGORYPOSTS10_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_CATEGORYPOSTS10_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_CATEGORYPOSTS10_SCROLL_LIST:
            case self::MODULE_DATALOAD_CATEGORYPOSTS10_SCROLL_LINE:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS10_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS10_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS10_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS10_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS10_SCROLL_LIST:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS10_SCROLL_LINE:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS10_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS10_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS10_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS10_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS10_SCROLL_LIST:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS10_SCROLL_LINE:
            case self::MODULE_DATALOAD_CATEGORYPOSTS10_CAROUSEL:
            case self::MODULE_DATALOAD_CATEGORYPOSTS10_CAROUSEL_CONTENT:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS10_CAROUSEL:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS10_CAROUSEL_CONTENT:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS10_CAROUSEL:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS10_CAROUSEL_CONTENT:
                $cat = POP_CATEGORYPOSTS_CAT_CATEGORYPOSTS10;
                break;

            case self::MODULE_DATALOAD_CATEGORYPOSTS01_SCROLL_NAVIGATOR:
            case self::MODULE_DATALOAD_CATEGORYPOSTS01_SCROLL_ADDONS:
            case self::MODULE_DATALOAD_CATEGORYPOSTS01_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_CATEGORYPOSTS01_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_CATEGORYPOSTS01_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_CATEGORYPOSTS01_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_CATEGORYPOSTS01_SCROLL_LIST:
            case self::MODULE_DATALOAD_CATEGORYPOSTS01_SCROLL_LINE:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS01_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS01_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS01_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS01_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS01_SCROLL_LIST:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS01_SCROLL_LINE:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS01_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS01_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS01_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS01_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS01_SCROLL_LIST:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS01_SCROLL_LINE:
            case self::MODULE_DATALOAD_CATEGORYPOSTS01_CAROUSEL:
            case self::MODULE_DATALOAD_CATEGORYPOSTS01_CAROUSEL_CONTENT:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS01_CAROUSEL:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS01_CAROUSEL_CONTENT:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS01_CAROUSEL:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS01_CAROUSEL_CONTENT:
                $cat = POP_CATEGORYPOSTS_CAT_CATEGORYPOSTS01;
                break;

            case self::MODULE_DATALOAD_CATEGORYPOSTS11_SCROLL_NAVIGATOR:
            case self::MODULE_DATALOAD_CATEGORYPOSTS11_SCROLL_ADDONS:
            case self::MODULE_DATALOAD_CATEGORYPOSTS11_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_CATEGORYPOSTS11_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_CATEGORYPOSTS11_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_CATEGORYPOSTS11_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_CATEGORYPOSTS11_SCROLL_LIST:
            case self::MODULE_DATALOAD_CATEGORYPOSTS11_SCROLL_LINE:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS11_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS11_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS11_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS11_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS11_SCROLL_LIST:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS11_SCROLL_LINE:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS11_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS11_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS11_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS11_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS11_SCROLL_LIST:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS11_SCROLL_LINE:
            case self::MODULE_DATALOAD_CATEGORYPOSTS11_CAROUSEL:
            case self::MODULE_DATALOAD_CATEGORYPOSTS11_CAROUSEL_CONTENT:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS11_CAROUSEL:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS11_CAROUSEL_CONTENT:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS11_CAROUSEL:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS11_CAROUSEL_CONTENT:
                $cat = POP_CATEGORYPOSTS_CAT_CATEGORYPOSTS11;
                break;

            case self::MODULE_DATALOAD_CATEGORYPOSTS02_SCROLL_NAVIGATOR:
            case self::MODULE_DATALOAD_CATEGORYPOSTS02_SCROLL_ADDONS:
            case self::MODULE_DATALOAD_CATEGORYPOSTS02_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_CATEGORYPOSTS02_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_CATEGORYPOSTS02_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_CATEGORYPOSTS02_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_CATEGORYPOSTS02_SCROLL_LIST:
            case self::MODULE_DATALOAD_CATEGORYPOSTS02_SCROLL_LINE:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS02_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS02_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS02_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS02_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS02_SCROLL_LIST:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS02_SCROLL_LINE:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS02_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS02_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS02_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS02_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS02_SCROLL_LIST:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS02_SCROLL_LINE:
            case self::MODULE_DATALOAD_CATEGORYPOSTS02_CAROUSEL:
            case self::MODULE_DATALOAD_CATEGORYPOSTS02_CAROUSEL_CONTENT:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS02_CAROUSEL:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS02_CAROUSEL_CONTENT:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS02_CAROUSEL:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS02_CAROUSEL_CONTENT:
                $cat = POP_CATEGORYPOSTS_CAT_CATEGORYPOSTS02;
                break;

            case self::MODULE_DATALOAD_CATEGORYPOSTS12_SCROLL_NAVIGATOR:
            case self::MODULE_DATALOAD_CATEGORYPOSTS12_SCROLL_ADDONS:
            case self::MODULE_DATALOAD_CATEGORYPOSTS12_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_CATEGORYPOSTS12_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_CATEGORYPOSTS12_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_CATEGORYPOSTS12_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_CATEGORYPOSTS12_SCROLL_LIST:
            case self::MODULE_DATALOAD_CATEGORYPOSTS12_SCROLL_LINE:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS12_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS12_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS12_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS12_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS12_SCROLL_LIST:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS12_SCROLL_LINE:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS12_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS12_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS12_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS12_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS12_SCROLL_LIST:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS12_SCROLL_LINE:
            case self::MODULE_DATALOAD_CATEGORYPOSTS12_CAROUSEL:
            case self::MODULE_DATALOAD_CATEGORYPOSTS12_CAROUSEL_CONTENT:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS12_CAROUSEL:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS12_CAROUSEL_CONTENT:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS12_CAROUSEL:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS12_CAROUSEL_CONTENT:
                $cat = POP_CATEGORYPOSTS_CAT_CATEGORYPOSTS12;
                break;

            case self::MODULE_DATALOAD_CATEGORYPOSTS03_SCROLL_NAVIGATOR:
            case self::MODULE_DATALOAD_CATEGORYPOSTS03_SCROLL_ADDONS:
            case self::MODULE_DATALOAD_CATEGORYPOSTS03_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_CATEGORYPOSTS03_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_CATEGORYPOSTS03_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_CATEGORYPOSTS03_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_CATEGORYPOSTS03_SCROLL_LIST:
            case self::MODULE_DATALOAD_CATEGORYPOSTS03_SCROLL_LINE:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS03_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS03_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS03_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS03_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS03_SCROLL_LIST:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS03_SCROLL_LINE:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS03_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS03_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS03_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS03_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS03_SCROLL_LIST:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS03_SCROLL_LINE:
            case self::MODULE_DATALOAD_CATEGORYPOSTS03_CAROUSEL:
            case self::MODULE_DATALOAD_CATEGORYPOSTS03_CAROUSEL_CONTENT:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS03_CAROUSEL:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS03_CAROUSEL_CONTENT:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS03_CAROUSEL:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS03_CAROUSEL_CONTENT:
                $cat = POP_CATEGORYPOSTS_CAT_CATEGORYPOSTS03;
                break;

            case self::MODULE_DATALOAD_CATEGORYPOSTS13_SCROLL_NAVIGATOR:
            case self::MODULE_DATALOAD_CATEGORYPOSTS13_SCROLL_ADDONS:
            case self::MODULE_DATALOAD_CATEGORYPOSTS13_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_CATEGORYPOSTS13_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_CATEGORYPOSTS13_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_CATEGORYPOSTS13_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_CATEGORYPOSTS13_SCROLL_LIST:
            case self::MODULE_DATALOAD_CATEGORYPOSTS13_SCROLL_LINE:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS13_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS13_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS13_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS13_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS13_SCROLL_LIST:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS13_SCROLL_LINE:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS13_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS13_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS13_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS13_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS13_SCROLL_LIST:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS13_SCROLL_LINE:
            case self::MODULE_DATALOAD_CATEGORYPOSTS13_CAROUSEL:
            case self::MODULE_DATALOAD_CATEGORYPOSTS13_CAROUSEL_CONTENT:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS13_CAROUSEL:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS13_CAROUSEL_CONTENT:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS13_CAROUSEL:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS13_CAROUSEL_CONTENT:
                $cat = POP_CATEGORYPOSTS_CAT_CATEGORYPOSTS13;
                break;

            case self::MODULE_DATALOAD_CATEGORYPOSTS04_SCROLL_NAVIGATOR:
            case self::MODULE_DATALOAD_CATEGORYPOSTS04_SCROLL_ADDONS:
            case self::MODULE_DATALOAD_CATEGORYPOSTS04_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_CATEGORYPOSTS04_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_CATEGORYPOSTS04_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_CATEGORYPOSTS04_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_CATEGORYPOSTS04_SCROLL_LIST:
            case self::MODULE_DATALOAD_CATEGORYPOSTS04_SCROLL_LINE:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS04_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS04_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS04_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS04_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS04_SCROLL_LIST:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS04_SCROLL_LINE:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS04_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS04_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS04_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS04_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS04_SCROLL_LIST:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS04_SCROLL_LINE:
            case self::MODULE_DATALOAD_CATEGORYPOSTS04_CAROUSEL:
            case self::MODULE_DATALOAD_CATEGORYPOSTS04_CAROUSEL_CONTENT:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS04_CAROUSEL:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS04_CAROUSEL_CONTENT:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS04_CAROUSEL:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS04_CAROUSEL_CONTENT:
                $cat = POP_CATEGORYPOSTS_CAT_CATEGORYPOSTS04;
                break;

            case self::MODULE_DATALOAD_CATEGORYPOSTS14_SCROLL_NAVIGATOR:
            case self::MODULE_DATALOAD_CATEGORYPOSTS14_SCROLL_ADDONS:
            case self::MODULE_DATALOAD_CATEGORYPOSTS14_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_CATEGORYPOSTS14_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_CATEGORYPOSTS14_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_CATEGORYPOSTS14_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_CATEGORYPOSTS14_SCROLL_LIST:
            case self::MODULE_DATALOAD_CATEGORYPOSTS14_SCROLL_LINE:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS14_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS14_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS14_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS14_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS14_SCROLL_LIST:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS14_SCROLL_LINE:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS14_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS14_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS14_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS14_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS14_SCROLL_LIST:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS14_SCROLL_LINE:
            case self::MODULE_DATALOAD_CATEGORYPOSTS14_CAROUSEL:
            case self::MODULE_DATALOAD_CATEGORYPOSTS14_CAROUSEL_CONTENT:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS14_CAROUSEL:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS14_CAROUSEL_CONTENT:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS14_CAROUSEL:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS14_CAROUSEL_CONTENT:
                $cat = POP_CATEGORYPOSTS_CAT_CATEGORYPOSTS14;
                break;

            case self::MODULE_DATALOAD_CATEGORYPOSTS05_SCROLL_NAVIGATOR:
            case self::MODULE_DATALOAD_CATEGORYPOSTS05_SCROLL_ADDONS:
            case self::MODULE_DATALOAD_CATEGORYPOSTS05_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_CATEGORYPOSTS05_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_CATEGORYPOSTS05_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_CATEGORYPOSTS05_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_CATEGORYPOSTS05_SCROLL_LIST:
            case self::MODULE_DATALOAD_CATEGORYPOSTS05_SCROLL_LINE:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS05_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS05_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS05_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS05_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS05_SCROLL_LIST:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS05_SCROLL_LINE:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS05_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS05_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS05_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS05_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS05_SCROLL_LIST:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS05_SCROLL_LINE:
            case self::MODULE_DATALOAD_CATEGORYPOSTS05_CAROUSEL:
            case self::MODULE_DATALOAD_CATEGORYPOSTS05_CAROUSEL_CONTENT:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS05_CAROUSEL:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS05_CAROUSEL_CONTENT:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS05_CAROUSEL:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS05_CAROUSEL_CONTENT:
                $cat = POP_CATEGORYPOSTS_CAT_CATEGORYPOSTS05;
                break;

            case self::MODULE_DATALOAD_CATEGORYPOSTS15_SCROLL_NAVIGATOR:
            case self::MODULE_DATALOAD_CATEGORYPOSTS15_SCROLL_ADDONS:
            case self::MODULE_DATALOAD_CATEGORYPOSTS15_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_CATEGORYPOSTS15_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_CATEGORYPOSTS15_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_CATEGORYPOSTS15_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_CATEGORYPOSTS15_SCROLL_LIST:
            case self::MODULE_DATALOAD_CATEGORYPOSTS15_SCROLL_LINE:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS15_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS15_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS15_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS15_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS15_SCROLL_LIST:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS15_SCROLL_LINE:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS15_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS15_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS15_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS15_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS15_SCROLL_LIST:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS15_SCROLL_LINE:
            case self::MODULE_DATALOAD_CATEGORYPOSTS15_CAROUSEL:
            case self::MODULE_DATALOAD_CATEGORYPOSTS15_CAROUSEL_CONTENT:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS15_CAROUSEL:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS15_CAROUSEL_CONTENT:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS15_CAROUSEL:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS15_CAROUSEL_CONTENT:
                $cat = POP_CATEGORYPOSTS_CAT_CATEGORYPOSTS15;
                break;

            case self::MODULE_DATALOAD_CATEGORYPOSTS06_SCROLL_NAVIGATOR:
            case self::MODULE_DATALOAD_CATEGORYPOSTS06_SCROLL_ADDONS:
            case self::MODULE_DATALOAD_CATEGORYPOSTS06_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_CATEGORYPOSTS06_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_CATEGORYPOSTS06_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_CATEGORYPOSTS06_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_CATEGORYPOSTS06_SCROLL_LIST:
            case self::MODULE_DATALOAD_CATEGORYPOSTS06_SCROLL_LINE:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS06_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS06_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS06_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS06_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS06_SCROLL_LIST:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS06_SCROLL_LINE:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS06_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS06_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS06_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS06_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS06_SCROLL_LIST:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS06_SCROLL_LINE:
            case self::MODULE_DATALOAD_CATEGORYPOSTS06_CAROUSEL:
            case self::MODULE_DATALOAD_CATEGORYPOSTS06_CAROUSEL_CONTENT:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS06_CAROUSEL:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS06_CAROUSEL_CONTENT:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS06_CAROUSEL:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS06_CAROUSEL_CONTENT:
                $cat = POP_CATEGORYPOSTS_CAT_CATEGORYPOSTS06;
                break;

            case self::MODULE_DATALOAD_CATEGORYPOSTS16_SCROLL_NAVIGATOR:
            case self::MODULE_DATALOAD_CATEGORYPOSTS16_SCROLL_ADDONS:
            case self::MODULE_DATALOAD_CATEGORYPOSTS16_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_CATEGORYPOSTS16_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_CATEGORYPOSTS16_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_CATEGORYPOSTS16_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_CATEGORYPOSTS16_SCROLL_LIST:
            case self::MODULE_DATALOAD_CATEGORYPOSTS16_SCROLL_LINE:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS16_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS16_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS16_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS16_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS16_SCROLL_LIST:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS16_SCROLL_LINE:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS16_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS16_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS16_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS16_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS16_SCROLL_LIST:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS16_SCROLL_LINE:
            case self::MODULE_DATALOAD_CATEGORYPOSTS16_CAROUSEL:
            case self::MODULE_DATALOAD_CATEGORYPOSTS16_CAROUSEL_CONTENT:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS16_CAROUSEL:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS16_CAROUSEL_CONTENT:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS16_CAROUSEL:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS16_CAROUSEL_CONTENT:
                $cat = POP_CATEGORYPOSTS_CAT_CATEGORYPOSTS16;
                break;

            case self::MODULE_DATALOAD_CATEGORYPOSTS07_SCROLL_NAVIGATOR:
            case self::MODULE_DATALOAD_CATEGORYPOSTS07_SCROLL_ADDONS:
            case self::MODULE_DATALOAD_CATEGORYPOSTS07_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_CATEGORYPOSTS07_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_CATEGORYPOSTS07_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_CATEGORYPOSTS07_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_CATEGORYPOSTS07_SCROLL_LIST:
            case self::MODULE_DATALOAD_CATEGORYPOSTS07_SCROLL_LINE:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS07_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS07_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS07_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS07_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS07_SCROLL_LIST:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS07_SCROLL_LINE:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS07_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS07_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS07_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS07_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS07_SCROLL_LIST:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS07_SCROLL_LINE:
            case self::MODULE_DATALOAD_CATEGORYPOSTS07_CAROUSEL:
            case self::MODULE_DATALOAD_CATEGORYPOSTS07_CAROUSEL_CONTENT:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS07_CAROUSEL:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS07_CAROUSEL_CONTENT:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS07_CAROUSEL:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS07_CAROUSEL_CONTENT:
                $cat = POP_CATEGORYPOSTS_CAT_CATEGORYPOSTS07;
                break;

            case self::MODULE_DATALOAD_CATEGORYPOSTS17_SCROLL_NAVIGATOR:
            case self::MODULE_DATALOAD_CATEGORYPOSTS17_SCROLL_ADDONS:
            case self::MODULE_DATALOAD_CATEGORYPOSTS17_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_CATEGORYPOSTS17_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_CATEGORYPOSTS17_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_CATEGORYPOSTS17_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_CATEGORYPOSTS17_SCROLL_LIST:
            case self::MODULE_DATALOAD_CATEGORYPOSTS17_SCROLL_LINE:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS17_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS17_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS17_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS17_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS17_SCROLL_LIST:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS17_SCROLL_LINE:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS17_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS17_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS17_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS17_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS17_SCROLL_LIST:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS17_SCROLL_LINE:
            case self::MODULE_DATALOAD_CATEGORYPOSTS17_CAROUSEL:
            case self::MODULE_DATALOAD_CATEGORYPOSTS17_CAROUSEL_CONTENT:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS17_CAROUSEL:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS17_CAROUSEL_CONTENT:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS17_CAROUSEL:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS17_CAROUSEL_CONTENT:
                $cat = POP_CATEGORYPOSTS_CAT_CATEGORYPOSTS17;
                break;

            case self::MODULE_DATALOAD_CATEGORYPOSTS08_SCROLL_NAVIGATOR:
            case self::MODULE_DATALOAD_CATEGORYPOSTS08_SCROLL_ADDONS:
            case self::MODULE_DATALOAD_CATEGORYPOSTS08_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_CATEGORYPOSTS08_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_CATEGORYPOSTS08_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_CATEGORYPOSTS08_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_CATEGORYPOSTS08_SCROLL_LIST:
            case self::MODULE_DATALOAD_CATEGORYPOSTS08_SCROLL_LINE:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS08_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS08_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS08_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS08_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS08_SCROLL_LIST:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS08_SCROLL_LINE:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS08_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS08_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS08_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS08_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS08_SCROLL_LIST:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS08_SCROLL_LINE:
            case self::MODULE_DATALOAD_CATEGORYPOSTS08_CAROUSEL:
            case self::MODULE_DATALOAD_CATEGORYPOSTS08_CAROUSEL_CONTENT:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS08_CAROUSEL:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS08_CAROUSEL_CONTENT:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS08_CAROUSEL:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS08_CAROUSEL_CONTENT:
                $cat = POP_CATEGORYPOSTS_CAT_CATEGORYPOSTS08;
                break;

            case self::MODULE_DATALOAD_CATEGORYPOSTS18_SCROLL_NAVIGATOR:
            case self::MODULE_DATALOAD_CATEGORYPOSTS18_SCROLL_ADDONS:
            case self::MODULE_DATALOAD_CATEGORYPOSTS18_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_CATEGORYPOSTS18_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_CATEGORYPOSTS18_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_CATEGORYPOSTS18_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_CATEGORYPOSTS18_SCROLL_LIST:
            case self::MODULE_DATALOAD_CATEGORYPOSTS18_SCROLL_LINE:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS18_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS18_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS18_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS18_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS18_SCROLL_LIST:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS18_SCROLL_LINE:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS18_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS18_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS18_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS18_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS18_SCROLL_LIST:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS18_SCROLL_LINE:
            case self::MODULE_DATALOAD_CATEGORYPOSTS18_CAROUSEL:
            case self::MODULE_DATALOAD_CATEGORYPOSTS18_CAROUSEL_CONTENT:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS18_CAROUSEL:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS18_CAROUSEL_CONTENT:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS18_CAROUSEL:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS18_CAROUSEL_CONTENT:
                $cat = POP_CATEGORYPOSTS_CAT_CATEGORYPOSTS18;
                break;

            case self::MODULE_DATALOAD_CATEGORYPOSTS09_SCROLL_NAVIGATOR:
            case self::MODULE_DATALOAD_CATEGORYPOSTS09_SCROLL_ADDONS:
            case self::MODULE_DATALOAD_CATEGORYPOSTS09_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_CATEGORYPOSTS09_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_CATEGORYPOSTS09_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_CATEGORYPOSTS09_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_CATEGORYPOSTS09_SCROLL_LIST:
            case self::MODULE_DATALOAD_CATEGORYPOSTS09_SCROLL_LINE:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS09_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS09_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS09_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS09_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS09_SCROLL_LIST:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS09_SCROLL_LINE:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS09_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS09_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS09_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS09_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS09_SCROLL_LIST:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS09_SCROLL_LINE:
            case self::MODULE_DATALOAD_CATEGORYPOSTS09_CAROUSEL:
            case self::MODULE_DATALOAD_CATEGORYPOSTS09_CAROUSEL_CONTENT:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS09_CAROUSEL:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS09_CAROUSEL_CONTENT:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS09_CAROUSEL:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS09_CAROUSEL_CONTENT:
                $cat = POP_CATEGORYPOSTS_CAT_CATEGORYPOSTS09;
                break;

            case self::MODULE_DATALOAD_CATEGORYPOSTS19_SCROLL_NAVIGATOR:
            case self::MODULE_DATALOAD_CATEGORYPOSTS19_SCROLL_ADDONS:
            case self::MODULE_DATALOAD_CATEGORYPOSTS19_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_CATEGORYPOSTS19_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_CATEGORYPOSTS19_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_CATEGORYPOSTS19_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_CATEGORYPOSTS19_SCROLL_LIST:
            case self::MODULE_DATALOAD_CATEGORYPOSTS19_SCROLL_LINE:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS19_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS19_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS19_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS19_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS19_SCROLL_LIST:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS19_SCROLL_LINE:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS19_SCROLL_DETAILS:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS19_SCROLL_SIMPLEVIEW:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS19_SCROLL_FULLVIEW:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS19_SCROLL_THUMBNAIL:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS19_SCROLL_LIST:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS19_SCROLL_LINE:
            case self::MODULE_DATALOAD_CATEGORYPOSTS19_CAROUSEL:
            case self::MODULE_DATALOAD_CATEGORYPOSTS19_CAROUSEL_CONTENT:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS19_CAROUSEL:
            case self::MODULE_DATALOAD_AUTHORCATEGORYPOSTS19_CAROUSEL_CONTENT:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS19_CAROUSEL:
            case self::MODULE_DATALOAD_TAGCATEGORYPOSTS19_CAROUSEL_CONTENT:
                $cat = POP_CATEGORYPOSTS_CAT_CATEGORYPOSTS19;
                break;
        }

        if ($cat) {
            $names = gdGetCategoryname($cat, 'plural-lc');
            $this->setProp([PoP_Module_Processor_DomainFeedbackMessageLayouts::class, PoP_Module_Processor_DomainFeedbackMessageLayouts::MODULE_LAYOUT_FEEDBACKMESSAGE_ITEMLIST], $props, 'pluralname', $names);
        }

        parent::initModelProps($componentVariation, $props);
    }
}



