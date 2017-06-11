<?php
 function lcr593c9d16c0648wi($cx, $v, $bp, $in, $cb, $else = null) {
  if (isset($bp[0])) {
   $v = lcr593c9d16c0648m($cx, $v, array($bp[0] => $v));
  }
  if (($v === false) || ($v === null) || (is_array($v) && (count($v) === 0))) {
   return $else ? $else($cx, $in) : '';
  }
  if ($v === $in) {
   $ret = $cb($cx, $v);
  } else {
   $cx['scopes'][] = $in;
   $ret = $cb($cx, $v);
   array_pop($cx['scopes']);
  }
  return $ret;
 }

 function lcr593c9d16c0648encq($cx, $var) {
  if ($var instanceof LS) {
   return (string)$var;
  }

  return str_replace(array('=', '`', '&#039;'), array('&#x3D;', '&#x60;', '&#x27;'), htmlspecialchars(lcr593c9d16c0648raw($cx, $var), ENT_QUOTES, 'UTF-8'));
 }

 function lcr593c9d16c0648hbbch($cx, $ch, $vars, &$_this, $inverted, $cb, $else = null) {
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
   } else if (isset($cx['blparam'][0])) {
    $ex = $cx['blparam'][0];
   }
   if (($context === '_NO_INPUT_HERE_') || ($context === $_this)) {
    $ret = $cb($cx, is_array($ex) ? lcr593c9d16c0648m($cx, $_this, $ex) : $_this);
   } else {
    $cx['scopes'][] = $_this;
    $ret = $cb($cx, is_array($ex) ? lcr593c9d16c0648m($cx, $context, $ex) : $context);
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

  return lcr593c9d16c0648exch($cx, $ch, $vars, $options);
 }

 function lcr593c9d16c0648hbch($cx, $ch, $vars, $op, &$_this) {
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

  return lcr593c9d16c0648exch($cx, $ch, $vars, $options);
 }

 function lcr593c9d16c0648ifvar($cx, $v, $zero) {
  return ($v !== null) && ($v !== false) && ($zero || ($v !== 0) && ($v !== 0.0)) && ($v !== '') && (is_array($v) ? (count($v) > 0) : true);
 }

 function lcr593c9d16c0648sec($cx, $v, $bp, $in, $each, $cb, $else = null) {
  $push = ($in !== $v) || $each;

  $isAry = is_array($v) || ($v instanceof \ArrayObject);
  $isTrav = $v instanceof \Traversable;
  $loop = $each;
  $keys = null;
  $last = null;
  $isObj = false;

  if ($isAry && $else !== null && count($v) === 0) {
   $ret = $else($cx, $in);
   return $ret;
  }

  // #var, detect input type is object or not
  if (!$loop && $isAry) {
   $keys = array_keys($v);
   $loop = (count(array_diff_key($v, array_keys($keys))) == 0);
   $isObj = !$loop;
  }

  if (($loop && $isAry) || $isTrav) {
   if ($each && !$isTrav) {
    // Detect input type is object or not when never done once
    if ($keys == null) {
     $keys = array_keys($v);
     $isObj = (count(array_diff_key($v, array_keys($keys))) > 0);
    }
   }
   $ret = array();
   if ($push) {
    $cx['scopes'][] = $in;
   }
   $i = 0;
   if ($cx['flags']['spvar']) {
    $old_spvar = $cx['sp_vars'];
    $cx['sp_vars'] = array_merge(array('root' => $old_spvar['root']), $old_spvar, array('_parent' => $old_spvar));
    if (!$isTrav) {
     $last = count($keys) - 1;
    }
   }

   $isSparceArray = $isObj && (count(array_filter(array_keys($v), 'is_string')) == 0);
   foreach ($v as $index => $raw) {
    if ($cx['flags']['spvar']) {
     $cx['sp_vars']['first'] = ($i === 0);
     $cx['sp_vars']['last'] = ($i == $last);
     $cx['sp_vars']['key'] = $index;
     $cx['sp_vars']['index'] = $isSparceArray ? $index : $i;
     $i++;
    }
    if (isset($bp[0])) {
     $raw = lcr593c9d16c0648m($cx, $raw, array($bp[0] => $raw));
    }
    if (isset($bp[1])) {
     $raw = lcr593c9d16c0648m($cx, $raw, array($bp[1] => $cx['sp_vars']['index']));
    }
    $ret[] = $cb($cx, $raw);
   }
   if ($cx['flags']['spvar']) {
    if ($isObj) {
     unset($cx['sp_vars']['key']);
    } else {
     unset($cx['sp_vars']['last']);
    }
    unset($cx['sp_vars']['index']);
    unset($cx['sp_vars']['first']);
    $cx['sp_vars'] = $old_spvar;
   }
   if ($push) {
    array_pop($cx['scopes']);
   }
   return join('', $ret);
  }
  if ($each) {
   if ($else !== null) {
    $ret = $else($cx, $v);
    return $ret;
   }
   return '';
  }
  if ($isAry) {
   if ($push) {
    $cx['scopes'][] = $in;
   }
   $ret = $cb($cx, $v);
   if ($push) {
    array_pop($cx['scopes']);
   }
   return $ret;
  }

  if ($v === true) {
   return $cb($cx, $in);
  }

  if (($v !== null) && ($v !== false)) {
   return $cb($cx, $v);
  }

  if ($else !== null) {
   $ret = $else($cx, $in);
   return $ret;
  }

  return '';
 }

 function lcr593c9d16c0648raw($cx, $v, $ex = 0) {
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
      $ret[] = lcr593c9d16c0648raw($cx, $vv);
     }
     return join(',', $ret);
    }
   } else {
    return 'Array';
   }
  }

  return "$v";
 }

 function lcr593c9d16c0648m($cx, $a, $b) {
  if (is_array($b)) {
   if ($a === null) {
    return $b;
   } else if (is_array($a)) {
    return array_merge($a, $b);
   } else if (($cx['flags']['method'] || $cx['flags']['prop']) && is_object($a)) {
    foreach ($b as $i => $v) {
     $a->$i = $v;
    }
   }
  }
  return $a;
 }

 function lcr593c9d16c0648exch($cx, $ch, $vars, &$options) {
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
   lcr593c9d16c0648err($cx, $e);
  }

  return $r;
 }

 function lcr593c9d16c0648err($cx, $err) {
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
            'generateId' => function($options) { 

		global $pop_serverside_helpers;
		return $pop_serverside_helpers->generateId($options);
	},
            'enterModule' => function($prevContext, $options){ 

		global $pop_serverside_helpers;
		return $pop_serverside_helpers->enterModule($prevContext, $options);
	},
            'withModule' => function($context, $module, $options) { 

		global $pop_serverside_helpers;
		return $pop_serverside_helpers->withModule($context, $module, $options);
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
    ob_start();echo '',lcr593c9d16c0648wi($cx, (($inary && isset($in['itemObject'])) ? $in['itemObject'] : null), null, $in, function($cx, $in) {$inary=is_array($in);echo '	<div class="layout user-layout preview ',lcr593c9d16c0648encq($cx, ((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]) && isset($cx['scopes'][count($cx['scopes'])-1]['class'])) ? $cx['scopes'][count($cx['scopes'])-1]['class'] : null)),'" ',lcr593c9d16c0648hbbch($cx, 'generateId', array(array(),array('context'=>$cx['scopes'][count($cx['scopes'])-1])), $in, false, function($cx, $in) {$inary=is_array($in);echo '',lcr593c9d16c0648encq($cx, ((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]) && isset($cx['scopes'][count($cx['scopes'])-1]['id'])) ? $cx['scopes'][count($cx['scopes'])-1]['id'] : null)),'';}),'>
