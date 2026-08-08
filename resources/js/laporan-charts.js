document.addEventListener("DOMContentLoaded", function () {
    const raw = document.getElementById("chart-data");

    if (!raw) {
        console.error(
            "[Laporan Chart] ❌ Element #chart-data tidak ditemukan di DOM!",
        );
        return;
    }

    let data;
    try {
        data = JSON.parse(raw.textContent.trim());
    } catch (e) {
        console.error("[Laporan Chart] ❌ Gagal parse JSON:", e);
        console.log(
            "[Laporan Chart] Isi mentah:",
            raw.textContent.substring(0, 300),
        );
        return;
    }

    console.log("[Laporan Chart] ✅ Data loaded:", data);

    const isDark = document.documentElement.classList.contains("dark");
    const gridColor = isDark ? "rgba(255,255,255,0.1)" : "rgba(0,0,0,0.05)";
    const textColor = isDark ? "#9ca3af" : "#6b7280";

    // ==========================================
    // CHART 1: Stok per Kategori (Grouped Bar)
    // ==========================================
    const stockCanvas = document.getElementById("stockCategoryChart");
    if (!stockCanvas) {
        console.error(
            "[Laporan Chart] ❌ Canvas #stockCategoryChart tidak ditemukan!",
        );
    } else {
        const stockCtx = stockCanvas.getContext("2d");

        new Chart(stockCtx, {
            type: "bar",
            data: {
                labels: data.stockLabels || [],
                datasets: [
                    {
                        label: "Stok Saat Ini",
                        data: data.stockData || [],
                        backgroundColor: "rgba(59, 130, 246, 0.8)",
                        borderColor: "rgb(59, 130, 246)",
                        borderWidth: 1,
                        borderRadius: 4,
                    },
                    {
                        label: "Stok Minimum",
                        data: data.stockMinimum || [],
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
                        grid: { color: gridColor },
                        ticks: { color: textColor, precision: 0 },
                    },
                    x: {
                        grid: { display: false },
                        ticks: { color: textColor },
                    },
                },
                plugins: {
                    legend: {
                        labels: { color: textColor },
                    },
                },
            },
        });
        console.log("[Laporan Chart] ✅ Bar chart rendered");
    }

    // ==========================================
    // CHART 2: Transaksi Harian (Line)
    // ==========================================
    const trxCanvas = document.getElementById("transactionDailyChart");
    if (!trxCanvas) {
        console.error(
            "[Laporan Chart] ❌ Canvas #transactionDailyChart tidak ditemukan!",
        );
    } else {
        const trxCtx = trxCanvas.getContext("2d");

        new Chart(trxCtx, {
            type: "line",
            data: {
                labels: data.trxLabels || [],
                datasets: [
                    {
                        label: "Barang Masuk",
                        data: data.trxMasuk || [],
                        borderColor: "rgb(34, 197, 94)",
                        backgroundColor: "rgba(34, 197, 94, 0.1)",
                        fill: true,
                        tension: 0.4,
                        pointRadius: 3,
                    },
                    {
                        label: "Barang Keluar",
                        data: data.trxKeluar || [],
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
                        grid: { color: gridColor },
                        ticks: { color: textColor, precision: 0 },
                    },
                    x: {
                        grid: { display: false },
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
                        labels: { color: textColor },
                    },
                },
            },
        });
        console.log("[Laporan Chart] ✅ Line chart rendered");
    }
});
