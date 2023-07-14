// Delete images dynamically from Edit Property Page
let dataDelete = document.querySelectorAll('[data-delete]');
dataDelete.forEach(element => {
    element.addEventListener('click', e => {
        e.preventDefault();
        element.parentNode.remove() // remove the picture from DOM
        // console.log(element.dataset.token);  // get token
        // console.log(element.getAttribute('data-token'));  // get token
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
            .catch(e => alert(e))
    })
});