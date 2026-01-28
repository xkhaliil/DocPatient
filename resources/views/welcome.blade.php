<x-site-layout title="Welcome">
    
    {{-- Hero Section with News and Map --}}
    <section class="bg-gradient-to-br from-blue-600 via-blue-700 to-blue-900 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid lg:grid-cols-2 gap-8 items-start">
                {{-- News Card --}}
                <div class="w-full">
                    <h3 class="text-sm font-semibold uppercase tracking-wider mb-4 text-blue-200">
                        Latest Healthcare News
                    </h3>
                    <article id="newsCard" class="bg-white rounded-lg shadow-xl overflow-hidden hover:shadow-2xl transition-shadow duration-500">
                        <div class="p-6">
                            <div id="newsLoading" class="flex items-center justify-center py-8">
                                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
                                <span class="ml-3 text-sm text-gray-500">Loading news...</span>
                            </div>
                            <div id="newsContent" class="hidden">
                                <div class="flex items-center gap-3 mb-4 flex-wrap">
                                    <span id="newsSource" class="text-xs font-medium text-blue-600 bg-blue-50 px-3 py-1 rounded-full">
                                        Source Name
                                    </span>
                                    <span id="newsDate" class="text-xs text-gray-500">
                                        Jan 25, 2026
                                    </span>
                                </div>
                                <h2 id="newsTitle" class="text-2xl font-bold text-gray-900 mb-4 hover:text-blue-600 cursor-pointer leading-tight">
                                    Article Title Goes Here
                                </h2>
                                <p id="newsDescription" class="text-gray-600 mb-6 line-clamp-3 leading-relaxed">
                                    Article description placeholder
                                </p>
                                <div class="flex items-center justify-between">
                                    <span id="newsAuthor" class="text-sm text-gray-500">
                                        By Author Name
                                    </span>
                                    <a id="newsLink" href="#" target="_blank" 
                                       class="text-sm text-blue-600 hover:text-blue-800 font-medium flex items-center gap-2 group">
                                        Read more 
                                        <span class="group-hover:translate-x-1 transition-transform duration-200">→</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </article>
                </div>

                {{-- Map Section --}}
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
            </div>
        </div>
    </section>

    {{-- Welcome Hero Section --}}
    <section class="bg-gradient-to-br from-slate-50 to-blue-50 py-20">
        <div class="max-w-5xl mx-auto text-center px-6">
            <h1 class="text-5xl md:text-6xl font-bold text-slate-900 mb-6 text-balance">
                Your Health, <span class="text-blue-600">Simplified</span>
            </h1>
            <p class="text-lg md:text-xl text-slate-600 mb-8 text-pretty max-w-3xl mx-auto leading-relaxed">
                Connect with top-rated doctors, explore trusted medical cabinets, and book your appointment in seconds. Healthcare made easy.
            </p>
            <div class="flex flex-wrap justify-center gap-4">
                <a href="/cabinets" class="inline-flex items-center justify-center px-8 py-4 text-base font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:ring-4 focus:ring-blue-200 transition-all duration-200 transform hover:scale-105">
                    <svg class="mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Find a Doctor
                </a>
                <a href="/appointments" class="inline-flex items-center justify-center px-8 py-4 text-base font-medium text-blue-600 bg-white border-2 border-blue-600 rounded-lg hover:bg-blue-50 focus:ring-4 focus:ring-blue-200 transition-all duration-200 transform hover:scale-105">
                    <svg class="mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    View Appointments
                </a>
            </div>
        </div>
    </section>

    {{-- Features Section --}}
    <section class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-3 gap-8">
                {{-- Easy Booking --}}
                <div class="text-center p-6">
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-blue-100 text-blue-600 mb-4">
                        <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold mb-2 text-slate-900">Easy Booking</h3>
                    <p class="text-slate-600 leading-relaxed">
                        Schedule appointments with just a few clicks. No phone calls needed.
                    </p>
                </div>

                {{-- Top Doctors --}}
                <div class="text-center p-6">
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-blue-100 text-blue-600 mb-4">
                        <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold mb-2 text-slate-900">Top Doctors</h3>
                    <p class="text-slate-600 leading-relaxed">
                        Access to verified, highly-rated medical professionals in your area.
                    </p>
                </div>

                {{-- Find Nearby --}}
                <div class="text-center p-6">
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-blue-100 text-blue-600 mb-4">
                        <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold mb-2 text-slate-900">Find Nearby</h3>
                    <p class="text-slate-600 leading-relaxed">
                        Locate medical centers close to you with our interactive map.
                    </p>
                </div>
            </div>
        </div>
    </section>

    {{-- Popular Cabinets Section --}}
    <section class="py-20 bg-gradient-to-br from-slate-50 to-blue-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl md:text-4xl font-bold text-slate-900 mb-4">
                    Most Popular Medical Centers
                </h2>
                <p class="text-lg text-slate-600 max-w-2xl mx-auto">
                    Discover trusted healthcare providers with excellent patient reviews and high appointment volumes.
                </p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($popularCabinets as $cabinet)
                    <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                        {{-- Doctor Picture --}}
                        <div class="p-6 flex items-center">
                            <img
                                src="{{ $cabinet->doctor->getFirstMediaUrl('profile')
                                ?: 'https://ui-avatars.com/api/?size=128&name=' . urlencode($cabinet->doctor->name) }}"
                                class="h-20 w-20 rounded-full object-cover border-4 border-white shadow-lg"
                                alt="{{ $cabinet->doctor->name }}"
                            >
                            <div class="ml-4">
                                <p class="text-xs font-semibold uppercase tracking-wider text-blue-600 mb-1">Doctor</p>
                                <p class="text-lg font-semibold text-slate-900">
                                    {{ $cabinet->doctor->name }}
                                </p>
                                <p class="text-sm text-slate-500">{{ $cabinet->doctor->email }}</p>
                            </div>
                        </div>

                        {{-- Cabinet Content --}}
                        <div class="px-6 pb-6">
                            <h3 class="text-xl font-semibold text-slate-900 mb-3">
                                {{ $cabinet->name }}
                            </h3>

                            @if($cabinet->location)
                                <p class="text-slate-600 text-sm mb-4 flex items-start">
                                    <svg class="h-4 w-4 text-slate-400 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                    {{ Str::limit($cabinet->location, 70) }}
                                </p>
                            @endif

                            {{-- Popularity Badge --}}
                            <div class="flex items-center justify-between mb-4">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-amber-100 text-amber-800">
                                    <svg class="h-3 w-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                    {{ $cabinet->appointments_count }} appointments this month
                                </span>
                            </div>

                            {{-- Buttons --}}
                            <div class="flex justify-between gap-3">
                                <a href="/cabinets/{{ $cabinet->id }}"
                                   class="flex-1 inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-slate-700 bg-slate-100 rounded-lg hover:bg-slate-200 transition-colors duration-200">
                                    View Cabinet
                                </a>

                                <a href="/appointments/create?cabinet_id={{ $cabinet->id }}"
                                   class="flex-1 inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition-colors duration-200">
                                    Book Now
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