',lcr593c9d16c0648hbbch($cx, 'withModule', array(array($cx['scopes'][count($cx['scopes'])-1],'quicklinkgroup-top'),array()), $in, false, function($cx, $in) {$inary=is_array($in);echo '			<div class="quicklinkgroup quicklinkgroup-top ',lcr593c9d16c0648encq($cx, ((isset($cx['scopes'][count($cx['scopes'])-2]) && is_array($cx['scopes'][count($cx['scopes'])-2]['classes']) && isset($cx['scopes'][count($cx['scopes'])-2]['classes']['quicklinkgroup-top'])) ? $cx['scopes'][count($cx['scopes'])-2]['classes']['quicklinkgroup-top'] : null)),'">
				',lcr593c9d16c0648encq($cx, lcr593c9d16c0648hbch($cx, 'enterModule', array(array($cx['scopes'][count($cx['scopes'])-2]),array()), 'encq', $in)),'
			</div>
';}),'		<div class="wrapper ',lcr593c9d16c0648encq($cx, ((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]['classes']) && isset($cx['scopes'][count($cx['scopes'])-1]['classes']['wrapper'])) ? $cx['scopes'][count($cx['scopes'])-1]['classes']['wrapper'] : null)),'">
			<div class="',lcr593c9d16c0648encq($cx, ((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]['classes']) && isset($cx['scopes'][count($cx['scopes'])-1]['classes']['avatar-wrapper'])) ? $cx['scopes'][count($cx['scopes'])-1]['classes']['avatar-wrapper'] : null)),'">
