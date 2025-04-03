<?php

/**
 * Template Name: Liste des Jeux
 * Template Post Type: page
 */
get_header();
?>
<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/ludotheque-styles.css">


<div class="container mx-auto px-4 py-8">
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        <?php
        $args = array(
            'post_type' => 'jeu',
            'posts_per_page' => 12,
            'paged' => get_query_var('paged') ? get_query_var('paged') : 1
        );

        $jeux_query = new WP_Query($args);

        if ($jeux_query->have_posts()) :
            while ($jeux_query->have_posts()) : $jeux_query->the_post();
        ?>
                <div class="game-card bg-white rounded-lg overflow-hidden shadow-lg transition-transform hover:scale-105">
                    <a href="<?php the_permalink(); ?>">
                        <?php if (has_post_thumbnail()) : ?>
                            <div class="game-thumbnail h-48 overflow-hidden">
                                <?php the_post_thumbnail('medium', array('class' => 'w-full h-full object-cover')); ?>
                            </div>
                        <?php else : ?>
                            <div class="game-thumbnail h-48 bg-gray-200 flex items-center justify-center">
                                <span class="text-gray-500">Pas d'image</span>
                            </div>
                        <?php endif; ?>

                        <div class="p-4">
                            <h2 class="text-xl font-bold mb-2"><?php the_title(); ?></h2>

                            <?php
                            // Affichage des types de jeu
                            $types = get_the_terms(get_the_ID(), 'type-jeu');
                            if (!empty($types) && !is_wp_error($types)) :
                            ?>
                                <div class="mb-2 flex flex-wrap gap-1">
                                    <?php foreach ($types as $type) : ?>
                                        <span
                                            class="bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded"><?php echo $type->name; ?></span>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>

                            <?php
                            // Informations du jeu
                            $joueurs_min = get_field('joueurs_min');
                            $joueurs_max = get_field('joueurs_max');
                            $duree = get_field('duree');
                            $age = get_field('age');
                            ?>

                            <div class="game-meta text-sm text-gray-600 mb-2">
                                <?php if ($joueurs_min && $joueurs_max) : ?>
                                    <div class="flex items-center gap-2 mb-1">
                                        <span class="dashicons dashicons-groups"></span>
                                        <span><?php echo $joueurs_min; ?> - <?php echo $joueurs_max; ?> joueurs</span>
                                    </div>
                                <?php endif; ?>

                                <?php if ($duree) : ?>
                                    <div class="flex items-center gap-2 mb-1">
                                        <span class="dashicons dashicons-clock"></span>
                                        <span>Environ <?php echo $duree; ?> min</span>
                                    </div>
                                <?php endif; ?>

                                <?php if ($age) : ?>
                                    <div class="flex items-center gap-2">
                                        <span class="dashicons dashicons-admin-users"></span>
                                        <span><?php echo $age; ?>+ ans</span>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <?php
                            // Note moyenne
                            $note_moyenne = calculer_note_moyenne(get_the_ID());
                            if ($note_moyenne > 0) :
                            ?>
                                <div class="flex items-center mb-2">
                                    <div class="text-yellow-400 mr-1">
                                        <?php
                                        // Afficher les étoiles
                                        for ($i = 1; $i <= 5; $i++) {
                                            if ($i <= $note_moyenne) {
                                                echo '<span class="dashicons dashicons-star-filled"></span>';
                                            } elseif ($i <= $note_moyenne + 0.5) {
                                                echo '<span class="dashicons dashicons-star-half"></span>';
                                            } else {
                                                echo '<span class="dashicons dashicons-star-empty"></span>';
                                            }
                                        }
                                        ?>
                                    </div>
                                    <span class="text-sm text-gray-600">(<?php echo $note_moyenne; ?>/5)</span>
                                </div>
                            <?php endif; ?>

                            <div class="mt-4">
                                <span class="block text-orange-500 font-medium hover:underline">Voir le détail →</span>
                            </div>
                        </div>
                    </a>
                </div>
            <?php
            endwhile;
            wp_reset_postdata();
        else :
            ?>
            <div class="col-span-full text-center py-12">
                <p class="text-xl text-gray-600">Aucun jeu trouvé</p>
            </div>
        <?php endif; ?>
    </div>

    <!-- Pagination -->
    <?php if ($jeux_query->max_num_pages > 1) : ?>
        <div class="mt-12 flex justify-center">
            <div class="pagination flex gap-2">
                <?php
                echo paginate_links(array(
                    'base' => str_replace(999999999, '%#%', esc_url(get_pagenum_link(999999999))),
                    'format' => '?paged=%#%',
                    'current' => max(1, get_query_var('paged')),
                    'total' => $jeux_query->max_num_pages,
                    'prev_text' => '&laquo; Précédent',
                    'next_text' => 'Suivant &raquo;',
                    'type' => 'list',
                ));
                ?>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php get_footer(); ?>