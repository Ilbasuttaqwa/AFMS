<nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow border-radius-xl" id="navbarBlur"
    data-scroll="true">
    <div class="container-fluid py-2 px-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">

            <h6 class="font-weight-bolder text-dark mb-0">
                Admin
            </h6>
        </nav>

        <ul class="navbar-nav justify-content-end">
            <!-- Real Time Clock -->
            <li class="nav-item me-3 d-flex align-items-center">
                <div class="d-flex flex-column align-items-end">
                    <span id="current-time" class="text-dark font-weight-bold mb-0" style="font-size: 0.875rem;"></span>
                    <span id="current-location" class="text-muted" style="font-size: 0.75rem;"></span>
                </div>
            </li>

            <li class="nav-item me-2">
                <a href="{{ route('logout') }}" class="btn btn-sm btn-outline-primary me-2"
                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="fas fa-sign-out-alt me-1"></i>
                    Logout
                </a>
            </li>
            <li class="nav-item d-xl-none ps-3 pe-0 d-flex align-items-center">
                <a href="javascript:void(0);" class="nav-link text-body p-0" id="iconNavbarSidenav">
                    <div class="sidenav-toggler-inner">
                        <i class="sidenav-toggler-line"></i>
                        <i class="sidenav-toggler-line"></i>
                        <i class="sidenav-toggler-line"></i>
                    </div>
                </a>
            </li>
            <li class="nav-item px-3 d-flex align-items-center">
                <a href="javascript:void(0);" class="nav-link text-body p-0">
                    <i class="fa fa-cog cursor-pointer"></i>
                </a>
            </li>
        </ul>
    </div>
</nav>

<style>
/* Fix navbar visibility saat scroll */
#navbarBlur {
    background-color: rgba(255, 255, 255, 0.95) !important;
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.2);
    transition: all 0.3s ease;
}

/* Memastikan navbar tetap terlihat saat di-scroll dengan class blur */
.navbar-main.navbar-blur,
.navbar-main[data-scroll="true"] {
    background-color: rgba(255, 255, 255, 0.95) !important;
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
}

/* Memastikan text tetap terlihat dengan kontras yang baik */
#navbarBlur .breadcrumb-item a,
#navbarBlur h6,
#navbarBlur .nav-link,
#navbarBlur .text-dark {
    color: #344767 !important;
    opacity: 1 !important;
}

/* Memastikan button logout tetap terlihat */
#navbarBlur .btn-outline-primary {
    border-color: #5e72e4 !important;
    color: #5e72e4 !important;
}

#navbarBlur .btn-outline-primary:hover {
    background-color: #5e72e4 !important;
    color: white !important;
}

/* Styling untuk real time clock */
#current-time {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    letter-spacing: 0.5px;
}

#current-location {
    font-size: 0.75rem !important;
    opacity: 0.8;
}
</style>

<script>
    // Function to update the clock
    function updateClock() {
        const now = new Date();
        const options = {
            timeZone: 'Asia/Jakarta',
            hour12: false,
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit'
        };
        const timeString = now.toLocaleTimeString('id-ID', options);
        document.getElementById('current-time').innerText = timeString;
    }

    // Function to get location and display region name
    async function updateLocation() {
        const locationElement = document.getElementById('current-location');
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                async (position) => {
                    const lat = position.coords.latitude;
                    const lon = position.coords.longitude;

                    // Reverse Geocoding with OpenStreetMap
                    try {
                        const response = await fetch(`https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=${lat}&lon=${lon}`);
                        if (response.ok) {
                            const data = await response.json();
                            const region = data.address.city || data.address.town || data.address.village || "Unknown location";
                            locationElement.innerText = region;
                        } else {
                            locationElement.innerText = "Unable to fetch location";
                        }
                    } catch (error) {
                        locationElement.innerText = "Error fetching location";
                    }
                },
                (error) => {
                    locationElement.innerText = "Lokasi Tidak Dapat Diakses";
                }
            );
        } else {
            locationElement.innerText = "Geolocation not supported";
        }
    }

    // Update the clock every second
    setInterval(updateClock, 1000);
    updateClock(); // Initialize clock immediately

    // Get location on page load
    updateLocation();
</script>
