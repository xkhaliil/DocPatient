<div class="w-full">
    <h3 class="text-sm font-semibold uppercase tracking-wider mb-4 text-blue-200">
        Medical Centers Near You
    </h3>
    <div id="location-display" class="mb-4"></div>
    <div id="error-display" class="mb-4 hidden"></div>
    <div class="bg-white rounded-lg shadow-xl overflow-hidden">
        <div id="map" class="h-[300px] w-full"></div>
        <div id="map-loading" class="h-[300px] w-full flex items-center justify-center bg-gray-50 hidden">
            <div class="text-center">
                <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600 mx-auto mb-4"></div>
                <p class="text-gray-600">Loading map...</p>
            </div>
        </div>
        <div id="map-error" class="h-[300px] w-full flex items-center justify-center bg-gray-50 hidden">
            <div class="text-center p-6">
                <svg class="w-16 h-16 text-red-500 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                </svg>
                <h3 class="text-lg font-semibold text-gray-800 mb-2">Map Loading Error</h3>
                <p class="text-gray-600 mb-4" id="map-error-message">Unable to load the map at this time.</p>
                <button id="retry-map" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors">
                    Try Again
                </button>
            </div>
        </div>
        <gmp-place-details-compact orientation="horizontal" class="hidden">
            <gmp-place-details-place-request place=""></gmp-place-details-place-request>
            <gmp-place-all-content></gmp-place-all-content>
        </gmp-place-details-compact>
    </div>
</div>

