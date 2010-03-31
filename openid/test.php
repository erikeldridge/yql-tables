<?php
require 'sdk.php';

function validate_structure( $url )
{
    $parsed_url = parse_url( $url );
    parse_str( $parsed_url['query'], $parsed_query );
    
    //default
    $properties = array( 'openid.ns', 'openid.realm', 'openid.mode', 'openid.return_to', 'openid.identity', 'openid.claimed_id' );
    
    //assoc
    if($assoc_handle){
        $properties[] = 'openid.assoc_handle';
    }
    
    //ax
    if($ax_params){
        foreach( $ax_params as $param ){
            $properties[] = 'openid.ax.type.'.$param['alias'];
        }
        $properties[] = 'openid.ax.mode';
        $properties[] = 'openid.ax.required';
    }
    
    //to minimize complexity for now, only check if field is defined
    foreach( $properties as $property ){
        if( !array_key_exists( str_replace( '.', '_', $property ), $parsed_query ) ){
            throw new Exception( 'property missing: '.var_export( $property, true ) );
        }
    }
    
}

//yahoo setup
$yahoo_assoc = fetch_association( 'https://open.login.yahooapis.com/openid/op/auth' );
// $google_assoc = fetch_association( '' );
$ax_params = array(
    array(
        'schema' => '',
        'alias' => 'email',
        'required' => 'false'
    ),
    array(
        'schema' => '',
        'alias' => 'fullname',
        'required' => 'false'
    )
);

//yahoo w/o assoc handle, ax
try {
    $test = 'yahoo w/o assoc handle, ax';
    $openid = 'yahoo.com';
    $return_to = 'http://test.erikeldridge.com/yql/openid/';
    $url = fetch_auth_url( $openid, $return_to );
    validate_structure( $url );
    printf('pass: %s<br/>', $test );
} catch (Exception $e) {
   printf("<p>fail: $test<br/><pre>%s</pre></p>", print_r( $e->getMessage(), true ) );
}

//yahoo w/ assoc handle
try {
    $test = 'yahoo w/ assoc handle';
    $openid = 'yahoo.com';
    $return_to = 'http://test.erikeldridge.com/yql/openid/';
    $url = fetch_auth_url( $openid, $return_to, $yahoo_assoc->assoc_handle );
    validate_structure( $url );
    printf('pass: %s<br/>', $test );
} catch (Exception $e) {
   printf("<p>fail:<br/><pre>%s</pre></p>", print_r( $e->getMessage(), true ) );
}

//yahoo w/ ax
try {
    $test = 'yahoo w/ ax';
    $openid = 'yahoo.com';
    $return_to = 'http://test.erikeldridge.com/yql/openid/';
    $url = fetch_auth_url( $openid, $return_to, null, json_encode( $ax_params ) );
    validate_structure( $url );
    printf('pass: %s<br/>', $test );
} catch (Exception $e) {
   printf("<p>fail:<br/><pre>%s</pre></p>", print_r( $e->getMessage(), true ) );
}

//yahoo w/ assoc handle, ax
try {
    $test = 'yahoo w/ assoc handle, ax';
    $openid = 'yahoo.com';
    $return_to = 'http://test.erikeldridge.com/yql/openid/';
    $url = fetch_auth_url( $openid, $return_to, $yahoo_assoc->assoc_handle, json_encode( $ax_params ) );
    validate_structure( $url );
    printf('pass: %s<br/>', $test );
} catch (Exception $e) {
   printf("<p>fail:<br/><pre>%s</pre></p>", print_r( $e->getMessage(), true ) );
}

//test valid base url
//yahoo
$test = 'test valid base url for yahoo';
$openid = 'yahoo.com';
$return_to = 'http://test.erikeldridge.com/yql/openid/';
$url = fetch_auth_url( $openid, $return_to );
$base_url = substr( $url, 0, strpos( $url, '?' ) );
if( 'https://open.login.yahooapis.com/openid/op/auth' == $base_url ){
    printf('pass: %s<br/>', $test );
} else {
   printf("<p>fail $test:<br/><pre>%s</pre></p>", print_r( "$base_url", true ) );
}

//google
$test = 'test valid base url for google';
$openid = 'yahoo.com';
$return_to = 'http://test.erikeldridge.com/yql/openid/';
$url = fetch_auth_url( $openid, $return_to );
$base_url = substr( $url, 0, strpos( $url, '?' ) );
if( 'https://open.login.yahooapis.com/openid/op/auth' == $base_url ){
    printf('pass: %s<br/>', $test );
} else {
   printf("<p>fail $test:<br/><pre>%s</pre></p>", print_r( "$base_url", true ) );
}

?>
