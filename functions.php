<?php

add_action('after_setup_theme', function () {
    add_theme_support('post-thumbnails');
});

add_action('acf/init', function () {
    if (!function_exists('acf_register_block_type')) return;

    acf_register_block_type([
        'name'            => 'case-studies',
        'title'           => __('Case Studies', 'hmmh'),
        'description'     => __('Lista case studies z filtrowaniem po typie', 'hmmh'),
        'render_template' => get_template_directory() . '/template-parts/blocks/case-studies.php',
        'category'        => 'widgets',
        'icon'            => 'portfolio',
        'mode'            => 'edit',
        'supports'        => ['align' => false, 'anchor' => true],
        'enqueue_assets'  => function () {
            $dist = get_template_directory_uri() . '/assets/dist';
            wp_enqueue_style('hmmh-case-studies', $dist . '/block_case-studies.css', [], null);
            wp_enqueue_script('hmmh-case-studies', $dist . '/block_case-studies.js', [], null, true);

        },
    ]);
});


add_filter('acf/settings/save_json', fn() => get_template_directory() . '/acf-json');
add_filter('acf/settings/load_json', function ($paths) {
    $paths[] = get_template_directory() . '/acf-json';
    return $paths;
});



//cpt

function hmmh_register_case_studies_cpt()
{

    $labels = [
        'name'                  => __('Case Studies', 'hmmh'),
        'singular_name'         => __('Case Study', 'hmmh'),
        'menu_name'             => __('Case Studies', 'hmmh'),
        'name_admin_bar'        => __('Case Study', 'hmmh'),
        'add_new'               => __('Dodaj nowy', 'hmmh'),
        'add_new_item'          => __('Dodaj nowe Case Study', 'hmmh'),
        'edit_item'             => __('Edytuj Case Study', 'hmmh'),
        'new_item'              => __('Nowe Case Study', 'hmmh'),
        'view_item'             => __('Zobacz Case Study', 'hmmh'),
        'search_items'          => __('Szukaj Case Studies', 'hmmh'),
        'not_found'             => __('Nie znaleziono', 'hmmh'),
        'not_found_in_trash'    => __('Nie znaleziono w koszu', 'hmmh'),
        'all_items'             => __('Wszystkie Case Studies', 'hmmh'),
    ];

    $args = [
        'labels'             => $labels,
        'public'             => true,
        'has_archive'        => true,
        'rewrite'            => ['slug' => 'case-studies'],
        'show_in_rest'       => true,
        'menu_icon'          => 'dashicons-portfolio',
        'supports'           => ['title',  'thumbnail', 'revisions'],
    ];

    register_post_type('case_studies', $args);
}
add_action('init', 'hmmh_register_case_studies_cpt');


/**
 * Taksonomia: Typ Case Study
 */
function hmmh_register_case_study_type_taxonomy()
{

    $labels = [
        'name'              => __('Typy Case Study', 'hmmh'),
        'singular_name'     => __('Typ Case Study', 'hmmh'),
        'search_items'      => __('Szukaj typów', 'hmmh'),
        'all_items'         => __('Wszystkie typy', 'hmmh'),
        'edit_item'         => __('Edytuj typ', 'hmmh'),
        'update_item'       => __('Aktualizuj typ', 'hmmh'),
        'add_new_item'      => __('Dodaj nowy typ', 'hmmh'),
        'new_item_name'     => __('Nazwa nowego typu', 'hmmh'),
        'menu_name'         => __('Typ Case Study', 'hmmh'),
    ];

    register_taxonomy('case_study_type', ['case_studies'], [
        'labels'            => $labels,
        'public'            => true,
        'hierarchical'      => true, 
        'show_ui'           => true,
        'show_admin_column' => true,
        'show_in_rest'      => true, 
        'rewrite'           => ['slug' => 'case-study-type'],
    ]);
}
add_action('init', 'hmmh_register_case_study_type_taxonomy');
