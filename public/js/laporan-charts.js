// ... (JS Chart.js tetap sama persis seperti blade asli kamu)
document.addEventListener("DOMContentLoaded", function () {
    const raw = document.getElementById("chart-data");
    if (!raw) {
        console.error(
            "[Laporan Chart] ❌ Element #chart-data tidak ditemukan!",
        );
        return;
    }
    let data;
    try {
        data = JSON.parse(raw.textContent.trim());
    } catch (e) {
        console.error("[Laporan Chart] ❌ Gagal parse JSON:", e);
        return;
    }
    console.log("[Laporan Chart] ✅ Data loaded:", data);

    const isDark = document.documentElement.classList.contains("dark");
    const gridColor = isDark ? "rgba(255,255,255,0.1)" : "rgba(0,0,0,0.05)";
    const textColor = isDark ? "#9ca3af" : "#6b7280";

    const stockCanvas = document.getElementById("stockCategoryChart");
    if (stockCanvas && data.stockLabels && data.stockLabels.length > 0) {
        new Chart(stockCanvas.getContext("2d"), {
            type: "bar",
            data: {
                labels: data.stockLabels,
                datasets: [
                    {
                        label: "Stok Saat Ini",
                        data: data.stockData,
                        backgroundColor: "rgba(59, 130, 246, 0.8)",
                        borderColor: "rgb(59, 130, 246)",
                        borderWidth: 1,
                        borderRadius: 4,
                    },
                    {
                        label: "Stok Minimum",
                        data: data.stockMinimum,
                        backgroundColor: "rgba(239, 68, 68, 0.3)",
                        borderColor: "rgba(239, 68, 68, 1)",
                        borderWidth: 2,
                        borderRadius: 4,
                        borderDash: [5, 5],
                    },
                ],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: gridColor,
                        },
                        ticks: {
                            color: textColor,
                            precision: 0,
                        },
                    },
                    x: {
                        grid: {
                            display: false,
                        },
                        ticks: {
                            color: textColor,
                        },
                    },
                },
                plugins: {
                    legend: {
                        labels: {
                            color: textColor,
                        },
                    },
                },
            },
        });
    }

    const trxCanvas = document.getElementById("transactionDailyChart");
    if (trxCanvas && data.trxLabels && data.trxLabels.length > 0) {
        new Chart(trxCanvas.getContext("2d"), {
            type: "line",
            data: {
                labels: data.trxLabels,
                datasets: [
                    {
                        label: "Barang Masuk",
                        data: data.trxMasuk,
                        borderColor: "rgb(34, 197, 94)",
                        backgroundColor: "rgba(34, 197, 94, 0.1)",
                        fill: true,
                        tension: 0.4,
                        pointRadius: 3,
                    },
                    {
                        label: "Barang Keluar",
                        data: data.trxKeluar,
                        borderColor: "rgb(239, 68, 68)",
                        backgroundColor: "rgba(239, 68, 68, 0.1)",
                        fill: true,
                        tension: 0.4,
                        pointRadius: 3,
                    },
                ],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: {
                    mode: "index",
                    intersect: false,
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: gridColor,
                        },
                        ticks: {
                            color: textColor,
                            precision: 0,
                        },
                    },
                    x: {
                        grid: {
                            display: false,
                        },
                        ticks: {
                            color: textColor,
                            maxRotation: 45,
                            minRotation: 45,
                            autoSkip: true,
                            maxTicksLimit: 10,
                        },
                    },
                },
                plugins: {
                    legend: {
                        labels: {
                            color: textColor,
                        },
                    },
                },
            },
        });
    }
});
