<?php
 function lcr5a2ad155aaa16hbbch($cx, $ch, $vars, &$_this, $inverted, $cb, $else = null) {
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
    $ret = $cb($cx, is_array($ex) ? lcr5a2ad155aaa16m($cx, $_this, $ex) : $_this);
   } else {
    $cx['scopes'][] = $_this;
    $ret = $cb($cx, is_array($ex) ? lcr5a2ad155aaa16m($cx, $context, $ex) : $context);
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

  return lcr5a2ad155aaa16exch($cx, $ch, $vars, $options);
 }

 function lcr5a2ad155aaa16encq($cx, $var) {
  if ($var instanceof LS) {
   return (string)$var;
  }

  return str_replace(array('=', '`', '&#039;'), array('&#x3D;', '&#x60;', '&#x27;'), htmlspecialchars(lcr5a2ad155aaa16raw($cx, $var), ENT_QUOTES, 'UTF-8'));
 }

 function lcr5a2ad155aaa16wi($cx, $v, $bp, $in, $cb, $else = null) {
  if (isset($bp[0])) {
   $v = lcr5a2ad155aaa16m($cx, $v, array($bp[0] => $v));
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

 function lcr5a2ad155aaa16m($cx, $a, $b) {
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

 function lcr5a2ad155aaa16exch($cx, $ch, $vars, &$options) {
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
   lcr5a2ad155aaa16err($cx, $e);
  }

  return $r;
 }

 function lcr5a2ad155aaa16raw($cx, $v, $ex = 0) {
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
      $ret[] = lcr5a2ad155aaa16raw($cx, $vv);
     }
     return join(',', $ret);
    }
   } else {
    return 'Array';
   }
  }

  return "$v";
 }

 function lcr5a2ad155aaa16err($cx, $err) {
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
    $helpers = array(            'generateId' => function($options) { 

		global $pop_serverside_helpers;
		return $pop_serverside_helpers->generateId($options);
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
    ob_start();echo '<div ',lcr5a2ad155aaa16hbbch($cx, 'generateId', array(array(),array()), $in, false, function($cx, $in) {$inary=is_array($in);echo '',lcr5a2ad155aaa16encq($cx, (($inary && isset($in['id'])) ? $in['id'] : null)),'';}),' data-action="',lcr5a2ad155aaa16encq($cx, (($inary && isset($in['action'])) ? $in['action'] : null)),'" data-template-download="',lcr5a2ad155aaa16encq($cx, (($inary && isset($in['template-download'])) ? $in['template-download'] : null)),'" data-template-upload="',lcr5a2ad155aaa16encq($cx, (($inary && isset($in['template-upload'])) ? $in['template-upload'] : null)),'" class="',lcr5a2ad155aaa16encq($cx, (($inary && isset($in['class'])) ? $in['class'] : null)),'" style="',lcr5a2ad155aaa16encq($cx, (($inary && isset($in['style'])) ? $in['style'] : null)),'">
	<div class="fileupload">
		
		<div class="fileupload-buttonbar">		
			
			<!--div class="fileupload-progress fade"></div-->	
			<div class="fileupload-loading"></div>
			<div role="presentation" class="clearfix">
				<div class="files" data-toggle="modal-gallery" data-target="#modal-gallery"></div>
			</div>

			<div class="preview pop-fileuploaded-hide">
				<div class="row">
					<div class="col-sm-4 col-md-3">
						<h5>',lcr5a2ad155aaa16encq($cx, ((isset($in['titles']) && is_array($in['titles']) && isset($in['titles']['avatar'])) ? $in['titles']['avatar'] : null)),'</h5>
',lcr5a2ad155aaa16wi($cx, (($inary && isset($in['default-thumb'])) ? $in['default-thumb'] : null), null, $in, function($cx, $in) {$inary=is_array($in);echo '							<a href="',lcr5a2ad155aaa16encq($cx, (($inary && isset($in['url'])) ? $in['url'] : null)),'" rel="',lcr5a2ad155aaa16encq($cx, ((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]) && isset($cx['scopes'][count($cx['scopes'])-1]['image-rel'])) ? $cx['scopes'][count($cx['scopes'])-1]['image-rel'] : null)),'" title="',lcr5a2ad155aaa16encq($cx, ((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]['titles']) && isset($cx['scopes'][count($cx['scopes'])-1]['titles']['avatar'])) ? $cx['scopes'][count($cx['scopes'])-1]['titles']['avatar'] : null)),'">
								<img class="thumbnail img-responsive" src="',lcr5a2ad155aaa16encq($cx, (($inary && isset($in['url'])) ? $in['url'] : null)),'" width="',lcr5a2ad155aaa16encq($cx, (($inary && isset($in['size'])) ? $in['size'] : null)),'" height="',lcr5a2ad155aaa16encq($cx, (($inary && isset($in['size'])) ? $in['size'] : null)),'">
							</a>
';}),'					</div>
					<div class="col-sm-4 col-md-9">
						<h5>',lcr5a2ad155aaa16encq($cx, ((isset($in['titles']) && is_array($in['titles']) && isset($in['titles']['photo'])) ? $in['titles']['photo'] : null)),'</h5>
',lcr5a2ad155aaa16wi($cx, (($inary && isset($in['default-image'])) ? $in['default-image'] : null), null, $in, function($cx, $in) {$inary=is_array($in);echo '							<a href="',lcr5a2ad155aaa16encq($cx, (($inary && isset($in['url'])) ? $in['url'] : null)),'" rel="',lcr5a2ad155aaa16encq($cx, ((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]) && isset($cx['scopes'][count($cx['scopes'])-1]['image-rel'])) ? $cx['scopes'][count($cx['scopes'])-1]['image-rel'] : null)),'" title="',lcr5a2ad155aaa16encq($cx, ((isset($cx['scopes'][count($cx['scopes'])-1]) && is_array($cx['scopes'][count($cx['scopes'])-1]['titles']) && isset($cx['scopes'][count($cx['scopes'])-1]['titles']['photo'])) ? $cx['scopes'][count($cx['scopes'])-1]['titles']['photo'] : null)),'">
								<img class="img-responsive" src="',lcr5a2ad155aaa16encq($cx, (($inary && isset($in['url'])) ? $in['url'] : null)),'" width="',lcr5a2ad155aaa16encq($cx, (($inary && isset($in['width'])) ? $in['width'] : null)),'" height="',lcr5a2ad155aaa16encq($cx, (($inary && isset($in['height'])) ? $in['height'] : null)),'">
							</a>
';}),'					</div>
				</div>
			</div>

			<span class="btn btn-primary fileinput-button pop-fileuploaded-hide">
				<span class="glyphicon glyphicon-plus"></span> ',lcr5a2ad155aaa16encq($cx, ((isset($in['titles']) && is_array($in['titles']) && isset($in['titles']['upload'])) ? $in['titles']['upload'] : null)),'
				<input type="file" name="files[]">
			</span>
			<input type="hidden" name="',lcr5a2ad155aaa16encq($cx, (($inary && isset($in['name'])) ? $in['name'] : null)),'" value="',lcr5a2ad155aaa16encq($cx, (($inary && isset($in['upload-path'])) ? $in['upload-path'] : null)),'">
		</div>
	</div>
</div>
<div class="clearfix"></div>';return ob_get_clean();
};
?>