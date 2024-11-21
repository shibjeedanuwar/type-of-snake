<?php get_header(); ?>

<style>
  .move-out-right {
    transition: transform 0.5s ease, opacity 0.5s ease;
    transform: translateX(100%); /* Move out to the right */
    opacity: 0; /* Fade out */
}
.move-in {
    transition: transform 0.5s ease, opacity 0.5s ease;
    transform: translateX(0); /* Move back to original position */
    opacity: 1; /* Fade in */
}
.move-out-left {
    transition: transform 0.5s ease, opacity 0.5s ease;
    transform: translateX(-110%); /* Move out to the right */
    opacity: 0; /* Fade out */
}
.hide{
  visibility: hidden; /* Hide the element */
}
.remove {
  display: none; /* Hide the element */
}
.fade-out {
    opacity: 0; /* Fade out */
    transition: opacity 0.5s ease; /* Smooth transition */
  }
  .fade-in {
    opacity: 1; /* Fade in */
    transition: opacity 0.5s ease; /* Smooth transition */
  }
</style>
	  <?php
// Get the current post ID
$post_id = get_the_ID();

// Fetch data from your custom table based on post ID
$table_name = $wpdb->prefix . 'venomous_creatures';
$query = $wpdb->prepare("SELECT * FROM $table_name WHERE post_id = %d", $post_id);
$results = $wpdb->get_results($query);

// Get the first row if results exist
$snake_data = !empty($results) ? $results[0] : null;

?>

<div class="row mt-md-3 mt-1">
<div id="app" class="max-w-7xl mx-auto px-4 py-8 relative ">
      <!-- Main Content -->
      <!-- Toggle Filter and Chat Button -->
      <div class="flex justify-between items-center mb-8">
        <div class="flex-1 flex justify-center">
          <div class="bg-slate-800 p-1 rounded-full inline-flex gap-1">
            <button id="snakeKey" class="flex items-center gap-1.5 px-3 py-1.5 rounded-full transition-all text-slate-400 hover:text-white">
            <svg class="w-3.5 h-3.5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M13 1L13.001 4.06201C16.6192 4.51365 19.4869 7.38163 19.9381 11L23 11V13L19.938 13.001C19.4864 16.6189 16.6189 19.4864 13.001 19.938L13 23H11L11 19.9381C7.38163 19.4869 4.51365 16.6192 4.06201 13.001L1 13V11L4.06189 11C4.51312 7.38129 7.38129 4.51312 11 4.06189L11 1H13ZM12 6C8.68629 6 6 8.68629 6 12C6 15.3137 8.68629 18 12 18C15.3137 18 18 15.3137 18 12C18 8.68629 15.3137 6 12 6ZM12 10C13.1046 10 14 10.8954 14 12C14 13.1046 13.1046 14 12 14C10.8954 14 10 13.1046 10 12C10 10.8954 10.8954 10 12 10Z"></path></svg>
            <!-- <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"> -->
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 21c-4.97 0-9-4.03-9-9s4.03-9 9-9 9 4.03 9 9-4.03 9-9 9z"></path>
              </svg>
              <span class="text-xs font-medium whitespace-nowrap">Snake details</span>
            </button>
            
            <button id="readArticle" class="flex items-center gap-1.5 px-3 py-1.5 rounded-full transition-all text-slate-400 hover:text-white">
              <!-- <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"> -->
              <svg class="w-3.5 h-3.5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M13 21V23H11V21H3C2.44772 21 2 20.5523 2 20V4C2 3.44772 2.44772 3 3 3H9C10.1947 3 11.2671 3.52375 12 4.35418C12.7329 3.52375 13.8053 3 15 3H21C21.5523 3 22 3.44772 22 4V20C22 20.5523 21.5523 21 21 21H13ZM20 19V5H15C13.8954 5 13 5.89543 13 7V19H20ZM11 19V7C11 5.89543 10.1046 5 9 5H4V19H11Z"></path></svg>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 21c-4.97 0-9-4.03-9-9s4.03-9 9-9 9 4.03 9 9-4.03 9-9 9z"></path>
              </svg>
              <span class="text-xs font-medium">Read article</span>
            </button>
          </div>
        </div>
       
      </div>
