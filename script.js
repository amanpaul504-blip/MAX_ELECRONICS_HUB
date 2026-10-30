const authModal = document.querySelector('.auth-modal');
const loginLink = document.querySelector('.login-link');
const registerLink = document.querySelector('.register-link');
const loginBtnModal = document.querySelector('.login-btn-modal');
const closeBtnModal = document.querySelector('.close-btn-modal');
const profileBox = document.querySelector('.profile-box');
const avatarCircle = document.querySelector('.avatar-circle');
const alertBox= document.querySelector('.alert-box');

registerLink.addEventListener('click',() => authModal.classList.add('slide'));
loginLink.addEventListener('click',() => authModal.classList.remove('slide'));

if (loginBtnModal)loginBtnModal.addEventListener('click',()=>authModal.classList.add('show'));
// Run this after a successful login
localStorage.setItem('isAuthenticated', 'true');
localStorage.setItem('userToken', 'your-jwt-token');

closeBtnModal.addEventListener('click',()=>authModal.classList.remove('show','slide'));

if (avatarCircle)avatarCircle.addEventListener('click',()=>profileBox.classList.toggle('show'));

if (alertBox) {
   setTimeout(()=>alertBox.classList.add('show'), 50);

   setTimeout(()=>{
	 alertBox.classList.remove('show');
	setTimeout(()=>alertBox.remove(),1000);
   }, 6000);
}

    function updateGreeting() {
        const date = new Date();
        const hour = date.getHours();
        const greetingElement = document.getElementById('greeting');
        const subtextElement = document.querySelector('.subtext');
        const userNameElement = document.getElementById('fname');
        
        // Example: If a server-side variable $fname is available in a real PHP environment
        // we can set the user name here dynamically. Otherwise, it defaults to a dot.
        // For static HTML, we leave it as is or hardcode a name if known.
        // Example if using pure JS/static HTML:
        // userNameElement.textContent = "John"; 

        let greetingText = '';
        let subtextText = '';

        if (hour >= 0 && hour < 12) {
            greetingText = 'Good Morning';
            subtextText = 'We wish you a lucky day!';
        } else if (hour >= 12 && hour < 15) {
            greetingText = 'Good Afternoon';
            subtextText = 'Enjoy your day!';
        } else if (hour >= 15 && hour < 18) {
            greetingText = 'Good Evening';
            subtextText = 'Hope you have a restful evening!';
        } else {
            greetingText = 'Good Night';
            subtextText = 'May you have a wonderful Night!';
        }

        greetingElement.textContent = greetingText;
        subtextElement.textContent = subtextText;
    }

    // Call the function when the page loads
    window.onload = updateGreeting;

    