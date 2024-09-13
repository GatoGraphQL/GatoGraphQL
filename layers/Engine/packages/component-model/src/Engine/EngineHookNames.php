<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Engine;

class EngineHookNames
{
    public final const ENGINE_ITERATION_START = __CLASS__ . ':engine-iteration-start';
    public final const ENGINE_ITERATION_ON_DATALOADING_COMPONENT = __CLASS__ . ':engine-iteration-on-dataloading-component';
    public final const ENGINE_ITERATION_END = __CLASS__ . ':engine-iteration-end';
    public final const ENTRY_COMPONENT_INITIALIZATION = __CLASS__ . ':entry-component-initialization';
    public final const GENERATE_DATA_BEGINNING = __CLASS__ . ':generate-data:beginning';
    public final const GENERATE_DATA_END = __CLASS__ . ':generate-data:end';
    public final const PROCESS_AND_GENERATE_DATA_HELPER_CALCULATIONS = __CLASS__ . ':process-and-generate-data:helper-calculations';
    public final const ADD_ETAG_HEADER = __CLASS__ . ':add-etag-header';
    public final const ETAG_HEADER_COMMON_CODE = __CLASS__ . ':etag-header:common-code';
    public final const EXTRA_ROUTES = __CLASS__ . ':extra-routes';
    public final const REQUEST_META = __CLASS__ . ':request-meta';
    public final const SESSION_META = __CLASS__ . ':session-meta';
    public final const SITE_META = __CLASS__ . ':site-meta';
    public final const HEADERS = __CLASS__ . ':headers';
    public final const PREPARE_RESPONSE = __CLASS__ . ':prepare-response';
}
