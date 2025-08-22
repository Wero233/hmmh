<?php
$query = get_posts([
    'post_type'      => 'case_studies',
    'posts_per_page' => -1,
]);

$terms = get_terms([
    'taxonomy'   => 'case_study_type',
    'hide_empty' => false,
]);
?>

<div class="case-studies__filter" role="tablist" aria-label="<?php esc_attr_e('Filtruj po typie', 'hmmh'); ?>">
    <div class="case-studies__filter-buttons">
        <button class="case-studies__filter-button case-studies__filter-button--all is-active" role="tab" aria-selected="true" data-filter="*">
            <?php _e('All', 'hmmh'); ?>
        </button>
        <?php if (!is_wp_error($terms) && $terms) :
            foreach ($terms as $t) : ?>
                <button class="case-studies__filter-button" role="tab" aria-selected="false" data-filter="<?php echo esc_attr($t->slug); ?>">
                    <?php echo esc_html($t->name); ?>
                </button>
        <?php endforeach;
        endif; ?>
    </div>
</div>

<div class="case-studies">
    <?php foreach ($query as $post):
        $types = get_the_terms($post->ID, 'case_study_type');
        $link  = get_field('link', $post->ID);
        $slugs = $types && !is_wp_error($types) ? wp_list_pluck($types, 'slug') : [];
    ?>

        <div class="case-studies__item" data-terms="<?php echo esc_attr(implode(' ', $slugs)); ?>">
            <div class="case-studies__img"><?php echo get_the_post_thumbnail($post, 'large'); ?></div>

            <?php if ($types && !is_wp_error($types)) : ?>
                <div class="case-studies__items">
                    <div class="case-studies__types">
                        <?php
                        $last = count($types) - 1;
                        foreach ($types as $i => $type) :
                            echo '<div class="case-studies__type">' . esc_html($type->name) . '</div>';
                            if ($i !== $last) echo '<div class="case-studies__sep"> / </div>';
                        endforeach;
                        ?>
                    </div>
                <?php endif; ?>

                <div class="case-studies__title"><?php echo esc_html(get_the_title($post)); ?></div>

                <div class="case-studies__link">
                    <?php if ($link) :
                        $url    = esc_url($link['url']);
                        $title  = esc_html($link['title']);
                        $target = $link['target'] ? esc_attr($link['target']) : '_self';
                        echo '<a href="' . $url . '" target="' . $target . '">' . $title . '</a>';
                    endif; ?>
                </div>
                </div>
        </div>
    <?php endforeach; ?>
</div>