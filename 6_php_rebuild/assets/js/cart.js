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
}(initCartSystem));  

/* ============================================================================================== */
function initCartSystem() 
{
    // Add event listeners for the various buttons this will be dealing with
    // Add to cart button
    $('.add-to-cart-btn').click(function(e) 
    {
        e.preventDefault();

        const form = $(this).closest('form');
        const productId = form.find('input[name="product_id"]').val();
        const quantity = form.find('input[name="quantity"]').val();

        addToCart(productId, quantity);
    });

    // Cart update button
    $('.shopping_cart .update-btn').click(function(e)
     {
        e.preventDefault();

        const row = $(this).closest("tr");
        const productId = row.data('product-id')
        const quantity = row.find('input[name="quantity"]').val();

        updateCartItem(productId, quantity);
    });

    // Remove from cart button
    $('.shopping_cart .remove-btn').click(function(e) 
    {
        e.preventDefault();

        const row = $(this).closest("tr");
        const productId = row.data('product-id')

        removeFromCart(productId, 0);
    });

    // Clear cart button
    $('.clear-cart-btn').click(function(e) 
    {
        e.preventDefault();

        if (confirm("Are you sure you want to clear your cart?"))
        {
            clearCart();
        }
    });
}

/* ============================================================================================== */
function addToCart(productId, quantity) 
{
    const formData = new FormData();
    formData.append("product_id", productId);
    formData.append("quantity", quantity);

    $.ajax({
        url: 'index.php?action=addToCart',
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        success: function(response)
        {
            let data;

            if (typeof response === "string")
            {
                try 
                {
                    data = JSON.parse(response);
                }
                catch (e)
                {
                    console.error("Failed to parse response", response);
                    showMessage("Invalid response from server", "error");
                    return;
                }
            }
            else
            {
                data = response;
            }

            if (data.success)
            {
                updateCartDisplay(data.data.cart);
                showMessage(data.message || "Item added to cart", "success");
            }
            else
            {
                console.error("Failed to add to cart: ", data);
                showMessage(data.message || "Failed to add item to cart", "error");
            }
        },
        error: function(xhr, status, error)
        {
            console.error("Error adding to cart:", error);
            console.error("Response text", xhr.responseText);
            showMessage("Could not connect to the server", "error");
        }
    });
}

/* ============================================================================================== */
function updateCartItem(productId, quantity)
{
    const formData = new FormData();
    formData.append("product_id", productId);
    formData.append("quantity", quantity);

    $.ajax({
        url: 'index.php?action=updateCartItem',
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        success: function(response)
        {
            let data;

            if (typeof response === "string")
            {
                try 
                {
                    data = JSON.parse(response);
                }
                catch (e)
                {
                    console.error("Failed to parse response", response);
                    showMessage("Invalid response from server", "error");
                    return;
                }
            }
            else
            {
                data = response;
            }

            if (data.success)
            {
                updateCartDisplay(data.data.cart);
                showMessage(data.message || "Cart updated", "success");

                // Highlight the updated item
                highlightItem(productId);
            }
            else
            {
                console.error("Failed to update cart: ", data);
                showMessage(data.message || "Failed to update cart", "error");
            }
        },
        error: function(xhr, status, error)
        {
            console.error("Error updating cart:", error);
            console.error("Response text", xhr.responseText);
            showMessage("Could not connect to the server", "error");
        }
    });
}

/* ============================================================================================== */
function removeFromCart(productId)
{
    const formData = new FormData();
    formData.append("product_id", productId);
    formData.append("quantity", 0);

    $.ajax({
        url: 'index.php?action=removeFromCart',
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        success: function(response)
        {
            let data;

            if (typeof response === "string")
            {
                try 
                {
                    data = JSON.parse(response);
                }
                catch (e)
                {
                    console.error("Failed to parse response", response);
                    showMessage("Invalid response from server", "error");
                    return;
                }
            }
            else
            {
                data = response;
            }

            if (data.success)
            {
                updateCartDisplay(data.data.cart);
                showMessage(data.message || "Item removed from cart", "success");
            }
            else
            {
                console.error("Failed to remove from cart: ", data);
                showMessage(data.message || "Failed to remove item from cart", "error");
            }
        },
        error: function(xhr, status, error)
        {
            console.error("Error removing from cart:", error);
            console.error("Response text", xhr.responseText);
            showMessage("Could not connect to the server", "error");
        }
    });
}

