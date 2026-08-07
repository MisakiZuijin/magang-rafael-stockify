document.addEventListener("DOMContentLoaded", function () {
    // Ambil data dari JSON script tag
    const rawData = document.getElementById("chart-data").textContent;
    const data = JSON.parse(rawData);

    const productNames = data.productNames.slice(0, 10);
    const productStocks = data.productStocks.slice(0, 10);

    // ==================== BAR CHART ====================
    const ctxBar = document.getElementById("stockChart").getContext("2d");
    new Chart(ctxBar, {
        type: "bar",
        data: {
            labels: productNames,
            datasets: [
                {
                    label: "Jumlah Stok",
                    data: productStocks,
                    backgroundColor: productStocks.map((stock) =>
                        stock < 10
                            ? "rgba(239, 68, 68, 0.8)"
                            : stock < 20
                              ? "rgba(245, 158, 11, 0.8)"
                              : "rgba(59, 130, 246, 0.8)",
                    ),
                    borderColor: productStocks.map((stock) =>
                        stock < 10
                            ? "rgb(239, 68, 68)"
                            : stock < 20
                              ? "rgb(245, 158, 11)"
                              : "rgb(59, 130, 246)",
                    ),
                    borderWidth: 1,
                    borderRadius: 6,
                    borderSkipped: false,
                },
            ],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: function (context) {
                            return "Stok: " + context.parsed.y + " unit";
                        },
                    },
                },
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { stepSize: 5 },
                    grid: { color: "rgba(0,0,0,0.05)" },
                },
                x: {
                    grid: { display: false },
                    ticks: { maxRotation: 45, minRotation: 0 },
                },
            },
        },
    });

    // ==================== DOUGHNUT CHART ====================
    const ctxDoughnut = document
        .getElementById("stockDoughnut")
        .getContext("2d");
    new Chart(ctxDoughnut, {
        type: "doughnut",
        data: {
            labels: ["Stok Aman", "Stok Sedang", "Stok Rendah"],
            datasets: [
                {
                    data: [data.stockAman, data.stockSedang, data.stockRendah],
                    backgroundColor: [
                        "rgba(34, 197, 94, 0.8)",
                        "rgba(245, 158, 11, 0.8)",
                        "rgba(239, 68, 68, 0.8)",
                    ],
                    borderColor: [
                        "rgb(34, 197, 94)",
                        "rgb(245, 158, 11)",
                        "rgb(239, 68, 68)",
                    ],
                    borderWidth: 2,
                    hoverOffset: 4,
                },
            ],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: "65%",
            plugins: {
                legend: {
                    position: "bottom",
                    labels: { usePointStyle: true, padding: 15 },
                },
            },
        },
    });
});
