document.addEventListener('DOMContentLoaded', function() {
    // Track active AJAX requests
    let activeRequest = null;
    
    // Initial setup of event handlers
    setupAjaxNavigation();
    setupAjaxForms();
    
    // Handle browser back/forward buttons
    window.addEventListener('popstate', function(event) 
    {
        if (event.state && event.state.page) 
        {
            loadContent(event.state.page);
        } 
        else 
        {
            loadContent('home');
        }
    });
    
    // Event delegation for ajax links to avoid duplicate handlers
    function setupAjaxNavigation() 
    {
        // Use event delegation instead of attaching to each link
        document.body.addEventListener('click', function(event) 
        {
            // Check if clicked element or its parent is an ajax-link
            let target = event.target;
            while (target && target !== document) 
            {
                if (target.classList.contains('ajax-link')) 
                {
                    event.preventDefault();
                    
                    const url = new URL(target.href);
                    const page = url.searchParams.get('page');
                    
                    // Don't reload current page
                    if (window.location.href !== target.href) 
                    {
                        window.history.pushState({page: page}, '', target.href);
                        loadContent(page);
                    }
                    return;
                }
                target = target.parentNode;
            }
        });
    }

    function setupAjaxForms() 
    {
        document.body.addEventListener('submit', function(event) {
            let target = event.target;

            if (target.classList.contains('ajax-form'))
            {
                event.preventDefault();
                submitForm(target);
            }
        });
    }

    function submitForm(form)
    {
        form.classList.add('loading');
        const submitButton = form.querySelector('button[type="submit"]');
        if (submitButton)
        {
            submitButton.disabled = true;
            submitButton.innerText = 'Sending...';
        }

        // Clear previous messages
        const container = form.parentNode;
        const existingMessages = container.querySelectorAll('.success, .error');
        existingMessages.forEach(el => el.remove());
        
        // Create form data
        const formData = new FormData(form);
        
        // Send AJAX request
        const xhr = new XMLHttpRequest();
        xhr.open('POST', form.action, true);
        xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
        
        xhr.onload = function() {
            // Reset form state
            form.classList.remove('loading');
            if (submitButton) {
                submitButton.disabled = false;
                submitButton.innerText = 'Send Message';
            }
            
            if (this.status === 200) {
                try {
                    const response = JSON.parse(this.responseText);
                    console.log(response);
                    
                    // Create message element
                    const messageDiv = document.createElement('div');
                    messageDiv.className = response.success ? 'success' : 'error';
                    messageDiv.innerHTML = response.message;
                    
                    // Add message before the form
                    form.parentNode.insertBefore(messageDiv, form);
                    
                    // Reset form if successful
                    if (response.success) {
                        form.reset();
                    }
                } catch (e) {
                    // If not valid JSON, reload the page content
                    const url = new URL(window.location.href);
                    loadContent(url.searchParams.get('page') || 'home');
                }
            }
        };
        
        xhr.onerror = function() {
            form.classList.remove('loading');
            if (submitButton) {
                submitButton.disabled = false;
                submitButton.innerText = 'Send Message';
            }
            
            // Show error message
            const messageDiv = document.createElement('div');
            messageDiv.className = 'error';
            messageDiv.innerText = 'Network error. Please try again.';
            form.parentNode.insertBefore(messageDiv, form);
        };
        
        xhr.send(formData);
    }
    
    function loadContent(page) 
    {
        // Cancel any existing request
        if (activeRequest) {
            activeRequest.abort();
        }
        
        // Show loading indicator
        const container = document.getElementById('main-content');
        container.classList.add('loading');
        
        // Create new request
        activeRequest = new XMLHttpRequest();
        activeRequest.open('GET', 'index.php?page=' + page, true);
        activeRequest.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
        
        activeRequest.onload = function() 
        {
            if (this.status === 200) 
            {
                container.innerHTML = this.responseText;
                container.classList.remove('loading');
                activeRequest = null;
            }
        };
        
        activeRequest.onerror = function() 
        {
            container.classList.remove('loading');
            activeRequest = null;
        };
        
        activeRequest.send();
    }
});