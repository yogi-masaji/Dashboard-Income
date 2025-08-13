document.addEventListener("DOMContentLoaded", function () {
    const darkModeToggle = document.getElementById('darkModeToggle');
    const body = document.body;

    /**
     * Globally accessible function to update chart colors.
     * This sets the global defaults for Chart.js and updates any existing instances.
     * This ensures that new charts created by dashboard.js will have the correct theme.
     * @param {boolean} isDarkMode - Whether dark mode is enabled.
     */
    window.updateChartColors = (isDarkMode) => {
        const newColor = isDarkMode ? '#ecf0f1' : '#000000'; // Text color
        const gridColor = isDarkMode ? 'rgba(236, 240, 241, 0.2)' : 'rgba(0, 0, 0, 0.1)'; // Grid line color

        if (typeof Chart !== 'undefined') {
            // Set global defaults. New charts will use these values.
            Chart.defaults.color = newColor;
            Chart.defaults.scale.ticks.color = newColor;
            Chart.defaults.scale.grid.color = gridColor;
            Chart.defaults.plugins.legend.labels.color = newColor;

            // Also, loop through any *currently existing* charts and update them.
            // This is useful for when the user manually clicks the theme toggle.
            for (const id in Chart.instances) {
                const chart = Chart.instances[id];
                
                // Update legend colors
                if (chart.options.plugins && chart.options.plugins.legend) {
                    chart.options.plugins.legend.labels.color = newColor;
                }

                // Update axis (scale) colors
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
                
                // Redraw the chart to apply changes, without animation
                chart.update('none');
            }
        }
    };

    /**
     * Applies the selected theme to the body and saves the preference.
     * @param {boolean} isDarkMode - Whether to apply the dark theme.
     */
    const applyTheme = (isDarkMode) => {
        if (isDarkMode) {
            body.classList.add("mode-gelap");
            darkModeToggle.checked = true;
        } else {
            body.classList.remove("mode-gelap");
            darkModeToggle.checked = false;
        }
        // Call the global function to set chart colors
        window.updateChartColors(isDarkMode);
    };

    // On page load, check for a saved theme in localStorage.
    const savedTheme = localStorage.getItem('theme');
    const isDarkModeSaved = savedTheme === 'dark';
    applyTheme(isDarkModeSaved);

    // Add event listener for the theme toggle switch.
    darkModeToggle.addEventListener('change', function () {
        const isDarkMode = this.checked;
        localStorage.setItem('theme', isDarkMode ? 'dark' : 'light');
        applyTheme(isDarkMode);
    });

    // Your original class, kept for compatibility if needed.
    document.body.classList.add("dark-mode");
});
