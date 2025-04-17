@push('styles')
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
<style>
    select {
        -webkit-appearance: none;
        -moz-appearance: none;
        appearance: none;
        padding: 0.375rem 1.75rem 0.375rem 0.75rem;
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20' fill='currentColor' aria-hidden='true'%3e%3cpath fill-rule='evenodd' d='M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z' clip-rule='evenodd'/%3e%3c/svg%3e");
        background-position: right 0.5rem center;
        background-repeat: no-repeat;
        background-size: 1.5em 1.5em;
    }
    
    /* Fix dropdown positioning */
    .status-dropdown-menu {
        position: absolute;
        right: 0;
        z-index: 100;
        overflow: visible !important;
    }
    
    /* Ensure action column is properly centered */
    .action-cell {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 0.5rem;
    }
    
    /* Fix overflow issues in table container */
    .bg-white.rounded-lg.shadow-sm.overflow-hidden {
        overflow: visible !important;
    }
    
    /* Adjust position of dropdown relative to parent */
    .relative.inline-block {
        position: relative !important;
    }
</style>
@endpush 