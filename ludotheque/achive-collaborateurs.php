<?php get_header(); ?>
<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/ludotheque-styles.css">

<main id="primary" class="site-main">
    <?php
    while (have_posts()) : the_post(); ?>
        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
            <header class="entry-header">
                <?php the_title('<h1 class="entry-title">', '</h1>'); ?>
            </header>

            <div class="entry-content">
                <?php the_content(); ?>
                <div class="collaborator-details">
                    <p><strong>Rôle : </strong><?php the_terms($post->ID, 'role'); ?></p>
                    <p><strong>Jeux associés : </strong>
                        <?php
                        $related_games = get_posts(array(
                            'post_type' => 'jeux',
                            'meta_query' => array(
                                array(
                                    'key' => 'related_collaborator',
                                    'value' => $post->ID,
                                    'compare' => 'LIKE'
                                )
                            )
                        ));
                        if ($related_games) {
                            echo '<ul>';
                            foreach ($related_games as $game) {
                                echo '<li><a href="' . get_permalink($game->ID) . '">' . get_the_title($game->ID) . '</a></li>';
                            }
                            echo '</ul>';
                        } else {
                            echo 'Aucun jeu associé.';
                        }
                        ?>
                    </p>
                </div>
            </div>
        </article>
    <?php endwhile; ?>
</main>

<?php get_sidebar(); ?>
<?php get_footer(); ?>