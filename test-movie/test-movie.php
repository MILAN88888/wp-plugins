<?php

/**
 * Plugin Name:       test-movie
 * Plugin URI:        https:milankumarchaudhary.com.np
 * Description:       movie details
 * Version:           1.0.0
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            Milan Kumar Chaudhary
 * Author URI:        https:milankumarchaudhary.com.np
 */
defined('ABSPATH') or die('error!! You cant access');
class Test_movie
{
    /**
     * Class contructor
     */
    public function __construct()
    {

        // input box select box and texarea meta boxes
        add_action('add_meta_boxes', array($this, 'custom_meta_boxs'));
        // save post for input box, select box, textarea box
        add_action('save_post', array($this, 'custom_meta_box_details'));

        // administration menu 
        add_action('admin_menu', array($this, "administration_menu"));
        // fruit custom taxonomy
        add_action('init', array($this, 'fruits_custom_taxonomy'));
        // movie custom post type 
        add_action('init', array($this, 'movie_custom_post_type'));
    }

    /**
     * Function for administration init call back
     * 
     */
    public function administration_option_init_fn()
    {
        // for registering and save 
        $inputs = array('input_field', 'radio_box', 'check_box', 'textarea_box', 'dropdown_box');
        foreach ($inputs as $input) {
            register_setting('adm-setting-group', $input);
        }

        // for section
        add_settings_section('adm-section-options', 'Adminstration Setting Option', array($this, 'adminstration_section_option'), 'adm_setting_slug');

        // for fields
        add_settings_field('input-field', 'Input Field', array($this, 'input_field_callback'), 'adm_setting_slug', 'adm-section-options', array(
            'label' => "Name:"
        ));
        add_settings_field('radio-box', 'Radio Box', array($this, 'radio_field_callback'), 'adm_setting_slug', 'adm-section-options', array(
            'label-1' => "Yes",
            'label-2' => "No",
            'label' => "have you completed engineering ?"
        ));
        add_settings_field('check-box', 'Check Box', array($this, 'check_box_callback'), 'adm_setting_slug', 'adm-section-options', array(
            'label' => 'Can you join office whenever we decide to join us ?',
            'type' => "yes"
        ));
        add_settings_field('textarea-box', 'Textarea Box', array($this, 'textarea_box_callback'), 'adm_setting_slug', 'adm-section-options', array(
            'label' => "Write About Your Selft",
        ));
        add_settings_field('dropdown-box', 'Dropdown Box', array($this, 'dropdown_box_callback'), 'adm_setting_slug', 'adm-section-options');
    }

    /**
     * Function for dropdown box
     * 
     */
    public function dropdown_box_callback()
    {
?>
        <select name="dropdown_box">
            <option>Yes</option>
            <option>No</option>
        </select>
    <?php
    }

    /**
     * Function for textarea box
     * 
     */
    public function textarea_box_callback($args)
    {
    ?>
        <label><?php esc_html_e($args['label']) ?></label><br>
        <textarea name="textarea_box"></textarea>
    <?php
    }

    /**
     * Function for check box
     * 
     */
    public function check_box_callback($args)
    {
    ?>
        <label><?php esc_html_e($args['label']) ?></label>
        <input type='checkbox' name='check_box' value='Yes' /><label><?php esc_html_e($args['type']) ?></label>
    <?php
    }

    /**
     * Function for radio button
     * 
     */
    public function radio_field_callback($args)
    {
    ?>
        <label><?php esc_html_e($args['label']) ?></label>
        <input type='radio' name='radio_box' value='Yes' /><label><?php esc_html_e($args['label-1']) ?></label>
        <input type='radio' name='radio_box' value='No' /><label><?php esc_html_e($args['label-2']) ?></label>
    <?php
    }

    /**
     * Function for input field
     * 
     */
    public function input_field_callback($args)
    {
    ?>
        <label> <?php esc_html_e($args['label']) ?></label>
        <input type='text' name='input_field' value='' />
    <?php
    }

    /**
     * Function for adminstration section
     * 
     */
    public function adminstration_section_option()
    {
    ?>
        <h4>Adminstration setting section</h4>
    <?php
    }

    /**
     * Function for adminstration form input callback
     * 
     */
    public function adm_form_input_callback()
    {
    ?>
        <input type="text" name="user_name" />
    <?php
    }

