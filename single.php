<?php get_header(); ?>
<div class="row mt-md-5 mt-1">
    <div class="col-12 col-md-7 m-md-auto" id="dynamic-css">
    <div class=" col">
        <div id="carouselExample" class="carousel slide" data-bs-ride="carousel">
            <div id="loading" style="display: none;">Loading...</div>
            <div class="carousel-inner">
                <?php
                // Initialize a flag for the first item
                $is_first = true;

                // Get the featured image
                if (has_post_thumbnail()) : ?>
                    <div class="carousel-item <?php echo $is_first ? 'active' : ''; ?>">
                        <img src="<?php echo esc_url(get_the_post_thumbnail_url()); ?>" class="d-block w-100" alt="<?php echo esc_attr(get_the_title()); ?>">
                    </div>
                    <?php $is_first = false; ?>
                <?php endif; ?>

                <?php
                // Get images in post content
                $content = get_the_content();
                preg_match_all('/<img[^>]+src="([^">]+)"/', $content, $matches);
                foreach ($matches[1] as $image_url) : ?>
                    <div class="carousel-item <?php echo $is_first ? 'active' : ''; ?>">
                        <img src="<?php echo esc_url($image_url); ?>" class="d-block w-100" alt="">
                    </div>
                    <?php $is_first = false; ?>
                <?php endforeach; ?>

                <?php
                // Get attached images
                $attachments = get_attached_media('image', get_the_ID());
                foreach ($attachments as $attachment) : ?>
                    <div class="carousel-item <?php echo $is_first ? 'active' : ''; ?>">
                        <img src="<?php echo esc_url(wp_get_attachment_url($attachment->ID)); ?>" class="d-block w-100" alt="<?php echo esc_attr(get_the_title($attachment->ID)); ?>">
                    </div>
                    <?php $is_first = false; ?>
                <?php endforeach; ?>
            </div>

            <!-- Indicators -->
            <div class="carousel-indicators">
                <?php
                // Count total slides
                $total_slides = (has_post_thumbnail() ? 1 : 0) + count($matches[1]) + count($attachments);
                for ($i = 0; $i < $total_slides; $i++) : ?>
                    <button type="button" data-bs-target="#carouselExample" data-bs-slide-to="<?php echo $i; ?>" class="<?php echo $i === 0 ? 'active' : ''; ?>" aria-current="<?php echo $i === 0 ? 'true' : 'false'; ?>" aria-label="Slide <?php echo $i + 1; ?>"></button>
                <?php endfor; ?>
            </div>

            <!-- Controls -->
            <!-- <button class="carousel-control-prev" type="button" data-bs-target="#carouselExample" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carouselExample" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button> -->
        </div>

       
    </div>
<div class="row">
    <div class="  col   ">
        <div class=" mw-100 p-3 text-white" style="background-color:  hsl(0, 0%, 0%);">
            <ul class="d-flex flex-column flex-md-row justify-content-around">
                
                 <li class="btn">
                     <a href="#" class="nav__link" id="dynamic-change">
                        <i class="ri-arrow-right-up-line"></i>
                        <span>Read Article</span>
                     </a>
                  </li>
                  <li class="btn">
                     <a href="#" class="nav__link" id="venoum">
                        <i class="ri-arrow-right-up-line"></i>
                        <span>Venoum</span> 
                     </a>
                  </li>
                  <li class="btn">
                     <a href="#" class="nav__link" id="antibiotic">
                        <i class="ri-arrow-right-up-line"></i>
                        <span>Antibiotic</span>
                     </a>
                  </li>
            </ul>
        </div>
        <?php
$post_id = get_the_ID(); // Get the current post ID
$venomous_data = get_venomous_data($post_id);
?>

<!-- Plugin article show here -->
 <div class="remove-for-toc">
<div class="plugin-article mw-100 mt-1 p-2 mw-100 p-3  mb-3 shadow"id='plugin-article-venoum' style="display:none ;">
<!-- // venoumous text here -->
 
<?php if ($venomous_data && !empty($venomous_data['venomous'])): ?>
        <?php echo wp_kses_post($venomous_data['venomous']); ?>
    <?php else: ?>
        <p class="no-custom-post text-center"><b>No venomous information available for this post.</b></p>
    <?php endif; ?>
     
        
</div>
<div class="plugin-article mw-100 mt-1 p-2 mw-100 p-3  mb-3 shadow"id='plugin-article-antibiotic'style="display:none ;">
<!-- // anitibiotic text here -->
<?php if ($venomous_data && !empty($venomous_data['antibiotic'])): ?>
        <?php echo wp_kses_post($venomous_data['antibiotic']); ?>
    <?php else: ?>
        <p class="text-center" ><b>No antibiotic information available for this post.</b></p>
    <?php endif; ?>

     </div>
 </div>

<!-- Plugin article show here -->
 
    </div>
</div>
 
<php if (have_posts()) :
    while (have_posts()) : the_post(); ?>

    <div class="col d-none" id="dynamicPost">
    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
            <header class="entry-header">
                <h1 class="entry-title" style="font-size:24px"><?php the_title(); ?></h1>
                
                <div class="entry-meta-single">
                    <b><span class="meta-author"><i class="ri-admin-line"></i>By Admin </span> |</b>
                    <b><span class="meta-date"> <i class="ri-timer-2-line"></i><?php the_time('F j, Y'); ?></span> |</b>
                   <b> <span class="meta-category">
                        <?php 
                        $categories = get_the_category();
                        if (!empty($categories)) {
                            echo implode(', ', wp_list_pluck($categories, 'name'));
                        }
                        ?>
                    </span></b>
                </div>
            </header>

            <div class="entry-content mt-3 mx-1">
                <?php the_content(); ?>
            </div>

         
        </article>

          

            
    </div>
   

    <!-- user comment section -->
     <div class="userComment  mt-5  d-none">
        
            <?php
        if (comments_open() || get_comments_number()) {
            comments_template();
        }
        ?>
     </div>
    <!-- user comment section -->


 </div>
</div>
  <!-- table of content -->

<div class="wp-content1">
       <div class="container">
    <div class="col-md-4  "id=''>
        <div class="fixed-toc"id="toc" style="display:none">
            <ul class="tree">
                <li>
                    <input type="checkbox" checked="checked" id="toc1" />
                    <label class="tree_label" for="toc1">
                        Table of Contents || <div class="on-off">
                <input type="checkbox" class="checkbox" id="checkbox">
                <label for="checkbox" class="checkbox-label">
                    <span class="ball off">OFF</span>
                </label>
    </div>
                    
                    </label>
                    <ul id="dynamic-toc-ul" style="display:none"></ul> <!-- Dynamic TOC will be generated here -->
                </li>
            </ul>
        </div>
    </div>
    </div>
 </div>
  <!-- table of content -->



<?php get_footer(); ?>