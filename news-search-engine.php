<?php
/**
 * Plugin Name:       News Search Engine
 * Plugin URI:        https://github.com/webmarcello8080/news-search-engine
 * Description:       News Search Engine is a Wordpress plugin that create a new section on the Wordpress Dashboard and it gives the possibility to search news through Google news API.
 * Version:           1.0.3
 * Requires at least: 4.5.13
 * Requires PHP:      7.0
 * Author:            Marcello Perri
 * Author URI:        http://webmarcello.co.uk
 */

define('PLUGIN_NSE_BASENAME', plugin_basename(__FILE__) );

//Our class extends the WP_List_Table class, so we need to make sure that it's there
if(!class_exists('WP_List_Table')){
   require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

foreach ( glob( plugin_dir_path( __FILE__ ) .'includes/*.php') as $filename)
{
    include_once $filename;
}

if ( !function_exists( 'news_search_engine_loader' ) ) {
    function news_search_engine_loader(){
        if( is_admin() ){
            new NewsSearchEngineAdmin;
            new NewsSearchEngineAjax;
        }
    }
    add_action('plugins_loaded', 'news_search_engine_loader');
}