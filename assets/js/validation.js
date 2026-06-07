// Form Validation for Hostel Booking System

// Validate email
function validateEmail(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
}

// Validate password
function validatePassword(password) {
    // Require at least eight digits and at least one letter or special character
    // Example valid password: 12345678a  OR  12345678@
    const passwordRegex = /^(?=(?:.*\d){8,})(?=.*[A-Za-z\W]).{9,}$/;
    return passwordRegex.test(password);
}

// Validate phone number (Tanzania format)
function validatePhone(phone) {
    const phoneRegex = /^(\+255|0)[67]\d{8}$/;
    return phoneRegex.test(phone.replace(/\s/g, ''));
}

// Validate registration number
function validateRegistrationNumber(regNum) {
    // Expected format: 8 digits, slash, single letter, dot, 1-2 digits
    // Example: 14325138/T.26
    const regNumRegex = /^\d{8}\/[A-Za-z]\.\d{1,2}$/;
    return regNumRegex.test(regNum);
}

// Validate form field
function validateField(field) {
    const value = field.value.trim();
    const type = field.getAttribute('data-validate');
    let isValid = true;
    let errorMessage = '';

    if (field.hasAttribute('required') && !value) {
        isValid = false;
        errorMessage = 'This field is required';
    } else if (value) {
        switch(type) {
            case 'email':
                if (!validateEmail(value)) {
                    isValid = false;
                    errorMessage = 'Please enter a valid email address';
                }
                break;
            
            case 'password':
                if (!validatePassword(value)) {
                    isValid = false;
                    errorMessage = 'Password must contain uppercase, lowercase, number, and be at least 6 characters';
                }
                break;
            
            case 'phone':
                if (!validatePhone(value)) {
                    isValid = false;
                    errorMessage = 'Please enter a valid Tanzania phone number';
                }
                break;
            
            case 'registration':
                if (!validateRegistrationNumber(value)) {
                    isValid = false;
                    errorMessage = 'Invalid registration number format';
                }
                break;
            
            case 'minlength':
                const minLength = field.getAttribute('minlength');
                if (value.length < minLength) {
                    isValid = false;
                    errorMessage = `Minimum length is ${minLength} characters`;
                }
                break;
            
            case 'maxlength':
                const maxLength = field.getAttribute('maxlength');
                if (value.length > maxLength) {
                    isValid = false;
                    errorMessage = `Maximum length is ${maxLength} characters`;
                }
                break;
            
            case 'match':
                const matchFieldId = field.getAttribute('data-match');
                const matchField = document.getElementById(matchFieldId);
                if (matchField && value !== matchField.value) {
                    isValid = false;
                    errorMessage = 'Fields do not match';
                }
                break;
            
            case 'number':
                if (isNaN(value)) {
                    isValid = false;
                    errorMessage = 'Please enter a valid number';
                }
                break;
            
            case 'url':
                try {
                    new URL(value);
                } catch (e) {
                    isValid = false;
                    errorMessage = 'Please enter a valid URL';
                }
                break;
        }
    }

    // Show/hide error message
    let errorElement = field.nextElementSibling;
    if (!errorElement || !errorElement.classList.contains('error-message')) {
        errorElement = document.createElement('div');
        errorElement.className = 'error-message';
        field.parentNode.insertBefore(errorElement, field.nextSibling);
    }

    if (!isValid) {
        field.classList.add('is-invalid');
        errorElement.textContent = errorMessage;
        errorElement.style.display = 'block';
    } else {
        field.classList.remove('is-invalid');
        errorElement.style.display = 'none';
    }

    return isValid;
}

// Validate all form fields
function validateAllFields(form) {
    const fields = form.querySelectorAll('[data-validate], [required]');
    let allValid = true;

    fields.forEach(field => {
        if (!validateField(field)) {
            allValid = false;
        }
    });

    return allValid;
}

// Real-time validation
function setupRealtimeValidation() {
    const fields = document.querySelectorAll('[data-validate], [required]');
    
    fields.forEach(field => {
        field.addEventListener('blur', function() {
            validateField(this);
        });

        field.addEventListener('input', function() {
            if (this.classList.contains('is-invalid')) {
                validateField(this);
            }
        });
    });
}

