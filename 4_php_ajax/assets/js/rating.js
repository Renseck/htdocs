$(document).ready(function() 
{ 
    initRatingSystem();
});

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
        const stars = container.find(".star");

        loadProductRating(productId, container);

        // Add event listeners to stars
        stars.each(function(index) 
        {
            const star = $(this);

            // Hover effects
            star.on("mouseenter", function () 
            {
                highLightStars(stars, index);
            });

            // Click to confirm rating
            star.on("click", function() 
            { 
                submitRating(productId, index+1, container);
            });
        });

        // Reset when mouse leaves the container
        container.on("mouseleave", function() 
        {
            resetStars(container);
        });
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
        }
    });
}

/* ============================================================================================== */
function submitRating(productId, rating, container)
{
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
        success: function(data) 
        {
            if (data.success) {
                loadProductRating(productId, container);
                showMessage("Your rating has been saved!", "success");
            }
            else
            {
                showMessage(data.message || "An error occurred while saving your rating", "error");
            }
        },
        error: function(xhr, status, error) 
        {
            console.error("Error submitting rating:", error);
            showMessage("Could not connect to the server.", "error");
        }
    });
}

/* ============================================================================================== */
function updateRatingDisplay(container, ratingData)
{
    const stars = container.find(".star");
    const avgRating = parseFloat(ratingData.average);
    const userRating = ratingData.userRating;

    const ratingText = container.find(".rating-text");
    if (ratingText.length)
    {
        ratingText.text(`${avgRating.toFixed(1)} (${ratingData.count} reviews)`);
    }

    if (userRating !== null)
    {
        container.data("userRating", userRating);
        highlightStars(stars, userRating - 1);
    }
    else
    {
        visualizeAverageRating(stars, avgRating);
    }
}

/* ============================================================================================== */
function highlightStars(stars, activeIndex)
{
    stars.each(function(index) {
        if (index <= activeIndex) {
            $(this).addClass("active");
        }
        else
        {
            $(this).removeClass("active");
        }
    });
}

/* ============================================================================================== */
function resetStars(container)
{
    const stars = container.find(".star");
    const userRating = container.data("userRating");

    if (userRating)
    {
        highlightStars(stars, parseInt(userRating) - 1);
    }
    else
    {
        const avgElement = container.find(".rating-text");
        if (avgElement.length)
        {
            const avgRating = parseFloat(avgElement.text());
            visualizeAverageRating(stars, avgRating);
        }
        else
        {
            stars.removeClass("active half-active");
        }
    }
}

/* ============================================================================================== */
function visualizeAverageRating(stars, avgRating) 
{
    stars.each(function(index) {
        const star = $(this);
        if (index < Math.floor(avgRating)) {
            star.addClass("active").removeClass("half-active");
        }
        else if (index === Math.floor(avgRating) && avgRating % 1 >= 0.5)
        {
            star.removeClass("active").addClass("half-active");
        }
        else
        {
            star.removeClass("active half-active");
        }
    });
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