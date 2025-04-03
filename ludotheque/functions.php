<?php

/**
 * Twenty Twenty-Five functions and definitions.
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package WordPress
 * @subpackage Twenty_Twenty_Five
 * @since Twenty Twenty-Five 1.0
 */

// Adds theme support for post formats.
if (! function_exists('twentytwentyfive_post_format_setup')) :
    /**
     * Adds theme support for post formats.
     *
     * @since Twenty Twenty-Five 1.0
     *
     * @return void
     */
    function twentytwentyfive_post_format_setup()
    {
        add_theme_support('post-formats', array('aside', 'audio', 'chat', 'gallery', 'image', 'link', 'quote', 'status', 'video'));
    }
endif;
add_action('after_setup_theme', 'twentytwentyfive_post_format_setup');

// Enqueues editor-style.css in the editors.
if (! function_exists('twentytwentyfive_editor_style')) :
    /**
     * Enqueues editor-style.css in the editors.
     *
     * @since Twenty Twenty-Five 1.0
     *
     * @return void
     */
    function twentytwentyfive_editor_style()
    {
        add_editor_style(get_parent_theme_file_uri('assets/css/editor-style.css'));
    }
endif;
add_action('after_setup_theme', 'twentytwentyfive_editor_style');

// Enqueues style.css on the front.
if (! function_exists('twentytwentyfive_enqueue_styles')) :
    /**
     * Enqueues style.css on the front.
     *
     * @since Twenty Twenty-Five 1.0
     *
     * @return void
     */
    function twentytwentyfive_enqueue_styles()
    {
        wp_enqueue_style(
            'twentytwentyfive-style',
            get_parent_theme_file_uri('style.css'),
            array(),
            wp_get_theme()->get('Version')
        );
    }
endif;
add_action('wp_enqueue_scripts', 'twentytwentyfive_enqueue_styles');

// Registers custom block styles.
if (! function_exists('twentytwentyfive_block_styles')) :
    /**
     * Registers custom block styles.
     *
     * @since Twenty Twenty-Five 1.0
     *
     * @return void
     */
    function twentytwentyfive_block_styles()
    {
        register_block_style(
            'core/list',
            array(
                'name'         => 'checkmark-list',
                'label'        => __('Checkmark', 'twentytwentyfive'),
                'inline_style' => '
				ul.is-style-checkmark-list {
					list-style-type: "\2713";
				}

				ul.is-style-checkmark-list li {
					padding-inline-start: 1ch;
				}',
            )
        );
    }
endif;
add_action('init', 'twentytwentyfive_block_styles');

// Registers pattern categories.
if (! function_exists('twentytwentyfive_pattern_categories')) :
    /**
     * Registers pattern categories.
     *
     * @since Twenty Twenty-Five 1.0
     *
     * @return void
     */
    function twentytwentyfive_pattern_categories()
    {

        register_block_pattern_category(
            'twentytwentyfive_page',
            array(
                'label'       => __('Pages', 'twentytwentyfive'),
                'description' => __('A collection of full page layouts.', 'twentytwentyfive'),
            )
        );

        register_block_pattern_category(
            'twentytwentyfive_post-format',
            array(
                'label'       => __('Post formats', 'twentytwentyfive'),
                'description' => __('A collection of post format patterns.', 'twentytwentyfive'),
            )
        );
    }
endif;
add_action('init', 'twentytwentyfive_pattern_categories');

// Registers block binding sources.
if (! function_exists('twentytwentyfive_register_block_bindings')) :
    /**
     * Registers the post format block binding source.
     *
     * @since Twenty Twenty-Five 1.0
     *
     * @return void
     */
    function twentytwentyfive_register_block_bindings()
    {
        register_block_bindings_source(
            'twentytwentyfive/format',
            array(
                'label'              => _x('Post format name', 'Label for the block binding placeholder in the editor', 'twentytwentyfive'),
                'get_value_callback' => 'twentytwentyfive_format_binding',
            )
        );
    }
