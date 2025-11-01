// Real-time form validation for Tech-Hub
console.log('Tech-Hub validation.js loaded successfully');

document.addEventListener('DOMContentLoaded', function() {
    initValidation();
});

function initValidation() {
    console.log('Initializing form validation');
    
    // Initialize login form validation if exists
    const loginForm = document.getElementById('loginForm');
    if (loginForm) {
        initLoginValidation();
    }
    
    // Initialize signup form validation if exists
    const signupForm = document.getElementById('signupForm');
    if (signupForm) {
        initSignupValidation();
    }
    
    // Initialize password toggles
    initPasswordToggles();
}

function initLoginValidation() {
    const loginForm = document.getElementById('loginForm');
    const emailInput = document.getElementById('loginEmail');
    const passwordInput = document.getElementById('loginPassword');
    
    if (!loginForm || !emailInput || !passwordInput) {
        console.log('Login form elements not found');
        return;
    }
    
    console.log('Initializing login form validation');
    
    // Real-time email validation
    emailInput.addEventListener('blur', function() {
        validateEmail(this.value, 'loginEmailError');
    });
    
    emailInput.addEventListener('input', function() {
        clearError('loginEmailError');
    });
    
    // Real-time password validation (simpler for login)
    passwordInput.addEventListener('blur', function() {
        validateLoginPassword(this.value, 'loginPasswordError');
    });
    
    passwordInput.addEventListener('input', function() {
        clearError('loginPasswordError');
    });
    
    // Form submission validation
    loginForm.addEventListener('submit', function(e) {
        if (!validateLoginForm()) {
            e.preventDefault();
            console.log('Login form validation failed');
        } else {
            console.log('Login form validation passed');
            showLoadingState('loginButton');
        }
    });
}

function initSignupValidation() {
    const signupForm = document.getElementById('signupForm');
    
    if (!signupForm) {
        console.log('Signup form not found');
        return;
    }
    
    console.log('Initializing signup form validation');
    
    // Real-time validation for all fields
    const fields = [
        { id: 'signupFirstName', validator: validateName, errorId: 'signupFirstNameError', fieldName: 'First name' },
        { id: 'signupLastName', validator: validateName, errorId: 'signupLastNameError', fieldName: 'Last name' },
        { id: 'signupEmail', validator: validateEmail, errorId: 'signupEmailError' },
        { id: 'signupPassword', validator: validatePassword, errorId: 'signupPasswordError' },
        { id: 'signupConfirmPassword', validator: validateConfirmPassword, errorId: 'signupConfirmPasswordError' }
    ];
    
    fields.forEach(field => {
        const input = document.getElementById(field.id);
        if (input) {
            input.addEventListener('blur', function() {
                if (field.validator === validateName) {
                    field.validator(this.value, field.errorId, field.fieldName);
                } else {
                    field.validator(this.value, field.errorId);
                }
            });
            
            input.addEventListener('input', function() {
                clearError(field.errorId);
                if (field.id === 'signupPassword') {
                    updatePasswordStrength(this.value);
                }
                if (field.id === 'signupConfirmPassword' || field.id === 'signupPassword') {
                    validatePasswordMatch();
                }
            });
        }
    });
    
    // Terms agreement validation
    const agreeTerms = document.getElementById('agreeTerms');
    if (agreeTerms) {
        agreeTerms.addEventListener('change', function() {
            clearError('agreeTermsError');
        });
    }
    
    // Form submission validation
    signupForm.addEventListener('submit', function(e) {
        if (!validateSignupForm()) {
            e.preventDefault();
            console.log('Signup form validation failed');
        } else {
            console.log('Signup form validation passed');
            showLoadingState('signupButton');
        }
    });
}

function initPasswordToggles() {
    const passwordToggles = document.querySelectorAll('.passwordToggle');
    
    passwordToggles.forEach(toggle => {
        toggle.addEventListener('click', function() {
            const input = this.parentElement.querySelector('input');
            const icon = this.querySelector('i');
            
            if (!input || !icon) return;
            
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        });
    });
}

// Validation functions
function validateLoginForm() {
    console.log('Validating login form');
    
    const email = document.getElementById('loginEmail')?.value || '';
    const password = document.getElementById('loginPassword')?.value || '';
    
    let isValid = true;
    
    if (!validateEmail(email, 'loginEmailError')) {
        isValid = false;
    }
    
    if (!validateLoginPassword(password, 'loginPasswordError')) {
        isValid = false;
    }
    
    return isValid;
}

function validateSignupForm() {
    console.log('Validating signup form');
    
    const firstName = document.getElementById('signupFirstName')?.value || '';
    const lastName = document.getElementById('signupLastName')?.value || '';
    const email = document.getElementById('signupEmail')?.value || '';
    const password = document.getElementById('signupPassword')?.value || '';
    const confirmPassword = document.getElementById('signupConfirmPassword')?.value || '';
    const agreeTerms = document.getElementById('agreeTerms')?.checked || false;
    
    let isValid = true;
    
    if (!validateName(firstName, 'signupFirstNameError', 'First name')) {
        isValid = false;
    }
    
    if (!validateName(lastName, 'signupLastNameError', 'Last name')) {
        isValid = false;
    }
    
    if (!validateEmail(email, 'signupEmailError')) {
        isValid = false;
    }
    
    if (!validatePassword(password, 'signupPasswordError')) {
        isValid = false;
    }
    
    if (!validateConfirmPassword(confirmPassword, 'signupConfirmPasswordError')) {
        isValid = false;
    }
    
    if (!agreeTerms) {
        showError('agreeTermsError', 'You must agree to the terms and conditions');
        isValid = false;
    }
    
    return isValid;
}