/* ============================================================================================== */
function clearCart()
{
    $.ajax({
        url: 'index.php?action=clearCart',
        type: 'POST',
        processData: false,
        contentType: false,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        success: function(response)
        {
            let data;

            if (typeof response === "string")
            {
                try 
                {
                    data = JSON.parse(response);
                }
                catch (e)
                {
                    console.error("Failed to parse response", response);
                    showMessage("Invalid response from server", "error");
                    return;
                }
            }
            else
            {
                data = response;
            }

            if (data.success)
            {
                updateCartDisplay(data.data.cart);
                showMessage(data.message || "Cart cleared", "success");
            }
            else
            {
                console.error("Failed to clear cart: ", data);
                showMessage(data.message || "Failed to clear cart", "error");
            }
        },
        error: function(xhr, status, error)
        {
            console.error("Error clearing cart:", error);
            console.error("Response text", xhr.responseText);
            showMessage("Could not connect to the server", "error");
        }
    });
}

/* ============================================================================================== */
function updateCartDisplay(cartData)
{
    // If we're on the cart page, update the display
    if ($('.shopping_cart').length){
        if (cartData.items.length === 0) {
            //Cart is empty
            $('.shopping_cart').html(
                '<div class="empty-cart-message">' +
                '<p>Your cart is empty</p>' +
                '<a href="index.php?page=webshop" class="continue-shopping">Continue Shopping</a>' +
                '</div>'
            );
        }
        else
        {
            // Cart is not empty - rebuild the table
            let cartHtml = '<table class="shopping_cart">';
            cartHtml += '<thead><tr><th>Product</th><th>Name</th><th>Price</th><th>Quantity</th><th>Total</th><th>Actions</th></tr></thead><tbody>';
            
            cartData.items.forEach(function(item) {
                const product = item.product
                cartHtml += `
                <tr class="item" data-product-id="${item.product_id}">
                    <td><img src="assets/images/${product.image || 'placeholder.png'}" alt="${product.name}"></td>
                    <td>${product.name}</td>
                    <td>€${parseFloat(product.price).toFixed(2)}</td>
                    <td>
                        <input type="number" name="quantity" min="1" max="99" value="${item.quantity}">
                        <button class="update-btn">Update</button>
                    </td>
                    <td>€${(parseFloat(product.price) * parseInt(item.quantity)).toFixed(2)}</td>
                    <td><button class="remove-btn">❌</button></td>
                </tr>`;
            });
            
            cartHtml += '</tbody></table>';
            
            // Add cart actions
            cartHtml += `
            <div class="cart-actions">
                <a href="index.php?page=webshop" class="continue-shopping">Continue Shopping</a>
                <button class="clear-cart-btn">Clear Cart</button>
                <div class="cart-total">
                    <span class="cart-total-label">Total:</span>
                    <span class="cart-total-value">€${parseFloat(cartData.total_price).toFixed(2)}</span>
                </div>
                <form action="index.php" method="POST" class="checkout-form">
                    <input type="hidden" name="page" value="checkout">
                    <button type="submit" class="checkout-btn">Checkout</button>
                </form>
            </div>`;
            
            $('.shopping_cart').html(cartHtml);
            
            // Reattach event handlers
            initCartSystem();
        }
    }

    updateCartIndicator(cartData.items.length);
}

/* ============================================================================================== */
function updateCartIndicator(itemCount) {
    // Update the item counter in hyperlink menu
    $('.cart-indicator').text(`CART [${itemCount}]`);
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
function highlightItem(productId) {
    const item = $(`.item[data-product-id="${productId}"]`);
    
    if (item.length) {
        // Remove any existing animation class first
        item.removeClass('item-updated');
        
        // Force browser to recognize the change by triggering reflow
        void item[0].offsetWidth;
        
        // Add the animation class
        item.addClass('item-updated');
    }
}

/* ============================================================================================== */
