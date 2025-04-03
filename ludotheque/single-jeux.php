<?php

/**
 * Template pour l'affichage d'un jeu individuel
 */

get_header();
?>
<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/ludotheque-styles.css">


<div class="container mx-auto px-4 py-8">
    <article id="jeu-<?php the_ID(); ?>" <?php post_class('bg-white rounded-lg shadow-lg overflow-hidden'); ?>>
        <div class="md:flex">
            <!-- Galerie d'images -->
            <div class="md:w-1/2">
                <?php if (has_post_thumbnail()) : ?>
                    <div class="main-image">
                        <?php the_post_thumbnail('large', array('class' => 'w-full h-auto')); ?>
                    </div>
                <?php endif; ?>

                <?php
                $galerie = get_field('galerie');
                if ($galerie) : ?>
                    <div class="galerie grid grid-cols-4 gap-2 mt-2 p-4">
                        <?php foreach ($galerie as $image) : ?>
                            <div class="galerie-item">
                                <a href="<?php echo esc_url($image['url']); ?>" class="block">
                                    <img src="<?php echo esc_url($image['sizes']['thumbnail']); ?>"
                                        alt="<?php echo esc_attr($image['alt']); ?>" class="w-full h-auto rounded">
                                </a>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Informations du jeu -->
            <div class="md:w-1/2 p-6">
                <h1 class="text-3xl font-bold mb-2"><?php the_title(); ?></h1>

                <?php
                // Remplacer l'appel direct à the_ratings() par une solution plus robuste
                // Si vous utilisez WP-PostRatings ou un plugin similaire, décommentez ce code
                /*
                if (function_exists('the_ratings')) {
                    the_ratings();
                }
                */

                // Informations du jeu
                $editeur = get_field('editeur');
                $annee = get_field('annee_sortie');
                $age = get_field('age_minimum');
                $joueurs_min = get_field('joueurs_min');
                $joueurs_max = get_field('joueurs_max');
                $duree = get_field('duree');
                $difficulte = get_field('difficulte');
                $note_perso = get_field('notation');
                $regles = get_field('regles');

                // Thématiques
                $thematiques = get_the_terms(get_the_ID(), 'thematique');
                ?>

                <div class="game-meta grid grid-cols-2 gap-4 mb-6 mt-4">
                    <?php if ($editeur) : ?>
                        <div class="meta-item">
                            <span class="font-semibold">Éditeur:</span> <?php echo $editeur; ?>
                        </div>
                    <?php endif; ?>

                    <?php if ($annee) : ?>
                        <div class="meta-item">
                            <span class="font-semibold">Année:</span> <?php echo $annee; ?>
                        </div>
                    <?php endif; ?>

                    <?php if ($age) : ?>
                        <div class="meta-item">
                            <span class="font-semibold">Âge:</span> <?php echo $age; ?>+ ans
                        </div>
                    <?php endif; ?>

                    <?php if ($joueurs_min && $joueurs_max) : ?>
                        <div class="meta-item">
                            <span class="font-semibold">Joueurs:</span>
                            <?php echo $joueurs_min === $joueurs_max ? $joueurs_min : $joueurs_min . '-' . $joueurs_max; ?>
                        </div>
                    <?php endif; ?>

                    <?php if ($duree) : ?>
                        <div class="meta-item">
                            <span class="font-semibold">Durée:</span> <?php echo $duree; ?> min
                        </div>
                    <?php endif; ?>

                    <?php if ($difficulte) : ?>
                        <div class="meta-item">
                            <span class="font-semibold">Difficulté:</span>
                            <?php
                            $difficulte_labels = [
                                'facile' => 'Facile',
                                'moyen' => 'Moyen',
                                'difficile' => 'Difficile',
                                'expert' => 'Expert'
                            ];
                            echo isset($difficulte_labels[$difficulte]) ? $difficulte_labels[$difficulte] : $difficulte;
                            ?>
                        </div>
                    <?php endif; ?>

                    <?php if ($thematiques && !is_wp_error($thematiques)) : ?>
                        <div class="meta-item col-span-2">
                            <span class="font-semibold">Thématiques:</span>
                            <?php
                            $thematique_names = array_map(function ($theme) {
                                return '<a href="' . get_term_link($theme) . '" class="text-blue-600 hover:underline">' . $theme->name . '</a>';
                            }, $thematiques);
                            echo implode(', ', $thematique_names);
                            ?>
                        </div>
                    <?php endif; ?>

                    <?php if ($note_perso) : ?>
                        <div class="meta-item">
                            <span class="font-semibold">Note personnelle:</span>
                            <div class="inline-block">
                                <?php
                                // Correction de l'affichage en étoiles (sur 5)
                                $note_sur_cinq = $note_perso / 2; // Conversion de la note sur 10 en note sur 5
                                $stars = floor($note_sur_cinq);
                                $half = ($note_sur_cinq - $stars) >= 0.5;

                                for ($i = 1; $i <= 5; $i++) {
                                    if ($i <= $stars) {
                                        echo '<span class="text-yellow-400">★</span>'; // Étoile pleine
                                    } elseif ($i == $stars + 1 && $half) {
                                        echo '<span class="text-yellow-400">★</span>'; // Étoile à moitié
                                    } else {
                                        echo '<span class="text-gray-300">★</span>'; // Étoile vide
                                    }
                                }
                                echo ' (' . $note_perso . '/10)';
                                ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <?php if ($regles) : ?>
                        <div class="meta-item col-span-2">
                            <a href="<?php echo esc_url($regles['url']); ?>"
                                class="inline-block bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition"
                                target="_blank">
                                <?php if (wp_style_is('dashicons')) : ?>
                                    <span class="dashicons dashicons-pdf mr-1"></span>
                                <?php endif; ?>
                                Télécharger les règles
                            </a>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Collaborateurs du jeu -->
                <?php
                $collaborateurs = get_field('collaborateurs');
                if ($collaborateurs) : ?>
                    <div class="collaborateurs mb-6">
                        <h3 class="text-xl font-bold mb-2">Collaborateurs</h3>
                        <ul class="list-disc pl-5">
                            <?php foreach ($collaborateurs as $collab) :
                                // Récupérer les rôles du collaborateur
                                $roles = get_the_terms($collab->ID, 'role');
                                $role_names = [];

                                if ($roles && !is_wp_error($roles)) {
                                    $role_names = array_map(function ($role) {
                                        return $role->name;
                                    }, $roles);
                                }
                            ?>
                                <li>
                                    <a href="<?php echo get_permalink($collab->ID); ?>" class="text-blue-600 hover:underline">
                                        <?php echo get_the_title($collab->ID); ?>
                                    </a>
                                    <?php if (!empty($role_names)) : ?>
                                        <span class="text-gray-600">
                                            (<?php echo implode(', ', $role_names); ?>)
                                        </span>
                                    <?php endif; ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Description du jeu -->
        <div class="game-description p-6 border-t">
            <h2 class="text-2xl font-bold mb-4">Description</h2>
            <div class="content">
                <?php the_content(); ?>
            </div>
        </div>

        <!-- Système de commentaires et notation -->
        <div class="comments-section p-6 border-t">
            <h2 class="text-2xl font-bold mb-4">Avis et commentaires</h2>
            <?php
            if (comments_open() || get_comments_number()) :
                comments_template();
            else :
                echo '<p class="text-gray-500">Les commentaires sont fermés pour ce jeu.</p>';
            endif;
            ?>
        </div>

        <!-- Jeux similaires -->
        <?php
        // Récupérer les jeux de la même thématique
        if ($thematiques && !is_wp_error($thematiques)) {
            $thematique_ids = wp_list_pluck($thematiques, 'term_id');

            $similar_args = array(
                'post_type' => 'jeux',
                'posts_per_page' => 4,
                'post__not_in' => array(get_the_ID()),
                'tax_query' => array(
                    array(
                        'taxonomy' => 'thematique',
                        'field' => 'term_id',
                        'terms' => $thematique_ids,
                    ),
                ),
            );

            $similar_query = new WP_Query($similar_args);

            if ($similar_query->have_posts()) : ?>
                <div class="similar-games p-6 border-t">
                    <h2 class="text-2xl font-bold mb-4">Jeux similaires</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        <?php while ($similar_query->have_posts()) : $similar_query->the_post(); ?>
                            <div class="game-card border rounded overflow-hidden shadow hover:shadow-md transition">
                                <a href="<?php the_permalink(); ?>" class="block">
                                    <?php if (has_post_thumbnail()) : ?>
                                        <div class="game-thumbnail h-40 overflow-hidden">
                                            <?php the_post_thumbnail('medium', array('class' => 'w-full h-full object-cover')); ?>
                                        </div>
                                    <?php endif; ?>
                                    <div class="p-3">
                                        <h3 class="text-lg font-semibold"><?php the_title(); ?></h3>

                                        <?php
                                        // Informations minimales
                                        $min_joueurs = get_field('joueurs_min');
                                        $max_joueurs = get_field('joueurs_max');
                                        $duree_jeu = get_field('duree');
                                        ?>

                                        <div class="game-meta text-sm text-gray-600 mt-1">
                                            <?php if ($min_joueurs && $max_joueurs) : ?>
                                                <span class="mr-2">
                                                    <?php echo $min_joueurs === $max_joueurs ? $min_joueurs : $min_joueurs . '-' . $max_joueurs; ?>
                                                    joueurs
                                                </span>
                                            <?php endif; ?>

                                            <?php if ($duree_jeu) : ?>
                                                <span>
                                                    <?php echo $duree_jeu; ?> min
                                                </span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        <?php endwhile; ?>
                    </div>
                </div>
        <?php
            endif;
            wp_reset_postdata();
        }
        ?>
    </article>
</div>

<?php get_footer(); ?>