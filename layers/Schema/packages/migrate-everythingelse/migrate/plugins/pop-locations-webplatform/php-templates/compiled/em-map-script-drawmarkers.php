<?php
function lcr5bbdeb66d6edfhbch($cx, $ch, $vars, $op, &$_this)
{
    if (isset($cx['blparam'][0][$ch])) {
        return $cx['blparam'][0][$ch];
    }

    $options = array(
        'name' => $ch,
        'hash' => $vars[1],
        'contexts' => count($cx['scopes']) ? $cx['scopes'] : array(null),
        'fn.blockParams' => 0,
        '_this' => &$_this
    );

    if ($cx['flags']['spvar']) {
        $options['data'] = $cx['sp_vars'];
    }

    return lcr5bbdeb66d6edfexch($cx, $ch, $vars, $options);
}

function lcr5bbdeb66d6edfencq($cx, $var)
{
    if ($var instanceof LS) {
        return (string)$var;
    }

    return str_replace(array('=', '`', '&#039;'), array('&#x3D;', '&#x60;', '&#x27;'), htmlspecialchars(lcr5bbdeb66d6edfraw($cx, $var), ENT_QUOTES, 'UTF-8'));
}

function lcr5bbdeb66d6edfexch($cx, $ch, $vars, &$options)
{
    $args = $vars[0];
    $args[] = $options;
    $e = null;
    $r = true;

    try {
        $r = call_user_func_array($cx['helpers'][$ch], $args);
    } catch (\Exception $E) {
        $e = "Runtime: call custom helper '$ch' error: " . $E->getMessage();
    }

    if ($e !== null) {
        lcr5bbdeb66d6edferr($cx, $e);
    }

    return $r;
}

function lcr5bbdeb66d6edfraw($cx, $v, $ex = 0)
{
    if ($ex) {
        return $v;
    }

    if ($v === true) {
        if ($cx['flags']['jstrue']) {
            return 'true';
        }
    }

    if (($v === false)) {
        if ($cx['flags']['jstrue']) {
            return 'false';
        }
    }

    if (is_array($v)) {
        if ($cx['flags']['jsobj']) {
            if (count(array_diff_key($v, array_keys(array_keys($v)))) > 0) {
                return '[object Object]';
            } else {
                $ret = array();
                foreach ($v as $k => $vv) {
                    $ret[] = lcr5bbdeb66d6edfraw($cx, $vv);
                }
                return join(',', $ret);
            }
        } else {
            return 'Array';
        }
    }

    return "$v";
}

function lcr5bbdeb66d6edferr($cx, $err)
{
    if ($cx['flags']['debug'] & $cx['constants']['DEBUG_ERROR_LOG']) {
        error_log($err);
        return;
    }
    if ($cx['flags']['debug'] & $cx['constants']['DEBUG_ERROR_EXCEPTION']) {
        throw new \Exception($err);
    }
}

if (!class_exists("LS")) {
    class LS
    {
        public static $jsContext = array(
            'flags' =>
            array(
                'jstrue' => 1,
                'jsobj' => 1,
            ),
        );
        public function __construct($str, $escape = false)
        {
            $this->string = $escape ? (($escape === 'encq') ? static::encq(static::$jsContext, $str) : static::enc(static::$jsContext, $str)) : $str;
        }
        public function tostring()
        {
            return $this->string;
        }
        public static function stripExtendedComments($template)
        {
            return preg_replace(static::EXTENDED_COMMENT_SEARCH, '{{! }}', $template);
        }
        public static function escapeTemplate($template)
        {
            return addcslashes(addcslashes($template, '\\'), "'");
        }
        public static function raw($cx, $v, $ex = 0)
        {
            if ($ex) {
                return $v;
            }

            if ($v === true) {
                if ($cx['flags']['jstrue']) {
                    return 'true';
                }
            }

            if (($v === false)) {
                if ($cx['flags']['jstrue']) {
                    return 'false';
                }
            }

            if (is_array($v)) {
                if ($cx['flags']['jsobj']) {
                    if (count(array_diff_key($v, array_keys(array_keys($v)))) > 0) {
                        return '[object Object]';
                    } else {
                        $ret = array();
                        foreach ($v as $k => $vv) {
                            $ret[] = static::raw($cx, $vv);
                        }
                        return join(',', $ret);
                    }
                } else {
                    return 'Array';
                }
            }

            return "$v";
        }
        public static function enc($cx, $var)
        {
            return htmlspecialchars(static::raw($cx, $var), ENT_QUOTES, 'UTF-8');
        }
        public static function encq($cx, $var)
        {
            return str_replace(array('=', '`', '&#039;'), array('&#x3D;', '&#x60;', '&#x27;'), htmlspecialchars(static::raw($cx, $var), ENT_QUOTES, 'UTF-8'));
        }
    }
}
return function ($in = null, $options = null) {
    $helpers = array(            'lastGeneratedId' => function ($options) {
        global $pop_serverside_kernelhelpers;
        return $pop_serverside_kernelhelpers->lastGeneratedId($options);
    },
    );
    $partials = array();
    $cx = array(
        'flags' => array(
            'jstrue' => true,
            'jsobj' => true,
            'jslen' => true,
            'spvar' => true,
            'prop' => false,
            'method' => false,
            'lambda' => false,
            'mustlok' => false,
            'mustlam' => false,
            'mustsec' => false,
            'echo' => true,
            'partnc' => false,
            'knohlp' => false,
            'debug' => isset($options['debug']) ? $options['debug'] : 1,
        ),
        'constants' =>  array(
            'DEBUG_ERROR_LOG' => 1,
            'DEBUG_ERROR_EXCEPTION' => 2,
            'DEBUG_TAGS' => 4,
            'DEBUG_TAGS_ANSI' => 12,
            'DEBUG_TAGS_HTML' => 20,
        ),
        'helpers' => isset($options['helpers']) ? array_merge($helpers, $options['helpers']) : $helpers,
        'partials' => isset($options['partials']) ? array_merge($partials, $options['partials']) : $partials,
        'scopes' => array(),
        'sp_vars' => isset($options['data']) ? array_merge(array('root' => $in), $options['data']) : array('root' => $in),
        'blparam' => array(),
        'partialid' => 0,
        'runtime' => '\LightnCandy\Runtime',
    );
    
    $inary=is_array($in);
    ob_start();
    echo '<script type="text/javascript">
(function($){
	$(document).one(\'module:merged\', function() {

		var mapDiv = $(\'#',lcr5bbdeb66d6edfencq($cx, lcr5bbdeb66d6edfhbch($cx, 'lastGeneratedId', array(array(),array('module'=>(($inary && isset($in['mapdiv-module'])) ? $in['mapdiv-module'] : null),'domain'=>((isset($in['bs']) && is_array($in['bs']) && isset($in['bs']['toplevel-domain'])) ? $in['bs']['toplevel-domain'] : null))), 'encq', $in)),'\');
		var block = pop.Manager.getBlock(mapDiv);
		var pageSection = pop.Manager.getPageSection(block);

		pop.MapRuntime.drawMarkers("',lcr5bbdeb66d6edfencq($cx, ((isset($in['tls']) && is_array($in['tls']) && isset($in['tls']['domain'])) ? $in['tls']['domain'] : null)),'", pageSection, block, mapDiv);
	});
})(jQuery);
</script>';
    return ob_get_clean();
};
