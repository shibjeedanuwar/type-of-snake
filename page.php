<?php
/*
Template Name: Custom Page
*/

get_header(); // Include the header
?>

<div class="col-12 col-md-8 m-md-auto mt-md-5 mt-2 custom-content p-2" id="custom-page">
  
    <h1><?php the_title(); ?></h1>
    
    <?php
    // Start the Loop
    if (have_posts()) :
        while (have_posts()) : the_post();
            the_content(); // Display the content from the WordPress editor
        endwhile;
    else :
        echo '<p>No content found</p>';
    endif;
    ?>
</div>

<?php
get_footer(); // Include the footer
?>