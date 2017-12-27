<?php
namespace App\Http\Response;

use Config;
class Response {
	protected $result;

    public function __construct () {

        $this->result = array(
        	'meta' => array(
                'code'      => '',
        		'success'   => true,
                'msg'       => ''
        	),
        	'response' => ''
        );
    }

    public static function response ($code = 200, $msg = [], $response = array(), $success = true) {

    	$result[ 'meta' ][ 'code' ] 	= $code;
    	$result[ 'meta' ][ 'success' ]  = $success;
    	$result[ 'meta' ][ 'msg' ] 	    = $msg;
    	$result[ 'response' ] 	        = $response;

    	return $result;
    }
}