    /**
     * Function for administration menu
     * 
     */
    public function administration_menu()
    {
        add_menu_page( 'Administration', 'Administration', 'manage_options','parent',  array($this, 'administration_callback'),'', "70.23423" );
        // this 'hides' the extra.  actually, just makes the text nothing: ''  
        add_submenu_page( 'parent', '',     '', 'manage_options',   'parent',   '__return_null' );
        //ah, but this removes it completely (you need to add it, then remove it :/     
        remove_submenu_page('parent','parent');

        add_submenu_page('parent', 'administration_setting', ' Administration_setting', 'manage_options', 'adm_setting_slug', array($this, 'administration_setting_callback'));
        add_action('admin_init', array($this, 'administration_option_init_fn'));
}
    public function wpdocs_orders_function(){

    }

    /**
     * Function for administration callback
     * 
     */
    public function administration_callback()
    {
        if (!current_user_can('manage_options')) {
            return;
        }
    ?>
        <h1>Administration</h1>
    <?php
    }

    /**
     * Function for administration setting callback
     * 
     */
    public function administration_setting_callback()
    {
        // check the user capability
        if (!current_user_can('manage_options')) {
            return;
        }

        // get the setting saved message
        if (isset($_GET['settings-updated'])) {

            add_settings_error('adm_messages', 'adm_message', 'setting saved', 'updated');
        }

        settings_errors('adm_messages');
    ?>
        <form method="post" action="options.php">
            <?php
            settings_fields('adm-setting-group');
            do_settings_sections("adm_setting_slug");
            submit_button('Save Details');
            ?>
        </form>
        <?php
        $name = get_option('input_field');
        $engineering = get_option('radio_box');
        $join_office = get_option('check_box');
        $about = get_option('textarea_box');
        $dropdown = get_option('dropdown_box');
        ?>
        <!--Saved Details List -->
        <caption>Your Details</caption>
        <table>
            <tr>
                <th>Name</th>
                <td><?php esc_html_e($name) ?></td>
            </tr>
            <tr>
                <th>Engineering</th>
                <td><?php esc_html_e($engineering) ?></td>
            </tr>
            <tr>
                <th>Join Office</th>
                <td><?php esc_html_e($join_office) ?></td>
            </tr>
            <tr>
                <th>About</th>
                <td><?php esc_html_e($about) ?></td>
            </tr>
            <tr>
                <th>Dropdown</th>
                <td><?php esc_html_e($dropdown) ?></td>
            </tr>
        </table>
        <!--End of saved detail list -->
    <?php
    }

    /**
     * Function for fruits custom taxonomy
     */
    public function fruits_custom_taxonomy()
    {
        $labels = array(
            'name' => 'Fruits',
            'singular_name' => 'Fruit',
            'parent_item'=>'Parent Fruit',
            'search_items' => "search fruit",
            'update_item'=>"Update Fruit",
            'edit_item'=>"Edit Fruit",
            'add_new_item'=>"Add New Fruit",
            'new_item_name'=>'New Fruit Name',
            
        );
        $args = array(
            'hierarchical' => true,
            'labels' => $labels,
            'hierarchical' => true,
            'show_ui' => true,
            'show_admin_column' => true,
            'query_var' => true,
            'rewrite' => ['slug' => 'fruits'],
        );
        register_taxonomy('fruits', ['movie'], $args);
    }


    /**
     * Function add movie costum meta box
     * 
     */
    public function movie_custom_meta_boxs()
    {
        add_meta_box(
            'movie_director_box',
            'movie director',
            array($this, 'movie_director_html'),
            'movie',
            'side'
        );
        add_meta_box(
            'movie_casts_box',
            'movie casts',
            array($this, 'movie_casts_html'),
            'movie',
            'side'
        );
        add_meta_box(
            'movie_release_date_box',
            'movie release date',
            array($this, 'movie_release_date_html'),
            'movie',
            'side'
        );
    }
    public function movie_director_html($post)
    {
        $value = get_post_meta($post->ID, '_movie_director', true);
    ?>
        <input type="text" name="movie_director" id="movie_director" value="<?php esc_attr_e($value); ?>" />
    <?php
    }
    public function movie_casts_html($post)
    {
        $value = get_post_meta($post->ID, '_movie_casts', true);

    ?>
        <input type="text" name="movie_casts" id="movie_casts" value="<?php esc_attr_e($value) ?>" />
    <?php
    }
    public function movie_release_date_html($post)
    {
        $value = get_post_meta($post->ID, '_movie_release_date', true);

    ?>
        <input type="text" name="movie_release_date" id="movie_release_date" value="<?php esc_attr_e($value) ?>" />
    <?php
    }

