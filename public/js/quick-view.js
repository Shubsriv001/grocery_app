document.addEventListener('DOMContentLoaded', function() {
    // Quick View Modal
    const quickViewModal = document.getElementById('quickViewModal');
    if (quickViewModal) {
        const modal = new bootstrap.Modal(quickViewModal);

        // Quick View Button Click Handler
        document.querySelectorAll('.quick-view').forEach(button => {
            button.addEventListener('click', function() {
                // Get product data from button attributes
                const productId = this.dataset.productId;
                const productName = this.dataset.productName;
                const productPrice = this.dataset.productPrice;
                const productDescription = this.dataset.productDescription;
                const productImage = this.dataset.productImage;

                // Update modal content
                document.getElementById('quickViewName').textContent = productName;
                document.getElementById('quickViewPrice').textContent = productPrice;
                document.getElementById('quickViewDescription').textContent = productDescription;
                
                // Update image with loading state
                const imageElement = document.getElementById('quickViewImage');
                imageElement.src = productImage;
                imageElement.alt = productName;

                // Ensure image loads properly
                imageElement.onload = function() {
                    imageElement.style.display = 'block';
                };
                imageElement.onerror = function() {
                    imageElement.src = '/images/placeholder.jpg'; // Fallback image
                };

                // Update buttons with product data
                const addToCartBtn = document.getElementById('quickViewAddToCart');
                if (addToCartBtn) {
                    addToCartBtn.dataset.productId = productId;
                    addToCartBtn.dataset.productName = productName;
                }

                const wishlistBtn = document.getElementById('quickViewToggleWishlist');
                if (wishlistBtn) {
                    wishlistBtn.dataset.productId = productId;
                    wishlistBtn.dataset.productName = productName;

                    // Check wishlist status
                    fetch(`/wishlist/check/${productId}`, {
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.inWishlist) {
                            wishlistBtn.innerHTML = '<i class="bi bi-heart-fill"></i> Remove from Wishlist';
                            wishlistBtn.classList.remove('btn-outline-primary');
                            wishlistBtn.classList.add('btn-danger');
                        } else {
                            wishlistBtn.innerHTML = '<i class="bi bi-heart"></i> Add to Wishlist';
                            wishlistBtn.classList.remove('btn-danger');
                            wishlistBtn.classList.add('btn-outline-primary');
                        }
                    })
                    .catch(error => console.error('Error checking wishlist status:', error));
                }
            });
        });

        // Add to Cart Handler in Quick View
        const quickViewAddToCart = document.getElementById('quickViewAddToCart');
        if (quickViewAddToCart) {
            quickViewAddToCart.addEventListener('click', function() {
                const productId = this.dataset.productId;
                const productName = this.dataset.productName;
                
                // Disable button and show loading state
                this.disabled = true;
                const originalText = this.innerHTML;
                this.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Adding...';

                fetch('/cart/add', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ product_id: productId })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Show success toast
                        const toast = new bootstrap.Toast(document.getElementById('successToast'));
                        document.querySelector('#successToast .toast-body').textContent = `${productName} added to cart`;
                        toast.show();

                        // Update cart count if available
                        const cartCount = document.querySelector('.cart-count');
                        if (cartCount) {
                            cartCount.textContent = data.cartCount;
                            cartCount.style.display = data.cartCount > 0 ? 'flex' : 'none';
                        }

                        // Close modal
                        modal.hide();
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    // Show error toast
                    const toast = new bootstrap.Toast(document.getElementById('errorToast'));
                    document.querySelector('#errorToast .toast-body').textContent = 'Failed to add item to cart';
                    toast.show();
                })
                .finally(() => {
                    // Reset button state
                    this.disabled = false;
                    this.innerHTML = originalText;
                });
            });
        }

        // Wishlist Toggle Handler in Quick View
        const quickViewWishlistBtn = document.getElementById('quickViewToggleWishlist');
        if (quickViewWishlistBtn) {
            quickViewWishlistBtn.addEventListener('click', function() {
                const productId = this.dataset.productId;
                const productName = this.dataset.productName;
                
                // Disable button and show loading state
                this.disabled = true;
                const originalText = this.innerHTML;
                this.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Processing...';

                fetch('/wishlist/toggle', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ product_id: productId })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Update button state
                        if (data.added) {
                            this.innerHTML = '<i class="bi bi-heart-fill"></i> Remove from Wishlist';
                            this.classList.remove('btn-outline-primary');
                            this.classList.add('btn-danger');
                        } else {
                            this.innerHTML = '<i class="bi bi-heart"></i> Add to Wishlist';
                            this.classList.remove('btn-danger');
                            this.classList.add('btn-outline-primary');
                        }

                        // Show success toast
                        const toast = new bootstrap.Toast(document.getElementById('successToast'));
                        document.querySelector('#successToast .toast-body').textContent = data.message;
                        toast.show();

                        // Update wishlist count if available
                        const wishlistCount = document.getElementById('wishlist-count');
                        if (wishlistCount) {
                            wishlistCount.textContent = data.wishlistCount;
                            wishlistCount.style.display = data.wishlistCount > 0 ? 'flex' : 'none';
                        }
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    // Show error toast
                    const toast = new bootstrap.Toast(document.getElementById('errorToast'));
                    document.querySelector('#errorToast .toast-body').textContent = 'Failed to update wishlist';
                    toast.show();
                    
                    // Reset button state
                    this.innerHTML = originalText;
                })
                .finally(() => {
                    this.disabled = false;
                });
            });
        }
    }
});
