<?php get_header(); ?>

<main class="row mt-md-5 mt-2">
    <div class="col-12 col-md-8 m-md-auto">
        <div id="carouselExample" class="carousel slide" data-bs-ride="carousel">
            <div class="loader mt-4 mb-3 mx-auto"></div>
            <div class="carousel-inner" style="display: none;"> <!-- Initially hide the carousel -->
                <?php
                // Query the latest posts
                $args = array(
                    'post_type' => 'post',
                    'posts_per_page' => 5,
                    'orderby' => 'date',
                    'order' => 'DESC',
                );
                $query = new WP_Query($args);
                $first_slide = true;

                if ($query->have_posts()) :
                    while ($query->have_posts()) : $query->the_post();
                        $active_class = $first_slide ? 'active' : '';
                        ?>
                        <div class="carousel-item <?php echo esc_attr($active_class); ?>">
                            <a href="<?php the_permalink(); ?>" title="<?php esc_attr(the_title()); ?>">
                                <?php if (has_post_thumbnail()) : ?>
                                    <img src="<?php echo esc_url(get_the_post_thumbnail_url()); ?>" class="img-fluid d-block w-100" 
                                         alt="<?php echo esc_attr(get_post_meta(get_post_thumbnail_id(), '_wp_attachment_image_alt', true)); ?>" loading="lazy" />
                                <?php else : ?>
                                    <img src="https://via.placeholder.com/800x450?text=No+Image" class="img-fluid d-block w-100" alt="No Image" />
                                <?php endif; ?>
                                <div class="carousel-caption">
                                    <h5><?php echo esc_html(get_the_title()); ?></h5>
                                    <p><?php echo esc_html(wp_trim_words(get_the_excerpt(), 15, '...')); ?></p>
                                </div>
                            </a>
                        </div>
                        <?php
                        $first_slide = false;
                    endwhile;
                    wp_reset_postdata();
                else :
                    ?>
                    <div class="carousel-item active">
                        <img src="https://via.placeholder.com/800x450?text=No+Posts" class="d-block w-100" alt="No Posts">
                        <div class="carousel-caption">
                            <h5>No Posts Available</h5>
                            <p>Please check back later.</p>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Show the carousel once images are loaded -->
            <?php if ($query->have_posts()) : ?>
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        document.querySelector('.loader').style.display = 'none'; // Hide loader
                        document.querySelector('.carousel-inner').style.display = 'block'; // Show carousel
                    });
                </script>
            <?php endif; ?>

            <!-- Indicators and controls -->
        </div>
    </div>
</main>
    
   <!-- post category -->
<div class="row mt-5">
    <div class="col-md-8 m-md-auto">
        <div class="alert alert-danger text-center" role="alert" id="alert-messages" style="display: none;">
            Please select at least one category to filter.
        </div>
        
        <form id="filter-form">
            <div class="d-md-flex justify-content-between mb-3" id="filter">
                <div class="filter-by-category">
                    <p class="fs-5">Filter by category:<i class="ri-arrow-right-up-line"></i></p>
                    <?php
                    // Get all categories
                    $categories = get_categories(array(
                        'orderby' => 'name',
                        'order'   => 'ASC',
                        'hide_empty' => true,
                    ));

                    // Always show "Uncategorized" category
                    echo '<label class="mx-2">';
                    echo '<input type="checkbox" name="uncategorized" checked /> ';
                    echo esc_html('Uncategorized');
                    echo '</label>';

                    foreach ($categories as $category) {
                        // Skip "Uncategorized" and check if the category has more than one post
                        if ($category->slug !== 'uncategorized' && $category->count > 0) {
                            echo '<label class="mx-2">';
                            echo '<input type="checkbox" name="' . esc_attr($category->slug) . '" /> ';
                            echo esc_html($category->name);
                            echo '</label>';
                        }
                    }
                    ?>
                </div>
                
                <label class="mx-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="ri-filter-fill"></i> Apply Filter
                    </button>
                </label>
                
                <div class="view-grid mt-3 mt-sm-0 fs-4 buttons">
                    <div class="grid-view red"><i class="ri-layout-grid-fill"></i></div>
                    <div class="list-view"><i class="ri-equalizer-line"></i></div>
                    <div class="" id="openPopup"><i class="ri-search-2-line"></i></div>
                    
                </div>
            </div>
        </form>
    </div>
</div>
<!-- post category  end here-->
<!-- search pop code -->
<form role="search" method="get" id="searchform" action="<?php //echo esc_url(home_url('/')); ?>">
<div class="popup" id="popup">

    <div class="popup-content">
        <span class="close" id="closePopup">&times;</span>
        <h2>Search</h2>
        <div class="input-group mb-3">
        <input type="text" class="form-control" placeholder="Type your search..." aria-label="Recipient's username" aria-describedby="basic-addon2" name="s" id='searchInput' required />
        <button  type="submit" id="search-button" >
        <div class="input-group-append">
            <span class="input-group-text" id="basic-addon2"><i class="ri-search-2-line"></i></span>
        </div>
        </button>
        </div>
        <!-- <input type="text" id="searchInput"  name="s" class="" placeholder="Type your search..." required /> -->
        
    </div>
</div>
     </form>


<!-- search pop code -->




        
        <main class="row mt-2  ">
        <div class=" col-12 col-md-8 m-auto " id="main-post-view">
        <div class="wrapper grid" id="postContainer">
        <!-- Items will be appended here by JavaScript -->
        
         
    </div>
               

           </div>
            <!-- pagination code --> 
             <div class="col-12 col-md-8 m-md-auto text-center mt-3 mb-3">
             <div class="pagination justify-content-center" id="pagination">
                
            </div>
             </div>
             <!-- pagination code -->
        </main>

<?php get_footer(); ?>