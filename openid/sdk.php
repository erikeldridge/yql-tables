<?php
function yql( $query )
{
    $params = array(
        'q' => $query,
        'callback' => '',
        'diagnostics' => 'true',
        'format' => 'json',
        'debug' => 'true'
    );
    $yql_uri = "http://query.yahooapis.com/v1/public/yql?".http_build_query( $params );
    $json = file_get_contents( $yql_uri );
    $data = json_decode( $json );
    return $data->query->results;
}
function normalize_id( $id )
{
    $table_uri = 'http://github.com/erikeldridge/yql-tables/raw/master/openid/openid.normalize.xml';
    $query = "use '$table_uri' as table; select * from table where id='$id'";
    $results = yql( $query );
    if( $results->error ){
        throw new Exception( 'error response: '.$results->error );
    }
    return $results->success;
}
function perform_yadis( $uri )
{
    $table_uri = 'http://github.com/erikeldridge/yql-tables/raw/master/openid/openid.yadis.xml';
    $query = "use '$table_uri' as table; select * from table where uri='$uri'";
    $results = yql( $query );
    if( $results->error ){
        throw new Exception( 'error response: '.$results->error );
    }
    return $results->success;
}
function fetch_association( $op_uri )
{
    $table_uri = 'http://github.com/erikeldridge/yql-tables/raw/master/openid/openid.assoc.xml';
    $query = "use '$table_uri' as table; select * from table where uri='$op_uri'";
    $results = yql( $query );
    if( $results->error ){
        throw new Exception( 'error response: '.$results->error );
    }
    return $results->success;
}
function fetch_auth_url( $id, $return_to, $assoc_handle = null, $ax_json = null )
{
    $table_uri = 'http://github.com/erikeldridge/yql-tables/raw/master/openid/openid.xml';
    $query = "use '$table_uri' as table; select * from table where id='$id' and return_to='$return_to'";
    if( $assoc_handle ){
        $query .= " and assoc_handle='$assoc_handle'";
    }
    if( $ax_fields ){
        $query .= " and axJson='$ax_json'";
    }
    $results = yql( $query );
    if( $results->error ){
        throw new Exception( 'error response: '.$results->error );
    }
    return $results->success;
}
function verify_assertion( $local_uri, $assert_json, $assoc_json = null, $nonce_store_uri = null )
{
    $table_uri = 'http://github.com/erikeldridge/yql-tables/raw/master/openid/openid.verify.xml';
    $query = "use '$table_uri' as table; select * from table where localUri='$local_uri' and assertJson='$assert_json'";
    if( $assoc_json ){
        $query .= " and assocJson='$assoc_json'";
    }
    if( $nonce_store_uri ){
        $query .= " and nonceStoreUri='$nonce_store_uri'";
    }
    $results = yql( $query );
    if( $results->error ){
        throw new Exception( 'error response: '.$results->error );
    }
    return $results->success;
}
?>