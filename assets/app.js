/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.css';

// Display form contact from show property page
// const contactButton = document.getElementById('contactButton');
// const contactForm = document.getElementById('contactForm');
// contactButton.addEventListener('click', e => {
//     e.preventDefault();
//     contactForm.style.display = 'contents';
//     contactButton.style.display = 'none';
// })
// console.log(contactButton);

console.log('Hello Immo78');

// Delete images dynamically from edit property page
let dataDelete = document.querySelectorAll('[data-delete]');
dataDelete.forEach(element => {
    element.addEventListener('click', e => {
        e.preventDefault();
        element.parentNode.remove() // remove the picture from DOM
        // console.log(element.dataset.token);  // token
        // console.log(element.getAttribute('data-token'));  // token
        // go to delete picture route
        fetch(element.getAttribute('href'), {
            method: 'DELETE', // only the Same method that the route is allowed
            headers: {
                'X-Requested-With': 'XMLHttpRequest', // Ajax/XML request
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ '_token': element.dataset.token })
        })
            // get response
            .then(response => response.json())
            // get data from response
            .then(data => {
                if (data.err) {
                    alert(data.err);
                }
            })
        // .catch(e => alert(e))
    })
});