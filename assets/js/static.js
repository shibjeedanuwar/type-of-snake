document.addEventListener('DOMContentLoaded', () => {
  const chatButton = document.getElementById('chatButton');
  const chatInterface = document.getElementById('chatInterface');
  const closeChat = document.getElementById('closeChat');
  const nonVenomousButton = document.getElementById('nonVenomousButton');
  const venomousButton = document.getElementById('venomousButton');
  
  const snakeDetails = document.getElementById('snakeDetails');
  const snakeGrid = document.getElementById('snakeGrid');
  const snakeDetailsLg = document.getElementById('snakeDetailsLg');
  const snakeGridLg = document.getElementById('snakeGridLg');
  const dragDivider = document.getElementById('dragDivider');
  const dragIcon = dragDivider ? dragDivider.querySelector('div') : null;
  const chatForm = document.getElementById('chatForm');
  const chatInput = document.getElementById('chatInput');
  const chatMessages = document.getElementById('chatMessages');
  // const sendUrl = my_ajax_obj.ajax_url;
  let isDragging = false;

   // Mobile drag functionality
   const dragDividerMobile = document.getElementById('dragDividerMobile');
//    const snakeDetails = document.getElementById('snakeDetails');
//    const snakeGrid = document.getElementById('snakeGrid');
   let isMobileDragging = false;
 
   if (dragDividerMobile) {
       const handleMobileMouseDown = (e) => {
         e.preventDefault();
         isMobileDragging = true;
         dragDividerMobile.classList.add('dragging-mobile');
       };
 
       const handleMobileMouseMove = (e) => {
         if (!isMobileDragging) return;
 
         const clientY = e.touches ? e.touches[0].clientY : e.clientY;
         const container = dragDividerMobile.parentElement;
         const containerRect = container.getBoundingClientRect();
         const containerHeight = containerRect.height;
         const relativeY = clientY - containerRect.top;
         const position = (relativeY / containerHeight) * 100;
         const clampedPosition = Math.min(Math.max(20, position), 80);
 
         if (snakeDetails && snakeGrid) {
             snakeDetails.style.height = `${clampedPosition}%`;
             snakeGrid.style.height = `${100 - clampedPosition}%`;
         }
       };
 
       const handleMobileMouseUp = () => {
         isMobileDragging = false;
         dragDividerMobile.classList.remove('dragging-mobile');
       };
 
       // Mobile mouse events
       dragDividerMobile.addEventListener('mousedown', handleMobileMouseDown);
       window.addEventListener('mousemove', handleMobileMouseMove);
       window.addEventListener('mouseup', handleMobileMouseUp);
 
       // Mobile touch events
       dragDividerMobile.addEventListener('touchstart', handleMobileMouseDown, { passive: false });
       window.addEventListener('touchmove', handleMobileMouseMove, { passive: false });
       window.addEventListener('touchend', handleMobileMouseUp);
 
       // Prevent mobile drag interference with chat
       dragDividerMobile.addEventListener('mousedown', (e) => {
         e.stopPropagation();
       });
   }

  // Update drag icon position
  function updateDragIconPosition() {
    if (dragIcon) {
        const rect = dragDivider.getBoundingClientRect();
        dragIcon.style.left = `${rect.left}px`;
        dragIcon.style.top = '50%';
        dragIcon.style.transform = 'translateY(-50%)';
    }
  }

  // Initial position
  updateDragIconPosition();

  const handleMouseDown = (e) => {
    e.preventDefault();
    isDragging = true;
    dragDivider.classList.add('dragging');
  };

  const handleMouseMove = (e) => {
    if (!isDragging) return;

    const clientX = e.touches ? e.touches[0].clientX : e.clientX;
    const containerWidth = document.documentElement.clientWidth;
    const position = (clientX / containerWidth) * 100;
    const clampedPosition = Math.min(Math.max(20, position), 80);

    if (snakeDetailsLg && snakeGridLg) {
        snakeDetailsLg.style.width = `${clampedPosition}%`;
        snakeGridLg.style.width = `${100 - clampedPosition}%`;
        updateDragIconPosition();
    }
  };

  const handleMouseUp = () => {
    isDragging = false;
    dragDivider.classList.remove('dragging');
  };

  // Mouse events
  dragDivider.addEventListener('mousedown', handleMouseDown);
  window.addEventListener('mousemove', handleMouseMove);
  window.addEventListener('mouseup', handleMouseUp);

  // Touch events
  dragDivider.addEventListener('touchstart', handleMouseDown, { passive: false });
  window.addEventListener('touchmove', handleMouseMove, { passive: false });
  window.addEventListener('touchend', handleMouseUp);

  // Update position on window resize
  window.addEventListener('resize', updateDragIconPosition);

  // Prevent drag interference with chat
  dragDivider.addEventListener('mousedown', (e) => {
    e.stopPropagation();
  });

  let isVenomous = false;

  chatButton.addEventListener('click', () => {
    chatInterface.classList.toggle('translate-x-full');
  });

  closeChat.addEventListener('click', () => {
    chatInterface.classList.add('translate-x-full');
  });

  chatForm.addEventListener('submit', (e) => {
    e.preventDefault();
    const message = chatInput.value.trim();
    if (message) {
      addMessage(message, true);
      chatInput.value = '';
      setTimeout(() => {
        addMessage("Thanks for your message! I'd be happy to help you learn more about snakes. We are working on this feature and it will be available very soon.", false);
      }, 1000);
    }
  });

  function addMessage(text, isUser) {
    const messageElement = document.createElement('div');
    messageElement.className = `${isUser ? 'bg-emerald-500 ml-auto' : 'bg-slate-800 mr-auto'} rounded-lg p-3 max-w-[80%]`;
    messageElement.innerHTML = `<p class="text-sm">${text}</p>`;
    chatMessages.appendChild(messageElement);
    chatMessages.scrollTop = chatMessages.scrollHeight;
  }

  


  function updateToggleButtons() {
    if (isVenomous) {
      venomousButton.classList.add('bg-emerald-500', 'text-white');
      nonVenomousButton.classList.remove('bg-emerald-500', 'text-white');
      nonVenomousButton.classList.add('text-slate-400');
    } else {
      nonVenomousButton.classList.add('bg-emerald-500', 'text-white');
      venomousButton.classList.remove('bg-emerald-500', 'text-white');
      venomousButton.classList.add('text-slate-400');
    }
  }
  function showLoader() {
    const loaderHTML = '<div class="loader mt-5 mb-3 mx-auto"></div>';
    if (snakeGrid && snakeGridLg) {
      snakeGrid.innerHTML = loaderHTML;
      snakeGridLg.innerHTML = loaderHTML;
    }
    if (snakeDetails && snakeDetailsLg && snakeGrid && snakeGridLg) {
      snakeDetails.innerHTML = loaderHTML;
      snakeDetailsLg.innerHTML = loaderHTML;
      snakeGrid.innerHTML = loaderHTML;
      snakeGridLg.innerHTML = loaderHTML;
    }

  }
  function hideLoader() {
    const loaders = document.querySelectorAll('.loader');
    loaders.forEach(loader => loader.remove());
  }


  function renderContent(snakeData = []) {
    hideLoader();  // Hide loader before rendering new content
    const title = isVenomous ? 'Venomous Species' : 'Non-venomous Species';
    const description = isVenomous
      ? 'These species possess highly potent venom designed to immobilize prey and aid in digestion. While they rarely seek human contact, their bites require immediate medical attention.'
      : 'These gentle species lack venom glands and typically subdue prey through constriction. They are generally docile and make excellent subjects for observation and study.';

    const detailsContent = `
      <div class="p-4 sm:p-6 space-y-6 sm:space-y-8">
        <div class="space-y-4">
          <h2 class="text-2xl sm:text-3xl font-bold tracking-tight text-emerald-400">${title}</h2>
          <p class="text-slate-300 leading-relaxed tracking-wide text-sm sm:text-base">${description}</p>
        </div>
        <div class="space-y-4 sm:space-y-6">
          <h3 class="text-lg sm:text-xl font-semibold tracking-wide text-slate-200">Key Characteristics</h3>
          <div class="grid gap-3 sm:gap-4">
            <div class="flex items-start gap-3 bg-slate-800/50 p-3 sm:p-4 rounded-lg">
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-alert-triangle w-5 h-5 text-red-400 mt-1 flex-shrink-0">
              <path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z">
              </path><path d="M12 9v4"></path><path d="M12 17h.01"></path></svg>
              <div>
                <h4 class="font-medium mb-1">Danger Level</h4>
                <p class="text-slate-300 text-sm">${isVenomous ? 'Extremely dangerous. Bites can be fatal without immediate treatment.' : 'Generally harmless to humans. May bite defensively but not dangerous.'}</p>
              </div>
            </div>
            <div class="flex items-start gap-3 bg-slate-800/50 p-3 sm:p-4 rounded-lg">
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-heart w-5 h-5 text-pink-400 mt-1 flex-shrink-0">
              <path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"></path></svg>
              <div>
                <h4 class="font-medium mb-1">Temperament</h4>
                <p class="text-slate-300 text-sm">${isVenomous ? 'Usually shy and reclusive. Will defend aggressively if threatened.' : 'Typically docile and calm. Many species adapt well to handling.'}</p>
              </div>
            </div>
            <div class="flex items-start gap-3 bg-slate-800/50 p-3 sm:p-4 rounded-lg">
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-ruler w-5 h-5 text-blue-400 mt-1 flex-shrink-0">
              <path d="M21.3 15.3a2.4 2.4 0 0 1 0 3.4l-2.6 2.6a2.4 2.4 0 0 1-3.4 0L2.7 8.7a2.41 2.41 0 0 1 0-3.4l2.6-2.6a2.41 2.41 0 0 1 3.4 0Z"></path>
              <path d="m14.5 12.5 2-2"></path><path d="m11.5 9.5 2-2"></path>
              <path d="m8.5 6.5 2-2"></path><path d="m17.5 15.5 2-2"></path></svg>
              <div>
                <h4 class="font-medium mb-1">Size Range</h4>
                <p class="text-slate-300 text-sm">${isVenomous ? 'Varies greatly. Most species range from 4-8 feet in length.' : 'Generally 3-6 feet, with some species reaching up to 8 feet.'}</p>
              </div>
            </div>
            <div class="flex items-start gap-3 bg-slate-800/50 p-3 sm:p-4 rounded-lg">
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-earth w-5 h-5 text-green-400 mt-1 flex-shrink-0"><path d="M21.54 15H17a2 2 0 0 0-2 2v4.54"></path><path d="M7 3.34V5a3 3 0 0 0 3 3v0a2 2 0 0 1 2 2v0c0 1.1.9 2 2 2v0a2 2 0 0 0 2-2v0c0-1.1.9-2 2-2h3.17"></path>
              <path d="M11 21.95V18a2 2 0 0 0-2-2v0a2 2 0 0 1-2-2v-1a2 2 0 0 0-2-2H2.05"></path>
              <circle cx="12" cy="12" r="10"></circle></svg>
              <div>
                <h4 class="font-medium mb-1">Habitat</h4>
                <p class="text-slate-300 text-sm">${isVenomous ? 'Found in diverse environments from deserts to rainforests.' : 'Adaptable to various habitats, including forests and grasslands.'}</p>
              </div>
            </div>
            <div class="flex items-start gap-3 bg-slate-800/50 p-3 sm:p-4 rounded-lg">
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-clock3 w-5 h-5 text-yellow-400 mt-1 flex-shrink-0">
              <circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16.5 12"></polyline></svg>
              <div>
                <h4 class="font-medium mb-1">Lifespan</h4>
                <p class="text-slate-300 text-sm">${isVenomous ? 'Average 10-15 years in the wild, up to 20 in captivity.' : 'Can live 20-30 years in captivity with proper care.'}</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    `;

   // Only try to render grid if snakeData is provided
    const gridContent = Array.isArray(snakeData) && snakeData.length ? snakeData.map(snake => `
      <a href="${snake.permalink}">
          <div class="group relative overflow-hidden rounded-xl bg-slate-800">
              <img src="${snake.imageUrl}" alt="${snake.imageAlt}" class="w-full h-48 object-cover transition-transform duration-300 group-hover:scale-105" />
              <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/80 to-transparent p-4">
                  <h3 class="text-lg font-semibold tracking-wide">${snake.name}</h3>
              </div>
          </div>
      </a>
  `).join('') : '<p>No snake data available</p>';
  
  if (snakeDetails && snakeDetailsLg && snakeGrid && snakeGridLg) {
    snakeDetails.innerHTML = detailsContent;
    snakeDetailsLg.innerHTML = detailsContent;
    snakeGrid.innerHTML = `<div class="grid grid-cols-1 sm:grid-cols-2 gap-4 p-4">${gridContent}</div>`;
    snakeGridLg.innerHTML = `<div class="grid grid-cols-1 sm:grid-cols-2 gap-4 p-4">${gridContent}</div>`;
  }
}

 // Debounce function
function debounce(func, delay) {
let timeout;
return function(...args) {
    clearTimeout(timeout);
    timeout = setTimeout(() => func.apply(this, args), delay);
};
}
// Updated data fetching function
const fetchSnakeData = async (title) => {
showLoader();  // Show loader before fetching
try {
  const baseUrl = window.ajax_object ? window.ajax_object.ajax_url : '/wp-admin/admin-ajax.php';
  const response = await fetch(`${baseUrl}?action=get_snake_images`, {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
    },
    body: JSON.stringify({ title: title })
  });
  
  if (!response.ok) {
    throw new Error('Network response was not ok');
  }
  
  const data = await response.json();
  return data.data;
} catch (error) {
  // console.error('Error fetching snake data:', error);
  return [];
}
};

const debouncedRenderData = debounce(async (title) => {
const snakeData = await fetchSnakeData(title);
  console.log(snakeData);

renderContent(snakeData);

}, 300);

nonVenomousButton.addEventListener('click', () => {
isVenomous = false;
updateToggleButtons();
debouncedRenderData('non-venomous');

});

venomousButton.addEventListener('click', () => {
isVenomous = true;
updateToggleButtons();
debouncedRenderData('venomous');
});

// Initial render
updateToggleButtons();
debouncedRenderData('non-venomous');
});
