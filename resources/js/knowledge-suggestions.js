/**
 * Knowledge Base Suggestion System for Ticket Creation
 * 
 * This script adds real-time knowledge base article suggestions 
 * based on ticket title and description during ticket creation.
 */

document.addEventListener('DOMContentLoaded', function() {
    // Elements
    const ticketForm = document.getElementById('ticket-form');
    const titleInput = document.getElementById('title');
    const descriptionInput = document.getElementById('description');
    const suggestionsContainer = document.getElementById('knowledge-suggestions');
    
    // Only run if we're on the ticket creation page
    if (!ticketForm || !suggestionsContainer) return;
    
    // Debounce function to limit API calls
    function debounce(func, wait) {
        let timeout;
        return function(...args) {
            clearTimeout(timeout);
            timeout = setTimeout(() => func.apply(this, args), wait);
        };
    }
    
    // Function to fetch article suggestions
    const fetchSuggestions = debounce(function() {
        const title = titleInput.value.trim();
        const description = descriptionInput.value.trim();
        
        // Don't search if both fields are empty or too short
        if ((title.length < 3 && description.length < 10)) {
            suggestionsContainer.innerHTML = '';
            suggestionsContainer.classList.add('d-none');
            return;
        }
        
        // Prepare form data
        const formData = new FormData();
        formData.append('title', title);
        formData.append('description', description);
        formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);
        
        // Fetch suggestions from API
        fetch('/knowledge/suggest-articles', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success && data.articles.length > 0) {
                displaySuggestions(data.articles);
            } else {
                suggestionsContainer.innerHTML = '';
                suggestionsContainer.classList.add('d-none');
            }
        })
        .catch(error => {
            console.error('Error fetching article suggestions:', error);
        });
    }, 500); // 500ms debounce
    
    // Function to display article suggestions
    function displaySuggestions(articles) {
        suggestionsContainer.innerHTML = '';
        
        // Create header
        const header = document.createElement('div');
        header.className = 'card-header bg-primary text-white d-flex align-items-center';
        header.innerHTML = `
            <i class="fas fa-lightbulb me-2"></i>
            <h5 class="mb-0">Artikel yang mungkin dapat membantu</h5>
        `;
        suggestionsContainer.appendChild(header);
        
        // Create card body
        const body = document.createElement('div');
        body.className = 'card-body';
        
        // Create article list
        const list = document.createElement('div');
        list.className = 'list-group list-group-flush';
        
        // Add each article
        articles.forEach(article => {
            const item = document.createElement('a');
            item.href = article.url;
            item.className = 'list-group-item list-group-item-action';
            item.target = '_blank';
            
            item.innerHTML = `
                <div class="d-flex w-100 justify-content-between">
                    <h6 class="mb-1">${article.title}</h6>
                </div>
                ${article.description ? `<p class="mb-1 small text-muted">${article.description}</p>` : ''}
            `;
            
            list.appendChild(item);
        });
        
        body.appendChild(list);
        suggestionsContainer.appendChild(body);
        
        // Add a footer with a link to knowledge base
        const footer = document.createElement('div');
        footer.className = 'card-footer bg-light text-center';
        footer.innerHTML = `
            <a href="/knowledge" class="text-primary" target="_blank">
                Lihat semua artikel di Knowledge Base
            </a>
        `;
        suggestionsContainer.appendChild(footer);
        
        // Show the suggestions
        suggestionsContainer.classList.remove('d-none');
    }
    
    // Attach event listeners
    if (titleInput) {
        titleInput.addEventListener('input', fetchSuggestions);
    }
    
    if (descriptionInput) {
        descriptionInput.addEventListener('input', fetchSuggestions);
    }
}); 