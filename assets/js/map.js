// Generate Map on Show Property Page

// Leafet for generate maps
import L, { icon } from 'leaflet';
import 'leaflet/dist/leaflet.css';

export default class Map {

    static init() {
        let map = document.getElementById('map');

        if (map === null) {
            return;
        }

        // lat and lng datas of the property
        let position = [map.dataset.lat, map.dataset.lng];

        // Configure the map
        map = L.map('map').setView(position, 13);

        // Display the map
        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
            maxZoom: 18,
            minZoom: 5,
        }).addTo(map);

        // Icon in the map
        let mapIcon = L.icon({
            iconUrl: '/assets/icons/marker-icon-2x.png',
            iconSize: [28, 50],
            iconAnchor: [12, 49],
            popupAnchor: [3, -46]
        });

        // Display the marker on the map
        L.marker(position, { icon: mapIcon })
            .addTo(map)
            .bindPopup(document.getElementById('property-title').textContent)
            .openPopup();

    }

}
