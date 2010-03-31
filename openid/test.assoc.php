<?php
require 'sdk.php';

function validate_assoc( $assoc ) {
    $properties = array('ns', 'assoc_handle', 'session_type', 'assoc_type', 'expires_in');
    foreach( $properties as $property ){
        if( !property_exists( $assoc, $property ) ){
            throw new Exception('property missing');
        }
    }
}

//yahoo
$test = 'yahoo';
$op_uri = 'https://open.login.yahooapis.com/openid/op/auth';
try {
    $assoc = fetch_association( $op_uri );
    validate_assoc( $assoc );
    printf('pass: %s<br/>', $test );
} catch (Exception $e) {
   printf("<p>fail $test:<br/><pre>%s</pre></p>", print_r( $e->getMessage(), true ) );
}

//google
$test = 'google';
$op_uri = '';
try {
    $assoc = fetch_association( $op_uri );
    validate_assoc( $assoc );
    printf('pass: %s<br/>', $test );
} catch (Exception $e) {
   printf("<p>fail $test:<br/><pre>%s</pre></p>", print_r( $e->getMessage(), true ) );
}
?>
