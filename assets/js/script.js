$(document).ready(function() {


    // Header navbar menu
    /*=============== SHOW MENU ===============*/

    const navMenu = document.getElementById('nav-menu'),
          navToggle = document.getElementById('nav-toggle'),
          navClose = document.getElementById('nav-close');
    
    /* Menu show */
    if(navToggle) {
        navToggle.addEventListener('click', () => {
            navMenu.classList.add('show-menu');
        });
    }

    /* Menu hidden */
    if(navClose) {
        navClose.addEventListener('click', () => {
            navMenu.classList.remove('show-menu');
        });
    }

    // Search popup
    $('#openPopup').click(function() {
        $('#popup').css('display', 'flex');
    });

    $('#closePopup').click(function() {
        $('#popup').css('display', 'none');
    }); 

    $('#searchform').on('submit', function(event) {
        var searchInput = $('#searchInput').val().trim();
        if (searchInput === '') {
            event.preventDefault(); // Stop form submission
            alert('Please enter type your search...');
        }
    });
    // Submenu toggle

     const dropDown = $('.has-submenu');

     if (dropDown.length > 0) { // Check if any elements exist
        dropDown.click(function(event) {
            // Prevent the click from bubbling up to higher elements
            event.stopPropagation();

            // Find the submenu within the clicked .has-submenu
            const submenu = $(this).find('.nav__submenu');

            // Toggle the display of the specific submenu
            submenu.toggle(); // Show/hide the submenu

            // Optionally, hide other submenus
            dropDown.find('.nav__submenu').not(submenu).hide(); // Hide other submenus
        });
    }
   
});