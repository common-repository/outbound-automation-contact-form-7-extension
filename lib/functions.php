<?php

require_once(  BDOA_PLUGIN_DIR . '/lib/UpdateListNameAndId.php' );

/**
 * add_filter( 'wpcf7_editor_panels', 'show_bd_metabox' ) adds the boberdoo tab to CF 7
 * add_action( 'wpcf7_after_save', 'save_bd_key' ); saves the api key and list name to the option values
 */
add_filter( 'wpcf7_editor_panels', 'show_bdOA_metabox' );
add_action( 'wpcf7_after_save', 'save_bdOA_key' );


/**
 * show_bd_metabox addes the boberdoo panel to the cf7 admin screen
 * @return $panels
 */
function show_bdOA_metabox ( $panels ) {

  $new_page = array(
    'Boberdoo-Extension' => array(
      'title' => __( 'Outbound Automation', 'contact-form-7' ),
      'callback' => 'wpcf7_bdOA_add'
    )
  );

  $panels = array_merge($panels, $new_page);
  return $panels;

}

/**
 * Checks on post if api key or list name have been updated.  Will update option if they have
 * if the list name is updated the updateListId is called to update the list id # and save it.
 */
function save_bdOA_key($args){

    if (!empty($_POST)){
        update_option( 'boberdoo-api-key', sanitize_text_field($_POST['boberdoo-api-key']));
    }
    if (!empty($_POST)){
        update_option( 'boberdoo-list-name', sanitize_text_field($_POST['boberdoo-list-name']));
        bdoaupdateListId(get_option('boberdoo-list-name'));
    }
}

/**
 * Adds the option values to be inputed by the user 
 */
function wpcf7_bdOA_add($args) {
  
    $host = esc_url_raw( $_SERVER['HTTP_HOST'] );
    $url = $_SERVER['REQUEST_URI'];
    $urlactual = $url;
    $key1 = get_option('boberdoo-api-key');
    $key2 = get_option('boberdoo-list-name');

    ?>

    <div class="metabox-holder">
    <h3>Outbound Automation Settings</h3> 
    <input size="100" type="text" name="boberdoo-api-key" id="boberdoo-api-key" value="<?php echo esc_html( $key1 ); ?>" placeholder="Enter Api Key Here" />
    <br><p>To get your API key click <a href="https://www.boberdoo.com/setting-up-dynamic-list-using-contact-form-7/" target="_blank">Here</a>.</p>
    <input size="100" type="text" name="boberdoo-list-name" id="boberdoo-list-name" value="<?php echo esc_html( $key2 ) ?>" placeholder="Enter List name Here"/><br>
    <p>Click <a target="_blank" href="https://www.boberdoo.com/setting-up-dynamic-list-using-contact-form-7/">here</a> to view information on lists, list names, and other information.</p>
    </div>
    <?php
    
}