<!-- hide div start -->
 <div id="snakeInformation" class="">
      <!-- mobile layout Layout -->
      <div class="lg:hidden mt-8  bg-gradient-to-br from-slate-900 to-slate-800 text-white mw-100 mt-1 p-2 mw-100 p-3 mb-3 shadow" style="min-height:109vh;">
        <div class="relative flex flex-col ">
          <div id="snakeDetails" class="" >
            <!-- Snake details content here -->
            <div id="carouselExample" class="carousel slide" data-bs-ride="carousel">
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
          <button type="button" data-bs-target="#carouselExample" data-bs-slide-to="<?php echo $i; ?>" 
                  class="<?php echo $i === 0 ? 'active' : ''; ?>" 
                  aria-current="<?php echo $i === 0 ? 'true' : 'false'; ?>" 
                  aria-label="Slide <?php echo $i + 1; ?>"></button>
      <?php endfor; ?>
            </div>

            
        </div>
          </div>
          
          <!-- Mobile Horizontal Drag Divider -->
          <div id="dragDividerMobile" class="hidden relative h-1 cursor-row-resize drag-divider-mobile bg-slate-600">
            <!-- Horizontal Line -->
            <div class="absolute inset-0 h-0.5 bg-slate-600 remove"></div>
            <!-- Drag Icon -->
            <div class="absolute w-8 h-8 left-1/2 -ml-4 -mt-4 bg-slate-700 rounded-full flex items-center justify-center grip">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-grip-vertical w-4 h-4">
              <circle cx="9" cy="12" r="1"></circle>
              <circle cx="9" cy="5" r="1"></circle>
              <circle cx="9" cy="19" r="1"></circle>
              <circle cx="15" cy="12" r="1"></circle>
              <circle cx="15" cy="5" r="1">
            </circle>
            <circle cx="15" cy="19" r="1"></circle>
          </svg>
            </div>
          </div>
     
          <div id="snakeGrid" class="overflow-y-auto absolute  " style="margin-top:19rem; height:20rem;">
            <!-- Snake grid content here -->
            <div class="space-y-4 sm:space-y-6">
            <div class="space-y-4 mt-3">
