<?php $this->layout("_theme"); ?>

<style>
    body {
        background: radial-gradient(circle at 20% 20%, #0f1b33, #060b14 80%);
        color: #fff;
        overflow-x: hidden;
    }

    .status-card {
        background: rgba(255, 255, 255, 0.06);
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: 1rem;
        padding: 1.5rem;
        backdrop-filter: blur(10px);
        transition: all 0.3s ease;
    }

    .status-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 0 25px rgba(0, 150, 255, 0.3);
    }

    .metric-value {
        font-size: 2rem;
        font-weight: 700;
        color: #00d1ff;
    }

    canvas {
        width: 100% !important;
        height: 350px !important;
    }

    .pulse-dot {
        width: 12px;
        height: 12px;
        background-color: #00ff99;
        border-radius: 50%;
        animation: pulse 2s infinite;
        display: inline-block;
        vertical-align: middle;
    }

    @keyframes pulse {
        0% {
            transform: scale(0.95);
            opacity: 0.7;
        }

        50% {
            transform: scale(1.2);
            opacity: 1;
        }

        100% {
            transform: scale(0.95);
            opacity: 0.7;
        }
    }

    .section-title {
        font-weight: 700;
        letter-spacing: 1px;
        color: #00eaff;
    }
</style>

<div class="container py-10">
    <h1 class="text-center mb-5 fw-bold text-white">📡 Status do Serviço - Sky Wave Fibra</h1>
    <p class="text-center text-gray-400 fs-5 mb-10">
        Monitoramento em tempo real da estabilidade e uso do servidor (simulação).
    </p>

    <div class="row g-5 mb-8">
        <div class="col-md-3">
            <div class="status-card text-center">
                <div class="text-gray-400 fw-semibold">Latência Média</div>
                <div class="metric-value" id="latency">0 ms</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="status-card text-center">
                <div class="text-gray-400 fw-semibold">Uptime</div>
                <div class="metric-value" id="uptime">99.99%</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="status-card text-center">
                <div class="text-gray-400 fw-semibold">Conexões Ativas</div>
                <div class="metric-value" id="connections">0</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="status-card text-center">
                <div class="text-gray-400 fw-semibold">Status</div>
                <div><span class="pulse-dot"></span> <span class="fw-bold">Online</span></div>
            </div>
        </div>
    </div>

    <div class="status-card">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="section-title mb-0">Tráfego do Servidor</h4>
            <small class="text-gray-400">Atualização a cada 2 segundos</small>
        </div>
        <canvas id="statusChart"></canvas>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('statusChart').getContext('2d');
    const data = {
        labels: [],
        datasets: [{
            label: 'Uso de Recursos (%)',
            data: [],
            fill: true,
            backgroundColor: 'rgba(0, 200, 255, 0.1)',
            borderColor: '#00d1ff',
            tension: 0.4
        }]
    };

    const config = {
        type: 'line',
        data: data,
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    max: 100,
                    ticks: {
                        color: "#8ee3ff"
                    },
                    grid: {
                        color: "rgba(255,255,255,0.1)"
                    }
                },
                x: {
                    ticks: {
                        color: "#8ee3ff"
                    },
                    grid: {
                        color: "rgba(255,255,255,0.05)"
                    }
                }
            },
            plugins: {
                legend: {
                    display: false
                }
            },
            animation: {
                duration: 800,
                easing: 'easeOutQuart'
            }
        }
    };

    const chart = new Chart(ctx, config);

    function randomData() {
        return Math.floor(Math.random() * 50) + 40; // valores entre 40 e 90
    }

    function updateMetrics() {
        document.getElementById("latency").innerText = (Math.random() * 50 + 10).toFixed(1) + " ms";
        document.getElementById("connections").innerText = Math.floor(Math.random() * 400) + 50;
    }

    setInterval(() => {
        const now = new Date().toLocaleTimeString();
        if (data.labels.length > 15) {
            data.labels.shift();
            data.datasets[0].data.shift();
        }
        data.labels.push(now);
        data.datasets[0].data.push(randomData());
        chart.update();
        updateMetrics();
    }, 2000);
</script>