// Display contact form when click contact button, from show property page
const contactForm = document.getElementById('contactForm');
const contactButton = document.getElementById('contactButton');
const cancelButton = document.getElementById('cancelButton');

if (contactButton !== null) {
    contactButton.addEventListener('click', e => {
        e.preventDefault();
        contactForm.style.display = 'initial';
        contactButton.style.display = 'none';
    })
}

if (cancelButton !== null) {
    cancelButton.addEventListener('click', e => {
        e.preventDefault();
        contactForm.style.display = 'none';
        contactButton.style.display = 'initial';
    })
}