<div class="app-theme-toggle">
    <input type="checkbox" id="appThemeToggle" class="app-theme-toggle__input" hidden>
    <label for="appThemeToggle" class="app-theme-toggle__switch" aria-label="Ganti mode tampilan">
        <span class="app-theme-toggle__icon app-theme-toggle__icon--sun" aria-hidden="true">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                <path d="M12 2.25a.75.75 0 01.75.75v2.25a.75.75 0 01-1.5 0V3a.75.75 0 01.75-.75zM7.5 12a4.5 4.5 0 119 0 4.5 4.5 0 01-9 0zM18.894 6.106a.75.75 0 010 1.06l-1.591 1.59a.75.75 0 11-1.06-1.06l1.59-1.59a.75.75 0 011.06 0zM21.75 12a.75.75 0 01-.75.75h-2.25a.75.75 0 010-1.5H21a.75.75 0 01.75.75zM17.894 17.894a.75.75 0 01-1.06 0l-1.59-1.591a.75.75 0 111.06-1.06l1.59 1.59a.75.75 0 010 1.06zM12 18.75a.75.75 0 01-.75.75v2.25a.75.75 0 011.5 0V19.5a.75.75 0 01-.75-.75zM5.106 17.894a.75.75 0 010-1.06l1.59-1.59a.75.75 0 111.06 1.06l-1.59 1.59a.75.75 0 01-1.06 0zM3 12a.75.75 0 01.75-.75h2.25a.75.75 0 010 1.5H3.75A.75.75 0 013 12zM6.106 5.106a.75.75 0 011.06 0l1.59 1.591a.75.75 0 01-1.06 1.06l-1.59-1.59a.75.75 0 010-1.06z"></path>
            </svg>
        </span>
        <span class="app-theme-toggle__icon app-theme-toggle__icon--moon" aria-hidden="true">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                <path fill-rule="evenodd" d="M9.528 1.718a.75.75 0 01.162.819A8.97 8.97 0 009 6a9 9 0 009 9 8.97 8.97 0 004.463-.69.75.75 0 01.981.981A10.503 10.503 0 0118 19.5 10.5 10.5 0 017.5 9c0-1.563.34-3.056.942-4.432a.75.75 0 01.819-.162z" clip-rule="evenodd"></path>
            </svg>
        </span>
        <span class="app-theme-toggle__thumb"></span>
    </label>
</div>

<script>
(function() {
    const STORAGE_KEY = 'appTheme';
    if (window.__appThemeToggleInit) {
        return;
    }
    window.__appThemeToggleInit = true;

    const body = document.body;
    const checkbox = document.getElementById('appThemeToggle');

    const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
    const getInitialTheme = () => {
        const stored = localStorage.getItem(STORAGE_KEY);
        if (stored === 'light' || stored === 'dark') {
            return stored;
        }
        const landingStored = localStorage.getItem('landingTheme');
        if (landingStored === 'light' || landingStored === 'dark') {
            return landingStored;
        }
        const current = body.dataset.theme;
        if (current === 'light' || current === 'dark') {
            return current;
        }
        return prefersDark ? 'dark' : 'light';
    };

    const applyTheme = (theme) => {
        const safeTheme = theme === 'light' ? 'light' : 'dark';
        body.dataset.theme = safeTheme;
        if (checkbox) {
            checkbox.checked = safeTheme === 'light';
        }
        localStorage.setItem(STORAGE_KEY, safeTheme);
        localStorage.setItem('landingTheme', safeTheme);
        body.dispatchEvent(new CustomEvent('app:theme-changed', { detail: { theme: safeTheme } }));
    };

    const initialTheme = getInitialTheme();
    applyTheme(initialTheme);

    if (checkbox) {
        checkbox.addEventListener('change', () => {
            applyTheme(checkbox.checked ? 'light' : 'dark');
        });
    }
})();
</script>
