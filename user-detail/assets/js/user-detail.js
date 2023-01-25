jQuery(document).ready(function () {
    jQuery('#search_input').change(function () {
        var search_input = jQuery('#search_input').val();
        jQuery.ajax({
            url:user_js.ajaxurl,
            method:'post',
            data:{action:'search_user', 'search_input':search_input, 'security':user_js.user_js_nonce},
            success: function(res){
                jQuery('.table-div').html(res);
            }
        })
    })

});
function pagi(i) {
    search_input = jQuery('#seach_input').val();
    console.log(search_input);
    jQuery.ajax({
        url:user_js.ajaxurl,
        method:'post',
        data:{action:'user_pagination', 'search_input':search_input,'i':i, 'security':user_js.user_js_nonce},
        success: function(res){
            jQuery('.table-div').html(res);
        }
    })
}
function name_select_func()
{
    select_input = jQuery('#name_select_input').val();
    jQuery.ajax({
        url:user_js.ajaxurl,
        method:'post',
        data:{action:'user_name_sorting', 'select_input':select_input, 'security':user_js.user_js_nonce},
        success: function(res){
            jQuery('.table-div').html(res);
        }
    })
}
function role_select_func()
{
    select_input = jQuery('#role_select_input').val();
    jQuery.ajax({
        url:user_js.ajaxurl,
        method:'post',
        data:{action:'user_role_sorting', 'select_input':select_input, 'security':user_js.user_js_nonce},
        success: function(res){
            jQuery('.table-div').html(res);
        }
    })
}
function id_select_func()
{
    select_input = jQuery('#id_select_input').val();
    jQuery.ajax({
        url:user_js.ajaxurl,
        method:'post',
        data:{action:'user_id_sorting', 'select_input':select_input, 'security':user_js.user_js_nonce},
        success: function(res){
            jQuery('.table-div').html(res);
        }
    })
}
