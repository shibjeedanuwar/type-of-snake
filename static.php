<?php get_header(); ?>


    <style type="text/tailwindcss">
      
      

.drag-divider {
  position: relative;
  z-index: 20;
  height: 100%;
  background: linear-gradient(to bottom, transparent 0%, #475569 50%, transparent 100%);
}

.drag-divider::before {
  content: '';
  position: absolute;
  top: 0;
  bottom: 0;
  left: 50%;
  width: 1px;
  background-color: #475569;
  transform: translateX(-50%);
}

.drag-divider .grip {
  transition: none;
  z-index: 30;
}

.dragging .grip {
  background-color: #4a5568;
}

@media (max-width: 1024px) {
  .drag-divider,
  .drag-divider .grip {
    display: none;
  }
}

.drag-divider-mobile {
  position: relative;
  z-index: 20;
  width: 100%;
  background: linear-gradient(to right, transparent 0%, #475569 50%, transparent 100%);
}

.drag-divider-mobile::before {
  content: '';
  position: absolute;
  left: 0;
  right: 0;
  top: 50%;
  height: 1px;
  background-color: #475569;
  transform: translateY(-50%);
}

.drag-divider-mobile .grip {
  transition: none;
  z-index: 30;
}

.dragging-mobile .grip {
  background-color: #4a5568;
}

@media (min-width: 1024px) {
  .drag-divider-mobile,
  .drag-divider-mobile .grip {
    display: none;
  }
}
#chatInterface{
 
}
</style>
    
    <div id="app" class="max-w-7xl mx-auto px-4 py-8 relative">
      <!-- Main Content -->
      <h1 class="text-3xl sm:text-4xl font-bold text-center mb-12 pt-12 tracking-tight">Snake Species Explorer</h1>
      
      <!-- Toggle Filter and Chat Button -->
      <div class="flex justify-between items-center mb-8">
        <div class="flex-1 flex justify-center">
          <div class="bg-slate-800 p-1 rounded-full inline-flex gap-1">
            <button id="nonVenomousButton" class="flex items-center gap-1.5 px-3 py-1.5 rounded-full transition-all text-slate-400 hover:text-white">
              <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 21c-4.97 0-9-4.03-9-9s4.03-9 9-9 9 4.03 9 9-4.03 9-9 9z"></path>
              </svg>
              <span class="text-xs font-medium whitespace-nowrap">Non-venomous</span>
            </button>
            
            <button id="venomousButton" class="flex items-center gap-1.5 px-3 py-1.5 rounded-full transition-all text-slate-400 hover:text-white">
              <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 21c-4.97 0-9-4.03-9-9s4.03-9 9-9 9 4.03 9 9-4.03 9-9 9z"></path>
              </svg>
              <span class="text-xs font-medium">Venomous</span>
            </button>
          </div>
        </div>
        <button id="chatButton" class="flex items-center gap-2 px-4 py-2 bg-emerald-500 hover:bg-emerald-600 rounded-full transition-colors shadow-lg">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M21 16.5a2.5 2.5 0 01-2.5 2.5H6l-4 4V5.5A2.5 2.5 0 014.5 3h13A2.5 2.5 0 0120 5.5v11z"></path>
          </svg>
          <span class="font-medium hidden sm:inline">Snake Chat</span>
        </button>
      </div>

      <!-- Responsive Layout -->
      <div class="lg:hidden mt-8">
        <div class="relative flex flex-col h-[calc(100vh-16rem)]">
          <div id="snakeDetails" class="overflow-y-auto" style="height: 50%;">
            <!-- Snake details content here -->
          </div>
          
          <!-- Mobile Horizontal Drag Divider -->
          <div id="dragDividerMobile" class="relative h-1 cursor-row-resize drag-divider-mobile bg-slate-600">
            <!-- Horizontal Line -->
            <div class="absolute inset-0 h-0.5 bg-slate-600"></div>
            <!-- Drag Icon -->
            <div class="absolute w-8 h-8 left-1/2 -ml-4 -mt-4 bg-slate-700 rounded-full flex items-center justify-center grip">
              <svg class="w-4 h-4 rotate-90" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01"></path>
              </svg>
            </div>
          </div>

          <div id="snakeGrid" class="overflow-y-auto" style="height: 50%;">
            <!-- Snake grid content here -->
          </div>
        </div>
      </div>

      <div class="hidden lg:flex relative h-[calc(100vh-12rem)] mt-8">
        <div id="snakeDetailsLg" class="overflow-y-auto" style="width: 50%;">
          <!-- Snake details content here -->
        </div>
        
        <!-- Update the dragDivider HTML -->
        <div id="dragDivider" class="relative w-1 cursor-col-resize drag-divider bg-slate-600" style="width: 0.5rem;">
          <!-- Vertical Line -->
          <div class="absolute inset-0 w-0.5 bg-slate-600"></div>
          <!-- Drag Icon -->
          <div class="absolute w-8 h-8 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-slate-700 rounded-full flex items-center justify-center grip" style="top: 50%; left: calc(50% - 1.01rem);">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01"></path>
            </svg>
          </div>
        </div>

        <div id="snakeGridLg" class="overflow-y-auto" style="width: 50%;">
          <!-- Snake grid content here -->
        </div>
      </div>

      <!-- Chat Interface -->
      <div id="chatInterface" style="z-index:800 !important;" class="fixed top-0 right-0 h-full w-full sm:w-96 bg-slate-900 shadow-2xl transform transition-transform duration-300 ease-in-out z-40 translate-x-full mt-16">
        <div class="flex flex-col h-full">
          <div class="flex items-center justify-between p-4 border-b border-slate-700">
            <h2 class="text-xl font-semibold text-emerald-400">Snake Chat</h2>
            <button id="closeChat" class="p-2 hover:bg-slate-800 rounded-full transition-colors">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
              </svg>
            </button>
          </div>
          <div id="chatMessages" class="flex-1 overflow-y-auto p-4 space-y-4">
            <!-- Chat messages will be dynamically inserted here -->
          </div>
          <form id="chatForm" class="p-4 border-t border-slate-700">
            <div class="flex gap-2">
              <input type="text" id="chatInput" placeholder="Type your message..." class="flex-1 bg-slate-800 text-white rounded-full px-4 py-2 focus:outline-none focus:ring-2 focus:ring-emerald-500" />
              <button type="submit" class="p-2 bg-emerald-500 hover:bg-emerald-600 rounded-full transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
    
<?php get_footer(); ?>