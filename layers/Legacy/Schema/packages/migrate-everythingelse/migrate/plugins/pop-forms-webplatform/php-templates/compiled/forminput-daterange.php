<?php
function lcr5bbdeb5aa9ebbhbbch($cx, $ch, $vars, &$_this, $inverted, $cb, $else = null)
{
    $options = array(
        'name' => $ch,
        'hash' => $vars[1],
        'contexts' => count($cx['scopes']) ? $cx['scopes'] : array(null),
        'fn.blockParams' => 0,
        '_this' => &$_this,
    );

    if ($cx['flags']['spvar']) {
        $options['data'] = $cx['sp_vars'];
    }

    if (isset($vars[2])) {
        $options['fn.blockParams'] = count($vars[2]);
    }

    // $invert the logic
    if ($inverted) {
        $tmp = $else;
        $else = $cb;
        $cb = $tmp;
    }

    $options['fn'] = function ($context = '_NO_INPUT_HERE_', $data = null) use ($cx, &$_this, $cb, $options, $vars) {
        if ($cx['flags']['echo']) {
            ob_start();
        }
        if (isset($data['data'])) {
            $old_spvar = $cx['sp_vars'];
            $cx['sp_vars'] = array_merge(array('root' => $old_spvar['root']), $data['data'], array('_parent' => $old_spvar));
        }
        $ex = false;
        if (isset($data['blockParams']) && isset($vars[2])) {
            $ex = array_combine($vars[2], array_slice($data['blockParams'], 0, count($vars[2])));
            array_unshift($cx['blparam'], $ex);
        } elseif (isset($cx['blparam'][0])) {
            $ex = $cx['blparam'][0];
        }
        if (($context === '_NO_INPUT_HERE_') || ($context === $_this)) {
            $ret = $cb($cx, is_array($ex) ? lcr5bbdeb5aa9ebbm($cx, $_this, $ex) : $_this);
        } else {
            $cx['scopes'][] = $_this;
            $ret = $cb($cx, is_array($ex) ? lcr5bbdeb5aa9ebbm($cx, $context, $ex) : $context);
            array_pop($cx['scopes']);
        }
        if (isset($data['data'])) {
            $cx['sp_vars'] = $old_spvar;
        }
        return $cx['flags']['echo'] ? ob_get_clean() : $ret;
    };

    if ($else) {
        $options['inverse'] = function ($context = '_NO_INPUT_HERE_') use ($cx, $_this, $else) {
            if ($cx['flags']['echo']) {
                ob_start();
            }
            if ($context === '_NO_INPUT_HERE_') {
                $ret = $else($cx, $_this);
            } else {
                $cx['scopes'][] = $_this;
                $ret = $else($cx, $context);
                array_pop($cx['scopes']);
            }
            return $cx['flags']['echo'] ? ob_get_clean() : $ret;
        };
    } else {
        $options['inverse'] = function () {
            return '';
        };
    }

        return lcr5bbdeb5aa9ebbexch($cx, $ch, $vars, $options);
}

function lcr5bbdeb5aa9ebbencq($cx, $var)
{
    if ($var instanceof LS) {
        return (string)$var;
    }

    return str_replace(array('=', '`', '&#039;'), array('&#x3D;', '&#x60;', '&#x27;'), htmlspecialchars(lcr5bbdeb5aa9ebbraw($cx, $var), ENT_QUOTES, 'UTF-8'));
}

function lcr5bbdeb5aa9ebbhbch($cx, $ch, $vars, $op, &$_this)
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

    return lcr5bbdeb5aa9ebbexch($cx, $ch, $vars, $options);
}

function lcr5bbdeb5aa9ebbifvar($cx, $v, $zero)
{
    return ($v !== null) && ($v !== false) && ($zero || ($v !== 0) && ($v !== 0.0)) && ($v !== '') && (is_array($v) ? (count($v) > 0) : true);
}

function lcr5bbdeb5aa9ebbm($cx, $a, $b)
{
    if (is_array($b)) {
        if ($a === null) {
            return $b;
        } elseif (is_array($a)) {
            return array_merge($a, $b);
        } elseif ($cx['flags']['method'] || $cx['flags']['prop']) {
            if (!is_object($a)) {
                $a = new StringObject($a);
            }
            foreach ($b as $i => $v) {
                $a->$i = $v;
            }
        }
    }
    return $a;
}

function lcr5bbdeb5aa9ebbexch($cx, $ch, $vars, &$options)
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
        lcr5bbdeb5aa9ebberr($cx, $e);
    }

    return $r;
}

