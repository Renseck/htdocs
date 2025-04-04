/* ============================================================================================== */
/*                             Single Page Application (SPA) framework                            */
/* ============================================================================================== */
(function (fn) 
{
    if (document.readyState === "complete" || document.readyState === "interactive") 
    {
        setTimeout(fn, 1);
    } 
    else
    {
        document.addEventListener("DOMContentLoaded", fn);
    }
}(SPA.init()));

/* ============================================================================================== */
const SPA = {
    init: function() 
    {
        this.setupAjaxForms();
        this.setupHistoryHandling();
        this.loadInitialContent();
    },

    setupAjaxForms: function() 
    {
        $(document).on('submit', 'form:not([data-no-ajax])', function(e) 
        {
            e.preventDefault();
            const $form = $(this);
            const formData = new FormData($form[0]);

            // Add ajax flag
            formData.append('is_ajax', true);

            $.ajax({
                url: $form.attr('action') || window.location.href,
                type: $form.attr('method') || "POST",
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) 
                {
                    SPA.handleResponse(response);
                },
                error: function(xhr) 
                {
                    console.error("Form submission error:", xhr.responseText);
                    alert("An error occurred. Please try again.");
                }
            });
        });
    },

    /* ========================================================================================== */
    // This is to make the back and forward page buttons work
    setupHistoryHandling: function() 
    {
        $(window).on("popstate", function(e) 
        {
            if (e.originalEvent.state) 
                {
                SPA.loadContent(window.location.href, false);
            }
        });
        $(document).on('click', 'a:not([data-no-ajax])', function(e) 
        {
            const href = $(this).attr("href");

            if (!href || href.startsWith("#") ||
                href.startsWith("javascript:") ||
                href.startsWith("http") && !href.startsWith(window.location.origin)) {
                    return true;
                }

            e.preventDefault();
            SPA.loadContent(href, true);

        });
    },

    /* ========================================================================================== */
    loadContent: function(url, updateHistory) 
    {
        this.showLoader();
        $.ajax({
            url: url,
            type: "GET",
            data: { is_ajax: true },
            success: function(response) 
            {
                if(updateHistory) {
                    history.pushState({ url: url }, "", url);
                }
                SPA.handleResponse(response);
            },
            error: function(xhr) 
            {
                console.error("Content loading error:", xhr.responseText);
                window.location.href = url;
            },
            complete: function() 
            {
                this.hideLoader();
            }
        });
    },

    /* ========================================================================================== */
    loadInitialContent: function() 
    {
        // Push initial state
        history.replaceState({ url: window.location.href }, "", window.location.href);
    },

    /* ========================================================================================== */
    handleResponse: function(response) 
    {
        // If response is not JSON, log error and return
            try 
        {
            if (typeof response === "string")
            {
                response = JSON.parse(response);
            }
        }
        catch (e) 
        {
            console.error("Invalid JSON response:", response);
            return;
        }

        // Check response data: require target and content to inject
        if (response.targets) 
        {
            // Update each target with its content
            for (const target in response.targets) 
            {
                const content = response.targets[target];
                $(target).html(content);
            }
        }

        // Execute any JS scripts from the response
        if (response.scripts)
        {
            for (const script of response.scripts)
            {
                eval(script);
            }
        }

        // Show any notifications that arise
        if (response.notifications) 
        {
            for (const notification of response.notifications)
            {
                this.showNotification(notification.type, notification.message);
            }
        }

        // Handle redirects / page loading
        if (response.redirect) 
        {
            if (response.redirect.external) 
            {
                window.location.href = response.redirect.url;
            }
            else
            {
                this.loadContent(response.redirect.url, true);
            }
        }
    },

    /* ========================================================================================== */
    showNotification: function(type, message)
    {
        const $notification = $('<div class="notification ' + type + '">' + message + '</div>');
        $('#notifications').append($notification);

        // Remove after 5 seconds
        setTimeout(function() 
        {
            $notification.fadeOut(function ()
            {
                $(this).remove();
            });
        }, 5000);
    },

    /* ========================================================================================== */
    showLoader: function () 
    {
        $('#app-loader').show();
    },

    /* ========================================================================================== */
    hideLoader: function()
    {
        $('#app-loader').hide();
    }
    /* ========================================================================================== */
};