function validateEmail(email, errorId) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    
    if (!email || email.trim() === '') {
        showError(errorId, 'Email is required');
        return false;
    }
    
    if (!emailRegex.test(email)) {
        showError(errorId, 'Please enter a valid email address');
        return false;
    }
    
    clearError(errorId);
    return true;
}

function validateLoginPassword(password, errorId) {
    if (!password || password.trim() === '') {
        showError(errorId, 'Password is required');
        return false;
    }
    
    clearError(errorId);
    return true;
}

function validateName(name, errorId, fieldName) {
    if (!name || name.trim() === '') {
        showError(errorId, `${fieldName} is required`);
        return false;
    }
    
    if (name.length < 2) {
        showError(errorId, `${fieldName} must be at least 2 characters`);
        return false;
    }
    
    if (!/^[a-zA-Z\s\-']+$/.test(name)) {
        showError(errorId, `${fieldName} can only contain letters, spaces, hyphens, and apostrophes`);
        return false;
    }
    
    clearError(errorId);
    return true;
}

function validatePassword(password, errorId) {
    if (!password || password.trim() === '') {
        showError(errorId, 'Password is required');
        return false;
    }
    
    if (password.length < 6) {
        showError(errorId, 'Password must be at least 6 characters long');
        return false;
    }
    
    // Check for at least one letter and one number
    if (!/(?=.*[a-zA-Z])(?=.*\d)/.test(password)) {
        showError(errorId, 'Password must contain at least one letter and one number');
        return false;
    }
    
    clearError(errorId);
    return true;
}

function validateConfirmPassword(confirmPassword, errorId) {
    const password = document.getElementById('signupPassword')?.value || '';
    
    if (!confirmPassword || confirmPassword.trim() === '') {
        showError(errorId, 'Please confirm your password');
        return false;
    }
    
    if (confirmPassword !== password) {
        showError(errorId, 'Passwords do not match');
        return false;
    }
    
    clearError(errorId);
    return true;
}

function validatePasswordMatch() {
    const password = document.getElementById('signupPassword')?.value || '';
    const confirmPassword = document.getElementById('signupConfirmPassword')?.value || '';
    
    if (confirmPassword && password !== confirmPassword) {
        showError('signupConfirmPasswordError', 'Passwords do not match');
        return false;
    }
    
    clearError('signupConfirmPasswordError');
    return true;
}

function updatePasswordStrength(password) {
    const strengthBar = document.querySelector('.strengthBar');
    const strengthText = document.querySelector('.strengthText');
    
    if (!strengthBar || !strengthText) return;
    
    let strength = 0;
    let color = '#e74c3c';
    let text = 'Weak';
    
    if (password.length >= 6) strength += 25;
    if (/[a-z]/.test(password)) strength += 25;
    if (/[A-Z]/.test(password)) strength += 25;
    if (/[0-9]/.test(password)) strength += 25;
    
    if (strength >= 75) {
        color = '#2ecc71';
        text = 'Strong';
    } else if (strength >= 50) {
        color = '#f39c12';
        text = 'Medium';
    } else if (strength >= 25) {
        color = '#e74c3c';
        text = 'Weak';
    } else {
        color = '#e74c3c';
        text = 'Very Weak';
    }
    
    strengthBar.style.width = strength + '%';
    strengthBar.style.backgroundColor = color;
    strengthText.textContent = `Password strength: ${text}`;
    strengthText.style.color = color;
}

// Utility functions
function showError(errorId, message) {
    const errorElement = document.getElementById(errorId);
    if (errorElement) {
        errorElement.textContent = message;
        errorElement.style.display = 'block';
        
        // Add error class to input
        const inputId = errorId.replace('Error', '');
        const input = document.getElementById(inputId);
        if (input) {
            input.classList.add('inputError');
        }
    }
    
    console.log(`Validation error (${errorId}):`, message);
}

function clearError(errorId) {
    const errorElement = document.getElementById(errorId);
    if (errorElement) {
        errorElement.textContent = '';
        errorElement.style.display = 'none';
        
        // Remove error class from input
        const inputId = errorId.replace('Error', '');
        const input = document.getElementById(inputId);
        if (input) {
            input.classList.remove('inputError');
        }
    }
}

function showLoadingState(buttonId) {
    const button = document.getElementById(buttonId);
    if (button) {
        const buttonText = button.querySelector('.buttonText');
        const buttonSpinner = button.querySelector('.buttonSpinner');
        
        if (buttonText && buttonSpinner) {
            buttonText.style.display = 'none';
            buttonSpinner.style.display = 'block';
            button.disabled = true;
        }
    }
}

// Make functions available globally
window.validateEmail = validateEmail;
window.validatePassword = validatePassword;
window.validateName = validateName;
window.validateConfirmPassword = validateConfirmPassword;