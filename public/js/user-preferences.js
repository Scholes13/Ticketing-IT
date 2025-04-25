/**
 * User Preferences Manager
 * Enables users to customize their view settings
 */

class UserPreferences {
    constructor() {
        this.preferences = this.loadPreferences();
        this.setupUI();
        this.applyPreferences();
    }
    
    /**
     * Load saved preferences from localStorage
     */
    loadPreferences() {
        const savedPrefs = localStorage.getItem('wgticket_user_preferences');
        const defaultPrefs = {
            theme: 'light',
            fontSize: 'medium',
            sidebarCollapsed: false,
            tableRows: 10,
            dateFormat: 'YYYY-MM-DD',
            denseUI: false,
            notifications: true,
            refreshInterval: 0 // 0 = no auto refresh
        };
        
        return savedPrefs ? {...defaultPrefs, ...JSON.parse(savedPrefs)} : defaultPrefs;
    }
    
    /**
     * Save preferences to localStorage
     */
    savePreferences() {
        localStorage.setItem('wgticket_user_preferences', JSON.stringify(this.preferences));
    }
    
    /**
     * Setup UI elements for preference settings
     */
    setupUI() {
        // Setup theme toggle
        const themeToggle = document.getElementById('theme-toggle');
        if (themeToggle) {
            themeToggle.checked = this.preferences.theme === 'dark';
            themeToggle.addEventListener('change', (e) => {
                this.preferences.theme = e.target.checked ? 'dark' : 'light';
                this.applyTheme();
                this.savePreferences();
            });
        }
        
        // Setup font size selector
        const fontSizeSelector = document.getElementById('font-size-selector');
        if (fontSizeSelector) {
            fontSizeSelector.value = this.preferences.fontSize;
            fontSizeSelector.addEventListener('change', (e) => {
                this.preferences.fontSize = e.target.value;
                this.applyFontSize();
                this.savePreferences();
            });
        }
        
        // Setup sidebar toggle
        const sidebarToggle = document.getElementById('sidebar-toggle');
        if (sidebarToggle) {
            sidebarToggle.addEventListener('click', () => {
                this.preferences.sidebarCollapsed = !this.preferences.sidebarCollapsed;
                this.applySidebarState();
                this.savePreferences();
            });
        }
        
        // Setup table rows selector
        const tableRowsSelector = document.getElementById('table-rows-selector');
        if (tableRowsSelector) {
            tableRowsSelector.value = this.preferences.tableRows;
            tableRowsSelector.addEventListener('change', (e) => {
                this.preferences.tableRows = parseInt(e.target.value);
                this.applyTableRows();
                this.savePreferences();
            });
        }
        
        // Setup dense UI toggle
        const denseUIToggle = document.getElementById('dense-ui-toggle');
        if (denseUIToggle) {
            denseUIToggle.checked = this.preferences.denseUI;
            denseUIToggle.addEventListener('change', (e) => {
                this.preferences.denseUI = e.target.checked;
                this.applyDenseUI();
                this.savePreferences();
            });
        }
        
        // Setup notifications toggle
        const notificationsToggle = document.getElementById('notifications-toggle');
        if (notificationsToggle) {
            notificationsToggle.checked = this.preferences.notifications;
            notificationsToggle.addEventListener('change', (e) => {
                this.preferences.notifications = e.target.checked;
                this.savePreferences();
            });
        }
        
        // Setup refresh interval selector
        const refreshIntervalSelector = document.getElementById('refresh-interval-selector');
        if (refreshIntervalSelector) {
            refreshIntervalSelector.value = this.preferences.refreshInterval;
            refreshIntervalSelector.addEventListener('change', (e) => {
                this.preferences.refreshInterval = parseInt(e.target.value);
                this.applyRefreshInterval();
                this.savePreferences();
            });
        }
        
        // Setup reset button
        const resetButton = document.getElementById('reset-preferences');
        if (resetButton) {
            resetButton.addEventListener('click', () => {
                localStorage.removeItem('wgticket_user_preferences');
                this.preferences = this.loadPreferences();
                this.applyPreferences();
                
                // Update UI components to reflect reset
                if (themeToggle) themeToggle.checked = this.preferences.theme === 'dark';
                if (fontSizeSelector) fontSizeSelector.value = this.preferences.fontSize;
                if (tableRowsSelector) tableRowsSelector.value = this.preferences.tableRows;
                if (denseUIToggle) denseUIToggle.checked = this.preferences.denseUI;
                if (notificationsToggle) notificationsToggle.checked = this.preferences.notifications;
                if (refreshIntervalSelector) refreshIntervalSelector.value = this.preferences.refreshInterval;
            });
        }
    }
    
