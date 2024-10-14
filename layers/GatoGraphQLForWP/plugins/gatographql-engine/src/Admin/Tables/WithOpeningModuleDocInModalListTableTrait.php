<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Admin\Tables;

use GatoGraphQL\GatoGraphQL\Constants\RequestParams;

trait WithOpeningModuleDocInModalListTableTrait
{
    protected function getOpeningModuleDocInModalLinkURL(
        string $page,
        string $module,
    ): string {
        return \admin_url(sprintf(
            'admin.php?page=%s&%s=%s&%s=%s&TB_iframe=true&width=600&height=550',
            $page,
            RequestParams::TAB,
            RequestParams::TAB_DOCS,
            RequestParams::MODULE,
            urlencode($module)
        ));
    }
}