endif;
add_action('init', 'twentytwentyfive_register_block_bindings');

// Registers block binding callback function for the post format name.
if (! function_exists('twentytwentyfive_format_binding')) :
    /**
     * Callback function for the post format name block binding source.
     *
     * @since Twenty Twenty-Five 1.0
     *
     * @return string|void Post format name, or nothing if the format is 'standard'.
     */
    function twentytwentyfive_format_binding()
    {
        $post_format_slug = get_post_format();

        if ($post_format_slug && 'standard' !== $post_format_slug) {
            return get_post_format_string($post_format_slug);
        }
    }
endif;

function wp_enqueue_assets()
{
    wp_enqueue_style(
        'parent-style',
        get_template_directory_uri() . '/style.css'
    );
}
add_action('wp_enqueue_scripts', 'wp_enqueue_assets');


function games_post_types()
{
    $labels = array(
        'name'               => 'Jeux',
        'singular_name'      => 'Jeu',
        'menu_name'          => 'Jeux',
        'add_new'            => 'Ajouter un jeu',
        'add_new_item'       => 'Ajouter un nouveau jeu',
        'edit_item'          => 'Modifier le jeu',
        'new_item'           => 'Nouveau jeu',
        'view_item'          => 'Voir le jeu',
        'search_items'       => 'Rechercher un jeu',
        'not_found'          => 'Aucun jeu trouvé',
        'not_found_in_trash' => 'Aucun jeu trouvé dans la corbeille'
    );

    $args = array(
        'labels'              => $labels,
        'public'              => true,
        'has_archive'         => true,
        'publicly_queryable'  => true,
        'query_var'           => true,
        'rewrite'             => array('slug' => 'jeux'),
        'capability_type'     => 'post',
        'menu_icon'           => 'dashicons-games',
        'supports'            => array('title', 'editor', 'thumbnail', 'excerpt', 'comments'),
        'show_in_rest'        => true,
    );

    register_post_type('jeux', $args);
}
add_action('init', 'games_post_types');

function types_of_games_taxonomy()
{
    $labels = array(
        'name'              => 'Types de jeux',
        'singular_name'     => 'Type de jeu',
        'search_items'      => 'Rechercher des types',
        'all_items'         => 'Tous les types',
        'parent_item'       => 'Type parent',
        'parent_item_colon' => 'Type parent:',
        'edit_item'         => 'Modifier le type',
        'update_item'       => 'Mettre à jour le type',
        'add_new_item'      => 'Ajouter un nouveau type',
        'new_item_name'     => 'Nom du nouveau type',
        'menu_name'         => 'Types de jeux',
    );

    $args = array(
        'hierarchical'      => true,
        'labels'            => $labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array('slug' => 'type-jeu'),
        'show_in_rest'      => true,
    );

    register_taxonomy('type-jeu', array('jeux'), $args);
}
add_action('init', 'types_of_games_taxonomy');


function thematique_taxonomy()
{
    $labels = array(
        'name'              => 'Thématiques',
        'singular_name'     => 'Thématique',
        'search_items'      => 'Rechercher des thématiques',
        'all_items'         => 'Toutes les thématiques',
        'parent_item'       => 'Thématique parente',
        'parent_item_colon' => 'Thématique parente:',
        'edit_item'         => 'Modifier la thématique',
        'update_item'       => 'Mettre à jour la thématique',
        'add_new_item'      => 'Ajouter une nouvelle thématique',
        'new_item_name'     => 'Nom de la nouvelle thématique',
        'menu_name'         => 'Thématiques',
    );

    $args = array(
        'hierarchical'      => true,
        'labels'            => $labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array('slug' => 'thematique'),
        'show_in_rest'      => true,
    );

    register_taxonomy('thematique', array('jeux'), $args);
}
add_action('init', 'thematique_taxonomy');

