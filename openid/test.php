<?php
function test_fetch_auth_url( $id, $return_to='http://example.com' ) {
    $table_uri = 'http://github.com/erikeldridge/yql-tables/raw/master/openid/openid.xml';
    $params = array(
        'q' => "use '$table_uri' as table; select * from table where id='$id' and return_to='$return_to'",
        'callback' => '',
        'diagnostics' => 'false',
        'format' => 'json',
        'debug' => 'true'
    );
    $yql_uri = "http://query.yahooapis.com/v1/public/yql?".http_build_query( $params );
    $json = file_get_contents( $yql_uri );
    $data = json_decode( $json );
    if( !$data->query->results->success ){
        throw new Exception('error response');
    }
    $parsed_url = parse_url( $data->query->results->success );
    parse_str( $parsed_url['query'], $parsed_query );
    $properties = array( 'openid.ns', 'openid.realm', 'openid.mode', 'openid.return_to', 'openid.identity', 'openid.claimed_id' );
    foreach( $properties as $property ){
        if( !array_key_exists( str_replace( '.', '_', $property ), $parsed_query ) ){
            throw new Exception( 'property missing: '.var_export( $parsed_query, true ) );
        }
    }
}

try {
    $openid = 'yahoo.com';
    test_fetch_auth_url( $openid, 'http://test.erikeldridge.com/yql/openid/' );
    printf('pass: %s', $openid );
} catch (Exception $e) {
   printf('<pre>%s</pre>', print_r( $e, true ) );
}
?>
