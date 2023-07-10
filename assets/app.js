/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.css';

const contactButton = document.getElementById('contactButton');
const contactForm = document.getElementById('contactForm');
contactButton.addEventListener('click', e => {
    e.preventDefault();
    contactForm.style.display = 'contents';
    contactButton.style.display = 'none';
})

console.log(contactButton);

console.log('Hello Immo78');