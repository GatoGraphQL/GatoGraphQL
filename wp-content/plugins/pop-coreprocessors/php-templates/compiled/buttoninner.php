<?php
 function lcr59cd19c61832bencq($cx, $var) {
  if ($var instanceof LS) {
   return (string)$var;
  }

  return str_replace(array('=', '`', '&#039;'), array('&#x3D;', '&#x60;', '&#x27;'), htmlspecialchars(lcr59cd19c61832braw($cx, $var), ENT_QUOTES, 'UTF-8'));
 }

 function lcr59cd19c61832bifvar($cx, $v, $zero) {
  return ($v !== null) && ($v !== false) && ($zero || ($v !== 0) && ($v !== 0.0)) && ($v !== '') && (is_array($v) ? (count($v) > 0) : true);
 }

 function lcr59cd19c61832braw($cx, $v, $ex = 0) {
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
      $ret[] = lcr59cd19c61832braw($cx, $vv);
     }
     return join(',', $ret);
    }
   } else {
    return 'Array';
   }
  }

  return "$v";
 }

 function lcr59cd19c61832bhbch($cx, $ch, $vars, $op, &$_this) {
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

  return lcr59cd19c61832bexch($cx, $ch, $vars, $options);
 }

 function lcr59cd19c61832bexch($cx, $ch, $vars, &$options) {
  $args = $vars[0];
  $args[] = $options;
  $e = null;
  $r = true;

  try {
   $r = call_user_func_array($cx['helpers'][$ch], $args);
  } catch (\Exception $E) {
   $e = "Runtime: call custom helper '$ch' error: " . $E->getMessage();
  }

  if($e !== null) {
   lcr59cd19c61832berr($cx, $e);
  }

  return $r;
 }

 function lcr59cd19c61832berr($cx, $err) {
  if ($cx['flags']['debug'] & $cx['constants']['DEBUG_ERROR_LOG']) {
   error_log($err);
   return;
  }
  if ($cx['flags']['debug'] & $cx['constants']['DEBUG_ERROR_EXCEPTION']) {
   throw new \Exception($err);
  }
 }

if (!class_exists("LS")) {
class LS {
 public static $jsContext = array (
  'flags' => 
  array (
    'jstrue' => 1,
    'jsobj' => 1,
  ),
);
    public function __construct($str, $escape = false) {
        $this->string = $escape ? (($escape === 'encq') ? static::encq(static::$jsContext, $str) : static::enc(static::$jsContext, $str)) : $str;
    }
    public function __toString() {
        return $this->string;
    }
    public static function stripExtendedComments($template) {
        return preg_replace(static::EXTENDED_COMMENT_SEARCH, '{{! }}', $template);
    }
    public static function escapeTemplate($template) {
        return addcslashes(addcslashes($template, '\\'), "'");
    }
    public static function raw($cx, $v, $ex = 0) {
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
    public static function enc($cx, $var) {
        return htmlspecialchars(static::raw($cx, $var), ENT_QUOTES, 'UTF-8');
    }
    public static function encq($cx, $var) {
        return str_replace(array('=', '`', '&#039;'), array('&#x3D;', '&#x60;', '&#x27;'), htmlspecialchars(static::raw($cx, $var), ENT_QUOTES, 'UTF-8'));
    }
}
}
return function ($in = null, $options = null) {
    $helpers = array(            'get' => function($db, $index, $options) { 

		global $pop_serverside_helpers;
		return $pop_serverside_helpers->get($db, $index, $options);
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
    ob_start();echo '<',lcr59cd19c61832bencq($cx, (($inary && isset($in['tag'])) ? $in['tag'] : null)),' class="btn-inner ',lcr59cd19c61832bencq($cx, (($inary && isset($in['class'])) ? $in['class'] : null)),'" style="',lcr59cd19c61832bencq($cx, (($inary && isset($in['style'])) ? $in['style'] : null)),'">
	';if (lcr59cd19c61832bifvar($cx, (($inary && isset($in['fontawesome'])) ? $in['fontawesome'] : null), false)){echo '<i class="fa ',lcr59cd19c61832bencq($cx, (($inary && isset($in['fontawesome'])) ? $in['fontawesome'] : null)),'"></i>';}else{echo '';}echo '<span class="pop-btn-title ',lcr59cd19c61832bencq($cx, ((isset($in['classes']) && is_array($in['classes']) && isset($in['classes']['btn-title'])) ? $in['classes']['btn-title'] : null)),'" style="',lcr59cd19c61832bencq($cx, ((isset($in['styles']) && is_array($in['styles']) && isset($in['styles']['btn-title'])) ? $in['styles']['btn-title'] : null)),'">',lcr59cd19c61832braw($cx, ((isset($in['titles']) && is_array($in['titles']) && isset($in['titles']['btn'])) ? $in['titles']['btn'] : null)),'';if (lcr59cd19c61832bifvar($cx, (($inary && isset($in['text-field'])) ? $in['text-field'] : null), false)){echo ' ',lcr59cd19c61832braw($cx, (($inary && isset($in['textfield-open'])) ? $in['textfield-open'] : null)),'<span class="',lcr59cd19c61832bencq($cx, (($inary && isset($in['itemDBKey'])) ? $in['itemDBKey'] : null)),'-',lcr59cd19c61832bencq($cx, (($inary && isset($in['text-field'])) ? $in['text-field'] : null)),'-',lcr59cd19c61832bencq($cx, ((isset($in['itemObject']) && is_array($in['itemObject']) && isset($in['itemObject']['id'])) ? $in['itemObject']['id'] : null)),' ',lcr59cd19c61832bencq($cx, ((isset($in['classes']) && is_array($in['classes']) && isset($in['classes']['text-field'])) ? $in['classes']['text-field'] : null)),'" style="',lcr59cd19c61832bencq($cx, ((isset($in['styles']) && is_array($in['styles']) && isset($in['styles']['text-field'])) ? $in['styles']['text-field'] : null)),'">',lcr59cd19c61832braw($cx, lcr59cd19c61832bhbch($cx, 'get', array(array((($inary && isset($in['itemObject'])) ? $in['itemObject'] : null),(($inary && isset($in['text-field'])) ? $in['text-field'] : null)),array()), 'raw', $in)),'</span>',lcr59cd19c61832braw($cx, (($inary && isset($in['textfield-close'])) ? $in['textfield-close'] : null)),'';}else{echo '';}echo '</span>
</',lcr59cd19c61832bencq($cx, (($inary && isset($in['tag'])) ? $in['tag'] : null)),'>';return ob_get_clean();
};
?>