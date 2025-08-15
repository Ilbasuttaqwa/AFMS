<!DOCTYPE html>
<html lang="en" class="light-style layout-menu-fixed" dir="ltr" data-theme="theme-default"
    data-assets-path="assets/" data-template="vertical-menu-template-free">

<head>
    <meta charset="utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>AFMS</title>

    <meta name="description" content="" />

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" w
        href="https://iili.io/2rOi5VR.th.png"
        class="rounded-circle" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
        rel="stylesheet" />

    <!-- Icons. Uncomment required icon fonts -->
    <link rel="stylesheet" href="<?php echo e(asset('admin/assets/vendor/fonts/boxicons.css')); ?>" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="<?php echo e(asset('admin/assets/vendor/css/core.css')); ?>"
        class="template-customizer-core-css" />
    <link rel="stylesheet" href="<?php echo e(asset('admin/assets/vendor/css/theme-default.css')); ?>"
        class="template-customizer-theme-css" />
    <link rel="stylesheet" href="<?php echo e(asset('admin/assets/css/demo.css')); ?>" />

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="<?php echo e(asset('admin/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css')); ?>" />

    <link rel="stylesheet" href="<?php echo e(asset('admin/assets/vendor/libs/apex-charts/apex-charts.css')); ?>" />

    <!-- Page CSS -->

    <!-- Helpers -->
    <script src="<?php echo e(asset('admin/assets/vendor/js/helpers.js')); ?>"></script>

    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
    <script src="<?php echo e(asset('admin/assets/js/config.js')); ?>"></script>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        .toast-container {
            z-index: 9999 !important;
        }
    </style>

    <?php echo $__env->yieldContent('css'); ?>
</head>

<body>
    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">
            <!-- BAGIAN SIDEBAR -->

            <?php echo $__env->make('include.admin.sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

            <!-- / BAGIAN SIDEBAR -->

            <!-- Layout container -->
            <div class="layout-page">
                <!-- INI BAGIAN HEADER -->

                <?php echo $__env->make('include.admin.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

                <!-- / INI BAGIAN HEADER -->

                <!-- Content wrapper -->
                <div class="content-wrapper">
                    <!-- Content -->
                    <?php echo $__env->yieldContent('content'); ?>
                    <!-- / Content -->

                    <!-- Footer -->
                    <?php echo $__env->make('include.admin.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                    <!-- / Footer -->
                </div>
                <!-- Content wrapper -->
            </div>
            <!-- / Layout page -->
        </div>

        <!-- Overlay -->
        <div class="layout-overlay layout-menu-toggle"></div>
    </div>
    <!-- / Layout wrapper -->
    <!-- Core JS -->
    <!-- build:js assets/vendor/js/core.js -->
    <script src="<?php echo e(asset('admin/assets/vendor/libs/jquery/jquery.js')); ?>"></script>
    <script src="<?php echo e(asset('admin/assets/vendor/libs/popper/popper.js')); ?>"></script>
    <script src="<?php echo e(asset('admin/assets/vendor/js/bootstrap.js')); ?>"></script>
    <script src="<?php echo e(asset('admin/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js')); ?>"></script>

    <script src="<?php echo e(asset('admin/assets/vendor/js/menu.js')); ?>"></script>
    <!-- endbuild -->

    <!-- Vendors JS -->
    <script src="<?php echo e(asset('admin/assets/vendor/libs/apex-charts/apexcharts.js')); ?>"></script>

    <!-- Main JS -->
    <script src="<?php echo e(asset('admin/assets/js/main.js')); ?>"></script>

    <!-- Page JS -->
    <script src="<?php echo e(asset('admin/assets/js/dashboards-analytics.js')); ?>"></script>

    <!-- Place this tag in your head or just before your close body tag. -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>

    <?php echo $__env->make('sweetalert::alert', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <?php echo $__env->yieldPushContent('scripts'); ?>

    <?php echo $__env->yieldContent('js'); ?>


    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const toastSuccess = document.getElementById('toastSuccess');
            const toastError = document.getElementById('toastError');

            if (toastSuccess) {
                setTimeout(function() {
                    toastSuccess.classList.add('toast-hide');
                }, 2000);
            }

            if (toastError) {
                setTimeout(function() {
                    toastError.classList.add('toast-hide');
                }, 2000);
            }
        });
    </script>


    <script>
        document.addEventListener('DOMContentLoaded', function() {
            <?php if ($errors->any()): ?>
                var toastEl = document.getElementById('validationToast');
                var toast = new bootstrap.Toast(toastEl, {
                    delay: 2000
                });
                toast.show();

                toastEl.addEventListener('hidden.bs.toast', function() {
                    toastEl.classList.add('hide'); // Tambahkan kelas hide untuk animasi keluar
                });

                setTimeout(function() {
                    toastEl.classList.remove('hide');
                }, 2000); // Waktu 2 detik sebelum menghapus kelas hide
            <?php endif; ?>
        });
    </script>


    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Dapatkan URL saat ini
            const currentUrl = window.location.href;
            console.log('Current URL:', currentUrl);

            // Ambil semua item menu
            const menuItems = document.querySelectorAll('.menu-item');

            // Tandai item menu yang sesuai
            menuItems.forEach(item => {
                const route = item.getAttribute('data-route');
                console.log('Menu item route:', route);
                if (route === currentUrl) {
                    item.classList.add('active');
                }
            });
        });
    </script>



    <script>
        document.getElementById('pegawai').addEventListener('change', function() {
            var selectedOption = this.options[this.selectedIndex];
            var jabatan = selectedOption.getAttribute('data-jabatan');

            // Set value di dropdown jabatan
            var jabatanDropdown = document.getElementById('jabatan');
            jabatanDropdown.innerHTML = ''; // Kosongkan dulu

            // Tambahkan opsi jabatan yang sesuai
            if (jabatan) {
                var option = document.createElement('option');
                option.value = jabatan;
                option.text = jabatan;
                jabatanDropdown.appendChild(option);
            }
        });
    </script>


    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const pdfButton = document.getElementById('lihatPdfButton');
            const pdfFrame = document.getElementById('pdfFrame');

            pdfButton.addEventListener('click', function() {
                const tanggalAwal = document.querySelector('input[name="tanggal_awal"]').value;
                const tanggalAkhir = document.querySelector('input[name="tanggal_akhir"]').value;
                const pdfUrl =
                    `<?php echo e(route('laporan.pegawai', ['pdf' => true])); ?>&tanggal_awal=${tanggalAwal}&tanggal_akhir=${tanggalAkhir}`;

                // Set URL PDF ke iframe
                pdfFrame.src = pdfUrl;

                // Tampilkan modal
                $('#pdfModal').modal('show');
            });
        });
    </script>


    <script>
        function addField() {
            const container = document.getElementById('additionalFields');
            const inputGroup = document.createElement('div');
            inputGroup.classList.add('mb-3');
            inputGroup.innerHTML = `
            <input type="text" name="additional_fields[]" class="form-control mt-2" placeholder="Nama Jabatan Tambahan" required>
        `;
            container.appendChild(inputGroup);
        }
    </script>

</body>

</html>
<?php /**PATH C:\laragon\www\dashboard-fingerprint\resources\views/layouts/admin/template.blade.php ENDPATH**/ ?>
