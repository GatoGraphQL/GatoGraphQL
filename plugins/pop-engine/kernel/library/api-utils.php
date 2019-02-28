<?php
namespace PoP\Engine;

class APIUtils
{
    public static function getEndpoint($url)
    {
        $endpoint = add_query_arg(
            GD_URLPARAM_ACTION,
            POP_ACTION_API,
            add_query_arg(
                GD_URLPARAM_OUTPUT,
                GD_URLPARAM_OUTPUT_JSON,
                add_query_arg(
                    GD_URLPARAM_DATAOUTPUTMODE,
                    GD_URLPARAM_DATAOUTPUTMODE_COMBINED,
                    add_query_arg(
                        GD_URLPARAM_DATABASESOUTPUTMODE,
                        GD_URLPARAM_DATABASESOUTPUTMODE_COMBINED,
                        add_query_arg(
                            GD_URLPARAM_DATAOUTPUTITEMS,
                            implode(
                                POP_CONSTANT_PARAMVALUE_SEPARATOR,
                                array(
                                GD_URLPARAM_DATAOUTPUTITEMS_DATASETMODULESETTINGS,
                                GD_URLPARAM_DATAOUTPUTITEMS_MODULEDATA,
                                GD_URLPARAM_DATAOUTPUTITEMS_DATABASES,
                                )
                            ),
                            $url
                        )
                    )
                )
            )
        );

        if ($mangled = $_REQUEST[GD_URLPARAM_MANGLED]) {
            $endpoint = add_query_arg(
                GD_URLPARAM_MANGLED,
                $mangled,
                $endpoint
            );
        }

        return $endpoint;
    }
}