function collaborators_post_types()
{
    $labels = array(
        'name'               => 'Collaborateurs',
        'singular_name'      => 'Collaborateur',
        'menu_name'          => 'Collaborateurs',
        'add_new'            => 'Ajouter un collaborateur',
        'add_new_item'       => 'Ajouter un nouveau collaborateur',
        'edit_item'          => 'Modifier le collaborateur',
        'new_item'           => 'Nouveau collaborateur',
        'view_item'          => 'Voir le collaborateur',
        'search_items'       => 'Rechercher un collaborateur',
        'not_found'          => 'Aucun collaborateur trouvé',
        'not_found_in_trash' => 'Aucun collaborateur trouvé dans la corbeille'
    );

    $args = array(
        'labels'              => $labels,
        'public'              => true,
        'has_archive'         => true,
        'publicly_queryable'  => true,
        'query_var'           => true,
        'rewrite'             => array('slug' => 'collaborateurs'),
        'capability_type'     => 'post',
        'menu_icon'           => 'dashicons-groups',
        'supports'            => array('title', 'editor', 'thumbnail', 'excerpt'),
        'show_in_rest'        => true,
    );

    register_post_type('collaborateur', $args);
}
add_action('init', 'collaborators_post_types');

function roles_taxonomy()
{
    $labels = array(
        'name'              => 'Rôles',
        'singular_name'     => 'Rôle',
        'search_items'      => 'Rechercher des rôles',
        'all_items'         => 'Tous les rôles',
        'edit_item'         => 'Modifier le rôle',
        'update_item'       => 'Mettre à jour le rôle',
        'add_new_item'      => 'Ajouter un nouveau rôle',
        'new_item_name'     => 'Nom du nouveau rôle',
        'menu_name'         => 'Rôles',
    );

    $args = array(
        'hierarchical'      => true,
        'labels'            => $labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array('slug' => 'role'),
        'show_in_rest'      => true,
    );

    register_taxonomy('role', array('collaborateur'), $args);
}
add_action('init', 'roles_taxonomy');



if (function_exists('acf_add_local_field_group')):

    // Champs pour les jeux
    acf_add_local_field_group(array(
        'key' => 'group_jeu',
        'title' => 'Informations du jeu',
        'fields' => array(
            array(
                'key' => 'field_editeur',
                'label' => 'Éditeur',
                'name' => 'editeur',
                'type' => 'text',
            ),
            array(
                'key' => 'field_annee',
                'label' => 'Année de sortie',
                'name' => 'annee_sortie',
                'type' => 'number',
                'min' => 1900,
                'max' => date('Y'),
            ),
            array(
                'key' => 'field_joueurs_min',
                'label' => 'Nombre minimum de joueurs',
                'name' => 'joueurs_min',
                'type' => 'number',
                'min' => 1,
            ),
            array(
                'key' => 'field_joueurs_max',
                'label' => 'Nombre maximum de joueurs',
                'name' => 'joueurs_max',
                'type' => 'number',
                'min' => 1,
            ),
            array(
                'key' => 'field_age',
                'label' => 'Âge minimum',
                'name' => 'age_minimum',
                'type' => 'number',
                'min' => 1,
            ),
            array(
                'key' => 'field_duree',
                'label' => 'Durée de jeu (en minutes)',
                'name' => 'duree',
                'type' => 'number',
                'min' => 1,
            ),
            array(
                'key' => 'field_difficulte',
                'label' => 'Niveau de difficulté',
                'name' => 'difficulte',
                'type' => 'select',
                'choices' => array(
                    'facile' => 'Facile',
                    'moyen' => 'Moyen',
                    'difficile' => 'Difficile',
                    'expert' => 'Expert'
                ),
            ),
            array(
                'key' => 'field_notation',
                'label' => 'Note personnelle',
                'name' => 'notation',
                'type' => 'number',
                'min' => 0,
                'max' => 10,
                'step' => 0.1,
            ),
            array(
                'key' => 'field_composants',
                'label' => 'Composants du jeu',
                'name' => 'composants',
                'type' => 'repeater',
                'sub_fields' => array(
                    array(
                        'key' => 'field_composant_nom',
                        'label' => 'Composant',
                        'name' => 'nom',
                        'type' => 'text',
                    ),
                    array(
                        'key' => 'field_composant_quantite',
                        'label' => 'Quantité',
                        'name' => 'quantite',
                        'type' => 'number',
                    ),
                ),
            ),
            array(
                'key' => 'field_regles',
                'label' => 'Règles du jeu (PDF)',
                'name' => 'regles',
                'type' => 'file',
                'return_format' => 'array',
                'mime_types' => 'pdf',
            ),
            array(
                'key' => 'field_collaborateurs',
                'label' => 'Collaborateurs',
                'name' => 'collaborateurs',
                'type' => 'relationship',
                'post_type' => array('collaborateur'),
                'return_format' => 'object',
            ),
            array(
                'key' => 'field_galerie',
                'label' => 'Galerie photos',
                'name' => 'galerie',
                'type' => 'gallery',
            ),
        ),
        'location' => array(
            array(
                array(
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'jeux',
                ),
            ),
        ),
    ));

