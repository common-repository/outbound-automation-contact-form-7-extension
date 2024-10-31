<?php

require_once(  BDOA_PLUGIN_DIR . '/lib/UpdateListNameAndId.php' );

function bdoauploadContact($args){

	$fieldsArray = $args;
	
	$fieldsToSend = array();
	$fieldsToSend["list_id"] = get_option( 'boberdoo-list-id');

	$firstNameEXP = '/(firstName|FirstName|firstname|first|firstN|firstn|nfirst|nFirst|FIRSTNAME|First|FName|fName|Fname|fn|Fn|FN|fN|first-name|first-Name|First-name|First-name|FIRST-name|FIRST-NAME|f-Name|f-name|F-name|F-Name)/';
	$lastNameEXP = '/(lastName|LastName|lastname|last|lastN|lastn|nlast|nLast|LASTNAME|Last|LName|lName|Lname|ln|Ln|LN|lN|last-name|last-Name|Last-name|Last-name|LAST-name|LAST-NAME|l-Name|l-name|L-name|L-Name)/';
	$emailEXP = '/(email|Email|EMAIL|EMail|e-mail|E-mail|e-MAIL|em|mail|mail-e|your-email)/';
	$phoneEXP = '/(tel|phone|telephone|telphone|Phone|TEL|Tel|PHONE|tel-phone|phone-number|tPhone|tphone|t-phone|cell|cellPhone|cellphone|Cell|CELL|CELLPHONE)/';

	$file = BDOA_PLUGIN_DIR . '/log.txt';

	//Fields Array is an array of fields from form post.
	foreach ($fieldsArray as $key => $value) {

		switch ($key) {

			//Switch statment for first_name
	   		case preg_match($firstNameEXP, $key) == true:
	   			$fieldsToSend["first_name"] = $value;
	        break;

	        //Switch statment for last name
	   		case preg_match($lastNameEXP, $key) == true:
	   			$fieldsToSend["last_name"] = $value;
	        break;

	        //Switch statment for email
	   		case preg_match($emailEXP, $key) == true:
	   			$fieldsToSend["email"] = $value;
	        break;

	        //Switch statement for phone
	        case preg_match($phoneEXP, $key) == true:
	   			$fieldsToSend["phone"] = $value;
	        break;

	        //Nothing for firstname|lastname|email|phone was found. Can not add to list.
    		default:
    			//get current file contents
	        	$current = file_get_contents($file);
				// Append a new person to the file
				$current .= $value . " Not in the required List at TIME: " . date("h:m:i") ."\n";
				// Write the contents back to the file
				file_put_contents(sanitize_text_field($file), $current);
		}

	}

	$current = file_get_contents($file);
	// Append a new person to the file
	$current .= print_r($fieldsToSend,true) . "\n";
	// Write the contents back to the file
	file_put_contents(sanitize_text_field($file), $current);
	

    //Adding in the url and /*path*
    $fullURL = BDOA_API_URL . BDOA_API_ADD_CONTACTS_TO_LIST;
        
    //adding in header for API key
    $args = array(
        'headers' => array( 
            "x-api-key" => BDOA_API_KEY),
            'content-type' => "application/x-www-form-urlencoded",
        'body' => $fieldsToSend
    );
     
    //response from http connection to url
    $response = wp_remote_post( $fullURL, $args );

    //this is the body of the response striped of headers
    $body = wp_remote_retrieve_body( $response );
        
    //settings json to the returned body.     
    $array = json_decode($body, true);  

    //get current file contents
	$current = file_get_contents($file);

	// Append a new person to the file
	$current .= " BEFORE IF " . print_r($array,true) . "\n";

	// Write the contents back to the file
	file_put_contents(sanitize_text_field($file), $current);  
    
    if ($array["status"] == 1) {
    	//get current file contents
	    $current = file_get_contents($file);

		// Append a new person to the file
		$current .= " Success " . print_r($array,true) . " at ".date("Y-m-d h:i:s a")."\n";

		// Write the contents back to the file
		file_put_contents(sanitize_text_field($file), $current);

    } else if($array["error"]["error_code"] == 404){
    	//get current file contents
    	$current = file_get_contents($file);

		// Append a new person to the file
		$current .= "404 ERROR " . print_r($array,true) . " BD list name: " . BDOA_LIST_NAME ." at ".date("Y-m-d h:i:s a")."\n";

		// Write the contents back to the file
		file_put_contents(sanitize_text_field($file), $current);

		//update List Id call
        bdoaupdateListId(BDOA_LIST_NAME,$fieldsArray);

    } else {
    	//get current file contents
	    $current = file_get_contents($file);

		// Append a new person to the file
		$current .= " ERROR " . print_r($array,true) . " at ".date("Y-m-d h:i:s a")."\n";

		// Write the contents back to the file
		file_put_contents(sanitize_text_field($file), $current);
    }
}