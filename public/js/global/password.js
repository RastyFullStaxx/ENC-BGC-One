
    /**
 * Global password toggle function
 * Toggles password visibility for any password field
 * @param {string} fieldId - The ID of the password input field
 */
function togglePassword(fieldId) {
    const field = document.getElementById(fieldId);
    if (field && field.type === 'password') {
        field.type = 'text';
    } else if (field) {
        field.type = 'password';
    }
}

/**
 * Initialize password toggle for all password fields with toggle buttons
 * Call this function on page load if you want automatic setup
 */
function initPasswordToggles() {
    // Find all password toggle buttons with data-target attribute
    const toggleButtons = document.querySelectorAll('[data-toggle-password]');
    
    toggleButtons.forEach(button => {
        const targetId = button.getAttribute('data-toggle-password');
        button.addEventListener('click', (e) => {
            e.preventDefault();
            togglePassword(targetId);
        });
    });
}

// Auto-initialize when DOM is ready
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initPasswordToggles);
} else {
    initPasswordToggles();
}
