document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('registrationForm');
    const inputs = form.querySelectorAll('input');
    const registerBtn = document.getElementById('registerBtn');
    const loginBtn = document.getElementById('loginBtn');
    const errorMessages = form.querySelectorAll('.error-me**ssage');

    // Form validation functions
    const validateName = (name) => {
        return name.length >= 2;
    };

    const validateEmail = (email) => {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    };

    const validatePassword = (password) => {
        return password.length >= 8;
    };

    const validateConfirmPassword = (password, confirmPassword) => {
        return password === confirmPassword;
    };

    // Update button states
    const updateButtonStates = () => {
        const name = document.getElementById('name').value;
        const email = document.getElementById('email').value;
        const password = document.getElementById('password').value;
        const confirmPassword = document.getElementById('confirmPassword').value;

        const isNameValid = validateName(name);
        const isEmailValid = validateEmail(email);
        const isPasswordValid = validatePassword(password);
        const isConfirmPasswordValid = validateConfirmPassword(password, confirmPassword);

        registerBtn.disabled = !(isNameValid && isEmailValid && isPasswordValid && isConfirmPasswordValid);
        loginBtn.disabled = !(isNameValid && isEmailValid && isPasswordValid && isConfirmPasswordValid);
    };

    // Keyboard navigation
    inputs.forEach((input, index) => {
        input.addEventListener('keydown', (e) => {
            if (e.key === 'ArrowDown' && index < inputs.length - 1) {
                inputs[index + 1].focus();
            } else if (e.key === 'ArrowUp' && index > 0) {
                inputs[index - 1].focus();
            }
        });
    });

    // Input validation
    inputs.forEach((input, index) => {
        input.addEventListener('input', () => {
            const value = input.value;
            let isValid = true;
            let errorMessage = '';

            switch (input.id) {
                case 'name':
                    isValid = validateName(value);
                    errorMessage = isValid ? '' : 'Name must be at least 2 characters long';
                    break;
                case 'email':
                    isValid = validateEmail(value);
                    errorMessage = isValid ? '' : 'Please enter a valid email address';
                    break;
                case 'password':
                    isValid = validatePassword(value);
                    errorMessage = isValid ? '' : 'Password must be at least 8 characters long';
                    break;
                case 'confirmPassword':
                    const password = document.getElementById('password').value;
                    isValid = validateConfirmPassword(password, value);
                    errorMessage = isValid ? '' : 'Passwords do not match';
                    break;
            }

            errorMessages[index].textContent = errorMessage;
            updateButtonStates();
        });
    });

    // Form submission
    form.addEventListener('submit', (e) => {
        e.preventDefault();
        // Redirect to home page
        window.location.href = 'home.html';
    });

    // Login button click
    loginBtn.addEventListener('click', () => {
        // Redirect to login page (to be implemented)
        window.location.href = 'login.html';
    });
});

// Mobile Navigation Toggle
document.addEventListener('DOMContentLoaded', function() {
    const menuToggle = document.querySelector('.menu-toggle');
    const navLinks = document.querySelector('.nav-links');

    if (menuToggle) {
        menuToggle.addEventListener('click', function() {
            navLinks.classList.toggle('active');
        });
    }

    // Close mobile menu when clicking outside
    document.addEventListener('click', function(event) {
        if (!event.target.closest('nav') && navLinks.classList.contains('active')) {
            navLinks.classList.remove('active');
        }
    });

    // Add to Cart functionality
    const addToCartButtons = document.querySelectorAll('.add-to-cart');
    addToCartButtons.forEach(button => {
        button.addEventListener('click', function() {
            const productCard = this.closest('.product-card');
            const productName = productCard.querySelector('h3').textContent;
            const productPrice = productCard.querySelector('.price').textContent;
            
            // Create cart item
            const cartItem = {
                name: productName,
                price: productPrice
            };

            // Add to cart (you can implement your cart logic here)
            console.log('Added to cart:', cartItem);
            
            // Show notification
            showNotification(`${productName} added to cart!`);
        });
    });

    // Search functionality
    const searchBtn = document.querySelector('.search-btn');
    if (searchBtn) {
        searchBtn.addEventListener('click', function() {
            const searchTerm = prompt('Enter search term:');
            if (searchTerm) {
                // Implement search functionality
                console.log('Searching for:', searchTerm);
            }
        });
    }
});

// Notification function
function showNotification(message) {
    const notification = document.createElement('div');
    notification.className = 'notification';
    notification.textContent = message;
    
    // Add styles
    notification.style.position = 'fixed';
    notification.style.bottom = '20px';
    notification.style.right = '20px';
    notification.style.backgroundColor = '#333';
    notification.style.color = 'white';
    notification.style.padding = '10px 20px';
    notification.style.borderRadius = '5px';
    notification.style.zIndex = '1000';
    notification.style.animation = 'slideIn 0.5s ease-out';
    
    document.body.appendChild(notification);
    
    // Remove notification after 3 seconds
    setTimeout(() => {
        notification.style.animation = 'slideOut 0.5s ease-in';
        setTimeout(() => {
            document.body.removeChild(notification);
        }, 500);
    }, 3000);
}

// Add CSS animations
const style = document.createElement('style');
style.textContent = `
    @keyframes slideIn {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
    
    @keyframes slideOut {
        from {
            transform: translateX(0);
            opacity: 1;
        }
        to {
            transform: translateX(100%);
            opacity: 0;
        }
    }
`;
document.head.appendChild(style); 