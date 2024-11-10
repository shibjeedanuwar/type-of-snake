    <div class="row">
    <div class="col-12 col-md-8 m-md-auto">
        <!-- cookie-consent.php -->
<div class="cookies-pop">

<div id="cookie-consent" class="card" style="display:none">
    <img width="80" height="80" src="https://img.icons8.com/plasticine/100/000000/cookie.png" alt="cookie"/>
    <p class="cookieHeading">We use cookies.</p>
    <p class="cookieDescription">This website uses cookies to ensure you get the best experience on our site.
        <a href="<?php echo home_url('/cookie-policy'); ?>" style="color: #7b57ff;" rel="noopener noreferrer" aria-label="Visit our cookies page">Learn more</a>
    </p>
    <div class="buttonContainer">
        <button class="acceptButton">Allow</button>
        <button class="declineButton">Decline</button>
    </div>
</div>
</div>
    </div>
    </div>
    <footer class="footer ">
        <p>Copyright &copy; <?php echo date('Y'); ?> 
        <img src="<?php echo  get_template_directory_uri() . '/assets/images/type-of-snake.png'?>" class="" alt="logo" style="width:102px; height:auto; margin-top: -6px;"/>
        All Rights Reserved.</p>
    </footer>
    <?php wp_footer(); ?>
    
   
</body>
</html> 