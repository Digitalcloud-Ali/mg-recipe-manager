<?php
/**
 * Template for displaying single recipe
 *
 * @package MG_Recipe_Manager
 * @since 1.0.0
 */

get_header();
?>

<div id="primary" class="content-area">
    <main id="main" class="site-main">
        <?php
        while (have_posts()) :
            the_post();
            ?>
            <article id="post-<?php the_ID(); ?>" <?php post_class('mg-recipe'); ?>>
                <header class="entry-header">
                    <?php the_title('<h1 class="entry-title">', '</h1>'); ?>
                </header>

                <?php if (has_post_thumbnail()) : ?>
                    <div class="mg-recipe-image">
                        <?php the_post_thumbnail('large'); ?>
                    </div>
                <?php endif; ?>

                <div class="entry-content">
                    <?php the_content(); ?>
                </div>

                <div class="mg-recipe-meta">
                    <h2><?php esc_html_e('Ingredients', 'mg-recipe-manager'); ?></h2>
                    <pre><?php echo esc_html(get_post_meta(get_the_ID(), '_mg_recipe_ingredients', true)); ?></pre>

                    <h2><?php esc_html_e('Instructions', 'mg-recipe-manager'); ?></h2>
                    <pre><?php echo esc_html(get_post_meta(get_the_ID(), '_mg_recipe_instructions', true)); ?></pre>
                </div>

                <footer class="entry-footer">
                    <?php
                    $categories_list = get_the_term_list(get_the_ID(), 'recipe_category', '', ', ');
                    if ($categories_list) {
                        /* translators: %s: list of categories */
                        printf('<span class="cat-links">' . esc_html__('Categories: %1$s', 'mg-recipe-manager') . '</span>', wp_kses_post($categories_list));
                    }

                    $tags_list = get_the_term_list(get_the_ID(), 'recipe_tag', '', ', ');
                    if ($tags_list) {
                        /* translators: %s: list of tags */
                        printf('<span class="tags-links">' . esc_html__('Tags: %1$s', 'mg-recipe-manager') . '</span>', wp_kses_post($tags_list));
                    }
                    ?>
                </footer>
            </article>
        <?php
        endwhile;
        ?>
    </main>
</div>

<?php
get_sidebar();
get_footer();