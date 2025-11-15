
    /**
 * Global password toggle function
 * Toggles password visibility for any password field
 * @param {string} fieldId - The ID of the password input field
 */
function togglePassword(fieldId) {
    const field = document.getElementById(fieldId);
    if (!field) return;
    
    // Find the toggle button (look for button that contains the field or has onclick with the fieldId)
    const container = field.closest('.relative') || field.parentElement;
    const toggleButton = container ? container.querySelector('button[onclick*="' + fieldId + '"]') : null;
    
    if (field.type === 'password') {
        field.type = 'text';
        // Update icon to show eye-slash (hidden password)
        if (toggleButton) {
            toggleButton.innerHTML = `
                <svg class="w-5 h-5 enc-text-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                </svg>
            `;
        }
    } else {
        field.type = 'password';
        // Update icon to show eye (visible password)
        if (toggleButton) {
            toggleButton.innerHTML = `
                <svg class="w-5 h-5 enc-text-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                </svg>
            `;
        }
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
