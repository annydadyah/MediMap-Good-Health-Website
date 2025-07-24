@extends('layouts.app')

@section('content')
<div class="container mx-auto py-8">

    {{-- Leaflet Map Section --}}
    <div class="bg-white shadow rounded-lg p-6 mb-6">
        <h5 class="text-lg font-semibold text-gray-700 mb-4">Healthcare Facility Locations</h5>

        {{-- NEW BUTTON TO ACTIVATE/DEACTIVATE MARKING --}}
        <button id="toggleMarkingButton" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mb-4">
            Activate Mark & Radius Mode
        </button>

        <div id="map" class="h-96 w-full rounded border"></div>
    </div>

    {{-- Summary Section --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <div class="bg-white shadow rounded-lg p-6">
            <h5 class="text-lg font-semibold text-gray-700">Total Hospitals</h5>
            <p class="text-2xl font-bold text-blue-600 mt-2">{{ $hospitals->count() }}</p>
        </div>
        <div class="bg-white shadow rounded-lg p-6">
            <h5 class="text-lg font-semibold text-gray-700">Public Hospitals</h5>
            <p class="text-2xl font-bold text-green-600 mt-2">{{ $publicHospitalsCount }}</p>
        </div>
        <div class="bg-white shadow rounded-lg p-6">
            <h5 class="text-lg font-semibold text-gray-700">Private Hospitals</h5>
            <p class="text-2xl font-bold text-yellow-600 mt-2">{{ $privateHospitalsCount }}</p>
        </div>
        <div class="bg-white shadow rounded-lg p-6">
            <h5 class="text-lg font-semibold text-gray-700">Average Rating</h5>
            <p class="text-2xl font-bold text-purple-600 mt-2">{{ number_format($averageRating, 2) }}</p>
        </div>
    </div>

    {{-- Top Rated Hospitals --}}
    <div class="bg-white shadow rounded-lg p-6">
        <h5 class="text-lg font-semibold text-gray-700 mb-4">Top Rated Hospitals</h5>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            @forelse($topRatedHospitals as $hospital)
                <div class="bg-gray-50 shadow rounded-lg p-4">
                    <h6 class="font-semibold text-gray-700">{{ $hospital->name }}</h6>
                    <p class="text-gray-500 text-sm">{{ $hospital->address }}</p>
                    <div class="flex items-center mt-2">
                        @for($i = 1; $i <= 5; $i++)
                            <i class="fas fa-star {{ $i <= $hospital->reviews_avg_rating ? 'text-yellow-400' : 'text-gray-300' }}"></i>
                        @endfor
                        <span class="ml-2 text-sm text-gray-600">{{ number_format($hospital->reviews_avg_rating, 1) }}</span>
                    </div>
                    <a href="{{ route('hospitals.showDetails', $hospital->id) }}" class="text-blue-600 mt-2 block text-sm">View Details</a>
                </div>
            @empty
                <p class="text-gray-500 col-span-3">No rated hospitals found.</p>
            @endforelse
        </div>
    </div>

</div>
@endsection

@push('scripts')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const mapElement = document.getElementById('map');
        const toggleMarkingButton = document.getElementById('toggleMarkingButton');
        let isMarkingModeActive = false;
        let userMarker = null; // To store the marker created by the user
        let userCircle = null; // To store the radius circle

        if (mapElement) {
            const hospitalsData = @json($hospitals);

            const defaultView = hospitalsData.length > 0 ? [hospitalsData[0].latitude, hospitalsData[0].longitude] : [-2.5489, 118.0149];
            const defaultZoom = hospitalsData.length > 0 ? 13 : 5;
            var map = L.map('map').setView(defaultView, defaultZoom);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors'
            }).addTo(map);

            hospitalsData.forEach(function(hospital) {
                if (hospital.latitude && hospital.longitude) {
                    let reviewsHtml = '';
                    hospital.reviews.forEach(function(review) {
                        reviewsHtml += `<div class="text-sm text-gray-600 border-t mt-1 pt-1">
                                            <p class="mb-0 leading-tight">${review.review.substring(0, 50)}...</p>
                                        </div>`;
                    });

                    if (reviewsHtml === '') {
                        reviewsHtml = '<p class="text-sm text-gray-500 mt-1">No reviews yet.</p>';
                    }

                    var popupContent = `<div class="p-1" style="max-width: 280px;">
                                            <strong class="text-base leading-tight block">${hospital.name}</strong>
                                            <p class="text-xs text-gray-600 mb-2">${hospital.address}</p>
                                            <a href="/health-facilities/${hospital.id}" class="text-blue-600 underline text-sm">View Details</a>
                                            <br><strong class="text-sm mt-2 block">Latest Reviews:</strong>
                                            ${reviewsHtml}
                                        </div>`;

                    L.marker([hospital.latitude, hospital.longitude]).addTo(map).bindPopup(popupContent, { minWidth: 250 });
                }
            });

            // ===============================================
            // Mark & Radius Button Functionality
            // ===============================================

            const onMapClick = function(e) {
                if (isMarkingModeActive) {
                    // Remove previous marker and circle if they exist
                    if (userMarker) {
                        map.removeLayer(userMarker);
                    }
                    if (userCircle) {
                        map.removeLayer(userCircle);
                    }

                    // Get click coordinates
                    var lat = e.latlng.lat;
                    var lng = e.latlng.lng;

                    // Create new marker
                    userMarker = L.marker([lat, lng]).addTo(map)
                        .bindPopup("Your Point: " + lat.toFixed(4) + ", " + lng.toFixed(4))
                        .openPopup();

                    // Create a circle with a 3 km radius (3000 meters)
                    userCircle = L.circle([lat, lng], {
                        color: 'red',
                        fillColor: '#f03',
                        fillOpacity: 0.2,
                        radius: 3000 // Radius in meters (3 km)
                    }).addTo(map);

                    console.log("Marker and circle added at:", lat, lng);
                }
            };

            toggleMarkingButton.addEventListener('click', function() {
                isMarkingModeActive = !isMarkingModeActive; // Toggle marking mode status

                if (isMarkingModeActive) {
                    map.on('click', onMapClick); // Activate map click event listener
                    toggleMarkingButton.textContent = 'Deactivate Mark & Radius Mode';
                    toggleMarkingButton.classList.remove('bg-blue-500', 'hover:bg-blue-700');
                    toggleMarkingButton.classList.add('bg-red-500', 'hover:bg-red-700');
                    alert('Mark & Radius Mode ACTIVE! Click on the map to mark a location.');
                } else {
                    map.off('click', onMapClick); // Deactivate map click event listener
                    toggleMarkingButton.textContent = 'Activate Mark & Radius Mode';
                    toggleMarkingButton.classList.remove('bg-red-500', 'hover:bg-red-700');
                    toggleMarkingButton.classList.add('bg-blue-500', 'hover:bg-blue-700');
                    alert('Mark & Radius Mode INACTIVE. Click the button to reactivate.');

                    // Remove user marker and circle when mode is deactivated
                    if (userMarker) {
                        map.removeLayer(userMarker);
                        userMarker = null;
                    }
                    if (userCircle) {
                        map.removeLayer(userCircle);
                        userCircle = null;
                    }
                }
            });
        }
    });
</script>
@endpush