<!-- 	snake name			 -->
				
    <h2 class="text-2xl sm:text-3xl font-bold tracking-tight text-emerald-400">
	 
	 <?php
                if ($snake_data && !empty($snake_data->snake_name)) {
                    echo $snake_data->snake_name;
                } else {
                    $isVenomous = false;
                    $categories = get_the_category();
                    foreach ($categories as $category) {
                        if ($category->slug === 'venomous') {
                            $isVenomous = true;
                            break;
                        }
                    }
                    echo '<b>' . $category->name . ' snakes</b>';

                }
		?>
				
		</h2>

				<!-- 	snake discription			 -->
           
				<p class="text-slate-300 leading-relaxed tracking-wide text-sm sm:text-base"> <?php
                if ($snake_data && !empty($snake_data->description)) {
                    echo $snake_data->description;
                } else {
                    echo $isVenomous 
                        ? 'These species possess highly potent venom designed to immobilize prey and aid in digestion. While they rarely seek human contact, their bites require immediate medical attention.'
                        : 'These gentle species lack venom glands and typically subdue prey through constriction. They are generally docile and make excellent subjects for observation and study.';
                }
                ?>
				</p>
				<!-- 	snake discription			 -->
				
          </div>
				
           <h3 class="text-lg sm:text-xl font-semibold tracking-wide text-slate-200">Key Characteristics</h3>
            <div class="grid gap-3 sm:gap-4">
				<!-- Danger Level -->
              <div class="flex items-start gap-3 bg-slate-800/50 p-3 sm:p-4 rounded-lg">
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-alert-triangle w-5 h-5 text-red-400 mt-1 flex-shrink-0">
                <path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z">
                </path><path d="M12 9v4"></path><path d="M12 17h.01"></path></svg>
                <div>
					
                  <h4 class="font-medium mb-1">Danger Level</h4>
                  <p class="text-slate-300 text-sm">
					     <?php
                if ($snake_data && !empty($snake_data->danger_level)) {
                    echo $snake_data->danger_level;
                } else {
                  
                    echo $isVenomous 
                        ? 'Generally dangerous. Bites can be fatal without immediate treatment.' 
                        : 'Generally harmless to humans. May bite defensively but not dangerous.';
                }
                ?>
            
					</p>
                </div>
              </div>
				 <!-- Temperament -->
              <div class="flex items-start gap-3 bg-slate-800/50 p-3 sm:p-4 rounded-lg">
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-heart w-5 h-5 text-pink-400 mt-1 flex-shrink-0">
              <path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"></path></svg>
                <div>
                  <h4 class="font-medium mb-1">Temperament</h4>
                   <p class="text-slate-300 text-sm">
                <?php
                if ($snake_data && !empty($snake_data->temperament)) {
                    echo $snake_data->temperament;
                } else {
                    echo $isVenomous 
                        ? 'Usually shy and reclusive. Will defend aggressively if threatened.' 
                        : 'Typically docile and calm. Many species adapt well to handling.';
                }
                ?>
            </p>
                </div>
              </div>
				    <!-- Size Range -->
              <div class="flex items-start gap-3 bg-slate-800/50 p-3 sm:p-4 rounded-lg">
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-ruler w-5 h-5 text-blue-400 mt-1 flex-shrink-0">
                <path d="M21.3 15.3a2.4 2.4 0 0 1 0 3.4l-2.6 2.6a2.4 2.4 0 0 1-3.4 0L2.7 8.7a2.41 2.41 0 0 1 0-3.4l2.6-2.6a2.41 2.41 0 0 1 3.4 0Z"></path>
                <path d="m14.5 12.5 2-2"></path><path d="m11.5 9.5 2-2"></path>
                <path d="m8.5 6.5 2-2"></path><path d="m17.5 15.5 2-2"></path></svg>
                <div>
                  <h4 class="font-medium mb-1">Size Range</h4>
                  <p class="text-slate-300 text-sm">
                <?php
                if ($snake_data && !empty($snake_data->size_range)) {
                    echo $snake_data->size_range;
                } else {
                    echo $isVenomous 
                        ? 'Varies greatly. Most species range from 4-8 feet in length.' 
                        : 'Generally 3-6 feet, with some species reaching up to 8 feet.';
                }
                ?>
            </p>
                </div>
              </div>
				
				 <!-- Habitat -->
              <div class="flex items-start gap-3 bg-slate-800/50 p-3 sm:p-4 rounded-lg">
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-earth w-5 h-5 text-green-400 mt-1 flex-shrink-0"><path d="M21.54 15H17a2 2 0 0 0-2 2v4.54"></path><path d="M7 3.34V5a3 3 0 0 0 3 3v0a2 2 0 0 1 2 2v0c0 1.1.9 2 2 2v0a2 2 0 0 0 2-2v0c0-1.1.9-2 2-2h3.17"></path>
                <path d="M11 21.95V18a2 2 0 0 0-2-2v0a2 2 0 0 1-2-2v-1a2 2 0 0 0-2-2H2.05"></path>
                <circle cx="12" cy="12" r="10"></circle></svg>
                <div>
                  <h4 class="font-medium mb-1">Habitat</h4>
                 <p class="text-slate-300 text-sm">
                <?php
                if ($snake_data && !empty($snake_data->habitat)) {
                    echo $snake_data->habitat;
                } else {
                    echo $isVenomous 
                        ? 'Found in diverse environments from deserts to rainforests.' 
                        : 'Adaptable to various habitats, including forests and grasslands.';
                }
                ?>
            </p>
                </div>
              </div>
				<!-- Lifespan -->
              <div class="flex items-start gap-3 bg-slate-800/50 p-3 sm:p-4 rounded-lg">
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-clock3 w-5 h-5 text-yellow-400 mt-1 flex-shrink-0">
              <circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16.5 12"></polyline></svg>
                <div>
                  <h4 class="font-medium mb-1">Lifespan</h4>
                 <p class="text-slate-300 text-sm">
                <?php
                if ($snake_data && !empty($snake_data->lifespan)) {
                    echo $snake_data->lifespan;
                } else {
                    echo $isVenomous 
                        ? 'Average 10-15 years in the wild, up to 20 in captivity.' 
                        : 'Can live 20-30 years in captivity with proper care.';
                }
                ?>
            </p>
                </div>
              </div>
            </div>
          </div>
            
          </div>
        </div>
      </div>

      <!-- // desktop layout  -->

      <div class="hidden lg:flex relative h-[calc(100vh-12rem)] mt-8 min-h-screen bg-gradient-to-br from-slate-900 to-slate-800 text-white mw-100 mt-1 p-2 mw-100 p-3 mb-3 shadow">
        <div id="snakeDetailsLg" class="overflow-y-auto" style="width: 50%;">
			
			
      <div id="carouselExample1" class="carousel slide" data-bs-ride="carousel">
    <div class="carousel-inner">
        <?php
        $is_first = true;

        if (has_post_thumbnail()) : ?>
            <div class="carousel-item <?php echo $is_first ? 'active' : ''; ?>">
                <img src="<?php echo esc_url(get_the_post_thumbnail_url()); ?>" class="d-block w-100" alt="<?php echo esc_attr(get_the_title()); ?>">
            </div>
            <?php $is_first = false; ?>
        <?php endif; ?>

        <?php
        $content = get_the_content();
        preg_match_all('/<img[^>]+src="([^">]+)"/', $content, $matches);
        foreach ($matches[1] as $image_url) : ?>
            <div class="carousel-item <?php echo $is_first ? 'active' : ''; ?>">
                <img src="<?php echo esc_url($image_url); ?>" class="d-block w-100" alt="">
            </div>
            <?php $is_first = false; ?>
        <?php endforeach; ?>

        <?php
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
        $total_slides = (has_post_thumbnail() ? 1 : 0) + count($matches[1]) + count($attachments);
        for ($i = 0; $i < $total_slides; $i++) : ?>
            <button type="button" data-bs-target="#carouselExample1" data-bs-slide-to="<?php echo $i; ?>" 
                    class="<?php echo $i === 0 ? 'active' : ''; ?>" 
                    aria-current="<?php echo $i === 0 ? 'true' : 'false'; ?>" 
                    aria-label="Slide <?php echo $i + 1; ?>"></button>
        <?php endfor; ?>
    </div>
