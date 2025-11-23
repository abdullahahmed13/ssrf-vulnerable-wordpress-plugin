<?php
/*
  Plugin Name: SSRF Vulnerable Plugin
  Description: This plugin is vulnerable to SSRF aka Server Side Request Forgery - Using it to test My SSRF Shield Plugin. User https://websiteurl.com/wp-json/ssrf-vulnerable-plugin/fetch?url= and abbend url to test SSRF
  Author: Abdullah Ahmed aka EntropyDrifter
  Author URI: https://www.linkedin.com/in/abdullahahmed11/
  Text Domain: ssrf-vulnerable-plugin
  Version: 1.0.0
*/

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Terminate script if not running within WordPress
}

class SSRF_Vulnerable_Plugin {

    public function __construct() {

        // Adding action of the rest_api_init
        add_action( 'rest_api_init', array($this, 'register_ssrf_endpoint') );
    }

    // SSRF Endpoint register
    public function register_ssrf_endpoint(){
        register_rest_route( 'ssrf-vulnerable-plugin', 'fetch', array(
            'methods' => 'GET',
            'callback' => array($this, 'ssrf_fetch_link'),
            'permission_callback' => '__return_true' // Making the Endpoint Accessible to anyone.

        ) );

    }

    public function ssrf_fetch_link($request){

        $url = $request->get_param('url');

        // Fetching the url the normal way
        $repsonse = wp_remote_get($url);

        if(is_array($repsonse) && !is_wp_error( $repsonse )){
            
            $headers = $repsonse['headers'];
            $body = $repsonse['body'];

            return array(
                'Headers: ' => $headers,
                'Body: '    => $body
            );
        }

        // // Fetching the url the safe way
        // $repsonse_safe = wp_safe_remote_get($url);

        // if(is_array($repsonse_safe) && !is_wp_error( $repsonse_safe )){
            
        //     $headers_safe = $repsonse_safe['headers'];
        //     $body_safe = $repsons_safe['body'];

        //     return array(
        //         'Headers Safe: ' => $headers_safe,
        //         'Body Safe: '    => $body_safe
        //     );
        // }
    }
}

new SSRF_Vulnerable_Plugin();