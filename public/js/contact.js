document.addEventListener('DOMContentLoaded', function() {
    const contactForm = document.getElementById('contactForm');
    const sendMessageBtn = document.getElementById('sendMessageBtn');
    const spinner = sendMessageBtn.querySelector('.spinner-border');

    if (contactForm) {
        contactForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Reset previous errors
            document.getElementById('emailError').textContent = '';
            document.getElementById('messageError').textContent = '';
            
            // Show loading state
            sendMessageBtn.disabled = true;
            spinner.classList.remove('d-none');
            
            const formData = new FormData(contactForm);
            
            fetch('/contact/send', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    email: formData.get('email'),
                    message: formData.get('message')
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Show success message
                    const alert = document.createElement('div');
                    alert.className = 'alert alert-success alert-dismissible fade show mt-3';
                    alert.innerHTML = `
                        ${data.message}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    `;
                    contactForm.insertAdjacentElement('beforebegin', alert);
                    
                    // Reset form
                    contactForm.reset();
                } else {
                    // Show validation errors
                    const errors = data.errors || {};
                    if (errors.email) {
                        document.getElementById('emailError').textContent = errors.email[0];
                    }
                    if (errors.message) {
                        document.getElementById('messageError').textContent = errors.message[0];
                    }
                }
            })
            .catch(error => {
                // Show error message
                const alert = document.createElement('div');
                alert.className = 'alert alert-danger alert-dismissible fade show mt-3';
                alert.innerHTML = `
                    An error occurred. Please try again later.
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                `;
                contactForm.insertAdjacentElement('beforebegin', alert);
            })
            .finally(() => {
                // Reset button state
                sendMessageBtn.disabled = false;
                spinner.classList.add('d-none');
            });
        });
    }
});
