<?php
get_header(); ?>

<main id="main-content">
    <section class="hero">
        <h1 class="text-3xl font-bold mb-8">La Ludothèque de Magali</h1>

        <!-- Moteur de recherche -->
        <div class="bg-gray-100 p-6 rounded-lg mb-8">
            <form role="search" method="get" class="search-form flex flex-wrap gap-4"
                action="<?php echo home_url('/'); ?>">
                <input type="hidden" name="post_type" value="jeu" />
                <div class="flex-grow">
                    <input type="search" class="w-full p-3 rounded" placeholder="Rechercher un jeu..."
                        value="<?php echo get_search_query(); ?>" name="s" />
                </div>
                <div>
                    <button type="submit"
                        class="bg-orange-500 text-white py-3 px-6 rounded hover:bg-orange-600">Rechercher</button>
                </div>
            </form>
        </div>

        <!-- Filtres -->
        <div class="mb-8">
            <h2 class="text-xl font-bold mb-4">Filtrer par :</h2>
            <div class="flex flex-wrap gap-4">
                <?php
                // Affichage des types de jeux
                $types = get_terms(array(
                    'taxonomy' => 'type-jeu',
                    'hide_empty' => false,
                ));

                if (!empty($types) && !is_wp_error($types)) :
                ?>
                    <div class="filter-group">
                        <label for="type-filter" class="block mb-2">Type de jeu</label>
                        <select id="type-filter" class="p-2 border rounded" onchange="window.location.href=this.value">
                            <option value="<?php echo get_post_type_archive_link('jeu'); ?>">Tous les types</option>
                            <?php foreach ($types as $type) : ?>
                                <option value="<?php echo get_term_link($type); ?>"><?php echo $type->name; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                <?php endif; ?>

                <?php
                // Affichage des thématiques
                $themes = get_terms(array(
                    'taxonomy' => 'thematique',
                    'hide_empty' => false,
                ));

                if (!empty($themes) && !is_wp_error($themes)) :
                ?>
                    <div class="filter-group">
                        <label for="theme-filter" class="block mb-2">Thématique</label>
                        <select id="theme-filter" class="p-2 border rounded" onchange="window.location.href=this.value">
                            <option value="<?php echo get_post_type_archive_link('jeu'); ?>">Toutes les thématiques</option>
                            <?php foreach ($themes as $theme) : ?>
                                <option value="<?php echo get_term_link($theme); ?>"><?php echo $theme->name; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <section class="game-list">
        <h2>Liste des jeux</h2>
        <?php
        // Boucle WP pour récupérer les jeux.
        $args = array(
            'post_type' => 'jeux',
            'posts_per_page' => 10,
        );
        $query = new WP_Query($args);
        if ($query->have_posts()) :
            while ($query->have_posts()) : $query->the_post(); ?>
                <article class="game">
                    <h3 style="text-align: center;"><?php the_title(); ?></h3>
                    <p><?php the_excerpt(); ?></p>
                    <a href="<?php the_permalink(); ?>">Voir plus</a>
                </article>
            <?php endwhile;
        else : ?>
            <p>Aucun jeu disponible pour le moment.</p>
        <?php endif;
        wp_reset_postdata(); ?>
    </section>
</main>

<?php get_footer(); ?>