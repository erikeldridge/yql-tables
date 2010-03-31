<?php
require 'sdk.php';

//yahoo setup
//use canned assoc/assert to minimize complex storage & auth dance
$yahoo_assoc = json_decode( '{"mac_key":"s2wIu2+v4HM8PJW01lTa1H+lnfQ=","ns":"http","assoc_handle":"fh2HmNb58hqwaicsbVFtIdzhRBlvadgedpa9QCOm.KrXqSS05HQt2_M3uG6HdhiR.4C60uAg00XXTz0LMfnp6yGhivtIoHTs_LTokvDWulLXNcRLXgyRmkWa5je3wuY-","session_type":"no-encryption","expires_in":"14400","assoc_type":"HMAC-SHA1"}' );
$yahoo_assert = 'http://test.erikeldridge.com/yql/openid/?openid.ns=http%3A%2F%2Fspecs.openid.net%2Fauth%2F2.0&openid.mode=id_res&openid.return_to=http%3A%2F%2Ftest.erikeldridge.com%2Fyql%2Fopenid%2F&openid.claimed_id=https%3A%2F%2Fme.yahoo.com%2Fpandayak%23415d4&openid.identity=https%3A%2F%2Fme.yahoo.com%2Fpandayak&openid.assoc_handle=fh2HmNb58hqwaicsbVFtIdzhRBlvadgedpa9QCOm.KrXqSS05HQt2_M3uG6HdhiR.4C60uAg00XXTz0LMfnp6yGhivtIoHTs_LTokvDWulLXNcRLXgyRmkWa5je3wuY-&openid.realm=http%3A%2F%2Ftest.erikeldridge.com&openid.response_nonce=2010-03-31T07%3A51%3A29ZwKZWhMukLuH9vo8ldKAPrScbFJfp14Up2Q--&openid.signed=assoc_handle%2Cclaimed_id%2Cidentity%2Cmode%2Cns%2Cop_endpoint%2Cresponse_nonce%2Creturn_to%2Csigned%2Cpape.auth_level.nist&openid.op_endpoint=https%3A%2F%2Fopen.login.yahooapis.com%2Fopenid%2Fop%2Fauth&openid.pape.auth_level.nist=0&openid.sig=m9vCMJiWHkRRmUeAM2byK2T%2FsZg%3D';

//yahoo
$test = 'test yahoo';
$openid = 'yahoo.com';
$return_to = 'http://test.erikeldridge.com/yql/openid/';
$url = fetch_auth_url( $openid, $return_to, $yahoo_assoc->assoc_handle );
printf( '<p><a href="%s">click</a></p>', $url );
$parsed_url = parse_url( $yahoo_assert );
parse_str( $parsed_url['query'], $parsed_query );
try {
    verify_assertion( 
        $return_to, 
        json_encode( $parsed_query ), 
        json_encode( $yahoo_assoc )
        //we're using a canned assert, so don't pass nonce store 
    );
    
    printf('pass: %s<br/>', $test );
} catch (Exception $e) {
   printf("<p>fail $test:<br/><pre>%s</pre></p>", print_r( $e->getMessage(), true ) );
}

?>