    /**
     * Apply all saved preferences
     */
    applyPreferences() {
        this.applyTheme();
        this.applyFontSize();
        this.applySidebarState();
        this.applyTableRows();
        this.applyDenseUI();
        this.applyRefreshInterval();
    }
    
    /**
     * Apply theme preference
     */
    applyTheme() {
        const htmlElement = document.documentElement;
        if (this.preferences.theme === 'dark') {
            htmlElement.classList.add('dark-theme');
            htmlElement.classList.remove('light-theme');
        } else {
            htmlElement.classList.add('light-theme');
            htmlElement.classList.remove('dark-theme');
        }
    }
    
    /**
     * Apply font size preference
     */
    applyFontSize() {
        const htmlElement = document.documentElement;
        htmlElement.classList.remove('text-sm', 'text-base', 'text-lg');
        
        switch (this.preferences.fontSize) {
            case 'small':
                htmlElement.classList.add('text-sm');
                break;
            case 'large':
                htmlElement.classList.add('text-lg');
                break;
            default:
                htmlElement.classList.add('text-base');
        }
    }
    
    /**
     * Apply sidebar state preference
     */
    applySidebarState() {
        const sidebar = document.querySelector('.sidebar');
        const content = document.querySelector('.content');
        
        if (sidebar && content) {
            if (this.preferences.sidebarCollapsed) {
                sidebar.classList.add('sidebar-collapsed');
                content.classList.add('content-expanded');
            } else {
                sidebar.classList.remove('sidebar-collapsed');
                content.classList.remove('content-expanded');
            }
        }
    }
    
    /**
     * Apply table rows preference
     */
    applyTableRows() {
        // Update pagination URL to include per_page parameter
        const paginationLinks = document.querySelectorAll('.pagination a');
        if (paginationLinks.length > 0) {
            paginationLinks.forEach(link => {
                const url = new URL(link.href);
                url.searchParams.set('per_page', this.preferences.tableRows);
                link.href = url.toString();
            });
        }
    }
    
    /**
     * Apply dense UI preference
     */
    applyDenseUI() {
        const mainContainer = document.querySelector('main');
        if (mainContainer) {
            if (this.preferences.denseUI) {
                mainContainer.classList.add('dense-ui');
            } else {
                mainContainer.classList.remove('dense-ui');
            }
        }
    }
    
    /**
     * Apply refresh interval preference
     */
    applyRefreshInterval() {
        if (window.autoRefreshTimer) {
            clearInterval(window.autoRefreshTimer);
        }
        
        if (this.preferences.refreshInterval > 0) {
            window.autoRefreshTimer = setInterval(() => {
                // Only refresh if not in the middle of creating/editing
                const currentPath = window.location.pathname;
                if (!currentPath.includes('/create') && !currentPath.includes('/edit')) {
                    window.location.reload();
                }
            }, this.preferences.refreshInterval * 1000);
        }
    }
}

// Initialize preferences when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    window.userPreferences = new UserPreferences();
    
    // Open preferences modal
    const preferencesButton = document.getElementById('open-preferences');
    const preferencesModal = document.getElementById('preferences-modal');
    
    if (preferencesButton && preferencesModal) {
        preferencesButton.addEventListener('click', () => {
            preferencesModal.classList.remove('hidden');
            preferencesModal.classList.add('flex');
        });
        
        // Close preferences modal
        const closePreferencesBtn = document.getElementById('close-preferences');
        if (closePreferencesBtn) {
            closePreferencesBtn.addEventListener('click', () => {
                preferencesModal.classList.add('hidden');
                preferencesModal.classList.remove('flex');
            });
        }
        
        // Close modal when clicking outside
        preferencesModal.addEventListener('click', function(e) {
            if (e.target === this) {
                preferencesModal.classList.add('hidden');
                preferencesModal.classList.remove('flex');
            }
        });
    }
}); 