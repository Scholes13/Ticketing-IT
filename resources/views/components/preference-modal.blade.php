<!-- User Preferences Modal -->
<div id="preferences-modal" class="fixed inset-0 bg-gray-600 bg-opacity-75 flex items-center justify-center z-50 hidden">
    <div class="preferences-modal bg-white dark:bg-gray-800 rounded-lg shadow-xl overflow-hidden max-w-2xl w-full mx-4">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                <i class="fas fa-sliders-h mr-2"></i> Customize Your Experience
            </h3>
            <button type="button" id="close-preferences" class="text-gray-400 hover:text-gray-500 focus:outline-none">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <div class="px-6 py-4 max-h-[70vh] overflow-y-auto">
            <!-- Appearance Section -->
            <div class="preferences-section">
                <h4 class="font-medium text-gray-900 dark:text-gray-100 mb-3">Appearance</h4>
                
                <!-- Theme Toggle -->
                <div class="preference-item">
                    <label for="theme-toggle" class="text-gray-700 dark:text-gray-300">Dark Mode</label>
                    <label class="toggle-switch">
                        <input type="checkbox" id="theme-toggle">
                        <span class="toggle-slider">
                            <i class="fas fa-sun"></i>
                            <i class="fas fa-moon"></i>
                        </span>
                    </label>
                </div>
                
                <!-- Font Size -->
                <div class="preference-item">
                    <label for="font-size-selector" class="text-gray-700 dark:text-gray-300">Font Size</label>
                    <select id="font-size-selector" class="form-select rounded-md shadow-sm mt-1 block w-32 bg-white dark:bg-gray-700 border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300">
                        <option value="small">Small</option>
                        <option value="medium">Medium</option>
                        <option value="large">Large</option>
                    </select>
                </div>
                
                <!-- Dense UI -->
                <div class="preference-item">
                    <label for="dense-ui-toggle" class="text-gray-700 dark:text-gray-300">Compact UI</label>
                    <label class="toggle-switch">
                        <input type="checkbox" id="dense-ui-toggle">
                        <span class="toggle-slider"></span>
                    </label>
                </div>
            </div>
            
            <!-- Table Settings Section -->
            <div class="preferences-section">
                <h4 class="font-medium text-gray-900 dark:text-gray-100 mb-3">Table Settings</h4>
                
                <!-- Rows Per Page -->
                <div class="preference-item">
                    <label for="table-rows-selector" class="text-gray-700 dark:text-gray-300">Rows Per Page</label>
                    <select id="table-rows-selector" class="form-select rounded-md shadow-sm mt-1 block w-32 bg-white dark:bg-gray-700 border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300">
                        <option value="5">5 rows</option>
                        <option value="10">10 rows</option>
                        <option value="15">15 rows</option>
                        <option value="25">25 rows</option>
                        <option value="50">50 rows</option>
                        <option value="100">100 rows</option>
                    </select>
                </div>
                
                <!-- Auto Refresh -->
                <div class="preference-item">
                    <label for="refresh-interval-selector" class="text-gray-700 dark:text-gray-300">Auto Refresh</label>
                    <select id="refresh-interval-selector" class="form-select rounded-md shadow-sm mt-1 block w-32 bg-white dark:bg-gray-700 border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300">
                        <option value="0">Off</option>
                        <option value="30">30 seconds</option>
                        <option value="60">1 minute</option>
                        <option value="180">3 minutes</option>
                        <option value="300">5 minutes</option>
                    </select>
                </div>
            </div>
            
            <!-- Notifications Section -->
            <div class="preferences-section">
                <h4 class="font-medium text-gray-900 dark:text-gray-100 mb-3">Notifications</h4>
                
                <!-- Browser Notifications -->
                <div class="preference-item">
                    <label for="notifications-toggle" class="text-gray-700 dark:text-gray-300">Browser Notifications</label>
                    <label class="toggle-switch">
                        <input type="checkbox" id="notifications-toggle">
                        <span class="toggle-slider"></span>
                    </label>
                </div>
                
                <!-- Sound Notifications - future enhancement -->
                <div class="preference-item opacity-50">
                    <label class="text-gray-700 dark:text-gray-300">Sound Notifications</label>
                    <label class="toggle-switch">
                        <input type="checkbox" disabled>
                        <span class="toggle-slider"></span>
                    </label>
                    <span class="text-xs text-gray-500 ml-2">(Coming soon)</span>
                </div>
            </div>
        </div>
        
        <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700 flex justify-between">
            <button type="button" id="reset-preferences" class="px-4 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-md hover:bg-gray-100 dark:hover:bg-gray-600 focus:outline-none transition-colors duration-150">
                Reset to Default
            </button>
            <button type="button" id="close-preferences-btn" class="px-4 py-2 bg-indigo-600 dark:bg-indigo-500 text-white rounded-md hover:bg-indigo-700 dark:hover:bg-indigo-600 focus:outline-none transition-colors duration-150">
                Close
            </button>
        </div>
    </div>
</div> 