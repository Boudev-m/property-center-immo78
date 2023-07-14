// Display form contact from show property page
const contactButton = document.getElementById('contactButton');
if (contactButton !== null) {
    contactButton.addEventListener('click', e => {
        e.preventDefault();
        document.getElementById('contactForm').style.display = 'contents';
        contactButton.style.display = 'none';
    })
}