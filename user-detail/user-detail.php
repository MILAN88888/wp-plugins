<?php

/**
 * Plugin Name:       user-detail
 * Plugin URI:        https:milankumarchaudhary.com.np
 * Description:       give the list of user details
 * Version:           1.0.0
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            Milan Kumar Chaudhary
 * Author URI:        https:milankumarchaudhary.com.np
 */

if (!defined('ABSPATH')) {
    die("You can't access directly");
}

/**
 * User_Detail class having all function for user related
 * 
 */
class User_Detail
{

    /**
     * Constructor of class
     * 
     */
    public function __construct()
    {
        add_action('plugin_loaded', array($this, 'user_load_plugin_textdomain'));

        //style and script action
        add_action('wp_enqueue_scripts', array($this, 'user_enqueue'));
        add_action('wp_ajax_search_user', array($this, 'search_user'));
        add_action('wp_ajax_user_pagination', array($this, 'user_pagination'));
        add_action('wp_ajax_user_sorting', array($this, 'user_sorting'));

        //User details shortcode
        add_shortcode('user_details', array($this, 'user_details'));
    }

    /**
     * Function for user sorting
     * 
     */
    public function user_sorting()
    {
        //checking and verifing the nonce
        if (isset($_POST['security']) && wp_verify_nonce(sanitize_key($_POST['security']), 'search_nonce')) {
            $select_input = isset($_POST['select_input']) ? sanitize_text_field($_POST['select_input']) : '';
            $total = get_users();
            if ($select_input == 'name') {
                $user_query = get_users(array('order' => 'ASC', 'orderby' => 'display_name', 'number' => 5));
            } elseif ($select_input == 'id') {

                $user_query = get_users(array('order' => 'ASC', 'orderby' => 'ID', 'number' => 5));
            } elseif ($select_input == 'role') {

                $user_query = get_users(array('meta_key' => 'wp_capabilities', 'orderby' => 'meta_value', 'number' => 5));
            }
            $i = 1;
            _e($this->user_table($user_query, $total, $i));
        } else {
            _e('error');
        }
        wp_die();
    }
    /**
     * Function for user pagination
     * 
     */
    public function user_pagination()
    {
        //checking and verifing the nonce
        if (isset($_POST['security']) && wp_verify_nonce(sanitize_key($_POST['security']), 'search_nonce')) {
            $search_input = isset($_POST['search_input']) ? sanitize_text_field($_POST['search_input']) : '';
            if ($search_input == '') {
                $i = isset($_POST['i']) ? sanitize_text_field($_POST['i']) : '';
                $user_query = get_users(
                    array(
                        'number' => 5,
                        'offset' => $i == 1 ? 0 : ($i - 1) * 5
                    )
                );
                $total = get_users();
            } else {
                $i = isset($_POST['i']) ? sanitize_text_field($_POST['i']) : '';
                $user_query = get_users(
                    array(
                        'search' => '*' . esc_attr($search_input) . '*',
                        'search_columns' => array(
                            'user_login',
                            'display_name',
                        ),
                        'number' => 5,
                        'offset' => $i == 1 ? 0 : ($i - 1) * 5
                    )
                );
                $total =  get_users(
                    array(
                        'search' => '*' . esc_attr($search_input) . '*',
                        'search_columns' => array(
                            'display_name',
                            'user_login',
                        )
                    )
                );
            }
            //calling to display table
            _e($this->user_table($user_query, $total, $i));
        } else {
            _e('error');
        }
        wp_die();
    }
    /**
     * Function for search  user 
     * 
     */
    public function search_user()
    {

        //checking and verifing the nonce
        if (isset($_POST['security']) && wp_verify_nonce(sanitize_key($_POST['security']), 'search_nonce')) {

            $search_input = isset($_POST['search_input']) ? sanitize_text_field($_POST['search_input']) : '';
            if ($search_input == '') {
                $total = get_users();
                $user_query = get_users(array('number' => 5));
            } else {
                $roles = array('administrator', 'editor', 'author', 'contributor', 'subscriber');
                if (in_array($search_input, $roles)) {

                    $user_query = get_users(
                        array(
                            'role' => $search_input,
                            'number' => 5,
                        )
                    );
                    $total = get_users(
                        array(
                            'role' => '*' . esc_attr($search_input) . '*',
                        )
                    );
                } else {
                    $user_query = get_users(
                        array(
                            'search' => '*' . esc_attr($search_input) . '*',
                            'search_columns' => array(
                                'user_login',
                                'user_display',
                            ),
                            'number' => 5,
                        )
                    );
                    $total = get_users(
                        array(
                            'search' => '*' . esc_attr($search_input) . '*',
                            'search_columns' => array(
                                'user_login',
                                'user_display',
                            )
                        )
                    );
                }
                // global $wpdb;
                // global $table_prefix;
                // $search_input = '%'.$search_input.'%';
                // $table = $table_prefix . 'users';
                // $tabl_2 = $table_prefix.'usermeta';
                // $sql = $wpdb->prepare("SELECT u.*, m.meta_value FROM $table as u INNER JOIN $tabl_2 as m ON u.ID = m.user_id  WHERE u.display_name LIKE %s OR m.meta_key LIKE 'wp_capabilities' AND m.meta_value LIKE %s", $search_input, $search_input);
                // $user_query =$wpdb->get_results($sql);

                // var_dump($user_query);
                // die();
            }
            $i = 1;
            _e($this->user_table($user_query, $total, $i));
        } else {
            _e('error');
        }
        wp_die();
    }

