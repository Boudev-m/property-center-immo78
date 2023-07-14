/*
 * Address Search and Autocompletion for :
 * - Set complete address in Edit and New Property Page
 * - Search Filter in index property Page
*/

// PlaceKitAutocomplete is an API for adress search and autocompletion
import PlaceKitAutocomplete from '@placekit/autocomplete-js';
// styles from PKA package
import '@placekit/autocomplete-js/dist/placekit-autocomplete.css';


/******** PKA for edit/new property page ************/

// Target input for PKA
const addressInput = document.getElementById("property_address");

// the addressInput is on Edit and New property page
if (addressInput !== null) {

    // Set options in PKA
    const pka = PlaceKitAutocomplete('pk_cVM6wIHkUuLx6TTW1/wMbMidJHLzD0YK9UOVuoEttCQ=', {
        target: addressInput,
        countries: ['fr'],
        maxResults: 10,
    });

    // Event, when selects(picks) an item from suggestions list
    pka.on('pick', (value, item, index) => {
        document.getElementById('property_city').value = item.city;
        document.getElementById('property_postal_code').value = item.zipcode[0];
        document.getElementById('property_latitude').value = item.lat;
        document.getElementById('property_longitude').value = item.lng;
    })
}

/******** PKA for search filter in index property page ************/

// Target input for PKA
const searchInput = document.getElementById("search_address");

// the addressInput is on Edit and New property page
if (searchInput !== null) {

    // Set options in PKA
    const pka = PlaceKitAutocomplete('pk_cVM6wIHkUuLx6TTW1/wMbMidJHLzD0YK9UOVuoEttCQ=', {
        target: searchInput,
        countries: ['fr'],
        maxResults: 5,
    });

    // Event, when selects(picks) an item from suggestions list
    pka.on('pick', (value, item, index) => {
        document.getElementById('latitude').value = item.lat;
        document.getElementById('longitude').value = item.lng;
    })
}