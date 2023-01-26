jQuery(document).ready(function () {
    jQuery('#search_input').change(function () {
        var search_input = jQuery('#search_input').val();
        jQuery.ajax({
            url:user_js.ajaxurl,
            method:'post',
            data:{action:'user_sorting', 'search_input':search_input, 'security':user_js.user_js_nonce},
            success: function(res){
                jQuery('.table-div').html(res);
            }
        })
    })

});
function pagi(i) {
    search_value = jQuery('#seach_input').val();
    console.log(search_input);
    jQuery.ajax({
        url:user_js.ajaxurl,
        method:'post',
        data:{action:'user_sorting', 'pagi_input':'pagi_input', 'search_value':search_value,'i':i, 'security':user_js.user_js_nonce},
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
        data:{action:'user_sorting', 'name_input':select_input, 'security':user_js.user_js_nonce},
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
        data:{action:'user_sorting', 'role_input':select_input, 'security':user_js.user_js_nonce},
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
        data:{action:'user_sorting', 'id_input':select_input, 'security':user_js.user_js_nonce},
        success: function(res){
            jQuery('.table-div').html(res);
        }
    })
}
