<?php get_header(); ?>

<div class="col-12 col-md-8 m-md-auto mt-md-5 mt-2 ">
<main>
    <?php if (have_posts()) : ?>
        <?php while (have_posts()) : the_post(); ?>
            <article>
                <h2><?php the_title(); ?></h2>
                <div><?php the_content(); ?></div>
            </article>
        <?php endwhile; ?>
    <?php else : ?>
        <p>No content found.</p>
    <?php endif; ?>
</main>
    </div>
    
<?php get_footer(); ?>