',lcr593c9d16c0648hbbch($cx, 'withModule', array(array($cx['scopes'][count($cx['scopes'])-1],'useravatar'),array()), $in, false, function($cx, $in) {$inary=is_array($in);echo '					<div class="avatar ',lcr593c9d16c0648encq($cx, ((isset($cx['scopes'][count($cx['scopes'])-2]) && is_array($cx['scopes'][count($cx['scopes'])-2]['classes']) && isset($cx['scopes'][count($cx['scopes'])-2]['classes']['avatar'])) ? $cx['scopes'][count($cx['scopes'])-2]['classes']['avatar'] : null)),'">
						',lcr593c9d16c0648encq($cx, lcr593c9d16c0648hbch($cx, 'enterModule', array(array($cx['scopes'][count($cx['scopes'])-2]),array()), 'encq', $in)),'

';if (lcr593c9d16c0648ifvar($cx, ((isset($cx['scopes'][count($cx['scopes'])-2]) && is_array($cx['scopes'][count($cx['scopes'])-2]['template-ids']) && isset($cx['scopes'][count($cx['scopes'])-2]['template-ids']['avatar-extras'])) ? $cx['scopes'][count($cx['scopes'])-2]['template-ids']['avatar-extras'] : null), false)){echo '							<div class="avatar-extras">
',lcr593c9d16c0648sec($cx, ((isset($cx['scopes'][count($cx['scopes'])-2]) && is_array($cx['scopes'][count($cx['scopes'])-2]['template-ids']) && isset($cx['scopes'][count($cx['scopes'])-2]['template-ids']['avatar-extras'])) ? $cx['scopes'][count($cx['scopes'])-2]['template-ids']['avatar-extras'] : null), null, $in, true, function($cx, $in) {$inary=is_array($in);echo '',lcr593c9d16c0648hbbch($cx, 'withModule', array(array($cx['scopes'][count($cx['scopes'])-3],$in),array()), $in, false, function($cx, $in) {$inary=is_array($in);echo '										',lcr593c9d16c0648encq($cx, lcr593c9d16c0648hbch($cx, 'enterModule', array(array($cx['scopes'][count($cx['scopes'])-4]),array()), 'encq', $in)),'
';}),'';}),'							</div>
';}else{echo '';}echo '					</div>
';}),'';if (lcr593c9d16c0648ifvar($cx, ((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]['template-ids']) && isset($cx['scopes'][count($cx['scopes'])-1]['template-ids']['belowavatar'])) ? $cx['scopes'][count($cx['scopes'])-1]['template-ids']['belowavatar'] : null), false)){echo '					<div class="extra ',lcr593c9d16c0648encq($cx, ((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]['classes']) && isset($cx['scopes'][count($cx['scopes'])-1]['classes']['belowavatar'])) ? $cx['scopes'][count($cx['scopes'])-1]['classes']['belowavatar'] : null)),'">
',lcr593c9d16c0648sec($cx, ((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]['template-ids']) && isset($cx['scopes'][count($cx['scopes'])-1]['template-ids']['belowavatar'])) ? $cx['scopes'][count($cx['scopes'])-1]['template-ids']['belowavatar'] : null), null, $in, true, function($cx, $in) {$inary=is_array($in);echo '',lcr593c9d16c0648hbbch($cx, 'withModule', array(array($cx['scopes'][count($cx['scopes'])-2],$in),array()), $in, false, function($cx, $in) {$inary=is_array($in);echo '								<div class="extra-inner">
									',lcr593c9d16c0648encq($cx, lcr593c9d16c0648hbch($cx, 'enterModule', array(array($cx['scopes'][count($cx['scopes'])-3]),array()), 'encq', $in)),'
								</div>