function lcr5bbdeb5aa9ebbraw($cx, $v, $ex = 0)
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
                    $ret[] = lcr5bbdeb5aa9ebbraw($cx, $vv);
                }
                return join(',', $ret);
            }
        } else {
            return 'Array';
        }
    }

    return "$v";
}

function lcr5bbdeb5aa9ebberr($cx, $err)
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
    $helpers = array(            'generateId' => function ($options) {
        global $pop_serverside_kernelhelpers;
        return $pop_serverside_kernelhelpers->generateId($options);
    },
        'formcomponentValue' => function ($value, $dbObject, $dbObjectField, $defaultValue, $options) {
            global $pop_serverside_formcomponentshelpers;
            return $pop_serverside_formcomponentshelpers->formcomponentValue($value, $dbObject, $dbObjectField, $defaultValue, $options);
        },
        'formatValue' => function ($value, $format, $options) {
            global $pop_serverside_formatvaluehelpers;
            return $pop_serverside_formatvaluehelpers->formatValue($value, $format, $options);
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
    echo '<input ',lcr5bbdeb5aa9ebbhbbch($cx, 'generateId', array(array(),array()), $in, false, function ($cx, $in) {
        $inary=is_array($in);
        echo '',lcr5bbdeb5aa9ebbencq($cx, (($inary && isset($in['id'])) ? $in['id'] : null)),'';
    }),' type="text" value="',lcr5bbdeb5aa9ebbencq($cx, lcr5bbdeb5aa9ebbhbch($cx, 'formatValue', array(array(lcr5bbdeb5aa9ebbhbch($cx, 'formcomponentValue', array(array((($inary && isset($in['value'])) ? $in['value'] : null),(($inary && isset($in['dbObject'])) ? $in['dbObject'] : null),(($inary && isset($in['dbobject-field'])) ? $in['dbobject-field'] : null),(($inary && isset($in['default-value'])) ? $in['default-value'] : null)),array('subfields'=>((isset($in['subfields']) && is_array($in['subfields']) && isset($in['subfields']['readable'])) ? $in['subfields']['readable'] : null))), 'raw', $in),(($inary && isset($in['value-format'])) ? $in['value-format'] : null)),array()), 'encq', $in)),'" class="form-control ',lcr5bbdeb5aa9ebbencq($cx, (($inary && isset($in['class'])) ? $in['class'] : null)),' ',lcr5bbdeb5aa9ebbencq($cx, (($inary && isset($in['timepicker'])) ? $in['timepicker'] : null)),'" style="',lcr5bbdeb5aa9ebbencq($cx, (($inary && isset($in['style'])) ? $in['style'] : null)),'" placeholder="',lcr5bbdeb5aa9ebbencq($cx, (($inary && isset($in['placeholder'])) ? $in['placeholder'] : null)),'">
<input type="hidden" value="',lcr5bbdeb5aa9ebbencq($cx, lcr5bbdeb5aa9ebbhbch($cx, 'formatValue', array(array(lcr5bbdeb5aa9ebbhbch($cx, 'formcomponentValue', array(array((($inary && isset($in['value'])) ? $in['value'] : null),(($inary && isset($in['dbObject'])) ? $in['dbObject'] : null),(($inary && isset($in['dbobject-field'])) ? $in['dbobject-field'] : null),(($inary && isset($in['default-value'])) ? $in['default-value'] : null)),array('subfields'=>((isset($in['subfields']) && is_array($in['subfields']) && isset($in['subfields']['from'])) ? $in['subfields']['from'] : null))), 'raw', $in),(($inary && isset($in['value-format'])) ? $in['value-format'] : null)),array()), 'encq', $in)),'" name="',lcr5bbdeb5aa9ebbencq($cx, ((isset($in['name']) && is_array($in['name']) && isset($in['name']['from'])) ? $in['name']['from'] : null)),'" class="',lcr5bbdeb5aa9ebbencq($cx, ((isset($in['classes']) && is_array($in['classes']) && isset($in['classes']['input'])) ? $in['classes']['input'] : null)),' from" style="',lcr5bbdeb5aa9ebbencq($cx, ((isset($in['styles']) && is_array($in['styles']) && isset($in['styles']['input'])) ? $in['styles']['input'] : null)),'">
<input type="hidden" value="',lcr5bbdeb5aa9ebbencq($cx, lcr5bbdeb5aa9ebbhbch($cx, 'formatValue', array(array(lcr5bbdeb5aa9ebbhbch($cx, 'formcomponentValue', array(array((($inary && isset($in['value'])) ? $in['value'] : null),(($inary && isset($in['dbObject'])) ? $in['dbObject'] : null),(($inary && isset($in['dbobject-field'])) ? $in['dbobject-field'] : null),(($inary && isset($in['default-value'])) ? $in['default-value'] : null)),array('subfields'=>((isset($in['subfields']) && is_array($in['subfields']) && isset($in['subfields']['to'])) ? $in['subfields']['to'] : null))), 'raw', $in),(($inary && isset($in['value-format'])) ? $in['value-format'] : null)),array()), 'encq', $in)),'" name="',lcr5bbdeb5aa9ebbencq($cx, ((isset($in['name']) && is_array($in['name']) && isset($in['name']['to'])) ? $in['name']['to'] : null)),'" class="',lcr5bbdeb5aa9ebbencq($cx, ((isset($in['classes']) && is_array($in['classes']) && isset($in['classes']['input'])) ? $in['classes']['input'] : null)),' to" style="',lcr5bbdeb5aa9ebbencq($cx, ((isset($in['styles']) && is_array($in['styles']) && isset($in['styles']['input'])) ? $in['styles']['input'] : null)),'">
';
    if (lcr5bbdeb5aa9ebbifvar($cx, (($inary && isset($in['timepicker'])) ? $in['timepicker'] : null), false)) {
        echo '	<input type="hidden" value="',lcr5bbdeb5aa9ebbencq($cx, lcr5bbdeb5aa9ebbhbch($cx, 'formatValue', array(array(lcr5bbdeb5aa9ebbhbch($cx, 'formcomponentValue', array(array((($inary && isset($in['value'])) ? $in['value'] : null),(($inary && isset($in['dbObject'])) ? $in['dbObject'] : null),(($inary && isset($in['dbobject-field'])) ? $in['dbobject-field'] : null),(($inary && isset($in['default-value'])) ? $in['default-value'] : null)),array('subfields'=>((isset($in['subfields']) && is_array($in['subfields']) && isset($in['subfields']['fromtime'])) ? $in['subfields']['fromtime'] : null))), 'raw', $in),(($inary && isset($in['value-format'])) ? $in['value-format'] : null)),array()), 'encq', $in)),'" name="',lcr5bbdeb5aa9ebbencq($cx, ((isset($in['name']) && is_array($in['name']) && isset($in['name']['fromtime'])) ? $in['name']['fromtime'] : null)),'" class="',lcr5bbdeb5aa9ebbencq($cx, ((isset($in['classes']) && is_array($in['classes']) && isset($in['classes']['input'])) ? $in['classes']['input'] : null)),' fromtime" style="',lcr5bbdeb5aa9ebbencq($cx, ((isset($in['styles']) && is_array($in['styles']) && isset($in['styles']['input'])) ? $in['styles']['input'] : null)),'">
	<input type="hidden" value="',lcr5bbdeb5aa9ebbencq($cx, lcr5bbdeb5aa9ebbhbch($cx, 'formatValue', array(array(lcr5bbdeb5aa9ebbhbch($cx, 'formcomponentValue', array(array((($inary && isset($in['value'])) ? $in['value'] : null),(($inary && isset($in['dbObject'])) ? $in['dbObject'] : null),(($inary && isset($in['dbobject-field'])) ? $in['dbobject-field'] : null),(($inary && isset($in['default-value'])) ? $in['default-value'] : null)),array('subfields'=>((isset($in['subfields']) && is_array($in['subfields']) && isset($in['subfields']['totime'])) ? $in['subfields']['totime'] : null))), 'raw', $in),(($inary && isset($in['value-format'])) ? $in['value-format'] : null)),array()), 'encq', $in)),'" name="',lcr5bbdeb5aa9ebbencq($cx, ((isset($in['name']) && is_array($in['name']) && isset($in['name']['totime'])) ? $in['name']['totime'] : null)),'" class="',lcr5bbdeb5aa9ebbencq($cx, ((isset($in['classes']) && is_array($in['classes']) && isset($in['classes']['input'])) ? $in['classes']['input'] : null)),' totime" style="',lcr5bbdeb5aa9ebbencq($cx, ((isset($in['styles']) && is_array($in['styles']) && isset($in['styles']['input'])) ? $in['styles']['input'] : null)),'">
';
    } else {
        echo '';
    }
    echo '';
    return ob_get_clean();
};
