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
 
/* <!-- cookie-consent.php --> */
const consentBanner = $('#cookie-consent');
const acceptButton = $('.acceptButton');
const declineButton = $('.declineButton');

// Check if the user has already made a choice
setTimeout(function() {
    if (getCookie('cookie_consent') === '') {
   // consentBanner.show(); // Show the banner if no choice has been made
    }
}, 15000); // 15 seconds

acceptButton.on('click', function() {
    setCookie('cookie_consent', 'accepted', 30);
    consentBanner.hide();
});

declineButton.on('click', function() {
    setCookie('cookie_consent', 'declined', 30);
    consentBanner.hide();
});

function setCookie(name, value, days) {
    const expires = new Date(Date.now() + days * 864e5).toUTCString();
    document.cookie = name + '=' + encodeURIComponent(value) + 
                      '; expires=' + expires + 
                      '; path=/' + 
                      '; domain=theme.typeofsnake.com;' + // Set the domain if necessary
                      'Secure; SameSite=Lax'; // Add Secure and SameSite attributes
}
function getCookie(name) {
    return document.cookie.split('; ').reduce((r, v) => {
        const parts = v.split('=');
        return parts[0] === name ? decodeURIComponent(parts[1]) : r;
    }, '');
}



   
});