';}),'';}),'					</div>
';}else{echo '';}echo '			</div>
			<div class="content-body ',lcr593c9d16c0648encq($cx, ((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]['classes']) && isset($cx['scopes'][count($cx['scopes'])-1]['classes']['content-body'])) ? $cx['scopes'][count($cx['scopes'])-1]['classes']['content-body'] : null)),'">
				<',lcr593c9d16c0648encq($cx, ((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]) && isset($cx['scopes'][count($cx['scopes'])-1]['title-htmlmarkup'])) ? $cx['scopes'][count($cx['scopes'])-1]['title-htmlmarkup'] : null)),' class="name ',lcr593c9d16c0648encq($cx, ((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]['classes']) && isset($cx['scopes'][count($cx['scopes'])-1]['classes']['name'])) ? $cx['scopes'][count($cx['scopes'])-1]['classes']['name'] : null)),'">
';if (lcr593c9d16c0648ifvar($cx, (($inary && isset($in['is-profile'])) ? $in['is-profile'] : null), false)){echo '						<a href="',lcr593c9d16c0648encq($cx, lcr593c9d16c0648hbch($cx, 'get', array(array($in,((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]) && isset($cx['scopes'][count($cx['scopes'])-1]['url-field'])) ? $cx['scopes'][count($cx['scopes'])-1]['url-field'] : null)),array()), 'encq', $in)),'" title="',lcr593c9d16c0648raw($cx, (($inary && isset($in['display-name'])) ? $in['display-name'] : null)),'" target="',lcr593c9d16c0648encq($cx, ((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]) && isset($cx['scopes'][count($cx['scopes'])-1]['link-target'])) ? $cx['scopes'][count($cx['scopes'])-1]['link-target'] : null)),'">',lcr593c9d16c0648raw($cx, (($inary && isset($in['display-name'])) ? $in['display-name'] : null)),'</a>
';}else{echo '						',lcr593c9d16c0648raw($cx, (($inary && isset($in['display-name'])) ? $in['display-name'] : null)),'
';}echo '					';if (lcr593c9d16c0648ifvar($cx, (($inary && isset($in['title'])) ? $in['title'] : null), false)){echo '<br/><small>',lcr593c9d16c0648raw($cx, (($inary && isset($in['title'])) ? $in['title'] : null)),'</small>';}else{echo '';}echo '
				</',lcr593c9d16c0648encq($cx, ((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]) && isset($cx['scopes'][count($cx['scopes'])-1]['title-htmlmarkup'])) ? $cx['scopes'][count($cx['scopes'])-1]['title-htmlmarkup'] : null)),'>
