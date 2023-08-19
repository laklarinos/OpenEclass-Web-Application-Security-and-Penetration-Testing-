<?php
if(!function_exists('hash_equals'))
{
    function hash_equals($str1, $str2)
    {
        if(strlen($str1) != strlen($str2))
        {
            return false;
        }
        else
        {
            $res = $str1 ^ $str2;
            $ret = 0;
            for($i = strlen($res) - 1; $i >= 0; $i--)
            {
                $ret |= ord($res[$i]);
            }
            return !$ret;
        }
    }
}
function generateCsrfToken($frm_name)
  {
  	$token = base64_encode(openssl_random_pseudo_bytes(32));
  	$_SESSION[$frm_name] = $token;
  	return $token;
  }

  //Varify submitted token
function validateCsrfToken($frm_name,$token_value)
  {
  	$token = $_SESSION[$frm_name];
  	if (!is_string($token_value)) {
  		return false;
  	}
  	$result = hash_equals($token, $token_value);

  	//$_SESSION[$key]=' ';
  	unset($_SESSION[$frm_name]);
  	return $result;
  }

  //Inject csrf token in form
function injectCsrfToken(){
  	$name = "CSRFGuard_".mt_rand(0,mt_getrandmax());
  	$token = generateCsrfToken($name);
    return "<input type='hidden' name='CSRFName' value='".$name."' />
  			<input type='hidden' name='CSRFToken' value='".$token."' />";

  }	