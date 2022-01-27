<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

define('POP_ID_ENTRY', 'main');
// define ('POP_ID_JSON', 'json');

define('GD_STRING_MORE', TranslationAPIFacade::getInstance()->__('more...', 'pop-engine-htmlcssplatform'));
define('GD_STRING_LESS', TranslationAPIFacade::getInstance()->__('less...', 'pop-engine-htmlcssplatform'));
define('GD_CONSTANT_LOADING_MSG', TranslationAPIFacade::getInstance()->__('Loading', 'pop-coreprocessors'));
define('GD_CONSTANT_LOADING_SPINNER', '<i class="fa fa-fw fa-spinner fa-spin"></i>');
define('POP_LOADING_MSG', GD_CONSTANT_LOADING_SPINNER.' '.GD_CONSTANT_LOADING_MSG);
define('GD_FORM_INPUT', 'forminput');