';if (lcr593c9d16c0648ifvar($cx, ((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]) && isset($cx['scopes'][count($cx['scopes'])-1]['show-short-description'])) ? $cx['scopes'][count($cx['scopes'])-1]['show-short-description'] : null), false)){echo '';if (lcr593c9d16c0648ifvar($cx, (($inary && isset($in['short-description-formatted'])) ? $in['short-description-formatted'] : null), false)){echo '						<p class="description ',lcr593c9d16c0648encq($cx, ((isset($cx['scopes'][count($cx['scopes'])-2]) && is_array($cx['scopes'][count($cx['scopes'])-2]['classes']) && isset($cx['scopes'][count($cx['scopes'])-2]['classes']['short-description'])) ? $cx['scopes'][count($cx['scopes'])-2]['classes']['short-description'] : null)),'">',lcr593c9d16c0648raw($cx, (($inary && isset($in['short-description-formatted'])) ? $in['short-description-formatted'] : null)),'</p>
';}else{echo '';}echo '';}else{echo '';}echo '';if (lcr593c9d16c0648ifvar($cx, ((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]) && isset($cx['scopes'][count($cx['scopes'])-1]['show-excerpt'])) ? $cx['scopes'][count($cx['scopes'])-1]['show-excerpt'] : null), false)){echo '';if (lcr593c9d16c0648ifvar($cx, (($inary && isset($in['excerpt'])) ? $in['excerpt'] : null), false)){echo '						<p class="excerpt ',lcr593c9d16c0648encq($cx, ((isset($cx['scopes'][count($cx['scopes'])-2]) && is_array($cx['scopes'][count($cx['scopes'])-2]['classes']) && isset($cx['scopes'][count($cx['scopes'])-2]['classes']['excerpt'])) ? $cx['scopes'][count($cx['scopes'])-2]['classes']['excerpt'] : null)),'">',lcr593c9d16c0648raw($cx, (($inary && isset($in['excerpt'])) ? $in['excerpt'] : null)),'</p>
';}else{echo '';}echo '';}else{echo '';}echo '';if (lcr593c9d16c0648ifvar($cx, ((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]['template-ids']) && isset($cx['scopes'][count($cx['scopes'])-1]['template-ids']['belowexcerpt'])) ? $cx['scopes'][count($cx['scopes'])-1]['template-ids']['belowexcerpt'] : null), false)){echo '					<div class="extra ',lcr593c9d16c0648encq($cx, ((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]['classes']) && isset($cx['scopes'][count($cx['scopes'])-1]['classes']['belowexcerpt'])) ? $cx['scopes'][count($cx['scopes'])-1]['classes']['belowexcerpt'] : null)),'">
',lcr593c9d16c0648sec($cx, ((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]['template-ids']) && isset($cx['scopes'][count($cx['scopes'])-1]['template-ids']['belowexcerpt'])) ? $cx['scopes'][count($cx['scopes'])-1]['template-ids']['belowexcerpt'] : null), null, $in, true, function($cx, $in) {$inary=is_array($in);echo '',lcr593c9d16c0648hbbch($cx, 'withModule', array(array($cx['scopes'][count($cx['scopes'])-2],$in),array()), $in, false, function($cx, $in) {$inary=is_array($in);echo '								<div class="extra-inner">
									',lcr593c9d16c0648encq($cx, lcr593c9d16c0648hbch($cx, 'enterModule', array(array($cx['scopes'][count($cx['scopes'])-3]),array()), 'encq', $in)),'
								</div>
';}),'';}),'					</div>
';}else{echo '';}echo '',lcr593c9d16c0648hbbch($cx, 'withModule', array(array($cx['scopes'][count($cx['scopes'])-1],'quicklinkgroup-bottom'),array()), $in, false, function($cx, $in) {$inary=is_array($in);echo '					<div class="quicklinkgroup quicklinkgroup-bottom ',lcr593c9d16c0648encq($cx, ((isset($cx['scopes'][count($cx['scopes'])-2]) && is_array($cx['scopes'][count($cx['scopes'])-2]['classes']) && isset($cx['scopes'][count($cx['scopes'])-2]['classes']['quicklinkgroup-bottom'])) ? $cx['scopes'][count($cx['scopes'])-2]['classes']['quicklinkgroup-bottom'] : null)),'">
						',lcr593c9d16c0648encq($cx, lcr593c9d16c0648hbch($cx, 'enterModule', array(array($cx['scopes'][count($cx['scopes'])-2]),array()), 'encq', $in)),'
					</div>
';}),'			</div>
		</div>
	</div>
';}),'';return ob_get_clean();
};
?>