{{-- Google Maps JavaScript --}}
<script>
    (g=>{var h,a,k,p="The Google Maps JavaScript API",c="google",l="importLibrary",q="__ib__",m=document,b=window;b=b[c]||(b[c]={});var d=b.maps||(b.maps={}),r=new Set,e=new URLSearchParams,u=()=>h||(h=new Promise(async(f,n)=>{await (a=m.createElement("script"));e.set("libraries",[...r]+"");for(k in g)e.set(k.replace(/[A-Z]/g,t=>"_"+t[0].toLowerCase()),g[k]);e.set("callback",c+".maps."+q);a.src=`https://maps.${c}apis.com/maps/api/js?`+e;d[q]=f;a.onerror=()=>h=n(Error(p+" could not load."));a.nonce=m.querySelector("script[nonce]")?.nonce||"";m.head.append(a)}));d[l]?console.warn(p+" only loads once. Ignoring:",g):d[l]=(f,...n)=>r.add(f)&&u().then(()=>d[l](f,...n))})({
        key: "{{ env('GOOGLE_MAPS_API_KEY') }}",
        v: "weekly",
        libraries: ["places", "marker"]
    });

    let map;
    let markers = [];
    let placeDetailsElement;

    async function initMap() {
        const { Map } = await google.maps.importLibrary("maps");
        const { AdvancedMarkerElement } = await google.maps.importLibrary("marker");
        const { PlaceDetailsElement, PlaceSearchElement } = await google.maps.importLibrary("places");

        // Get user's location from the server
        const userLocation = @json($userLocation);
        
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

</x-site-layout>