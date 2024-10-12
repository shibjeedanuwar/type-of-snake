$(document).ready(function() {
    let isTocVisible = false; // Track TOC visibility
    
     // Check for no-custom-post class
     if ($('#plugin-article-venoum').find('.no-custom-post').length) {
        // If no-custom-post is present
        toggleDisplay('#plugin-article-venoum', 'none'); // Hide the article
        updateButtonText('#dynamic-change', 'Hide Article', true);
        
        // Toggle visibility of dynamicPost and userComment
        $('#dynamicPost').toggleClass('d-none d-block');
        $('.userComment').toggleClass('d-none d-block');
        toggleDisplay('#toc', 'block'); // Show TOC
        isTocVisible = true; // Update state
    } else {
        $('#venoum').addClass('text-danger'); // Add class if no-custom-post found
        toggleDisplay('#plugin-article-venoum', 'block');
    }
    const currentDisplay = $('#toc').css('display');

    function toggleDisplay(selector, state) {
        $(selector).css('display', state === 'block' ? 'block' : 'none');
    }

    function updateButtonText(button, text, isDanger) {
        $(button).text(text).toggleClass('text-danger', isDanger);
    }

    $('#dynamic-change').click(function() {

        $('#venoum').removeClass('text-danger');
        $('#antibiotic').removeClass('text-danger');

        // Hide articles
        toggleDisplay('#plugin-article-venoum', 'none');
        toggleDisplay('#plugin-article-antibiotic', 'none');

        // Toggle visibility of dynamicPost and userComment
        $('#dynamicPost').toggleClass('d-none d-block');
        $('.userComment').toggleClass('d-none d-block');

        // const isOffset = $('#dynamic-css').hasClass('isOffset');    
        const currentText = $(this).text(); // Find the <span> inside the button

        if (currentText == 'Read Article') {
            updateButtonText(this, 'Hide Article', true);
            toggleDisplay('#toc', 'block');
            isTocVisible = true; // Update state
        } else if(currentText == 'Hide Article') {
            updateButtonText(this, 'Read Article', false);
            toggleDisplay('#toc', 'none');
            isTocVisible = false; // Update state
            toggleDisplay('#plugin-article-venoum', 'block');
            $('#venoum').addClass('text-danger');
            $('#dynamic-css').removeClass('isOffset offset');
        }
    });

    function handleArticleClick(activeBtn, inactiveBtn, articleToShow, articleToHide) {
        $('#dynamicPost').addClass('d-none');
        $('.userComment').addClass('d-none');

        $(activeBtn).addClass('text-danger');
        $(inactiveBtn).removeClass('text-danger');

        if ($('#dynamic-css').hasClass('offset')) {
            $('#dynamic-css').removeClass('offset');
            updateButtonText('#dynamic-change', 'Read Article', false);
            toggleDisplay('#toc', 'none');
            isTocVisible = false; // Update state
        }

        toggleDisplay(articleToShow, 'block');
        toggleDisplay(articleToHide, 'none');
    }

    $('#antibiotic').click(function() {
        handleArticleClick('#antibiotic', '#venoum', '#plugin-article-antibiotic', '#plugin-article-venoum');
        toggleDisplay('#toc', 'none');
        updateButtonText('#dynamic-change', 'Read Article', false);
        isTocVisible = false; // Update state
        $('#dynamic-toc-ul').css('display', 'none'); // Hide table of contents
        $('#checkbox').prop('checked', false); // Uncheck the checkbox
    });

    $('#venoum').click(function() {
        handleArticleClick('#venoum', '#antibiotic', '#plugin-article-venoum', '#plugin-article-antibiotic');
        toggleDisplay('#toc', 'none');
        updateButtonText('#dynamic-change', 'Read Article', false);
        isTocVisible = false; // Update state
        $('#dynamic-toc-ul').css('display', 'none'); // Hide table of contents
        $('#checkbox').prop('checked', false); // Uncheck the checkbox
    });


      // table of content structures Function to generate TOC dynamically
      function generateTOC() {
        const toc = $('.fixed-toc ul.tree > li > ul');
        toc.empty(); // Clear any existing TOC entries

        let currentH2 = null; // Store the current H2
        let currentH3 = null; // Store the current H3

        // Select all headings from h2 to h6 that are not inside .userComment
        $('h1, h2, h3, h4, h5, h6')
    .not('.remove-for-toc h1, .remove-for-toc h2, .remove-for-toc h3, .remove-for-toc h4, .remove-for-toc h5, .remove-for-toc h6')
    .not('.userComment h1, .userComment h2, .userComment h3, .userComment h4, .userComment h5, .userComment h6')
    .each(function() {
            const heading = $(this);
            const headingText = heading.text().trim();

           

            const id = heading.attr('id') || headingText.toLowerCase().replace(/[^a-z0-9]+/g, '-');
            heading.attr('id', id); // Set the ID to the heading

            const tocItem = $('<li></li>');
            const link = $('<a></a>').attr('href', `#${id}`).addClass('toc-link').text(heading.text());
            tocItem.append(link);

            // Nest under the appropriate heading level
            if (heading.is('h2')) {
                toc.append(tocItem);
                currentH2 = tocItem; // Update current H2
                currentH3 = null; // Reset current H3
            } else if (heading.is('h3')) {
                if (currentH2) {
                    if (!currentH2.find('ul').length) {
                        currentH2.append('<ul></ul>'); // Create ul if it doesn't exist
                    }
                    currentH2.find('ul').append(tocItem);
                    currentH3 = tocItem; // Update current H3
                }
            } else if (heading.is('h4')) {
                if (currentH3) {
                    if (!currentH3.find('ul').length) {
                        currentH3.append('<ul></ul>'); // Create ul if it doesn't exist
                    }
                    currentH3.find('ul').append(tocItem);
                }
            }
        });
    }

    

    // Smooth scrolling and highlighting active TOC item
$('.toc-link').on('click', function(event) {
    event.preventDefault();
    const target = $(this).attr('href');
    const targetOffset = $(target).offset().top; // Get the target offset
    $('html, body').animate({
        scrollTop: targetOffset // Scroll to the target offset
    }, 500);
});

$(window).on('scroll', function() {
    const scrollPos = $(window).scrollTop();
    const windowHeight = $(window).height();

    // Loop through each TOC link and determine if it should be active
    $('.toc-link').each(function() {
        const target = $(this).attr('href');
        const targetOffset = $(target).offset().top; // Get the target offset
        const targetHeight = $(target).outerHeight(); // Get the target height

        // Check if the target is in view
        if (scrollPos >= targetOffset - windowHeight / 2 && scrollPos < targetOffset + targetHeight) {
            $('.toc-link').removeClass('active'); // Remove active class from all
            $(this).addClass('active').css('color', 'red'); // Add active class and change color
        } else {
            // Reset color if not active
            $(this).css('color', '');
        }
    });
});
// table of content code 
 
     // table of content NO/OFF
     const checkbox = $("#checkbox");
     const ballText = $(".ball");

            checkbox.change(function() {
                if (checkbox.is(":checked")) {
                    ballText.text("ON").removeClass("off").addClass("on");
                    $('#dynamic-css').addClass('offset');
                    $('#dynamic-toc-ul').css('display', 'block');
                  //Call the function to generate the TOC
                   generateTOC();

                } else {
                    ballText.text("OFF").removeClass("on").addClass("off");
                    $('#dynamic-css').removeClass('offset');
                    $('#dynamic-toc-ul').css('display', 'none'); // hide table of contend
                    // $('#checkbox').prop('checked', false); // Uncheck the checkbox



                }
            });

});