<script>
    // Enhanced Google Maps API Component with comprehensive error handling
    class MedicalCentersMap {
        constructor() {
            this.map = null;
            this.markers = [];
            this.placeDetailsElement = null;
            this.userLocation = null;
            this.userCoords = null;
            this.maxRetries = 3;
            this.retryDelay = 1000; // milliseconds
            this.apiKey = "{{ env('GOOGLE_MAPS_API_KEY') }}";
            this.libraries = ["places", "marker"];
            this.sampleMedicalCenters = null;
            
            this.init();
        }

        async init() {
            try {
                this.showLoadingState();
                await this.loadGoogleMapsAPI();
                await this.initializeMap();
                this.hideLoadingState();
            } catch (error) {
                console.error('Failed to initialize medical centers map:', error);
                this.handleInitializationError(error);
            }
        }

        /**
         * Load Google Maps API with retry mechanism
         */
        async loadGoogleMapsAPI() {
            if (typeof google !== 'undefined' && google.maps) {
                return; // Already loaded
            }

            for (let attempt = 1; attempt <= this.maxRetries; attempt++) {
                try {
                    await this.loadGoogleMapsScript();
                    return;
                } catch (error) {
                    console.warn(`Google Maps API load attempt ${attempt} failed:`, error.message);
                    
                    if (attempt === this.maxRetries) {
                        throw new Error(`Failed to load Google Maps API after ${this.maxRetries} attempts: ${error.message}`);
                    }
                    
                    // Wait before retry with exponential backoff
                    await this.delay(this.retryDelay * attempt);
                }
            }
        }

        /**
         * Load Google Maps script dynamically
         */
        loadGoogleMapsScript() {
            return new Promise((resolve, reject) => {
                if (document.querySelector('script[src*="maps.googleapis.com"]')) {
                    resolve();
                    return;
                }

                const script = document.createElement('script');
                script.src = `https://maps.googleapis.com/maps/api/js?key=${this.apiKey}&v=weekly&libraries=${this.libraries.join(',')}&callback=googleMapsLoaded`;
                script.async = true;
                script.defer = true;
                
                script.onerror = () => {
                    reject(new Error('Failed to load Google Maps script'));
                };

                // Set up global callback
                window.googleMapsLoaded = () => {
                    delete window.googleMapsLoaded;
                    resolve();
                };

                document.head.appendChild(script);
            });
        }

        /**
         * Initialize the map with comprehensive error handling
         */
        async initializeMap() {
            try {
                // Get user location with fallback
                this.userLocation = await this.getUserLocationWithFallback();
                this.userCoords = this.getUserCoordinates();

                // Update UI
                this.updateLocationDisplay(this.userLocation);

                // Initialize map
                await this.createMap();

                // Search for medical centers
                await this.searchMedicalCenters();

                // Initialize place details
                this.placeDetailsElement = document.querySelector('gmp-place-details-compact');

            } catch (error) {
                console.error('Map initialization error:', error);
                throw error;
            }
        }

        /**
         * Get user location with multiple fallback strategies
         */
        async getUserLocationWithFallback() {
            try {
                // Try to get location from data attribute
                const userLocationElement = document.querySelector('[data-user-location]');
                if (userLocationElement) {
                    try {
                        const locationData = JSON.parse(userLocationElement.dataset.userLocation);
                        if (this.isValidLocation(locationData)) {
                            return locationData;
                        }
                    } catch (parseError) {
                        console.warn('Failed to parse user location data:', parseError);
                    }
                }

                // Try to get location from browser geolocation
                const browserLocation = await this.getBrowserGeolocation();
                if (browserLocation) {
                    return browserLocation;
                }

                // Fallback to Barcelona
                return this.getFallbackLocation();

            } catch (error) {
                console.warn('All location detection methods failed, using fallback:', error);
                return this.getFallbackLocation();
            }
        }

        /**
         * Validate location data
         */
        isValidLocation(location) {
            return location && 
                   (location.latitude || location.lat) && 
                   (location.longitude || location.lng) &&
                   this.isValidCoordinate(location.latitude || location.lat) &&
                   this.isValidCoordinate(location.longitude || location.lng, true);
        }

        /**
         * Validate coordinate values
         */
        isValidCoordinate(value, isLongitude = false) {
            const num = parseFloat(value);
            if (isNaN(num)) return false;
            
            const min = isLongitude ? -180 : -90;
            const max = isLongitude ? 180 : 90;
            return num >= min && num <= max;
        }

        /**
         * Get browser geolocation with timeout and error handling
         */
        getBrowserGeolocation() {
            return new Promise((resolve) => {
                if (!navigator.geolocation) {
                    console.warn('Geolocation not supported');
                    resolve(null);
                    return;
                }

                const options = {
                    enableHighAccuracy: false,
                    timeout: 5000,
                    maximumAge: 300000 // 5 minutes
                };

                navigator.geolocation.getCurrentPosition(
                    (position) => {
                        resolve({
                            latitude: position.coords.latitude,
                            longitude: position.coords.longitude,
                            city: 'Current Location',
                            country: 'Unknown',
                            source: 'browser_geolocation'
                        });
                    },
                    (error) => {
                        console.warn('Geolocation error:', error.message);
                        resolve(null);
                    },
                    options
                );

                // Timeout fallback
                setTimeout(() => {
                    console.warn('Geolocation timeout');
                    resolve(null);
                }, options.timeout + 1000);
            });
        }

        /**
         * Get fallback location (Barcelona)
         */
        getFallbackLocation() {
            return {
                latitude: 41.3851,
                longitude: 2.1734,
                city: 'Barcelona',
                country: 'Spain',
                source: 'fallback'
            };
        }

        /**
         * Get user coordinates from location data
         */
        getUserCoordinates() {
            return {
                lat: parseFloat(this.userLocation.latitude || this.userLocation.lat),
                lng: parseFloat(this.userLocation.longitude || this.userLocation.lng)
            };
        }

        /**
         * Create the map with error handling
         */
        async createMap() {
            try {
                const { Map } = await google.maps.importLibrary("maps");
                
                this.map = new Map(document.getElementById("map"), {
                    center: this.userCoords,
                    zoom: 13,
                    mapId: "user_location_medical_centers_map_enhanced",
                    mapTypeControl: false,
                    streetViewControl: false,
                    fullscreenControl: true,
                    zoomControl: true,
                    mapTypeId: 'roadmap'
                });

                // Add map load error listener
                this.map.addListener('error', (error) => {
                    console.error('Map load error:', error);
                    this.handleMapError('Failed to load map tiles');
                });

            } catch (error) {
                console.error('Failed to create map:', error);
                throw new Error(`Map creation failed: ${error.message}`);
            }
        }

        /**
         * Search for medical centers with comprehensive error handling
         */
        async searchMedicalCenters() {
            try {
                const { Place } = await google.maps.importLibrary("places");
                
                // Build search request with error handling
                const request = this.buildSearchRequest();
                
                // Search with timeout and retry
                const places = await this.searchWithTimeout(Place, request);
                
                if (places && places.length > 0) {
                    await this.displayMedicalCenters(places);
                } else {
                    console.warn('No medical centers found, using sample data');
                    await this.displaySampleMedicalCenters();
                }

            } catch (error) {
                console.error('Medical centers search failed:', error);
                await this.displaySampleMedicalCenters();
            }
        }

        /**
         * Build search request with validation
         */
        buildSearchRequest() {
            const city = this.userLocation.city || 'Barcelona';
            
            return {
                textQuery: `medical center hospital clinic doctor ${city}`,
                fields: ['displayName', 'location', 'formattedAddress', 'rating', 'websiteUri', 'nationalPhoneNumber'],
                locationRestriction: {
                    center: this.userCoords,
                    radius: 10000 // 10km radius
                },
                includedPrimaryTypes: ['hospital', 'doctor', 'health', 'medical_care', 'clinic'],
                maxResultCount: 10,
                language: 'en'
            };
        }

        /**
         * Search with timeout and retry mechanism
         */
        async searchWithTimeout(Place, request) {
            return new Promise((resolve, reject) => {
                const timeoutId = setTimeout(() => {
                    reject(new Error('Places API search timeout'));
                }, 10000); // 10 second timeout

                Place.searchByText(request)
                    .then(result => {
                        clearTimeout(timeoutId);
                        resolve(result.places || []);
                    })
                    .catch(error => {
                        clearTimeout(timeoutId);
                        reject(error);
                    });
            });
        }

        /**
         * Display medical centers on map
         */
        async displayMedicalCenters(places) {
            try {
                // Clear existing markers
                this.clearMarkers();

                // Create markers for each place
                for (let i = 0; i < places.length; i++) {
                    const place = places[i];
                    if (place.location) {
                        await this.createMedicalCenterMarker(place, i);
                    }
                }

                console.log(`Displayed ${places.length} medical centers`);

            } catch (error) {
                console.error('Failed to display medical centers:', error);
                throw error;
            }
        }

        /**
         * Create medical center marker with error handling
         */
        async createMedicalCenterMarker(place, index) {
            try {
                const { AdvancedMarkerElement } = await google.maps.importLibrary("marker");
                
                const marker = new AdvancedMarkerElement({
                    map: this.map,
                    position: place.location,
                    title: place.displayName || 'Medical Center',
                    content: this.createMarkerContent(index + 1)
                });

                // Create info window
                const infoWindow = new google.maps.InfoWindow({
                    content: this.createInfoWindowContent(place)
                });

                // Add click listener
                marker.addListener('click', () => {
                    infoWindow.open(this.map, marker);
                    this.showPlaceDetails(place);
                });

                this.markers.push(marker);

            } catch (error) {
                console.error('Failed to create marker:', error);
                // Continue with other markers
            }
        }

        /**
         * Display sample medical centers when API fails
         */
        async displaySampleMedicalCenters() {
            try {
                this.clearMarkers();
                
                const sampleCenters = this.getSampleMedicalCenters();
                
                for (let i = 0; i < sampleCenters.length; i++) {
                    const center = sampleCenters[i];
                    await this.createSampleMarker(center, i + 1);
                }

                console.log('Displayed sample medical centers as fallback');

            } catch (error) {
                console.error('Failed to display sample medical centers:', error);
                this.showError('Unable to display medical centers. Please refresh the page.');
            }
        }

        /**
         * Get sample medical centers based on location
         */
        getSampleMedicalCenters() {
            const city = this.userLocation.city || 'Barcelona';
            const baseCoords = this.userCoords;
            
            return [
                {
                    name: `${city} Medical Center`,
                    lat: baseCoords.lat + 0.01,
                    lng: baseCoords.lng - 0.02,
                    address: `Central ${city}`,
                    rating: 4.5
                },
                {
                    name: `${city} General Hospital`,
                    lat: baseCoords.lat - 0.01,
                    lng: baseCoords.lng + 0.02,
                    address: `North ${city}`,
                    rating: 4.2
                },
                {
                    name: `${city} Health Clinic`,
                    lat: baseCoords.lat + 0.02,
                    lng: baseCoords.lng - 0.01,
                    address: `East ${city}`,
                    rating: 4.7
                },
                {
                    name: `${city} Family Practice`,
                    lat: baseCoords.lat - 0.02,
                    lng: baseCoords.lng + 0.01,
                    address: `West ${city}`,
                    rating: 4.3
                }
            ];
        }

        /**
         * Create sample marker
         */
        async createSampleMarker(center, number) {
            try {
                const { AdvancedMarkerElement } = await google.maps.importLibrary("marker");
                
                const marker = new AdvancedMarkerElement({
                    map: this.map,
                    position: { lat: center.lat, lng: center.lng },
                    title: center.name,
                    content: this.createMarkerContent(number)
                });

                const infoWindow = new google.maps.InfoWindow({
                    content: this.createSampleInfoWindowContent(center)
                });

                marker.addListener('click', () => {
                    infoWindow.open(this.map, marker);
                });

                this.markers.push(marker);

            } catch (error) {
                console.error('Failed to create sample marker:', error);
            }
        }

        /**
         * Utility methods
         */
        createMarkerContent(number) {
            const div = document.createElement('div');
            div.innerHTML = `
                <div style="
                    background: #3b82f6;
                    color: white;
                    border-radius: 50%;
                    width: 30px;
                    height: 30px;
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

        createInfoWindowContent(place) {
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

        createSampleInfoWindowContent(center) {
            return `
                <div style="padding: 12px; max-width: 250px;">
                    <h3 style="margin: 0 0 8px 0; font-size: 16px; font-weight: bold; color: #1f2937;">${center.name}</h3>
                    <p style="margin: 0 0 8px 0; font-size: 14px; color: #6b7280;">${center.address}</p>
                    ${center.rating ? `<p style="margin: 0 0 8px 0; font-size: 14px; color: #f59e0b;">⭐ ${center.rating}/5</p>` : ''}
                    <p style="margin: 0; font-size: 12px; color: #9ca3af;">Sample location</p>
                </div>
            `;
        }

        updateLocationDisplay(userLocation) {
            const locationDisplay = document.getElementById('location-display');
            if (locationDisplay) {
                const city = userLocation.city || 'Unknown';
                const country = userLocation.country || 'Unknown';
                const source = userLocation.source ? ` (${userLocation.source})` : '';
                
                locationDisplay.innerHTML = `
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-3 mb-4">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            <span class="text-sm text-blue-800">
                                Showing medical centers near <strong>${city}, ${country}</strong>${source}
                            </span>
                        </div>
                    </div>
                `;
            }
        }

        showPlaceDetails(place) {
            if (this.placeDetailsElement) {
                this.placeDetailsElement.classList.remove('hidden');
                console.log('Place details:', place);
            }
        }

        clearMarkers() {
            this.markers.forEach(marker => {
                if (marker.map) {
                    marker.map = null;
                }
            });
            this.markers = [];
        }

        showLoadingState() {
            document.getElementById('map-loading').classList.remove('hidden');
            document.getElementById('map').classList.add('hidden');
            document.getElementById('map-error').classList.add('hidden');
        }

        hideLoadingState() {
            document.getElementById('map-loading').classList.add('hidden');
            document.getElementById('map').classList.remove('hidden');
        }

        showError(message) {
            document.getElementById('map-loading').classList.add('hidden');
            document.getElementById('map').classList.add('hidden');
            document.getElementById('map-error').classList.remove('hidden');
            document.getElementById('map-error-message').textContent = message;
        }

        handleInitializationError(error) {
            this.showError('Unable to load the map. Please check your internet connection and try again.');
            console.error('Map initialization failed:', error);
        }

        handleMapError(message) {
            this.showError(message);
        }

        delay(ms) {
            return new Promise(resolve => setTimeout(resolve, ms));
        }
    }

    // Initialize the enhanced map component
    document.addEventListener('DOMContentLoaded', () => {
        // Set up retry button
        const retryButton = document.getElementById('retry-map');
        if (retryButton) {
            retryButton.addEventListener('click', () => {
                // Clear error state and reinitialize
                document.getElementById('map-error').classList.add('hidden');
                new MedicalCentersMap();
            });
        }

        // Initialize map
        try {
            new MedicalCentersMap();
        } catch (error) {
            console.error('Failed to initialize medical centers map:', error);
        }
    });
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

    #map-loading, #map-error {
        min-height: 300px;
    }

    @keyframes spin {
        to {
            transform: rotate(360deg);
        }
    }

    .animate-spin {
        animation: spin 1s linear infinite;
    }
</style>