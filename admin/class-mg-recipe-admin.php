<?php
/**
 * MG Recipe Manager Admin
 *
 * @package MG_Recipe_Manager
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

class MG_Recipe_Admin {
    public function __construct() {
        add_action('admin_menu', array($this, 'add_admin_menu'));
        add_action('admin_init', array($this, 'register_settings'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_scripts'));
    }

    public function add_admin_menu() {
        add_menu_page('MG Recipe Manager', 'Recipes', 'manage_options', 'mg-recipe-manager', array($this, 'render_admin_page'), 'dashicons-food', 20);
        add_submenu_page('mg-recipe-manager', 'Settings', 'Settings', 'manage_options', 'mg-recipe-manager-settings', array($this, 'render_settings_page'));
    }

    public function render_admin_page() {
        ?>
        <div class="wrap">
            <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
            <p><?php esc_html_e('Welcome to the MG Recipe Manager plugin!', 'mg-recipe-manager'); ?></p>
            <h2><?php esc_html_e('Recent Recipes', 'mg-recipe-manager'); ?></h2>
            <?php
            $args = array(
                'post_type' => 'recipe',
                'posts_per_page' => 5,
            );
            $recent_recipes = new WP_Query($args);
            if ($recent_recipes->have_posts()) :
                ?>
                <ul>
                    <?php while ($recent_recipes->have_posts()) : $recent_recipes->the_post(); ?>
                        <li><a href="<?php echo esc_url(get_edit_post_link()); ?>"><?php echo esc_html(get_the_title()); ?></a></li>
                    <?php endwhile; ?>
                </ul>
                <?php
                wp_reset_postdata();
            else :
                esc_html_e('No recipes found.', 'mg-recipe-manager');
            endif;
            ?>
        </div>
        <?php
    }

    public function render_settings_page() {
        ?>
        <div class="wrap">
            <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
            <form action="options.php" method="post">
                <?php
                settings_fields('mg_recipe_manager_settings');
                do_settings_sections('mg-recipe-manager-settings');
                submit_button();
                ?>
            </form>
        </div>
        <?php
    }

    public function register_settings() {
        register_setting('mg_recipe_manager_settings', 'mg_recipe_manager_options');

        add_settings_section(
            'mg_recipe_manager_general_section',
            __('General Settings', 'mg-recipe-manager'),
            array($this, 'render_general_section'),
            'mg-recipe-manager-settings'
        );

        add_settings_field(
            'mg_recipe_manager_display_author',
            __('Display Author', 'mg-recipe-manager'),
            array($this, 'render_display_author_field'),
            'mg-recipe-manager-settings',
            'mg_recipe_manager_general_section'
        );
    }

    public function render_general_section() {
        echo '<p>' . esc_html__('Configure general settings for the MG Recipe Manager plugin.', 'mg-recipe-manager') . '</p>';
    }

    public function render_display_author_field() {
        $options = get_option('mg_recipe_manager_options');
        $display_author = isset($options['display_author']) ? $options['display_author'] : 0;
        ?>
        <input type="checkbox" id="mg_recipe_manager_display_author" name="mg_recipe_manager_options[display_author]" value="1" <?php checked(1, $display_author); ?>>
        <label for="mg_recipe_manager_display_author"><?php esc_html_e('Display recipe author on frontend', 'mg-recipe-manager'); ?></label>
        <?php
    }

    public function enqueue_admin_scripts($hook) {
        if ('toplevel_page_mg-recipe-manager' !== $hook && 'recipes_page_mg-recipe-manager-settings' !== $hook) {
            return;
        }

        wp_enqueue_style('mg-recipe-manager-admin-style', MG_RECIPE_MANAGER_PLUGIN_URL . 'assets/css/mg-recipe-manager-admin.css', array(), MG_RECIPE_MANAGER_VERSION);
        wp_enqueue_script('mg-recipe-manager-admin-script', MG_RECIPE_MANAGER_PLUGIN_URL . 'assets/js/mg-recipe-manager-admin.js', array('jquery'), MG_RECIPE_MANAGER_VERSION, true);
    }
}