<?php
/**
 * MG Recipe Manager Post Type
 *
 * @package MG_Recipe_Manager
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

class MG_Recipe_Post_Type {
    public function __construct() {
        add_action('init', array($this, 'register_recipe_post_type'));
        add_action('init', array($this, 'register_recipe_taxonomies'));
        add_action('add_meta_boxes', array($this, 'add_recipe_meta_boxes'));
        add_action('save_post', array($this, 'save_recipe_meta'));
    }

    public function register_recipe_post_type() {
        $labels = array(
            'name'               => _x('Recipes', 'post type general name', 'mg-recipe-manager'),
            'singular_name'      => _x('Recipe', 'post type singular name', 'mg-recipe-manager'),
            'menu_name'          => _x('Recipes', 'admin menu', 'mg-recipe-manager'),
            'add_new'            => _x('Add New', 'recipe', 'mg-recipe-manager'),
            'add_new_item'       => __('Add New Recipe', 'mg-recipe-manager'),
            'edit_item'          => __('Edit Recipe', 'mg-recipe-manager'),
            'new_item'           => __('New Recipe', 'mg-recipe-manager'),
            'view_item'          => __('View Recipe', 'mg-recipe-manager'),
            'search_items'       => __('Search Recipes', 'mg-recipe-manager'),
            'not_found'          => __('No recipes found', 'mg-recipe-manager'),
            'not_found_in_trash' => __('No recipes found in Trash', 'mg-recipe-manager'),
        );

        $args = array(
            'labels'             => $labels,
            'public'             => true,
            'publicly_queryable' => true,
            'show_ui'            => true,
            'show_in_menu'       => true,
            'query_var'          => true,
            'rewrite'            => array('slug' => 'recipe'),
            'capability_type'    => 'post',
            'has_archive'        => true,
            'hierarchical'       => false,
            'menu_position'      => null,
            'supports'           => array('title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments'),
            'menu_icon'          => 'dashicons-food',
        );

        register_post_type('recipe', $args);
    }

    public function register_recipe_taxonomies() {
        // Register Category taxonomy
        register_taxonomy('recipe_category', 'recipe', array(
            'hierarchical' => true,
            'labels' => array(
                'name' => _x('Recipe Categories', 'taxonomy general name', 'mg-recipe-manager'),
                'singular_name' => _x('Recipe Category', 'taxonomy singular name', 'mg-recipe-manager'),
            ),
            'show_ui' => true,
            'show_admin_column' => true,
            'query_var' => true,
            'rewrite' => array('slug' => 'recipe-category'),
        ));

        // Register Tag taxonomy
        register_taxonomy('recipe_tag', 'recipe', array(
            'hierarchical' => false,
            'labels' => array(
                'name' => _x('Recipe Tags', 'taxonomy general name', 'mg-recipe-manager'),
                'singular_name' => _x('Recipe Tag', 'taxonomy singular name', 'mg-recipe-manager'),
            ),
            'show_ui' => true,
            'show_admin_column' => true,
            'query_var' => true,
            'rewrite' => array('slug' => 'recipe-tag'),
        ));
    }

    public function add_recipe_meta_boxes() {
        add_meta_box('recipe_details', 'Recipe Details', array($this, 'render_recipe_meta_box'), 'recipe', 'normal', 'high');
    }

    public function render_recipe_meta_box($post) {
        wp_nonce_field('mg_recipe_meta_box', 'mg_recipe_meta_box_nonce');

        $ingredients = get_post_meta($post->ID, '_mg_recipe_ingredients', true);
        $instructions = get_post_meta($post->ID, '_mg_recipe_instructions', true);

        ?>
        <p>
            <label for="mg_recipe_ingredients"><?php esc_html_e('Ingredients:', 'mg-recipe-manager'); ?></label><br>
            <textarea name="mg_recipe_ingredients" id="mg_recipe_ingredients" rows="5" cols="50"><?php echo esc_textarea($ingredients); ?></textarea>
        </p>
        <p>
            <label for="mg_recipe_instructions"><?php esc_html_e('Instructions:', 'mg-recipe-manager'); ?></label><br>
            <textarea name="mg_recipe_instructions" id="mg_recipe_instructions" rows="5" cols="50"><?php echo esc_textarea($instructions); ?></textarea>
        </p>
        <?php
    }

    public function save_recipe_meta($post_id) {
        if (!isset($_POST['mg_recipe_meta_box_nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['mg_recipe_meta_box_nonce'])), 'mg_recipe_meta_box')) {
            return;
        }

        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        if (!current_user_can('edit_post', $post_id)) {
            return;
        }

        if (isset($_POST['mg_recipe_ingredients'])) {
            update_post_meta($post_id, '_mg_recipe_ingredients', sanitize_textarea_field(wp_unslash($_POST['mg_recipe_ingredients'])));
        }

        if (isset($_POST['mg_recipe_instructions'])) {
            update_post_meta($post_id, '_mg_recipe_instructions', sanitize_textarea_field(wp_unslash($_POST['mg_recipe_instructions'])));
        }
    }
}