document.addEventListener('DOMContentLoaded', function() {
    // Get all links with the ajax-link class
    const ajaxLinks = document.querySelectorAll('.ajax-link');
    
    // Add click event listeners to all navigation links
    ajaxLinks.forEach(link => {
        link.addEventListener('click', function(event) {
            event.preventDefault(); // Prevent the default link behavior
            
            // Extract the page parameter from the href
            const url = new URL(this.href);
            const page = url.searchParams.get('page');
            
            // Update the browser URL without reloading
            window.history.pushState({page: page}, '', this.href);
            
            // Load the content via AJAX
            loadContent(page);
        });
    });
    
    // Handle browser back/forward buttons
    window.addEventListener('popstate', function(event) {
        if (event.state && event.state.page) {
            loadContent(event.state.page);
        } else {
            // Default to home if no state
            loadContent('home');
        }
    });
    
    // Function to load content via AJAX
    function loadContent(page) {
        const xhr = new XMLHttpRequest();
        xhr.open('GET', 'content.php?page=' + page, true);
        
        xhr.onload = function() {
            if (this.status === 200) {
                document.getElementById('content-container').innerHTML = this.responseText;
            }
        };
        
        xhr.send();
    }
});