</div>
          <!-- Snake details content here -->
          <div class="space-y-4 mt-3">
            <h2 class="text-2xl sm:text-3xl font-bold tracking-tight text-emerald-400">
				<?php
                if ($snake_data && !empty($snake_data->snake_name)) {
                    echo $snake_data->snake_name;
                } else {
                    $isVenomous = false;
                    $categories = get_the_category();
                    foreach ($categories as $category) {
                        if ($category->slug === 'venomous') {
                            $isVenomous = true;
                            break;
                        }
                    }
                    echo '<b>' . $category->name . '</b>';

                }
		?>
			  </h2>
         <!-- 	 snake discription			 -->
           
				<p class="text-slate-300 leading-relaxed tracking-wide text-sm sm:text-base"> 
					<?php
                if ($snake_data && !empty($snake_data->description)) {
                    echo $snake_data->description;
                } else {
                   echo $description = $isVenomous
    ? 'These species possess highly potent venom designed to immobilize prey and aid in digestion. While they rarely seek human contact, their bites require immediate medical attention.'
    : 'These gentle species lack venom glands and typically subdue prey through constriction. They are generally docile and make excellent subjects for observation and study.';
                }
                ?>
				</p>
			 
				<!-- 	snake discription			 -->
			  
          </div>
        </div>
        
        <!-- Update the dragDivider HTML -->
        <div id="dragDivider" class="relative w-1 cursor-col-resize drag-divider bg-slate-600" style="width: 0.5rem;">
          <!-- Vertical Line -->
          <div class="absolute inset-0 w-0.5 bg-slate-600" style="display:none"></div>
          <!-- Drag Icon -->
          <div class="absolute w-8 h-8 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-slate-700 rounded-full flex items-center justify-center grip" style="top: 50%; left: calc(50% - 1.01rem);">
          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-grip-vertical w-4 h-4">
              <circle cx="9" cy="12" r="1"></circle>
              <circle cx="9" cy="5" r="1"></circle>
              <circle cx="9" cy="19" r="1"></circle>
              <circle cx="15" cy="12" r="1"></circle>
              <circle cx="15" cy="5" r="1">
            </circle>
            <circle cx="15" cy="19" r="1"></circle>
          </svg>
          </div>
        </div>

        <div id="snakeGridLg" class="overflow-y-auto" style="width: 50%; color:red;">
          <!-- Snake grid content here -->
          <div class="min-h-screen bg-gradient-to-br from-slate-900 to-slate-800 text-white mw-100 mt-1 p-2 mw-100 p-3 mb-3 shadow" id='plugin-article-venoum' >
            <!-- venomous text here -->
            <div class="p-4 sm:p-6 space-y-6 sm:space-y-8">
        
          <div class="space-y-4 sm:space-y-6">
            <h3 class="text-lg sm:text-xl font-semibold tracking-wide text-slate-200">Key Characteristics</h3>
            <div class="grid gap-3 sm:gap-4">
              <div class="flex items-start gap-3 bg-slate-800/50 p-3 sm:p-4 rounded-lg">
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-alert-triangle w-5 h-5 text-red-400 mt-1 flex-shrink-0">
                <path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z">
                </path><path d="M12 9v4"></path><path d="M12 17h.01"></path></svg>
                <div>
                  <h4 class="font-medium mb-1">Danger Level</h4>
                  <p class="text-slate-300 text-sm">
					     <?php
                if ($snake_data && !empty($snake_data->danger_level)) {
                    echo $snake_data->danger_level;
                } else {
                    
                    echo $isVenomous 
                        ? 'Generally dangerous. Bites can be fatal without immediate treatment.' 
                        : 'Generally harmless to humans. May bite defensively but not dangerous.';
                }
                ?>
            
					</p>
                </div>
              </div>
              <div class="flex items-start gap-3 bg-slate-800/50 p-3 sm:p-4 rounded-lg">
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-heart w-5 h-5 text-pink-400 mt-1 flex-shrink-0">
              <path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"></path></svg>
                <div>
                  <h4 class="font-medium mb-1">Temperament</h4>
                  <p class="text-slate-300 text-sm">
                <?php
                if ($snake_data && !empty($snake_data->temperament)) {
                    echo $snake_data->temperament;
                } else {
                    echo $isVenomous 
                        ? 'Usually shy and reclusive. Will defend aggressively if threatened.' 
                        : 'Typically docile and calm. Many species adapt well to handling.';
                }
                ?>
            </p>
                </div>
              </div>
              <div class="flex items-start gap-3 bg-slate-800/50 p-3 sm:p-4 rounded-lg">
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-ruler w-5 h-5 text-blue-400 mt-1 flex-shrink-0">
                <path d="M21.3 15.3a2.4 2.4 0 0 1 0 3.4l-2.6 2.6a2.4 2.4 0 0 1-3.4 0L2.7 8.7a2.41 2.41 0 0 1 0-3.4l2.6-2.6a2.41 2.41 0 0 1 3.4 0Z"></path>
                <path d="m14.5 12.5 2-2"></path><path d="m11.5 9.5 2-2"></path>
                <path d="m8.5 6.5 2-2"></path><path d="m17.5 15.5 2-2"></path></svg>
                <div>
                  <h4 class="font-medium mb-1">Size Range</h4>
                 <p class="text-slate-300 text-sm">
                <?php
                if ($snake_data && !empty($snake_data->lifespan)) {
                    echo $snake_data->size_range;
                } else {
                    echo $isVenomous 
                        ? 'Varies greatly. Most species range from 4-8 feet in length.' 
                        : 'Generally 3-6 feet, with some species reaching up to 8 feet.';
                }
                ?>
            </p>
                </div>
              </div>
              <div class="flex items-start gap-3 bg-slate-800/50 p-3 sm:p-4 rounded-lg">
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-earth w-5 h-5 text-green-400 mt-1 flex-shrink-0"><path d="M21.54 15H17a2 2 0 0 0-2 2v4.54"></path><path d="M7 3.34V5a3 3 0 0 0 3 3v0a2 2 0 0 1 2 2v0c0 1.1.9 2 2 2v0a2 2 0 0 0 2-2v0c0-1.1.9-2 2-2h3.17"></path>
                <path d="M11 21.95V18a2 2 0 0 0-2-2v0a2 2 0 0 1-2-2v-1a2 2 0 0 0-2-2H2.05"></path>
                <circle cx="12" cy="12" r="10"></circle></svg>
                <div>
                  <h4 class="font-medium mb-1">Habitat</h4>
                <p class="text-slate-300 text-sm">
                <?php
                if ($snake_data && !empty($snake_data->habitat)) {
                    echo $snake_data->habitat;
                } else {
                    echo $isVenomous 
                        ? 'Found in diverse environments from deserts to rainforests.' 
                        : 'Adaptable to various habitats, including forests and grasslands.';
                }
                ?>
            </p>
                </div>
              </div>
              <div class="flex items-start gap-3 bg-slate-800/50 p-3 sm:p-4 rounded-lg">
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-clock3 w-5 h-5 text-yellow-400 mt-1 flex-shrink-0">
              <circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16.5 12"></polyline></svg>
                <div>
                  <h4 class="font-medium mb-1">Lifespan</h4>
                   <p class="text-slate-300 text-sm">
                <?php
                if ($snake_data && !empty($snake_data->lifespan)) {
                    echo $snake_data->lifespan;
                } else {
                    echo $isVenomous 
                        ? 'Average 10-15 years in the wild, up to 20 in captivity.' 
                        : 'Can live 20-30 years in captivity with proper care.';
                }
                ?>
            </p>
                </div>
              </div>
            </div>
          </div>
        </div>
    <?php // endif; ?>
     </div>
        </div>
      </div>
                </div>
      <!-- end hide div -->
       <article>
        <div id="blog-article" class=" col col-md-8 mx-md-auto p-2 shadow move-out-left  remove">
          <php if (have_posts()) :
            while (have_posts()) : the_post(); ?>
        
           
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
        
                    <div class="entry-content  mx-1">
                        <?php the_content(); ?>
                    </div>
        
                 
                </article>
        </div>

       </article>
    </div>
  
    </div>
