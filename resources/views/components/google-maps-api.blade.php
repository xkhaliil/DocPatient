<div class="w-full">
    <h3 class="text-sm font-semibold uppercase tracking-wider mb-4 text-blue-200">
        Medical Centers Near You
    </h3>
    <div id="location-display" class="mb-4"></div>
    <div class="bg-white rounded-lg shadow-xl overflow-hidden">
        <div id="map" class="h-[300px] w-full"></div>
        <gmp-place-details-compact orientation="horizontal" class="hidden">
            <gmp-place-details-place-request place=""></gmp-place-details-place-request>
            <gmp-place-all-content></gmp-place-all-content>
        </gmp-place-details-compact>
    </div>
</div>

<script>
    // Google Maps API Component JavaScript
    let map;
    let markers = [];
    let placeDetailsElement;

    // Load Google Maps API
    (g=>{var h,a,k,p="The Google Maps JavaScript API",c="google",l="importLibrary",q="__ib__",m=document,b=window;b=b[c]||(b[c]={});var d=b.maps||(b.maps={}),r=new Set,e=new URLSearchParams,u=()=>h||(h=new Promise(async(f,n)=>{await (a=m.createElement("script"));e.set("libraries",[...r]+"");for(k in g)e.set(k.replace(/[A-Z]/g,t=>"_"+t[0].toLowerCase()),g[k]);e.set("callback",c+".maps."+q);a.src=`https://maps.${c}apis.com/maps/api/js?`+e;d[q]=f;a.onerror=()=>h=n(Error(p+" could not load."));a.nonce=m.querySelector("script[nonce]")?.nonce||"";m.head.append(a)}));d[l]?console.warn(p+" only loads once. Ignoring:",g):d[l]=(f,...n)=>r.add(f)&&u().then(()=>d[l](f,...n))})({
        key: "{{ env('GOOGLE_MAPS_API_KEY') }}",
        v: "weekly",
        libraries: ["places", "marker"]
    });

    async function initMap() {
        const { Map } = await google.maps.importLibrary("maps");
        const { AdvancedMarkerElement } = await google.maps.importLibrary("marker");
        const { PlaceDetailsElement, PlaceSearchElement } = await google.maps.importLibrary("places");

        // Get user's location from the data attribute
        const userLocationElement = document.querySelector('[data-user-location]');
        const userLocation = userLocationElement ? JSON.parse(userLocationElement.dataset.userLocation) : {
            latitude: null,
            longitude: null,
            city: 'Barcelona',
            country: 'Spain'
        };
        
        // Use user's actual location or fallback to Barcelona
        const userCoords = userLocation.latitude && userLocation.longitude 
            ? { lat: userLocation.latitude, lng: userLocation.longitude }
            : { lat: 41.3851, lng: 2.1734 }; // Barcelona fallback

        // Initialize the map centered on user's location
        map = new Map(document.getElementById("map"), {
            center: userCoords,
            zoom: 13,
            mapId: "user_location_medical_centers_map",
            mapTypeControl: false,
            streetViewControl: false,
            fullscreenControl: true
        });

        // Update UI to show user's location
        updateLocationDisplay(userLocation);

        // Search for medical centers near user's location
        searchNearbyMedicalCenters(userCoords, userLocation.city);

        // Initialize place details element
        placeDetailsElement = document.querySelector('gmp-place-details-compact');
    }

    async function searchNearbyMedicalCenters(location, city = 'Barcelona') {
        const { Place } = await google.maps.importLibrary("places");
        
        // Location-specific search request
        const request = {
            textQuery: `medical center hospital clinic doctor ${city}`,
            fields: ['displayName', 'location', 'formattedAddress', 'rating', 'websiteUri', 'nationalPhoneNumber'],
            locationRestriction: {
                center: location,
                radius: 10000 // 10km radius around user's location
            },
            includedPrimaryTypes: ['hospital', 'doctor', 'health', 'medical_care', 'clinic'],
            maxResultCount: 10,
            language: 'en'
        };

        try {
            const { places } = await Place.searchByText(request);
            
            // Clear existing markers
            markers.forEach(marker => marker.map = null);
            markers = [];

            if (places && places.length > 0) {
                places.forEach((place, index) => {
                    if (place.location) {
                        createMedicalCenterMarker(place, index);
                    }
                });
            } else {
                // If no results from Places API, use sample data based on city
                addSampleMedicalCenters(location, city);
            }

        } catch (error) {
            console.error('Error searching for medical centers:', error);
            // Fallback: Add sample medical centers based on city
            addSampleMedicalCenters(location, city);
        }
    }

    async function createMedicalCenterMarker(place, index) {
        const { AdvancedMarkerElement } = await google.maps.importLibrary("marker");
        
        const marker = new AdvancedMarkerElement({
            map: map,
            position: place.location,
            title: place.displayName,
            content: createMarkerElement(index + 1)
        });

        // Create info window
        const infoWindow = new google.maps.InfoWindow({
            content: createInfoWindowContent(place)
        });

        // Add click listener
        marker.addListener('click', () => {
            infoWindow.open(map, marker);
            showPlaceDetails(place);
        });

        markers.push(marker);
    }

    function createMarkerElement(number) {
        const div = document.createElement('div');
        div.className = 'medical-marker';
        div.innerHTML = `
            <div style="
                background: #3b82f6;
                color: white;
                border-radius: 50%;
                width: 32px;
                height: 32px;
                display: flex;
                align-items: center;
                justify-content: center;
                font-weight: bold;
                font-size: 14px;
                border: 3px solid white;
                box-shadow: 0 2px 6px rgba(0,0,0,0.3);
                cursor: pointer;
            ">${number}</div>
        `;
        return div;
    }

    function createInfoWindowContent(place) {
        return `
            <div style="padding: 12px; max-width: 250px;">
                <h3 style="margin: 0 0 8px 0; font-size: 16px; font-weight: bold; color: #1f2937;">${place.displayName}</h3>
                <p style="margin: 0 0 8px 0; font-size: 14px; color: #6b7280;">${place.formattedAddress || 'Address not available'}</p>
                ${place.rating ? `<p style="margin: 0 0 8px 0; font-size: 14px; color: #f59e0b;">⭐ ${place.rating}/5</p>` : ''}
                ${place.nationalPhoneNumber ? `<p style="margin: 0 0 8px 0; font-size: 14px; color: #6b7280;">📞 ${place.nationalPhoneNumber}</p>` : ''}
                ${place.websiteUri ? `<a href="${place.websiteUri}" target="_blank" style="color: #3b82f6; text-decoration: none;">Visit Website →</a>` : ''}
            </div>
        `;
    }

    function showPlaceDetails(place) {
        if (placeDetailsElement) {
            placeDetailsElement.classList.remove('hidden');
            // You can customize this to show more detailed information
            console.log('Place details:', place);
        }
    }

    function addSampleMedicalCenters(location, city = 'Barcelona') {
        // Sample medical centers based on city/location
        let sampleCenters = [];
        
        if (city.toLowerCase().includes('barcelona')) {
            sampleCenters = [
                { 
                    name: "Hospital Clínic de Barcelona", 
                    lat: 41.3881, 
                    lng: 2.1598, 
                    address: "Carrer de Villarroel, 170, 08036 Barcelona", 
                    rating: 4.6 
                },
                { 
                    name: "Hospital Sant Pau", 
                    lat: 41.4114, 
                    lng: 2.1748, 
                    address: "Carrer de Sant Antoni Maria Claret, 167, 08025 Barcelona", 
                    rating: 4.4 
                },
                { 
                    name: "Centro Médico Teknon", 
                    lat: 41.4019, 
                    lng: 2.1325, 
                    address: "Carrer de Vilana, 12, 08022 Barcelona", 
                    rating: 4.5 
                }
            ];
        } else {
            // Generic sample centers around user's location
            sampleCenters = [
                { 
                    name: `${city} Medical Center`, 
                    lat: location.lat + 0.01, 
                    lng: location.lng + 0.01, 
                    address: `Downtown ${city}`, 
                    rating: 4.5 
                },
                { 
                    name: `${city} Family Health Clinic`, 
                    lat: location.lat - 0.01, 
                    lng: location.lng + 0.02, 
                    address: `North ${city}`, 
                    rating: 4.2 
                },
                { 
                    name: `${city} General Hospital`, 
                    lat: location.lat + 0.02, 
                    lng: location.lng - 0.01, 
                    address: `East ${city}`, 
                    rating: 4.7 
                }
            ];
        }

        sampleCenters.forEach((center, index) => {
            createSampleMarker(center, index + 1);
        });
    }

    function updateLocationDisplay(userLocation) {
        const locationDisplay = document.getElementById('location-display');
        if (locationDisplay) {
            const city = userLocation.city || 'Unknown';
            const country = userLocation.country || 'Unknown';
            locationDisplay.innerHTML = `
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-3 mb-4">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        <div>
                            <p class="text-sm font-medium text-blue-900">Showing medical centers near you</p>
                            <p class="text-xs text-blue-700">📍 ${city}, ${country}</p>
                        </div>
                    </div>
                </div>
            `;
        }
    }

    async function createSampleMarker(center, index) {
        const { AdvancedMarkerElement } = await google.maps.importLibrary("marker");
        
        const marker = new AdvancedMarkerElement({
            map: map,
            position: { lat: center.lat, lng: center.lng },
            title: center.name,
            content: createMarkerElement(index)
        });

        const infoWindow = new google.maps.InfoWindow({
            content: `
                <div style="padding: 12px; max-width: 250px;">
                    <h3 style="margin: 0 0 8px 0; font-size: 16px; font-weight: bold; color: #1f2937;">${center.name}</h3>
                    <p style="margin: 0 0 8px 0; font-size: 14px; color: #6b7280;">${center.address}</p>
                    <p style="margin: 0 0 8px 0; font-size: 14px; color: #f59e0b;">⭐ ${center.rating}/5</p>
                    <button onclick="window.location.href='/cabinets'" style="
                        background: #3b82f6;
                        color: white;
                        border: none;
                        padding: 6px 12px;
                        border-radius: 4px;
                        cursor: pointer;
                        font-size: 12px;
                    ">View Details</button>
                </div>
            `
        });

        marker.addListener('click', () => {
            infoWindow.open(map, marker);
        });

        markers.push(marker);
    }

    // Initialize the map when the page loads
    window.addEventListener('load', initMap);
</script>

<style>
    .medical-marker:hover div {
        transform: scale(1.1);
        transition: transform 0.2s ease;
    }
    
    gmp-place-details-compact {
        margin-top: 16px;
        border-radius: 8px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    }
</style>