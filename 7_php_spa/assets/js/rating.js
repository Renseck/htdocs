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
}(initRatingSystem));  

/* ============================================================================================== */
function initRatingSystem()
{
    // Find all rating containers on the page
    const ratingContainers = $(".rating-container");

    // Load the known product rating for every container
    ratingContainers.each(function() 
    {
        const container = $(this);
        const productId = container.data("productId");
        const starRating = container.find(".star-rating");
        const starsOverlay = container.find(".stars-overlay");
        
        // Calculate the width of each star for interaction
        const totalStars = 5;
        const starRatingWidth = starRating.width();
        const starWidth = starRatingWidth / totalStars;
        
        // Handle mouse movement over the star rating area
        starRating.on("mousemove", function(e) 
        {
            // Get the relative mouse position
            const relativeX = e.pageX - $(this).offset().left;
            
            // Determine which star is being hovered
            const starIndex = Math.floor(relativeX / starWidth);
            if (starIndex >= 0 && starIndex < totalStars) 
            {
                // Set width to show the correct number of stars
                const newWidth = ((starIndex + 1) * 100 / totalStars) + '%';
                starsOverlay.css('width', newWidth);
            }
        });
        
        // Handle clicking on stars
        starRating.on("click", function(e) 
        {
            // Get the relative mouse position
            const relativeX = e.pageX - $(this).offset().left;
            
            // Determine which star was clicked (0-4)
            const starIndex = Math.floor(relativeX / starWidth);
            
            if (starIndex >= 0 && starIndex < totalStars) {
                // Submit rating (adding 1 since ratings are 1-5)
                submitRating(productId, starIndex + 1, container);
            }
        });
        
        // Reset when mouse leaves the container
        container.on("mouseleave", function() {
            resetStars(container);
        });
        
        // Load initial rating
        loadProductRating(productId, container);
    });
}

/* ============================================================================================== */
function loadProductRating(productId, container)
{
    $.ajax({
        url: `index.php?action=getAvgProductRating&id=${productId}`,
        type: "GET",
        dataType: "json",
        headers: {
            "X-Requested-With": "XMLHttpRequest"
        },
        success: function(data) 
        {
            if (data.success)
            {
                updateRatingDisplay(container, data.data);
            }
            else
            {
                console.error("Error loading rating:", data.message);
            }
        },
        error: function(xhr, status, error) {
            console.error("Error fetching rating data", error);
            console.error("Response text:", xhr.responseText);
        }
    });
}

/* ============================================================================================== */
function submitRating(productId, rating, container)
{
    console.log("Submitting rating:", productId, rating);

    // Create form data
    const formData = new FormData();
    formData.append("product_id", productId);
    formData.append("rating", rating);

    $.ajax({
        url: `index.php?action=rateProduct`,
        type: "POST",
        data: formData,
        processData: false,
        contentType: false,
        headers: {
            "X-Requested-With": "XMLHttpRequest"
        },
        success: function(response) 
        {
            console.log("Rating response:", response);
            let data;
            
            // Try to parse the response if it's a string
            if (typeof response === 'string') {
                try {
                    data = JSON.parse(response);
                } catch (e) {
                    console.error("Failed to parse response:", response);
                    showMessage("Invalid response from server", "error");
                    return;
                }
            } else {
                data = response;
            }
            
            if (data.success) {
                loadProductRating(productId, container);
                showMessage("Your rating has been saved!", "success");
            }
            else
            {
                console.error("Rating failed:", data);
                showMessage(data.message || "An error occurred while saving your rating", "error");
            }
        },
        error: function(xhr, status, error) 
        {
            console.error("Error submitting rating:", error);
            console.error("Response text:", xhr.responseText);
            showMessage("Could not connect to the server.", "error");
        }
    });
}

/* ============================================================================================== */
function updateRatingDisplay(container, ratingData) 
{
    const avgRating = parseFloat(ratingData.average);
    const userRating = ratingData.userRating;
    const starsOverlay = container.find('.stars-overlay');
    
    const ratingText = container.find(".rating-text");
    if (ratingText.length) 
    {
        // Display the rating with one decimal place precision
        ratingText.text(`${avgRating.toFixed(1)} (${ratingData.count} reviews)`);
    }
    
    if (userRating !== null) 
    {
        container.data("userRating", userRating);
        const totalStars = 5;
        const starWidth = starsOverlay.parent().width() / totalStars;
        starsOverlay.css('width', (userRating * starWidth) + 'px');
    } 
    else 
    {
        visualizeAverageRating(starsOverlay, avgRating);
    }
}

/* ============================================================================================== */
function highlightStars(starsOverlay, activeIndex) 
{
    const container = starsOverlay.closest('.rating-container');
    const starWidth = starsOverlay.width() / 5; // Assuming 5 stars
    
    // Set width to show the correct number of stars
    starsOverlay.css('width', ((activeIndex + 1) * starWidth) + 'px');
}

/* ============================================================================================== */
function resetStars(container) 
{
    const userRating = container.data("userRating");
    const starsOverlay = container.find('.stars-overlay');
    
    if (userRating) 
    {
        const starWidth = starsOverlay.width() / 5; // Assuming 5 stars
        starsOverlay.css('width', (parseInt(userRating) * starWidth) + 'px');
    } 
    else 
    {
        const avgElement = container.find(".rating-text");
        if (avgElement.length) 
        {
            const text = avgElement.text();
            const match = text.match(/(\d+\.\d+)/);
            if (match && match[0]) 
            {
                const avgRating = parseFloat(match[0]);
                visualizeAverageRating(starsOverlay, avgRating);
            } 
            else 
            {
                starsOverlay.css('width', '0px');
            }
        } 
        else 
        {
            starsOverlay.css('width', '0px');
        }
    }
}

/* ============================================================================================== */
function visualizeAverageRating(starsOverlay, avgRating) 
{
    const container = starsOverlay.closest('.rating-container');
    const starRating = container.find('.star-rating');
    
    // Calculate width percentage based on rating (out of 5 stars)
    const totalStars = 5; // Your HTML shows 5 stars
    const fillPercent = (avgRating / totalStars) * 100;
    
    // Set the width as a percentage of the total width
    container.find('.stars-overlay').css('width', fillPercent + '%');
    container.find('.stars-overlay').show();
}

/* ============================================================================================== */
function showMessage(message, type = "info")
{
    const messageElement = $("<div>", {
        class: `message message-${type}`,
        text: message
    });

    $("body").append(messageElement);

    setTimeout(function() {
        messageElement.addClass("fade-out");
        setTimeout(function() {
            messageElement.remove();
        }, 500);
    }, 3000);
}

/* ============================================================================================== */