endif;


// Fonction pour ajouter le système de notation aux commentaires
function ajouter_champ_note_commentaire($fields)
{
    $fields['rating'] = '<p class="comment-form-rating">
        <label for="rating">Note <span class="required">*</span></label>
        <span class="commentratingbox">
            <select name="rating" id="rating" required>
                <option value="">Noter...</option>
                <option value="5">5 - Excellent</option>
                <option value="4">4 - Très bon</option>
                <option value="3">3 - Bon</option>
                <option value="2">2 - Moyen</option>
                <option value="1">1 - Mauvais</option>
            </select>
        </span>
    </p>';

    return $fields;
}
add_filter('comment_form_default_fields', 'ajouter_champ_note_commentaire');

// Fonction pour sauvegarder la note
function sauvegarder_note_commentaire($comment_id)
{
    if (isset($_POST['rating'])) {
        $rating = intval($_POST['rating']);
        if ($rating >= 1 && $rating <= 5) {
            add_comment_meta($comment_id, 'rating', $rating);
        }
    }
}
add_action('comment_post', 'sauvegarder_note_commentaire');

// Fonction pour afficher la note moyenne d'un jeu
function calculer_note_moyenne($post_id)
{
    $args = array(
        'post_id' => $post_id,
        'status' => 'approve',
        'meta_key' => 'rating',
    );

    $comments = get_comments($args);
    if (empty($comments)) {
        return 0;
    }

    $total = 0;
    $count = 0;

    foreach ($comments as $comment) {
        $rating = get_comment_meta($comment->comment_ID, 'rating', true);
        if ($rating) {
            $total += $rating;
            $count++;
        }
    }

    return $count > 0 ? round($total / $count, 1) : 0;
}

// Affiche les données ACF dans la colonne
function show_collaborators($column, $post_id)
{
    if ($column === 'collaborateurs') {
        $collaborateurs = get_field('collaborateurs', $post_id);
        if ($collaborateurs && !empty($collaborateurs)) {
            $noms = array();
            foreach ($collaborateurs as $collab) {
                $noms[] = '<a href="' . get_edit_post_link($collab->ID) . '">' . $collab->post_title . '</a>';
            }
            echo implode(', ', $noms);
        } else {
            echo '—';
        }
    }
}
add_action('manage_jeux_posts_custom_column', 'afficher_collaborateurs_acf', 10, 2);


function ludotheque_enqueue_styles()
{
    wp_enqueue_style('ludotheque-styles', get_template_directory_uri() . '/ludotheque-styles.css');
}
add_action('wp_enqueue_scripts', 'ludotheque_enqueue_styles');
