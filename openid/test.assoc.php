<?php
function test_assoc_endpoint($op_uri) {
    $table_uri = 'http://github.com/erikeldridge/yql-tables/raw/master/openid/openid.assoc.xml';
    $op_uri = 'https://open.login.yahooapis.com/openid/op/auth';
    $params = array(
        'q' => "use '$table_uri' as table; select * from table where uri='$op_uri'",
        'callback' => '',
        'diagnostics' => 'false',
        'format' => 'json',
        'debug' => 'true'
    );
    $yql_uri = "http://query.yahooapis.com/v1/public/yql?".http_build_query( $params );
    $json = file_get_contents( $yql_uri );
    $data = json_decode( $json );
    if(!$data->query->results->success){
        throw new Exception('error response');
    }
    $properties = array('ns', 'assoc_handle', 'session_type', 'assoc_type', 'expires_in');
    foreach( $properties as $property ){
        if( !property_exists( $data->query->results->success, $property ) ){
            throw new Exception('property missing');
        }
    }
}
try {
    $op_uri = 'https://open.login.yahooapis.com/openid/op/auth';
    test_assoc_endpoint( $op_uri );
    printf('pass: %s', $op_uri );
} catch (Exception $e) {
   printf('<pre>%s</pre>', print_r( $e->message, true ) );
}
?>
