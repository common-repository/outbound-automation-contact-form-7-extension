jQuery(document).ready(function(){
  jQuery(".wpcf7-submit").on("click", function(){
    var fields = {};
    jQuery(".wpcf7-form input").each( function(index){  
        var input = jQuery(this);
        var fieldName = input.attr("name");
        var fieldValue = input.val();

        fields[fieldName] = fieldValue;

        console.info("Type: " + input.attr("type") + " Name: " + input.attr("name") + " Value: " + input.val());
    });
    jQuery.ajax({
        url : ajax_script_bdoa.BDOA,
        data: {
          action : 'process_bdoa_input',
          fieldsArray  : fields
        },
        type: "POST",
        success: function(response){console.info(response + " <- AJAX RESPONSE");return true;},
        error: function(response){console.info(response);}
    });
    return true;
  });
}); 


