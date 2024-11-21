document.addEventListener('DOMContentLoaded', () => {
   
let currentPage = 1; // Track the current page
let totalPosts = 0; // Total number of posts
const postsPerPage = 9; // Number of posts per page
const adminUrl= ajax_object.ajax_url;
const listButton = $('.list-view');
const gridButton = $('.grid-view');
const wrapper = $('#postContainer'); // Container for posts
const loader=`  
      <div class="loader mt-4 mb-3 mx-auto"></div>`;

    wrapper.append(loader);
    /* <b class="p-2 mx-1 mobile-category  ">${cat}</b>
            <h2 class="list-h2 p-2 mx-1">${title} </h2>
            <img src="${imageUrl}" alt="Post Image" class="img-fluid mb-2" alt="${alt_text}"/> */

// Function to create a post item
function createPostItem(title, imageUrl,alt_text, desc, link, cat,date) { 
    const truncatedExcerpt = desc.length > 100 ? desc.substring(0, 100) + '...' : desc; // Limit to 100 characters
       const cheeckAlt= alt_text ? alt_text : 'PostImage';
    return `
    <a href="${link}" class="text-dark">
    <div class="item">

             <div class="shadow-sm " id="list-images">
              <img class="card-img-top " src="${imageUrl}" alt="${cheeckAlt}"/>
          
            <div class="card-body d-sr-block d-md-none mobile-category">
                <h5 class="card-title">${title}</h5>
                <p class="mb-2">${truncatedExcerpt}</p>
            </div>
        </div>

        <div class="details card-body ">
         
            <h2>${title}</h2>
             <b class="p-2 ">${cat} | Admin | ${date}</b>
            <p class="mb-2">${truncatedExcerpt}</p>
           

        </div>
    </div>
   </a>`;}

// Load posts based on selected categories
function loadPosts(offset = 0) {
    const selectedCategories = [];
    $('input[type="checkbox"]:checked').each(function() {
        selectedCategories.push($(this).attr('name')); // Get the name (slug) of checked categories
    });
    // console.log(selectedCategories);
    

    $.ajax({
        url: adminUrl,
        type: 'POST',
        data: {
            action: 'load_posts',
            categories: selectedCategories,
            page: currentPage, // Send the current page
            posts_per_page: postsPerPage // Send the number of posts per page
        },
        success: function(data) {
            const response = JSON.parse(data);
            const posts = response.posts;
            totalPosts = response.total; // Get total number of posts
            updatePagination(); // Update pagination after loading posts

            wrapper.empty(); // Clear previous posts
            posts.forEach(post => {
                const postItem = createPostItem(post.title, post.imageUrl,post.alt_text, post.postDesc, post.permalink, post.categories,post.date);
                wrapper.append(postItem);
            });
        },
        error: function() {
            alert('Error loading posts.');
        }
    });
}


// Update pagination based on total posts
function updatePagination() {
    const totalPages = Math.ceil(totalPosts / postsPerPage);
    const paginationWrapper = $('#pagination');
    paginationWrapper.empty(); // Clear previous pagination links
    wrapper.empty(); // Clear previous posts
    wrapper.append(loader);

    const ul = $('<ul class="pagination"></ul>');

    // Previous Button
    if (currentPage > 1) {
        ul.append('<li class="page-item d-none d-md-block"><a class="page-link" href="#" data-page="' + (currentPage - 1) + '">Previous</a></li>');
    } else {
        ul.append('<li class="page-item disabled d-none d-md-block"><a class="page-link" href="#" tabindex="-1">Previous</a></li>');
    }

    // Page Numbers
    for (let i = 1; i <= totalPages; i++) {
        const pageItem = $('<li class="page-item"></li>');
        const link = $('<a class="page-link" href="#" data-page="' + i + '">' + i + '</a>');
        if (i === currentPage) {
            pageItem.addClass('active');
            // link.append(' <span class="sr-only"></span>');
        }
        pageItem.append(link);
        ul.append(pageItem);
    }

    // Next Button
    if (currentPage < totalPages) {
        ul.append('<li class="page-item d-none d-md-block"><a class="page-link" href="#" data-page="' + (currentPage + 1) + '">Next</a></li>');
    } else {
        ul.append('<li class="page-item disabled d-none d-md-block"><a class="page-link" href="#" tabindex="-1">Next</a></li>');
    }

    paginationWrapper.append(ul); // Add the complete pagination to the wrapper
}



// Trigger loading posts on page load
$(document).ready(function() {

    // Trigger loading posts when the "Apply Filter" button is clicked
    $('#filter-form').on('submit', function(event) {
        event.preventDefault(); // Prevent default form submission
        currentPage = 1; // Reset to the first page

        // Check if at least one checkbox is checked
        const checkedCount = $('input[type="checkbox"]:checked').length;
        if (checkedCount === 0) {
            $('#alert-messages').show(); // Show alert if no checkboxes are checked
        } else {
            $('#alert-messages').hide(); // Hide alert if at least one checkbox is checked
            wrapper.empty(); // Clear previous posts

            wrapper.append(loader);

            loadPosts(); // Load posts based on selected categories
        }
    });

    // Trigger loading posts when checkboxes change
    $('input[type="checkbox"]').on('change', function() {
        $('#alert-messages').hide(); // Hide alert when user checks a checkbox
    });

    // Pagination click event
    $('#pagination').on('click', 'a', function(event) {
        event.preventDefault();
        const page = $(this).data('page');
        if (page !== currentPage) {
            currentPage = page; // Update current page
            wrapper.empty(); // Clear previous posts
            wrapper.append(loader);
            loadPosts(); // Load posts for the new page
        }
    });

    loadPosts(); // Initial load of all posts

});



// List view button functionality
listButton.on('click', function() {
    gridButton.removeClass('red');
    listButton.addClass('red');
    wrapper.removeClass('grid-v').addClass('list');
    $('.item').css({
        width: '100%', // Full width for list view
        float: 'none'  // Remove float for list view
    });
});

// Grid view button functionality
gridButton.on('click', function() {
    listButton.removeClass('red');
    gridButton.addClass('red');
    wrapper.removeClass('list').addClass('grid-v');
    $('.item').css({
        width: '', // Adjust as needed for grid view
        float: 'left' // Re-enable float for grid view
    });
});

});