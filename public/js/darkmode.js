document.addEventListener("DOMContentLoaded", function () {
    const darkModeToggle = document.getElementById("dark-mode-toggle");
    const moonIcon = document.getElementById("moon-icon");
    const sunIcon = document.getElementById("sun-icon");
    let INTERVAL_ID;

    function applyDarkMode() {
        const darkModeEnabled = localStorage.getItem("darkMode") === "enabled";
        document.body.classList.toggle("dark-mode", darkModeEnabled);
        moonIcon.style.display = darkModeEnabled ? "none" : "inline";
        sunIcon.style.display = darkModeEnabled ? "inline" : "none";

        // Only store dark mode status, remove unnecessary timestamps
        if (darkModeEnabled) {
            localStorage.setItem("darkMode", "enabled");
        } else {
            localStorage.removeItem("darkMode");
        }

        updateChart();
    }

    darkModeToggle.addEventListener("click", function () {
        localStorage.setItem(
            "darkMode",
            document.body.classList.contains("dark-mode") ? "" : "enabled"
        );
        applyDarkMode();
    });

    function updateChart() {
        initChart(
            localStorage.getItem("darkMode") === "enabled" ? "#151B23" : "#fff"
        );
    }

    function initChart(bgColor) {
        const darkModeEnabled = localStorage.getItem("darkMode") === "enabled";
        if (window.chart) window.chart.dispose();

        window.chart = JSC.chart("chartDiv", {
            debug: false,
            type: "gauge",
            box: {
                outline: {
                    color: bgColor,
                },
            },
            animation_duration: 1000,
            legend_visible: false,
            chartArea: {
                fill: bgColor,
                boxVisible: false,
                outline: { opacity: 0 },
                shadow: false,
            },
            xAxis: { spacingPercentage: 0.4 },
            yAxis: {
                defaultTick: {
                    padding: -6,
                    label_style_fontSize: "14px",
                    color: "#696969",
                    label: {
                        color: darkModeEnabled ? "white" : "black",
                    },
                },
                line: { width: 10, color: "smartPalette", breaks_gap: 0.09 },
                scale_range: [0, 240],
                customTicks: [240],
            },
            palette: {
                pointValue: "{%value/240}",
                colors: ["red", "red", "red", "yellow", "yellow", "green"],
            },

            defaultTooltip_enabled: false,
            defaultSeries: {
                angle: { sweep: 180 },
                shape: {
                    innerSize: "70%",
                    label: {
                        text: `<span color="%color">{%sum:n1}</span><br/>
                        <span color="${
                            darkModeEnabled ? "white" : "black"
                        }" fontSize="20px">Volt</span>`,

                        style_fontSize: "46px",
                        verticalAlign: "middle",
                    },
                },
            },
            series: [
                {
                    type: "column roundcaps",
                    points: [{ id: "1", x: "speed", y: 0 }],
                },
            ],
        });
    }

    function setGauge(y) {
        if (window.chart) {
            window.chart
                .series(0)
                .options({ points: [{ id: "1", x: "speed", y }] });
        }
    }

    function update() {
        INTERVAL_ID = setInterval(() => setGauge(getActualData()), 1200);
    }

    function getActualData() {
        return Math.floor(Math.random() * 240);
    }

    applyDarkMode();
    update();
});
