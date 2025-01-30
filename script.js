const wrapper = document.querySelector('.wrapper');
const registerLink = document.querySelector('.register-link');
const loginLink = document.querySelector('.login-link');

// Show signup form
registerLink.onclick = () => {
    wrapper.classList.add('active');
}

// Show login form
loginLink.onclick = () => {
    wrapper.classList.remove('active');
}
