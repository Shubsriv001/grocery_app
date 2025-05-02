document.addEventListener('DOMContentLoaded', function() {
    // Toggle wishlist item
    function toggleWishlist(productId, productName) {
        fetch('/wishlist/toggle', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                product_id: productId
            })
        })
        .then(response => {
            if (!response.ok) {
                if (response.status === 401) {
                    window.location.href = '/login';
                    throw new Error('Please login to add items to wishlist');
                }
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            const wishlistButton = document.querySelector(`.wishlist-btn[data-product-id="${productId}"]`);
            if (wishlistButton) {
                if (data.status === 'added') {
                    wishlistButton.classList.add('active');
                    showToast(`${productName} added to wishlist!`);
                } else {
                    wishlistButton.classList.remove('active');
                    showToast(`${productName} removed from wishlist!`);
                }
            }
            updateWishlistCount(data.wishlist_count);
            updateWishlistDropdown();
        })
        .catch(error => {
            console.error('Error:', error);
            showToast(error.message || 'Error updating wishlist. Please try again.');
        });
    }

    // Update wishlist count in navbar
    function updateWishlistCount(count) {
        // Update the count in the navbar
        const badge = document.querySelector('#wishlistDropdown .badge');
        if (badge) {
            if (count > 0) {
                badge.textContent = count;
                badge.style.display = 'inline-block';
            } else {
                badge.style.display = 'none';
            }
        }
    }

    // Update wishlist dropdown content
    function updateWishlistDropdown() {
        fetch('/wishlist/preview')
            .then(response => response.json())
            .then(data => {
                const dropdownMenu = document.querySelector('#wishlistDropdown + .dropdown-menu');
                if (dropdownMenu) {
                    const content = data.items.length > 0 
                        ? `
                            <div class="p-3">
                                <h6 class="mb-3">My Wishlist</h6>
                                ${data.items.map(item => `
                                    <div class="d-flex align-items-center mb-3">
                                        <img src="${item.image}" alt="${item.name}" class="rounded" style="width: 50px; height: 50px; object-fit: cover;">
                                        <div class="ms-3 flex-grow-1">
                                            <h6 class="mb-0 text-truncate" style="max-width: 150px;">${item.name}</h6>
                                            <span class="text-primary">₹${item.price}</span>
                                        </div>
                                        <button class="btn btn-sm btn-danger ms-2 remove-from-wishlist" data-product-id="${item.id}" data-product-name="${item.name}">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                `).join('')}
                                <div class="d-grid gap-2">
                                    <a href="/wishlist" class="btn btn-primary">View All</a>
                                </div>
                            </div>
                        `
                        : `
                            <div class="p-3 text-center">
                                <p class="mb-0">Your wishlist is empty</p>
                            </div>
                        `;
                    dropdownMenu.innerHTML = content;

                    // Reattach event listeners for remove buttons
                    dropdownMenu.querySelectorAll('.remove-from-wishlist').forEach(button => {
                        button.addEventListener('click', function(e) {
                            e.preventDefault();
                            e.stopPropagation();
                            const productId = this.dataset.productId;
                            const productName = this.dataset.productName;
                            toggleWishlist(productId, productName);
                        });
                    });
                }
            })
            .catch(error => console.error('Error updating wishlist dropdown:', error));
    }

    // Show toast notification
    function showToast(message) {
        const toast = new bootstrap.Toast(document.getElementById('wishlistToast'));
        document.querySelector('#wishlistToastBody').textContent = message;
        toast.show();
    }

    // Add event listeners to wishlist buttons
    document.querySelectorAll('.wishlist-btn').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            const productId = this.dataset.productId;
            const productName = this.dataset.productName;
            toggleWishlist(productId, productName);
        });
    });

    // Handle wishlist link in navbar
    const wishlistLink = document.querySelector('.nav-link[href="/wishlist"]');
    if (wishlistLink) {
        wishlistLink.addEventListener('click', function(e) {
            if (!document.querySelector('meta[name="user-auth"]')) {
                e.preventDefault();
                window.location.href = '/login';
            }
        });
    }

    // Check wishlist status for each product
    if (document.querySelector('meta[name="user-auth"]')) {
        document.querySelectorAll('.wishlist-btn').forEach(button => {
            const productId = button.dataset.productId;
            fetch(`/wishlist/check/${productId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.inWishlist) {
                        button.classList.add('active');
                    }
                })
                .catch(error => console.error('Error:', error));
        });
    }
});
