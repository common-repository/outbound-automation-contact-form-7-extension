<?php
/*
Plugin Name: Contact Form 7 Outboud Automation Extension
Plugin URI: https://www.boberdoo.com/setting-up-dynamic-list-using-contact-form-7/
Description: Integrate Contact Form 7 with Boberdoo's Outbound Automation.
Author: boberdoo.com
Author URI: https://www.boberdoo.com
License: GPLv2 or later
Text Domain: contact-form-7
Domain Path: /languages/
Version: 1.0.0
*/

/*
  Copyright 2017  boberdoo.com  (email: admin_wp_plugin@boberdoo.com)
  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License, version 2, as
  published by the Free Software Foundation.

  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.

  You should have received a copy of the GNU General Public License
  along with this program; if not, write to the Free Software
  Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */


/**
 * Defaults values for Plugin Directories and Names.
 */
define( 'BDOA_VERSION', '1.0.0' );
define( 'BDOA_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );
define( 'BDOA_PLUGIN_NAME', trim( dirname( BDOA_PLUGIN_BASENAME ), '/' ) );
define( 'BDOA_PLUGIN_DIR', untrailingslashit( dirname( __FILE__ ) ) );
define( 'BDOA_PLUGIN_URL', untrailingslashit( plugins_url( '', __FILE__ ) ) );
define( 'BDOA_VERSION_STATUS', 'PRODUCTION');

/**
 *  Defaults for APIURL, API Key, Listname and ListId
 */
define( 'BDOA_API_URL', 'https://api-or.boberdoo.com' );
define( 'BDOA_API_KEY', get_option('boberdoo-api-key') );
define( 'BDOA_LIST_NAME', get_option('boberdoo-list-name'));
define( 'BDOA_LIST_ID', get_option('boberdoo-list-id'));

/**
 * Defaults for end point interactions with API
 */
define( 'BDOA_API_GET_CONTACTS_LIST', '/contact-lists' );
define( 'BDOA_API_POST_CONTACTS_LIST' , '/contact-list' ); //Requires "name" field and will create a contact list
define( 'BDOA_API_ADD_CONTACTS_TO_LIST' , '/add-contact-to-list'); //Requires "list-id", "email", "first_name", "last_name" and will add a contact to a list

/**
 * Needs to be called First.  Pulls in required .php files in from /lib/
 */
require_once( BDOA_PLUGIN_DIR . '/lib/boberdoo.php' );


/**
 * These will set the default values for apikey, apiurl, and listname/listid
 * They will remain blank until set inside the tab under contact form 7
 */
if( !get_option( 'boberdoo-api-key' ) ) {
    add_option( 'boberdoo-api-key', '', '', 'yes' );
    update_option( 'boberdoo-api-key', "" );
}
if( !get_option( 'boberdoo-list-name') ) {
    add_option( 'boberdoo-list-name', '', '', 'yes' );
    update_option( 'boberdoo-list-name', "" );
}
if( !get_option( 'boberdoo-list-id') ) {
    add_option( 'boberdoo-list-id', '', '', 'yes' );
    update_option( 'boberdoo-list-id', "" );
}

/**
 * Ajax_process_oa_input will take the form scraper input and then send it to the parsing class
 * to be posted to the proper account and list name.
 */
function ajax_process_bdoa_input(){
  
  $allFields = $_REQUEST['fieldsArray'];
  require_once(  BDOA_PLUGIN_DIR . '/lib/addContactToList.php' );
  bdoauploadContact($allFields);
  die("2" . var_dump($fieldsToSend) . " Line 88");
  //die("2" . var_dump($allFields) . " List Id => " . get_option('boberdoo-list-id'));*/
}

/**
 * bd_oa_header will input the 3.1.0 jquery code into the head.  This is required for the form scraper to work.
 */
function bd_oa_header(){
  echo '<!--Start OutboundAutomation Integration-->';
}

/**
 * ajax_load_scrips is called by add_action('wp_enqueue_scripts') and is placed into the head to make a global object for ajax
 * ajax_script_bd.BDOA will output the proper admin-ajax.php url to be used.
 */
function bdoa_ajax_load_scripts() {
  wp_enqueue_script( "ajax-bdoa", BDOA_PLUGIN_URL . '/include/js/bd_oa_send.js', array( 'jquery' ) );
  wp_localize_script( 'ajax-bdoa', 'ajax_script_bdoa', array( 'BDOA' => admin_url( 'admin-ajax.php' ) ) );
}

/**
 *  these two add actions are called by wordpress on load.  
 */
add_action( 'wp_enqueue_scripts', 'bdoa_ajax_load_scripts' );
add_action( 'wp_head', 'bd_oa_header', 1);

/**
 * process_oa_input are the call back functions that are used as the actions in bd_oa_send.js
 */
add_action('wp_ajax_process_bdoa_input', 'ajax_process_bdoa_input');
add_action('wp_ajax_nopriv_process_bdoa_input', 'ajax_process_bdoa_input');