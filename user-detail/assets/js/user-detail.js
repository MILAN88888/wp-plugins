//Ajax call for search the use by name, role and ID
jQuery(document).ready(function () {
    jQuery('#search_input').change(function () {
        var search_input = jQuery('#search_input').val();
        jQuery.ajax({
            url:user_js.ajaxurl,
            method:'post',
            data:{action:'user_data', 'search_input':search_input, 'security':user_js.user_js_nonce},
            success: function(res){
                jQuery('.table-div').html(res);
            }
        })
    })

});

// Function for pagination
function pagi(i) {
    search_value = jQuery('#seach_input').val();
    jQuery.ajax({
        url:user_js.ajaxurl,
        method:'post',
        data:{action:'user_data', 'pagi_input':'pagi_input', 'search_value':search_value,'i':i, 'security':user_js.user_js_nonce},
        success: function(res){
            jQuery('.table-div').html(res);
        }
    })
}
//Function for sorting user by Name
function name_select_func()
{
    select_input = jQuery('#name_select_input').val();
    jQuery.ajax({
        url:user_js.ajaxurl,
        method:'post',
        data:{action:'user_data', 'name_input':select_input, 'security':user_js.user_js_nonce},
        success: function(res){
            jQuery('.table-div').html(res);
        }
    })
}
//Function for sorting the user by Role
function role_select_func()
{
    select_input = jQuery('#role_select_input').val();
    jQuery.ajax({
        url:user_js.ajaxurl,
        method:'post',
        data:{action:'user_data', 'role_input':select_input, 'security':user_js.user_js_nonce},
        success: function(res){
            jQuery('.table-div').html(res);
        }
    })
}
//Function for sorting the user by their ID
function id_select_func()
{
    select_input = jQuery('#id_select_input').val();
    jQuery.ajax({
        url:user_js.ajaxurl,
        method:'post',
        data:{action:'user_data', 'id_input':select_input, 'security':user_js.user_js_nonce},
        success: function(res){
            jQuery('.table-div').html(res);
        }
    })
}
