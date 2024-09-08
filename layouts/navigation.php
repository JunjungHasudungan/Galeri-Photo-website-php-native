<?php
    $configPath = __DIR__ . '/../config/app.php';

// Cek apakah file ada sebelum include
if (file_exists($configPath)) {
    include $configPath;
} else {
    echo "File not found: $configPath";
}

// Pastikan sesi dimulai jika diperlukan
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

    // Cek status login dan role
    $isLoggedIn = isset($_SESSION['logged_in']) && $_SESSION['logged_in'];
    $userName = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : 'Guest';
    $userRole = isset($_SESSION['user_role']) ? $_SESSION['user_role'] : 'user'; 
?>

    <nav class="navbar">
        <ul class="nav-menu">
            <?php if (!$isLoggedIn): ?>
                <li><a href="/belajar-web-native/auth/login.php" class="btn-login">Login</a></li>
                <li><a href="/belajar-web-native/auth/register.php" class="btn-register">Register</a></li>
            <?php else: ?>
                <?php if ($userRole == 'admin'): ?>
                    <li><a href="/belajar-web-native/views/admin/dashboard.php"> Dashbord Admin</a></li>
                    <li><a href="/belajar-web-native/views/admin/index-galeri.php"> Galeri Photo </a>
                    </li>
                <?php else: ?>
                    <li><a href="/belajar-web-native/views/users/dashboard.php">Dashboard User</a></li>
                    <li><a href="/belajar-web-native/views/users/like-galeri.php">Likes Galeri</a></li>
                <?php endif; ?>
                <div id="app">
                    <li class="dropdown" @click="toggleDropdown">
                        <a href="#" class="dropdown-toggle" id="userDropdown">
                            <?= htmlspecialchars($userName); ?> <span>&#9662;</span>
                        </a>
                        <ul :class="{'dropdown-menu': true, 'show': isDropdownVisible}" id="dropdownMenu">
                            <li><a href="profile.php">Profile</a></li>
                            <li>
                                <form id="logout-form" action="/belajar-web-native/auth/logout.php" method="post" style="display: none;">
                                </form>
                                <a href="#" class="btn-logout" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    Logout
                                </a>
                            </li>
                        </ul>
                    </li>
                </div>
            <?php endif; ?>
        </ul>
    </nav>


<script>
    const { createApp, ref, onMounted, onBeforeUnmount } = Vue;

    createApp({
        setup() {
            const userName = '<?= htmlspecialchars($userName); ?>'; // Variabel PHP
            const isDropdownVisible = ref(false);

            function toggleDropdown() {
                isDropdownVisible.value = !isDropdownVisible.value;
            }

            function closeDropdown() {
                isDropdownVisible.value = false;
            }

            function handleClickOutside(event) {
                const dropdownElement = document.getElementById('dropdownMenu');
                const toggleElement = document.getElementById('userDropdown');

                // Jika klik terjadi di luar elemen dropdown atau toggle, tutup dropdown
                if (!dropdownElement.contains(event.target) && !toggleElement.contains(event.target)) {
                    closeDropdown();
                }
            }

            onMounted(() => {
                document.addEventListener('click', handleClickOutside);
            });

            onBeforeUnmount(() => {
                document.removeEventListener('click', handleClickOutside);
            });

            return {
                userName,
                isDropdownVisible,
                toggleDropdown,
                closeDropdown
            };
        }
    }).mount('#app');
</script>
