<?php
namespace PoP\TrendingTags;

trait SettingsProcessor_Trait
{
    public function routesToProcess()
    {
        return array_filter(
            array(
                POP_TRENDINGTAGS_ROUTE_TRENDINGTAGS,
            )
        );
    }
}
