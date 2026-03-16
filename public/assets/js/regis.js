// DOM Elements
const loginForm = document.getElementById('loginForm');
const usernameInput = document.getElementById('username');
const passwordInput = document.getElementById('password');
const togglePassword = document.getElementById('togglePassword');
const loginButton = document.getElementById('loginButton');
const loginSpinner = document.getElementById('loginSpinner');
const btnText = document.querySelector('.btn-text');
const notification = document.getElementById('notification');
const notificationText = document.getElementById('notificationText');

// Toggle Password Visibility
togglePassword.addEventListener('click', () => {
  const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
  passwordInput.setAttribute('type', type);
  
  // Toggle eye icon
  const icon = togglePassword.querySelector('i');
  icon.classList.toggle('fa-eye');
  icon.classList.toggle('fa-eye-slash');
});

// Show Notification
function showNotification(message, type = 'success') {
  notificationText.textContent = message;
  notification.className = 'notification';
  notification.classList.add(type);
  notification.classList.remove('hidden');
  
  // Hide after 3 seconds
  setTimeout(() => {
    notification.classList.add('hidden');
  }, 3000);
}

// Simulate Login Process
function simulateLogin(username, password) {
  return new Promise((resolve, reject) => {
    setTimeout(() => {
      // Simple validation
      if (username && password.length >= 6) {
        resolve({ success: true, message: 'Login successful!' });
      } else {
        reject({ success: false, message: 'Invalid credentials' });
      }
    }, 1200);
  });
}

// Handle Form Submission
loginForm.addEventListener('submit', async (e) => {
  e.preventDefault();
  
  const username = usernameInput.value.trim();
  const password = passwordInput.value.trim();
  
  // Basic validation
  if (!username || !password) {
    showNotification('Please fill in all fields', 'warning');
    return;
  }
  
  if (password.length < 6) {
    showNotification('Password must be at least 6 characters', 'warning');
    return;
  }
  
  // Show loading state
  loginSpinner.classList.remove('hidden');
  btnText.classList.add('hidden');
  loginButton.disabled = true;
  
  try {
    const result = await simulateLogin(username, password);
    showNotification(result.message, 'success');
    
    // Reset form
    setTimeout(() => {
      loginForm.reset();
      loginSpinner.classList.add('hidden');
      btnText.classList.remove('hidden');
      loginButton.disabled = false;
    }, 1000);
    
  } catch (error) {
    showNotification(error.message, 'error');
    loginSpinner.classList.add('hidden');
    btnText.classList.remove('hidden');
    loginButton.disabled = false;
  }
});

// Add focus effect to inputs
const inputs = document.querySelectorAll('input');
inputs.forEach(input => {
  input.addEventListener('focus', () => {
    input.parentElement.style.borderColor = '#667eea';
    input.parentElement.style.boxShadow = '0 0 0 3px rgba(102, 126, 234, 0.1)';
  });
  
  input.addEventListener('blur', () => {
    input.parentElement.style.borderColor = '#e0e0e0';
    input.parentElement.style.boxShadow = 'none';
  });
});

// Forgot password link
document.querySelector('.forgot-link').addEventListener('click', (e) => {
  e.preventDefault();
  showNotification('Password reset feature would be implemented here', 'warning');
});

// Auto-focus username on page load
window.addEventListener('DOMContentLoaded', () => {
  usernameInput.focus();
});