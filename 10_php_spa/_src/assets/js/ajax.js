// Ajax utility functions
const AjaxUtils = {
    // Update a target element with content
    updateContent: function(target, content) {
        console.log("Updating target:", target);
        $(target).html(content);
    },
    
    // Process response data that contains target/content pairs
    processUpdates: function(data) {
        if (data) {
            $.each(data, function(i) {
                const item = data[i];
                AjaxUtils.updateContent(item.target, item.content);
            });
        }
    },
    
    // Make an Ajax request with a callback for success
    makeRequest: function(url, type = "GET", data = null, callback = null) {
        return $.ajax({
            type: type,
            cache: false,
            url: url,
            data: data,
            dataType: 'json',
            success: function(responseData) {
                if (callback) {
                    callback(responseData);
                } else {
                    AjaxUtils.processUpdates(responseData);
                }
            }
        });
    }
};

// Initialize the event handlers
$(document).ready(function(){
    $(document).on("click", "button#populate", function(e) {
        AjaxUtils.makeRequest('index.php?action=ajax&function=populate');
    });

    $(document).on("click", "button#reset", function(e) {
        AjaxUtils.makeRequest('index.php?action=ajax&function=reset');
    });
});