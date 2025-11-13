/**
 * Login Form Handler with Error Display
 */

// Clear all error messages
function clearLoginErrors() {
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
function showLoginError(fieldName, message) {
    const field = document.getElementById(fieldName);
    if (!field) return;
    
    // Add red border to input
    field.classList.remove('border-gray-300');
    field.classList.add('border-red-500');
    
    // Check if error message already exists
    const parentDiv = field.closest('.relative, .mb-4');
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
    
    // Insert after the input's container
    parentDiv.parentNode.insertBefore(errorElement, parentDiv.nextSibling);
}

// Handle form submission
function handleLoginSubmit(event) {
    const form = event.target;
    
    // Clear previous errors
    clearLoginErrors();
    
    // Validate form first
    if (!form.checkValidity()) {
        return true; // Let browser validation handle it
    }

    // Prevent default form submission
    event.preventDefault();
    
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
        // Redirect to loading page
        window.location.href = data.redirectUrl;
    })
    .catch(error => {
        // Show error messages
        if (error.errors) {
            // Validation errors - show inline
            Object.keys(error.errors).forEach(fieldName => {
                const messages = error.errors[fieldName];
                if (messages && messages.length > 0) {
                    showLoginError(fieldName, messages[0]);
                }
            });
            
            // Scroll to first error
            const firstError = document.querySelector('.validation-error');
            if (firstError) {
                firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        } else {
            // General error - show in password field
            showLoginError('password', error.message || 'Login failed. Please try again.');
        }
    });
    
    return false;
}

// Initialize form handler when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    const loginForm = document.getElementById('loginForm');
    if (loginForm) {
        loginForm.addEventListener('submit', handleLoginSubmit);
    }
});
