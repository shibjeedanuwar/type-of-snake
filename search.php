<?php get_header(); ?>

<div class="row">
    <div class="col-12 col-md-8 m-md-auto mt-2 mt-md-5">
        <div class="search-results">
            <?php 
            // Get the search query
            $search_query = isset($_GET['s']) ? sanitize_text_field($_GET['s']) : ''; 
            ?>
            <h2>Search Results for: <?php echo esc_html($search_query); ?></h2>
            <hr>
            
            <?php if (have_posts()) : ?>
                <h2>Search Results:</h2>
                <div class="row">
                    <?php while (have_posts()) : the_post(); ?>
                        <div class="col-6 col-md-4 mb-4">
                            <div class="card">
                                <?php if (has_post_thumbnail()) : ?>
                                    <a href="<?php the_permalink(); ?>"><?php the_post_thumbnail('medium', ['class' => 'card-img-top']); ?></a>
                                <?php endif; ?>
                                <div class="card-body">
                                    <h5 class="card-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h5>
                                    <p class="card-text"><?php echo wp_trim_words(get_the_excerpt(), 10); ?></p>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
            <?php else : ?>
                <p>No results found.</p>
                <h3>You Might Like This Post:</h3>
                <div class="row">
                    <?php
                    // Query for related posts
                    $related_posts = new WP_Query(array(
                        'posts_per_page' => 6, // Number of posts to display
                        'post_type' => 'post',
                        'orderby' => 'rand', // Random posts
                    ));

                    if ($related_posts->have_posts()) : 
                        while ($related_posts->have_posts()) : $related_posts->the_post(); ?>
                            <div class="col-6 col-md-4 mb-4">
                                <div class="card">
                                    <?php if (has_post_thumbnail()) : ?>
                                        <a href="<?php the_permalink(); ?>"><?php the_post_thumbnail('medium', ['class' => 'card-img-top']); ?></a>
                                    <?php endif; ?>
                                    <div class="card-body">
                                        <h5 class="card-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h5>
                                        <p class="card-text"><?php echo wp_trim_words(get_the_excerpt(), 15); ?></p>
                                    </div>
                                </div>
                            </div>
                        <?php endwhile; 
                    endif; 
                    wp_reset_postdata(); // Reset post data
                    ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php get_footer(); ?>