    /**
     * Function to display user details in user table
     * 
     * @var $users array of user lists
     * @var $total 
     * @var $i
     */
    public function user_table(array $users, array $total, int $i)
    {
?>
        <table class="table">
            <tr>
                <th>SN</th>
                <th>Name</th>
                <th>Display Name</th>
                <th>Role</th>
            </tr>
            <?php
            $i = $i == 1 ? 1 : ($i - 1) * 5 + 1;
            foreach ($users as $user_detail) {
            ?>
                <tr>
                    <td><?php esc_html_e($i, 'user-detail') ?></td>
                    <td><?php esc_html_e($user_detail->user_nicename, 'user-detail') ?></td>
                    <td><?php esc_attr_e($user_detail->display_name, 'user-detail') ?></td>
                    <td><?php esc_attr_e($user_detail->roles[0], 'user-detail') ?></td>
                </tr>
            <?php
                $i++;
            }
            ?>
        </table>
        <?php
        //calculating pagination
        $number_of_users = count($total);
        $ceil = ceil($number_of_users / 5);
        for ($i = 1; $i <= $ceil; $i++) {
        ?>
            <button onclick="pagi('<?php esc_html_e($i) ?>')"><?php esc_html_e($i) ?></button>
            <?php
        }
        return;
    }
    /**
     * Function show the table of user lists with pagination
     * 
     */
    public function user_details()
    {
        if (is_user_logged_in()) {

            $users = wp_get_current_user();
            $roles = (array)$users->roles;
            if ($roles[0] == 'administrator' || $roles[0] == 'editor') {

                $total = get_users();
                $users = get_users(array('number' => 5));

                ob_start();
            ?>
                <div class="container">
                    <h5> <?php _e('User Detail Lists', 'user-detail') ?> </h5>
                    <div class="search-div">
                        <label>Filter</label>
                        <select onchange="select_func()" id="select_input" name="select_input" class="ml-2">
                            <option value="name">Display Name</option>
                            <option value="id">Id</option>
                            <option value="role">Role</option>
                        </select>
                        <label><?php _e('Search', 'user-detail') ?></label>
                        <input type="text" name="search_input" id="search_input" placeholder="Enter User Name or  Role" />


                    </div>
                    <div class="table-div">
                        <?php
                        $i = 1;
                        //calling the user table
                        $this->user_table($users, $total, $i);
                        ?>
                    </div>
                </div>

            <?php
                return ob_get_clean();
            } else {
            ?>
                <div class="alert">
                    <p> <?php _e('Only administrator and editor can see the details of all users', 'user-detail') ?></p>
                </div>
<?php
            }
        }
    }

    /**
     * Function to load style and script 
     * 
     */
    public function user_enqueue()
    {
        // style
        wp_register_style('user-style', plugins_url('/user-detail/assets/css/user-detail.css'), false, 'all');
        wp_enqueue_style('user-style');
        wp_register_style('bootstrap-style', plugins_url('/user-detail/assets/bootstrap/css/bootstrap.min.css'), false, 'all');
        wp_enqueue_style('bootstrap-style');

        //scripts
        wp_register_script('user-js', plugins_url('/user-detail/assets/js/user-detail.js'), array('jquery'), '', true);
        wp_localize_script('user-js', 'user_js', array('ajaxurl' => admin_url('admin-ajax.php'), 'user_js_nonce' => wp_create_nonce('search_nonce')));
        wp_enqueue_script('user-js');
    }

    /**
     * Function to load plugin textdomain
     * 
     */
    public function user_load_plugin_textdomain()
    {
        load_plugin_textdomain('user-detail', false, plugin_basename(dirname(__FILE__)) . '/languages');
    }

    /**
     * Function for active the plugin
     * 
     */
    public function user_register_activation()
    {
        $this->user_details();
        flush_rewrite_rules();
    }

    /**
     * Function for deative the plugin
     * 
     */
    public function user_register_deactivation()
    {

        flush_rewrite_rules();
    }
}

// creating the object of User_Detail Class
if (class_exists('User_Detail')) {
    $user_detail = new User_Detail();
}

// Registration Hook
register_activation_hook(__FILE__, array($user_detail, 'user_register_activation'));
register_deactivation_hook(__FILE__, array($user_detail, 'user_register_deactivation '));
