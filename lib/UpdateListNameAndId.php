<?php
require_once(  BDOA_PLUGIN_DIR . '/lib/addContactToList.php' );
/**
 * Adds the list name to the list names in API
 * then calls bdoaupdateListIdFromJson($listName) to update list ID#
 */
function bdoaupdateListId($listName,$arrayHolding){
    //Adding in the url and /*API_POST_CONTACTS_LIST*
    $fullURL = BDOA_API_URL . BDOA_API_POST_CONTACTS_LIST;
        
    //adding in header for API key
    $args = array(
        'headers' => array( 
            "x-api-key" => BDOA_API_KEY),
            'content-type' => "application/x-www-form-urlencoded",
            'body' => array( 'name' => $listName)
        );

    $response = wp_remote_post( $fullURL, $args );
        
    $body = wp_remote_retrieve_body( $response );

    $array = json_decode($body,true);

    if($array["error"] != null || $array["error"]["error_code"] == "200"){

      $fullURL1 = BDOA_API_URL . BDOA_API_GET_CONTACTS_LIST;
      $args1 = array(
        'headers' => array( 
            "x-api-key" => BDOA_API_KEY)
        );

      $response1 = wp_remote_get( $fullURL1, $args1 );
      $body1     = wp_remote_retrieve_body( $response1 );
      $array1    = json_decode($body1,true);

      foreach($array1["data"]["contactLists"] as $item1){
         
        if($item1["name"] == $listName){

          $id = $item1["contact_list_id"];
          $id = sanitize_text_field($id);
          break;   
        }
            
      }

    } else {
      $id = $array["data"]["contactList"]['contact_list_id'];
      $id = sanitize_text_field($id);
    }
    update_option( 'boberdoo-list-id', $id);
    bdoauploadContact($arrayHolding);
}