<script>
  // Ensure the DOM is fully loaded before adding event listeners
  document.addEventListener('DOMContentLoaded', () => {
    const readArticle = document.getElementById('readArticle');
    const snakeKey = document.getElementById('snakeKey');
    let updateColor = true;

    // Function to handle the transition for showing snake information
    function showSnakeInformation() {
      document.getElementById('snakeInformation').classList.remove('remove'); 
      document.getElementById('snakeInformation').classList.add('move-in'); 
      document.getElementById("blog-article").classList.add('move-out-left');
      setTimeout(() => {
        document.getElementById("blog-article").classList.add('remove');
      }, 500); // Match this duration with the CSS transition duration
    }

    // Function to handle the transition for hiding snake information
    function hideSnakeInformation() {
      document.getElementById("blog-article").classList.remove('move-out-left');
      document.getElementById("blog-article").classList.remove('move-in');
      document.getElementById('snakeInformation').classList.remove('move-in'); 
      document.getElementById('snakeInformation').classList.add('move-out-right'); 
      setTimeout(() => {
        document.getElementById('snakeInformation').classList.add('remove'); 
        document.getElementById("blog-article").classList.remove('remove'); // Show blog-article
        document.getElementById("blog-article").classList.add('move-in'); // Add transition effect
      }, 500); // Match this duration with the CSS transition duration
    }

    // Add event listeners for the buttons
    readArticle.addEventListener('click', () => {
      updateColor = false;
      updateToggleButtons();
      hideSnakeInformation();
    });

    snakeKey.addEventListener('click', () => {
      updateColor = true;
      updateToggleButtons();
      showSnakeInformation();
    });

    function updateToggleButtons() {
      if (updateColor) {
        snakeKey.classList.add('bg-emerald-500', 'text-white');
        readArticle.classList.remove('bg-emerald-500', 'text-white');
        readArticle.classList.add('text-slate-400');
      } else {
        readArticle.classList.add('bg-emerald-500', 'text-white');
        snakeKey.classList.remove('bg-emerald-500', 'text-white');
        readArticle.classList.add('text-slate-400');
      }
    }
    updateToggleButtons();
  });
</script>
<?php get_footer(); ?>