// Validate registration form
function validateRegisterForm(form) {
    const name = form.querySelector('[name="name"]');
    const email = form.querySelector('[name="email"]');
    const password = form.querySelector('[name="password"]');
    const confirmPassword = form.querySelector('[name="confirm_password"]');
    const phone = form.querySelector('[name="phone"]');
    const regNum = form.querySelector('[name="registration_number"]');

    let isValid = true;

    // Validate name
    if (!name.value.trim()) {
        showFieldError(name, 'Name is required');
        isValid = false;
    } else if (name.value.trim().length < 3) {
        showFieldError(name, 'Name must be at least 3 characters');
        isValid = false;
    } else {
        clearFieldError(name);
    }

    // Validate email
    if (!validateEmail(email.value)) {
        showFieldError(email, 'Please enter a valid email');
        isValid = false;
    } else {
        clearFieldError(email);
    }

    // Validate password
    if (!validatePassword(password.value)) {
        showFieldError(password, 'Password must have uppercase, lowercase, number (min 6 chars)');
        isValid = false;
    } else {
        clearFieldError(password);
    }

    // Validate password match
    if (password.value !== confirmPassword.value) {
        showFieldError(confirmPassword, 'Passwords do not match');
        isValid = false;
    } else {
        clearFieldError(confirmPassword);
    }

    // Validate phone
    if (!validatePhone(phone.value)) {
        showFieldError(phone, 'Invalid Tanzania phone number');
        isValid = false;
    } else {
        clearFieldError(phone);
    }

    // Validate registration number
    if (regNum && !validateRegistrationNumber(regNum.value)) {
        showFieldError(regNum, 'Invalid registration number format');
        isValid = false;
    } else if (regNum) {
        clearFieldError(regNum);
    }

    return isValid;
}

// Validate login form
function validateLoginForm(form) {
    const email = form.querySelector('[name="email"]');
    const password = form.querySelector('[name="password"]');

    let isValid = true;

    if (!validateEmail(email.value)) {
        showFieldError(email, 'Please enter a valid email');
        isValid = false;
    } else {
        clearFieldError(email);
    }

    if (!password.value) {
        showFieldError(password, 'Password is required');
        isValid = false;
    } else {
        clearFieldError(password);
    }

    return isValid;
}

// Validate booking form
function validateBookingForm(form) {
    const hostelId = form.querySelector('[name="hostel_id"]');
    const academicYear = form.querySelector('[name="academic_year"]');
    const semester = form.querySelector('[name="semester"]');

    let isValid = true;

    if (!hostelId.value) {
        showFieldError(hostelId, 'Please select a hostel');
        isValid = false;
    } else {
        clearFieldError(hostelId);
    }

    if (!academicYear.value) {
        showFieldError(academicYear, 'Please select academic year');
        isValid = false;
    } else {
        clearFieldError(academicYear);
    }

    if (!semester.value) {
        showFieldError(semester, 'Please select semester');
        isValid = false;
    } else {
        clearFieldError(semester);
    }

    return isValid;
}

// Show field error
function showFieldError(field, message) {
    field.classList.add('is-invalid');
    
    let errorElement = field.nextElementSibling;
    if (!errorElement || !errorElement.classList.contains('error-message')) {
        errorElement = document.createElement('div');
        errorElement.className = 'error-message';
        field.parentNode.insertBefore(errorElement, field.nextSibling);
    }
    
    errorElement.textContent = message;
    errorElement.style.display = 'block';
}

// Clear field error
function clearFieldError(field) {
    field.classList.remove('is-invalid');
    const errorElement = field.nextElementSibling;
    if (errorElement && errorElement.classList.contains('error-message')) {
        errorElement.style.display = 'none';
    }
}

// Initialize validation on document ready
document.addEventListener('DOMContentLoaded', function() {
    setupRealtimeValidation();

    // Form submissions
    const registerForm = document.getElementById('registerForm');
    if (registerForm) {
        registerForm.addEventListener('submit', function(e) {
            if (!validateRegisterForm(this)) {
                e.preventDefault();
            }
        });
    }

    const loginForm = document.getElementById('loginForm');
    if (loginForm) {
        loginForm.addEventListener('submit', function(e) {
            if (!validateLoginForm(this)) {
                e.preventDefault();
            }
        });
    }

    const bookingForm = document.getElementById('bookingForm');
    if (bookingForm) {
        bookingForm.addEventListener('submit', function(e) {
            if (!validateBookingForm(this)) {
                e.preventDefault();
            }
        });
    }
});

// Custom error message styles
const style = document.createElement('style');
style.textContent = `
    .error-message {
        color: #c0392b;
        font-size: 0.85rem;
        margin-top: 5px;
        display: none;
    }
    
    .is-invalid {
        border-color: #c0392b !important;
        background-color: #fadbd8 !important;
    }
    
    .is-invalid:focus {
        box-shadow: 0 0 5px rgba(192, 57, 43, 0.3) !important;
    }
`;
document.head.appendChild(style);
