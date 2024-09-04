<?php
/**
 * MG Recipe Manager Shortcode
 *
 * @package MG_Recipe_Manager
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

class MG_Recipe_Shortcode {
    public function __construct() {
        add_shortcode('mg_recipe_manager', array($this, 'render_random_recipe'));
    }

    public function render_random_recipe($atts) {
        $args = array(
            'post_type' => 'recipe',
            'posts_per_page' => 1,
            'orderby' => 'rand',
        );

        $query = new WP_Query($args);

        if ($query->have_posts()) {
            ob_start();
            while ($query->have_posts()) {
                $query->the_post();
                ?>
                <div class="mg-recipe">
                    <h2><?php the_title(); ?></h2>
                    <?php if (has_post_thumbnail()) : ?>
                        <div class="mg-recipe-image">
                            <?php the_post_thumbnail('medium'); ?>
                        </div>
                    <?php endif; ?>
                    <div class="mg-recipe-content">
                        <?php the_content(); ?>
                    </div>
                    <div class="mg-recipe-meta">
                        <h3><?php esc_html_e('Ingredients', 'mg-recipe-manager'); ?></h3>
                        <pre><?php echo esc_html(get_post_meta(get_the_ID(), '_mg_recipe_ingredients', true)); ?></pre>
                        
                        <h3><?php esc_html_e('Instructions', 'mg-recipe-manager'); ?></h3>
                        <pre><?php echo esc_html(get_post_meta(get_the_ID(), '_mg_recipe_instructions', true)); ?></pre>
                    </div>
                </div>
                <?php
            }
            wp_reset_postdata();
            return ob_get_clean();
        }

        return __('No recipes found.', 'mg-recipe-manager');
    }
}