    /**
     * Function to save the movie details
     * 
     */
    public function movie_details($post_id)
    {
        $movie_director = isset($_POST['movie_director']) ? sanitize_text_field($_POST['movie_director']) : '';
        $movie_casts = isset($_POST['movie_casts']) ? sanitize_text_field($_POST['movie_casts']) : '';
        $movie_release_date = isset($_POST['movie_release_date']) ? sanitize_text_field($_POST['movie_release_date']) : '';

        $details = array(
            '_movie_director' => $movie_director, '_movie_casts' => $movie_casts, '_movie_release_date' => $movie_release_date
        );
        foreach ($details as $key => $value) {
            update_post_meta(
                $post_id,
                $key,
                $value
            );
        }
    }
    /**
     * Function to add costum post product detail form
     * 
     */
    public function custom_meta_boxs()
    {
        $screen = ['post'];
        add_meta_box(
            'custom_text_box',
            'custom text box',
            array($this, 'custom_text_box_html'),
            $screen,
            'side'

        );
        add_meta_box(
            'custom_select_box',
            'custom select box',
            array($this, 'custom_select_box_html'),
            $screen,
            'side'
        );
        add_meta_box(
            'custom_textarea_box',
            'custom textarea box',
            array($this, 'custom_textarea_html'),
            $screen,
            'side'
        );
    }
    /**
     * Function for select box html
     * 
     */
    public function custom_select_box_html($post)
    {
        $value = get_post_meta($post->ID, '_custom_select_box', true);
    ?>

        <select name="input_select">
            <option value="Yes" <?php selected($value, 'Yes'); ?>>Yes</option>
            <option value="No" <?php selected($value, 'No') ?>>No</option>
        </select>
    <?php
    }

    /**
     * Function for textarea html
     * 
     */
    public function custom_textarea_html($post)
    {
        $value = get_post_meta($post->ID, '_custom_textarea_box', true);
    ?>

        <textarea name="input_textarea"><?php echo esc_textarea($value) ?></textarea>
    <?php
    }

    /**
     * Function of custom text box html
     * 
     */
    public function custom_text_box_html($post)
    {
        $value = get_post_meta($post->ID, '_custom_text_box', true);
    ?>
        <input type="text" name="input_text" id="text_input" value="<?php esc_attr_e($value) ?>" />
<?php
    }

    /**
     * Function to save custom meta boxes
     * 
     * @var $post_id is id of post
     */
    public function custom_meta_box_details($post_id)
    {
        $input_value1 = isset($_POST['input_textarea']) ? sanitize_text_field($_POST['input_textarea']) : '';
        $input_value2 = isset($_POST['input_select']) ? sanitize_text_field($_POST['input_select']) : '';
        $input_value3 = isset($_POST['input_text']) ? sanitize_text_field($_POST['input_text']) : '';
        $details = array(
            '_custom_textarea_box' => $input_value1,
            '_custom_select_box' => $input_value2,
            '_custom_text_box' => $input_value3
        );
        foreach ($details as $key => $value) {
            update_post_meta(
                $post_id,
                $key,
                $value
            );
        }
    }

    /**
     * Function custom post type
     * 
     */
    public function movie_custom_post_type()
    {
         // movie meta boxes
         add_action('add_meta_boxes', array($this, 'movie_custom_meta_boxs'));
         //save movie meta boxes
         add_action('save_post', array($this, 'movie_details'));

        $labels = array(
            'name' => 'Movies',
            'singular_name' => 'Movie',
            'add_new' => 'Add Movie',
            'add_new_item' => "Movie Title",
        );
        $supports = array(
            'title', 'editor', 'thumbnail', 'comments', 'excerpt'
        );
        register_post_type('movie', array(
            'labels' => $labels,
            'description' => "we can add movies",
            'public' => true,
            'has_archive' => true,
            'rewrite' => array('slug' => 'movies'),
            'publicly_queryable' => true,
            'show_ui' => true,
            'supports' => $supports,
            'taxonomies' => array('fruits')
        ));
    }
    /**
     * Funciton to active plugin
     * 
     */
    public function test_register_activate()
    {
        flush_rewrite_rules();
    }

    /**
     * Function to deactive the plugins
     */
    public function test_register_deactivate()
    {
        flush_rewrite_rules();
    }
}
// If class is exit the create object of class
if (class_exists('Test_movie')) {
    $movie = new Test_movie();
}

//activation and deactivation hook for plugin
register_activation_hook(__FILE__, array($movie, 'test_register_activate'));
register_deactivation_hook(__FILE__, array($movie, 'test_register_deactivate'));
