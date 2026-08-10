document.addEventListener("DOMContentLoaded", function () {
    // ==========================================
    // 1. Apply min-width SEBELUM chart init
    // ==========================================
    document.querySelectorAll(".chart-scroll-wrapper").forEach(function (el) {
        const minW = el.dataset.minWidth;
        if (minW) {
            el.style.minWidth = minW + "px";
        }
    });

    // ==========================================
    // 2. Baru parse data & init chart
    // ==========================================
    const raw = document.getElementById("chart-data");
    if (!raw) return;

    const data = JSON.parse(raw.textContent);

    // BAR CHART
    const ctxBar = document.getElementById("stockChart");
    if (ctxBar) {
        new Chart(ctxBar.getContext("2d"), {
            type: "bar",
            data: {
                labels: data.productNames,
                datasets: [
                    {
                        label: "Stock Saat Ini",
                        data: data.productStocks,
                        maxBarThickness: 60,
                        categoryPercentage: 0.6,
                        barPercentage: 0.75,
                        backgroundColor: data.productStocks.map((stock, i) =>
                            stock <= data.productMinimums[i]
                                ? "rgba(239, 68, 68, 0.8)"
                                : "rgba(59, 130, 246, 0.8)",
                        ),
                        borderColor: data.productStocks.map((stock, i) =>
                            stock <= data.productMinimums[i]
                                ? "rgb(239, 68, 68)"
                                : "rgb(59, 130, 246)",
                        ),
                        borderWidth: 1,
                        borderRadius: 6,
                    },
                    {
                        label: "Stock Minimum",
                        data: data.productMinimums,
                        maxBarThickness: 60,
                        categoryPercentage: 0.6,
                        barPercentage: 0.75,
                        backgroundColor: "rgba(156, 163, 175, 0.3)",
                        borderColor: "rgba(156, 163, 175, 1)",
                        borderWidth: 2,
                        borderRadius: 6,
                        borderDash: [5, 5],
                    },
                ],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: { beginAtZero: true, ticks: { precision: 0 } },
                    x: {
                        grid: { display: false },
                        ticks: { autoSkip: false, maxRotation: 45 },
                    },
                },
                plugins: {
                    legend: { position: "top" },
                    tooltip: {
                        callbacks: {
                            afterLabel: function (context) {
                                if (context.datasetIndex === 0) {
                                    const min =
                                        data.productMinimums[context.dataIndex];
                                    const stock = context.raw;
                                    if (stock <= min)
                                        return "⚠️ Di bawah minimum!";
                                    return "✅ Aman";
                                }
                            },
                        },
                    },
                },
            },
        });
    }

    // DOUGHNUT CHART (tetap sama)
    const ctxDoughnut = document.getElementById("stockDoughnut");
    if (ctxDoughnut) {
        new Chart(ctxDoughnut.getContext("2d"), {
            type: "doughnut",
            data: {
                labels: ["Stok Aman", "Stok Kritis", "Stok Habis"],
                datasets: [
                    {
                        data: [
                            data.stockAman,
                            data.stockKritis,
                            data.stockHabis,
                        ],
                        backgroundColor: [
                            "rgb(34, 197, 94)",
                            "rgb(239, 68, 68)",
                            "rgb(156, 163, 175)",
                        ],
                        borderWidth: 0,
                        hoverOffset: 4,
                    },
                ],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: "70%",
                plugins: { legend: { display: false } },
            },
        });
    }
});
