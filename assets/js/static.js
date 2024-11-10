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
    const dragIcon = dragDivider.querySelector('div');
    const chatForm = document.getElementById('chatForm');
    const chatInput = document.getElementById('chatInput');
    const chatMessages = document.getElementById('chatMessages');
    let isDragging = false;
  
     // Mobile drag functionality
     const dragDividerMobile = document.getElementById('dragDividerMobile');
  //    const snakeDetails = document.getElementById('snakeDetails');
  //    const snakeGrid = document.getElementById('snakeGrid');
     let isMobileDragging = false;
   
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
   
       snakeDetails.style.height = `${clampedPosition}%`;
       snakeGrid.style.height = `${100 - clampedPosition}%`;
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
  
    // Update drag icon position
    function updateDragIconPosition() {
      const rect = dragDivider.getBoundingClientRect();
      dragIcon.style.left = `${rect.left}px`;
      dragIcon.style.top = '50%';
      dragIcon.style.transform = 'translateY(-50%)';
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
  
      snakeDetailsLg.style.width = `${clampedPosition}%`;
      snakeGridLg.style.width = `${100 - clampedPosition}%`;
      updateDragIconPosition();
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
  
    nonVenomousButton.addEventListener('click', () => {
      isVenomous = false;
      updateToggleButtons();
      renderContent();
    });
  
    venomousButton.addEventListener('click', () => {
      isVenomous = true;
      updateToggleButtons();
      renderContent();
    });
  
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
  
    function renderContent() {
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
                <svg class="w-5 h-5 text-red-400 mt-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 21c-4.97 0-9-4.03-9-9s4.03-9 9-9 9 4.03 9 9-4.03 9-9 9z"></path>
                </svg>
                <div>
                  <h4 class="font-medium mb-1">Danger Level</h4>
                  <p class="text-slate-300 text-sm">${isVenomous ? 'Extremely dangerous. Bites can be fatal without immediate treatment.' : 'Generally harmless to humans. May bite defensively but not dangerous.'}</p>
                </div>
              </div>
              <div class="flex items-start gap-3 bg-slate-800/50 p-3 sm:p-4 rounded-lg">
                <svg class="w-5 h-5 text-pink-400 mt-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 21c-4.97 0-9-4.03-9-9s4.03-9 9-9 9 4.03 9 9-4.03 9-9 9z"></path>
                </svg>
                <div>
                  <h4 class="font-medium mb-1">Temperament</h4>
                  <p class="text-slate-300 text-sm">${isVenomous ? 'Usually shy and reclusive. Will defend aggressively if threatened.' : 'Typically docile and calm. Many species adapt well to handling.'}</p>
                </div>
              </div>
              <div class="flex items-start gap-3 bg-slate-800/50 p-3 sm:p-4 rounded-lg">
                <svg class="w-5 h-5 text-blue-400 mt-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 21c-4.97 0-9-4.03-9-9s4.03-9 9-9 9 4.03 9 9-4.03 9-9 9z"></path>
                </svg>
                <div>
                  <h4 class="font-medium mb-1">Size Range</h4>
                  <p class="text-slate-300 text-sm">${isVenomous ? 'Varies greatly. Most species range from 4-8 feet in length.' : 'Generally 3-6 feet, with some species reaching up to 8 feet.'}</p>
                </div>
              </div>
              <div class="flex items-start gap-3 bg-slate-800/50 p-3 sm:p-4 rounded-lg">
                <svg class="w-5 h-5 text-green-400 mt-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 21c-4.97 0-9-4.03-9-9s4.03-9 9-9 9 4.03 9 9-4.03 9-9 9z"></path>
                </svg>
                <div>
                  <h4 class="font-medium mb-1">Habitat</h4>
                  <p class="text-slate-300 text-sm">${isVenomous ? 'Found in diverse environments from deserts to rainforests.' : 'Adaptable to various habitats, including forests and grasslands.'}</p>
                </div>
              </div>
              <div class="flex items-start gap-3 bg-slate-800/50 p-3 sm:p-4 rounded-lg">
                <svg class="w-5 h-5 text-yellow-400 mt-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 21c-4.97 0-9-4.03-9-9s4.03-9 9-9 9 4.03 9 9-4.03 9-9 9z"></path>
                </svg>
                <div>
                  <h4 class="font-medium mb-1">Lifespan</h4>
                  <p class="text-slate-300 text-sm">${isVenomous ? 'Average 10-15 years in the wild, up to 20 in captivity.' : 'Can live 20-30 years in captivity with proper care.'}</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      `;
  
      const snakes = isVenomous ? [
        { name: 'Inland Taipan', image: 'https://images.unsplash.com/photo-1581528947699-d225e66a72f9?auto=format&fit=crop&q=80' },
        { name: 'Black Mamba', image: 'https://images.unsplash.com/photo-1590855915491-f37d55e8aec9?auto=format&fit=crop&q=80' },
        { name: 'King Cobra', image: 'https://images.unsplash.com/photo-1583525957858-73c7174c6f74?auto=format&fit=crop&q=80' },
        { name: 'Eastern Brown Snake', image: 'https://images.unsplash.com/photo-1583525011026-a8421bbe2176?auto=format&fit=crop&q=80' },
        { name: 'Tiger Snake', image: 'https://images.unsplash.com/photo-1583524505974-6facd53f4597?auto=format&fit=crop&q=80' },
        { name: 'Death Adder', image: 'https://images.unsplash.com/photo-1583524504661-9b7db8502f13?auto=format&fit=crop&q=80' }
      ] : [
        { name: 'Ball Python', image: 'https://images.unsplash.com/photo-1531386151447-fd76ad50012f?auto=format&fit=crop&q=80' },
        { name: 'Corn Snake', image: 'https://images.unsplash.com/photo-1585095595274-aeffcc5647e1?auto=format&fit=crop&q=80' },
        { name: 'Rosy Boa', image: 'https://images.unsplash.com/photo-1583525009732-c3891972862e?auto=format&fit=crop&q=80' },
        { name: 'Rainbow Boa', image: 'https://images.unsplash.com/photo-1583524505974-6facd53f4597?auto=format&fit=crop&q=80' },
        { name: 'Green Tree Python', image: 'https://images.unsplash.com/photo-1583525011026-a8421bbe2176?auto=format&fit=crop&q=80' },
        { name: 'Kingsnake', image: 'https://images.unsplash.com/photo-1583524505974-6facd53f4597?auto=format&fit=crop&q=80' }
      ];
  
      const gridContent = snakes.map(snake => `
        <div class="group relative overflow-hidden rounded-xl bg-slate-800">
          <img src="${snake.image}" alt="${snake.name}" class="w-full h-48 object-cover transition-transform duration-300 group-hover:scale-105" />
          <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/80 to-transparent p-4">
            <h3 class="text-lg font-semibold tracking-wide">${snake.name}</h3>
          </div>
        </div>
      `).join('');
  
      snakeDetails.innerHTML = detailsContent;
      snakeDetailsLg.innerHTML = detailsContent;
      snakeGrid.innerHTML = `<div class="grid grid-cols-1 sm:grid-cols-2 gap-4 p-4">${gridContent}</div>`;
      snakeGridLg.innerHTML = `<div class="grid grid-cols-1 sm:grid-cols-2 gap-4 p-4">${gridContent}</div>`;
    }
  
    updateToggleButtons();
    renderContent();
  });
  