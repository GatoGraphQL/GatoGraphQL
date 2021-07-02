<?php

trait PoPWebPlatform_AAL_Module_SettingsProcessor_Trait
{
    public function silentDocument()
    {
        return array(
            POP_NOTIFICATIONS_ROUTE_NOTIFICATIONS_MARKALLASREAD => true,
            POP_NOTIFICATIONS_ROUTE_NOTIFICATIONS_MARKASREAD => true,
            POP_NOTIFICATIONS_ROUTE_NOTIFICATIONS_MARKASUNREAD => true,
        );
    }
}
