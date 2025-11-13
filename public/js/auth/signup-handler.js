/**
 * Signup Form Handler with Loading Animation and Success Modal
 */

// Show loading overlay
function showLoading() {
    const loadingOverlay = document.getElementById('loadingOverlay');
    if (loadingOverlay) {
        loadingOverlay.classList.remove('hidden');
        loadingOverlay.classList.add('flex');
    }
}

// Hide loading overlay
function hideLoading() {
    const loadingOverlay = document.getElementById('loadingOverlay');
    if (loadingOverlay) {
        loadingOverlay.classList.remove('flex');
        loadingOverlay.classList.add('hidden');
    }
}

// Clear all error messages
function clearErrors() {
    const errorMessages = document.querySelectorAll('.validation-error');
    errorMessages.forEach(error => error.remove());
    
    // Remove error border from inputs
    const inputs = document.querySelectorAll('.border-red-500');
    inputs.forEach(input => {
        input.classList.remove('border-red-500');
        input.classList.add('border-gray-300');
    });
}

// Show error message below input field
function showFieldError(fieldName, message) {
    const field = document.getElementById(fieldName);
    if (!field) return;
    
    // Add red border to input
    field.classList.remove('border-gray-300');
    field.classList.add('border-red-500');
    
    // Check if error message already exists
    const parentDiv = field.closest('.mb-2, .mb-3');
    if (!parentDiv) return;
    
    const existingError = parentDiv.querySelector('.validation-error');
    if (existingError) {
        existingError.textContent = message;
        return;
    }
    
    // Create error message element
    const errorElement = document.createElement('p');
    errorElement.className = 'validation-error text-red-500 text-xs mt-1';
    errorElement.textContent = message;
    
    // Insert after the input's relative container
    const relativeDiv = field.closest('.relative') || field;
    relativeDiv.parentNode.insertBefore(errorElement, relativeDiv.nextSibling);
}

// Show success modal with SweetAlert2
function showSuccessModal(message, landingUrl, loginUrl) {
    Swal.fire({
        title: 'Success!',
        text: message,
        icon: 'success',
        showCancelButton: true,
        confirmButtonText: 'Go to Login',
        cancelButtonText: 'Return Home',
        allowOutsideClick: true,
        allowEscapeKey: true,
        width: '400px',
        padding: '1.5rem'
    }).then((result) => {
        if (result.isConfirmed) {
            // Go to login page
            window.location.href = loginUrl;
        } else if (result.dismiss === Swal.DismissReason.cancel) {
            // Go to landing page
            window.location.href = landingUrl;
        }
    });
}

// Handle form submission
function handleSignupSubmit(event) {
    const form = event.target;
    
    // Clear previous errors
    clearErrors();
    
    // Validate form first
    if (!form.checkValidity()) {
        return true; // Let browser validation handle it
    }

    // Prevent default form submission
    event.preventDefault();
    
    // Show loading
    showLoading();
    
    // Get form data
    const formData = new FormData(form);
    
    // Submit form via fetch
    fetch(form.action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => {
        if (!response.ok) {
            return response.json().then(data => {
                throw data;
            });
        }
        return response.json();
    })
    .then(data => {
        // Hide loading after 2 seconds
        setTimeout(() => {
            hideLoading();
            
            // Show success modal
            showSuccessModal(
                data.message || 'Account created successfully! Please log in.',
                data.landingUrl || '/',
                data.loginUrl || '/login'
            );
        }, 2000);
    })
    .catch(error => {
        // Hide loading
        hideLoading();
        
        // Show error message
        if (error.errors) {
            // Validation errors - show inline
            Object.keys(error.errors).forEach(fieldName => {
                const messages = error.errors[fieldName];
                if (messages && messages.length > 0) {
                    showFieldError(fieldName, messages[0]);
                }
            });
            
            // Scroll to first error
            const firstError = document.querySelector('.validation-error');
            if (firstError) {
                firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        } else {
            // General error - show in modal
            Swal.fire({
                title: 'Error',
                text: error.message || 'Something went wrong. Please try again.',
                icon: 'error',
                confirmButtonText: 'OK',
                allowOutsideClick: true,
                allowEscapeKey: true,
                width: '400px',
                padding: '1.5rem'
            });
        }
    });
    
    return false;
}

// Initialize form handler when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    const signupForm = document.getElementById('staffSignupForm');
    if (signupForm) {
        signupForm.addEventListener('submit', handleSignupSubmit);
    }
});
