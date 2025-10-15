<button id="theme-toggle"
        class="px-3 py-1 bg-gray-200 dark:bg-gray-700 dark:text-gray-200 rounded-lg shadow-md hover:opacity-80 transition">
    🌙 Ganti Mode
</button>

<script>
    // Set tema saat halaman pertama dimuat
    if (localStorage.theme === 'dark' ||
        (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
        document.documentElement.classList.add('dark');
    } else {
        document.documentElement.classList.remove('dark');
    }

    // Event listener untuk tombol toggle
    document.addEventListener('DOMContentLoaded', () => {
        const toggle = document.getElementById('theme-toggle');
        toggle.addEventListener('click', function () {
            document.documentElement.classList.toggle('dark');
            if (document.documentElement.classList.contains('dark')) {
                localStorage.theme = 'dark';
            } else {
                localStorage.theme = 'light';
            }
        });
    });
</script>
