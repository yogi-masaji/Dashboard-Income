document.addEventListener("DOMContentLoaded", function () {
    const darkModeToggle = document.getElementById('darkModeToggle');
    const body = document.body;

    // Fungsi untuk mengubah warna chart
    // Ini akan mengubah warna default untuk semua chart yang dibuat setelahnya,
    // dan memperbarui chart yang sudah ada.
    const updateChartColors = (isDarkMode) => {
        const newColor = isDarkMode ? '#ecf0f1' : '#000000'; // Warna teks untuk mode gelap dan terang
        const gridColor = isDarkMode ? 'rgba(236, 240, 241, 0.2)' : 'rgba(0, 0, 0, 0.1)'; // Warna garis grid

        // Mengatur warna default global untuk semua chart
        if (typeof Chart !== 'undefined') {
            Chart.defaults.color = newColor;
            
            // Perbarui semua instance chart yang sedang aktif
            for (const id in Chart.instances) {
                const chart = Chart.instances[id];
                
                // Update warna pada legend
                if (chart.options.plugins && chart.options.plugins.legend) {
                    chart.options.plugins.legend.labels.color = newColor;
                }

                // Update warna pada sumbu (ticks dan grid lines)
                if (chart.options.scales) {
                    for (const scaleId in chart.options.scales) {
                        const scale = chart.options.scales[scaleId];
                        if (scale.ticks) {
                            scale.ticks.color = newColor;
                        }
                        if (scale.grid) {
                            scale.grid.color = gridColor;
                        }
                    }
                }
                
                chart.update();
            }
        }
    };

    // Fungsi untuk menerapkan tema
    const applyTheme = (isDarkMode) => {
        if (isDarkMode) {
            body.classList.add("mode-gelap");
            darkModeToggle.checked = true;
        } else {
            body.classList.remove("mode-gelap");
            darkModeToggle.checked = false;
        }
        // Panggil fungsi untuk update warna chart setelah menerapkan tema
        updateChartColors(isDarkMode);
    };

    // Cek tema yang tersimpan di localStorage saat halaman dimuat
    const savedTheme = localStorage.getItem('theme');
    const isDarkModeSaved = savedTheme === 'dark';
    applyTheme(isDarkModeSaved);

    // Tambahkan event listener untuk toggle
    darkModeToggle.addEventListener('change', function () {
        const isDarkMode = this.checked;
        localStorage.setItem('theme', isDarkMode ? 'dark' : 'light');
        applyTheme(isDarkMode);
    });

    // --- Kode lama Anda yang menambahkan class 'dark-mode' ---
    // Class 'dark-mode' dari file asli Anda tetap ditambahkan
    // jika memang dibutuhkan oleh style dasar template Anda.
    // Jika tidak, Anda bisa menghapus baris ini.
    document.body.classList.add("dark-mode");
});
