<?php get_header(); ?>
<div class="row">
<div class="col-12 col-md-8 m-md-auto mt-md-5 mt-2">
<div class="error-404" id="error-page">
    <div class="content">
        <h2 class="error-header" data-text="404">404</h2>
        <h4 data-text="Oops! Page not found tex-center">Oops! Page not found</h4>
        <p class="p-2">
            Sorry, the page you're looking for doesn't exist. If you think something is broken, please report a problem.
        </p>
        <div class="btns">
            <a href="<?php echo home_url(); ?>">Return Home</a>
            <a href="<?php echo home_url('/contact-us'); ?>">Report Problem</a> <!-- Adjust this link as needed -->
        </div>
    </div>
</div>
</div>
</div>

<?php get_footer(); ?>