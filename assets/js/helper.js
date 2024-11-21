$(document).ready(function() {
    console.log('Script is running'); // Untuk memastikan script berjalan

    // handle validasi field input login
    $('#loginForm').on('submit', function (e) {
        e.preventDefault(); // Mencegah form submit secara default
    
        // Mengambil nilai input
        var email = $('#email').val().trim();
        var password = $('#password').val().trim();
    
        // Menghapus pesan error sebelumnya
        $('#emailError').text('');
        $('#passwordError').text('');
    
        // Validasi client-side
        var errors = {};
        if (email === '') {
            errors.email = 'Email is required.';
        }
        if (password === '') {
            errors.password = 'Password is required.';
        }
    
        // Jika ada error, tampilkan di form
        if (Object.keys(errors).length > 0) {
            if (errors.email) {
                $('#emailError').text(errors.email);
            }
            if (errors.password) {
                $('#passwordError').text(errors.password);
            }
            return; // Hentikan proses jika ada error
        }
    
        // Mengirim data ke login_process.php dengan AJAX
        $.ajax({
            url: '/belajar-web-native/auth/login_process.php',
            type: 'POST',
            contentType: 'application/json',
            data: JSON.stringify({ email: email, password: password }),
            success: function (response) {
                if (response.success == true) {
                    if (response.role === 'admin') {
                        window.location.href = '/belajar-web-native/views/admin/dashboard.php'; // Ganti sesuai dengan halaman admin
                    } else {
                        window.location.href = '/belajar-web-native/views/users/dashboard.php'; // Ganti sesuai dengan halaman user
                    }
                }
            },
            error: function (response) {
                if (response.responseJSON.errors.email) {
                    $('#emailError').text(response.responseJSON.errors.email);
                }
                if (response.responseJSON.errors.password) {
                    $('#passwordError').text(response.responseJSON.errors.password);
                }
            }
        });
    });
    
    // Fungsi untuk memuat konten halaman tanpa reload
    function loadPage(url) {
        $.ajax({
            url: url,
            type: 'GET',
            success: function (data) {
                // Mengganti konten utama halaman (misalnya, bagian dengan id #main-content)
                $('#main-content').html(data); // Pastikan elemen ini ada di halaman Anda
                
                // Perbarui URL tanpa reload halaman
                history.pushState(null, '', url);
            },
        });
    }

    $('#registerForm').on('submit', function(event) {
        event.preventDefault();

        const username = $('#username').val().trim();
        const email = $('#email').val().trim();
        const password = $('#password').val().trim();
        const confirmPassword = $('#confirmPassword').val().trim();

        $('.error-message').text('');

        let hasError = false;

        if (username === '') {
            $('#usernameError').text('Username is wa.');
            hasError = true;
        }

        if (email === '') {
            $('#emailError').text('Email is required.');
            hasError = true;
        } else if (!validateEmail(email)) {
            $('#emailError').text('Invalid email format.');
            hasError = true;
        }

        if (password === '') {
            $('#passwordError').text('Password is required.');
            hasError = true;
        }

        if (confirmPassword === '') {
            $('#confirmPasswordError').text('Confirm Password is required.');
            hasError = true;
        } else if (password !== confirmPassword) {
            $('#confirmPasswordError').text('Passwords do not match.');
            hasError = true;
        }

        if (hasError) {
            return;
        }

        $.ajax({
            url: '/belajar-web-native/auth/register_process.php',
            method: 'POST',
            contentType: 'application/json',
            data: JSON.stringify({ username, email, password, confirmPassword }),
            success: function(response) {
                if (response.success == true) {
                    $('#registerForm')[0].reset();
                    window.location.href = '/belajar-web-native/views/users/dashboard.php';
                } 
                $('#emailError').text(response.errors);
            },
        });
    });

    

    $('#userDropdown').on('click', function(event) {
        console.log('Dropdown clicked'); // Cek apakah tombol diklik
        event.preventDefault();
        $('#dropdownMenu').toggle(); // Menggunakan jQuery toggle
    });

    $(document).on('click', function(event) {
        if (!$(event.target).closest('#userDropdown').length && !$(event.target).closest('#dropdownMenu').length) {
            $('#dropdownMenu').hide(); // Menutup dropdown jika klik di luar
        }
    });

    function validateEmail(email) {
        const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return re.test(email);
    }
});

// password