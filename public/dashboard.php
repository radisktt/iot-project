<?php
include 'header.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>IoT Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        .custom-card-height {
            height: 250px;
        }
        .custom-button-card-height {
            height: 116.25;
        }
        .chart-container {
            position: relative;
            width: 100%;
            height: 450px;
        }
        canvas {
            display: block;
            width: 100% !important;
            height: 100% !important;
        }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body style="background-color: #E5E7EB;">
    <div class="container mt-4">
        <dir class="row">
            <!-- Temperature Card -->            
            <div class="col-12 col-sm-6 col-xl-4 mb-4">
                <div class="card border-0 shadow custom-card-height" id="bg-temp">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col">
                                <h6 class="mb-0" style="font-size: xx-large;">Temperature</h6>
                                <h3 class="mb-0" style="font-size: xxx-large;" id="temp"></h3>
                            </div>
                        </div>
                        <div class="col-auto d-flex justify-content-between align-items-end">
                            <img src="../iot/assets/celsius.svg" alt="Celsius" style="width: 80px;">
                            <img src="../iot/assets/temp.svg" alt="Temperature" style="width: 100px; height: 100px;">
                        </div>
                    </div>
                </div>
            </div>
            <!-- Humidity Card -->
            <div class="col-12 col-sm-6 col-xl-4 mb-4">
                <div class="card border-0 shadow custom-card-height" id="bg-humid">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col">
                                <h6 class="mb-0" style="font-size: xx-large;">Humidity</h6>
                                <h3 class="mb-0" style="font-size: xxx-large;" id="humid"></h3>
                            </div>
                        </div>
                        <div class="col-auto d-flex justify-content-between align-items-end">
                            <img src="../iot/assets/percent.svg" alt="Percentage" style="width: 80px;">
                            <img src="../iot/assets/humi.svg" style="width: 100px;height: 100px;">
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Brightness Card -->
            <div class="col-12 col-sm-6 col-xl-4 mb-4">
                <div class="card border-0 shadow custom-card-height" id="bg-bright">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col">
                                <h6 class="mb-0" style="font-size: xx-large;">Brightness</h6>
                                <h3 class="mb-0" style="font-size: xxx-large;" id="bright"></h3>
                            </div>
                        </div>
                        <div class="col-auto d-flex justify-content-between align-items-end">
                            <img src="../iot/assets/Luxx.svg" alt="Lux" style="width: 80px;color: black;">
                            <img src="../iot/assets/bright.svg" style="width: 100px;height: 100px;">
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Chart and button -->
            <div class="row m-0 p-0">
                <div class="col-12 col-sm-12 col-xl-9 mb-4">
                    <div class="card border-0 shadow">
                        <!-- Time -->
                        <div class="card-header d-sm-flex flex-row align-items-center justify-content-between" style="background-color: bisque;">
                            <div class="d-block mb-3 mb-sm-0">
                                <div class="h5 d-none d-sm-flex">Live Time Metrics</div>
                            </div>
                    
                            <div class="date-time" id="currentDateTime"></div>
                        </div>
                        <!-- Chart -->
                        <div class="card-body p-2">
                            <div class="chart-container">
                                <canvas id="combinedChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Device Button -->
                <div class="col col-sm-12 col-xl-3 ">
                    <div class="row flex">
                        <!-- Fan -->
                        <div class="col-12 col-sm-6 col-xl-12 mb-4">
                            <div class="card border-0 shadow custom-button-card-height">
                                <div class="card-body">
                                    <h5 style="display: flex;">Fan</h5>
                                    <div class="row align-items-center">
                                        <div class="col">
                                            <img src="../iot/assets/fan.svg" alt="Fan" style="width: 50px;">
                                        </div>
                                        <div class="col">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" id="fanToggleButton" style="width: 100px; height: 50px;">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Conditioner -->
                        <div class="col-12 col-sm-6 col-xl-12 mb-4">
                            <div class="card border-0 shadow custom-button-card-height">
                                <div class="card-body">
                                    <h5 style="display: flex;">Conditioner</h5>
                                    <div class="row align-items-center">
                                        <div class="col">
                                            <img src="../iot/assets/aircd.svg" alt="Aircd" style="width: 50px;">
                                        </div>
                                        <div class="col">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" id="acToggleButton" style="width: 100px; height: 50px;">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Light Bulb -->
                        <div class="col-12 col-sm-6 col-xl-12 mb-4">
                            <div class="card border-0 shadow custom-button-card-height">
                                <div class="card-body">
                                    <h5 style="display: flex;">Light Bulb</h5>
                                    <div class="row align-items-center">
                                        <div class="col">
                                            <img src="../iot/assets/light.svg" alt="Bulb" style="width: 50px;">
                                        </div>
                                        <div class="col">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" id="lightToggleButton" style="width: 100px; height: 50px;">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-sm-6 col-xl-12 mb-4">
                            <div class="card border-0 shadow custom-button-card-height">
                                <div class="card-body">
                                <h5 style="display: flex;">   </h5>
                                    <div class="row align-items-center">
                                        <div class="col">
                                            <!-- <img src="../iot/assets/fan.svg" alt="ALL" style="width: 50px;"> -->
                                             <h5 style="display: flex;">ALL</h5>
                                        </div>
                                        <div class="col">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" id="allToggleButton" style="width: 100px; height: 50px;">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            
            
        </dir>
        
    </div>





    <script>
    let fan = 0, ac = 0, light = 0;

    // Attach event listeners
    document.getElementById("fanToggleButton").addEventListener("change", () => toggleDevice("fan", "fanToggleButton"));
    document.getElementById("acToggleButton").addEventListener("change", () => toggleDevice("ac", "acToggleButton"));
    document.getElementById("lightToggleButton").addEventListener("change", () => toggleDevice("light", "lightToggleButton"));
    document.getElementById("allToggleButton").addEventListener("change", () => toggleDevice("all", "allToggleButton"));

    // Function to toggle devices
    function toggleDevice(device, toggleButtonId) {
        const toggleButton = document.getElementById(toggleButtonId);
        const state = toggleButton.checked ? "1" : "0"; // Determine state (1 for on, 0 for off)

        if (device === "all") {
            // Toggle all devices
            fan = ac = light = state === "1" ? 1 : 0;
            document.getElementById("fanToggleButton").checked = fan === 1;
            document.getElementById("acToggleButton").checked = ac === 1;
            document.getElementById("lightToggleButton").checked = light === 1;
            sendPostRequest("all", state); // Send "all" request
        } else {
            // Update specific device state
            if (device === "fan") fan = state === "1" ? 1 : 0;
            if (device === "ac") ac = state === "1" ? 1 : 0;
            if (device === "light") light = state === "1" ? 1 : 0;
            sendPostRequest(device, state); // Send specific request
            updateAllToggleButton(); // Update "All" toggle button
        }
    }

    // Function to send POST request
    function sendPostRequest(device, state) {
        const data = { device, state };
        fetch("http://localhost/iot/api/device_control.php", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify(data)
        })
            .then(response => {
                if (!response.ok) throw new Error(`Error: ${response.status}`);
                return response.json();
            })
            .then(result => {
                console.log(`Device: ${device}, State: ${state}, Server Response:`, result);
            })
            .catch(error => {
                console.error("Error toggling device:", error);
            });
    }

    // Function to update "All" toggle button state
    function updateAllToggleButton() {
        const allToggleButton = document.getElementById("allToggleButton");
        allToggleButton.checked = fan === 1 && ac === 1 && light === 1; // Turn ON "All" if all are ON
    }

    // Function to get device states on load
    function getState() {
        const devices = ["fan", "ac", "light"];
        devices.forEach((device) => {
            fetch(`http://localhost/iot/api/get_lastest_action.php?device=${device}`)
                .then(response => {
                    if (!response.ok) throw new Error(`Error fetching ${device} state: ${response.status}`);
                    return response.json();
                })
                .then(result => {
                    if (result.success && result.data) {
                        const state = result.data.state;

                        // Check if the element exists before accessing it
                        const toggleButton = document.getElementById(`${device}ToggleButton`);
                        if (toggleButton) {
                            toggleButton.checked = state === 1;
                        } else {
                            console.error(`Element with ID ${device}ToggleButton not found`);
                        }

                        // Update state variables
                        if (device === "fan") fan = state;
                        if (device === "ac") ac = state;
                        if (device === "light") light = state;

                        updateAllToggleButton();
                    } else {
                        console.error(`Invalid response for ${device}`, result);
                    }
                })
                .catch(error => {
                    console.error(`Error loading ${device} state:`, error);
                });
        });
    }

    // Call getState on page load
    getState();
</script>

<script>
    document.addEventListener('DOMContentLoaded', async () => {
        const ctx = document.getElementById('combinedChart').getContext('2d');
        let combinedChart;

        // Khởi tạo biểu đồ với nhãn x cố định
        function initChart(data) {
            const temperatures = data.map(entry => entry.temperature || 0).reverse();
            const humidities = data.map(entry => entry.humidity || 0).reverse();
            const brightnesses = data.map(entry => entry.brightness || 0).reverse();

            combinedChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: Array(10).fill('').map((_, i) => `-${(9 - i) * 5}s`).concat([' ']),
                    datasets: [
                        {
                            label: 'Temperature (°C)',
                            data: temperatures,
                            borderColor: 'rgba(255, 99, 132, 1)',
                            backgroundColor: 'rgba(255, 99, 132, 0.2)',
                            borderWidth: 2,
                            yAxisID: 'y-left',
                            spanGaps: true,
                            cubicInterpolationMode: 'monotone', // Smooth curves
                        },
                        {
                            label: 'Humidity (%)',
                            data: humidities,
                            borderColor: 'rgba(54, 162, 235, 1)',
                            backgroundColor: 'rgba(54, 162, 235, 0.2)',
                            borderWidth: 2,
                            yAxisID: 'y-left',
                            spanGaps: true,
                            cubicInterpolationMode: 'monotone', // Smooth curves
                        },
                        {
                            label: 'Brightness (Lux)',
                            data: brightnesses,
                            borderColor: 'rgba(255, 206, 86, 1)',
                            backgroundColor: 'rgba(255, 206, 86, 0.2)',
                            borderWidth: 2,
                            yAxisID: 'y-right',
                            spanGaps: true,
                            cubicInterpolationMode: 'monotone', // Smooth curves
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        'y-left': {
                            type: 'linear',
                            position: 'left',
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Temperature (°C) / Humidity (%)'
                            }
                        },
                        'y-right': {
                            type: 'linear',
                            position: 'right',
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Brightness (Lux)'
                            },
                            grid: {
                                drawOnChartArea: false
                            }
                        }
                    }
                }
            });
        }

        // Hàm lấy dữ liệu ban đầu
        async function fetchInitialData() {
            try {
                const response = await fetch('http://localhost/iot/api/get_data_sensor.php');
                const result = await response.json();
                return result;
            } catch (error) {
                console.error('Error fetching data:', error);
                return null;
            }
        }

        // Hàm lấy và cập nhật dữ liệu mới
        async function fetchSensorData() {
            try {
                const response = await fetch('http://localhost/iot/api/get_lastest_data.php');
                const result = await response.json();

                if (result.success && result.data) {
                    updateCards(result.data);
                    updateChart(result.data);
                } else {
                    console.error('Lỗi khi lấy dữ liệu:', result.message || 'Không có dữ liệu');
                }
            } catch (error) {
                console.error('Error fetching data:', error);
            }

            setTimeout(fetchSensorData, 5000); // Lặp lại sau 5 giây
        }

        // Hàm cập nhật giá trị của các thẻ
        function updateCards(data) {
            document.getElementById('temp').innerText = data.temperature || 'N/A';
            document.getElementById('humid').innerText = data.humidity || 'N/A';
            document.getElementById('bright').innerText = data.brightness || 'N/A';
            updateBackgroundColors(data);
        }

        // Hàm cập nhật màu nền dựa trên dữ liệu
        function updateBackgroundColors(data) {
            const humidity = parseFloat(data.humidity);
            const temperature = parseFloat(data.temperature);
            const brightness = parseFloat(data.brightness);

            document.getElementById('bg-humid').style.backgroundColor = `hsl(240, 100%, ${100 - (humidity / 100) * 50}%)`;
            document.getElementById('bg-temp').style.backgroundColor = `hsl(0, 100%, ${100 - (temperature / 50) * 50}%)`;
            document.getElementById('bg-bright').style.backgroundColor = `hsl(40, 100%, ${100 - (brightness / 1000) * 50}%)`;
        }

        // Hàm cập nhật biểu đồ với dữ liệu mới nhất
        function updateChart(data) {
            const temperature = data.temperature || 0;
            const humidity = data.humidity || 0;
            const brightness = data.brightness || 0;

            combinedChart.data.datasets[0].data.push(temperature);
            combinedChart.data.datasets[1].data.push(humidity);
            combinedChart.data.datasets[2].data.push(brightness);

            if (combinedChart.data.datasets[0].data.length > 10) {
                combinedChart.data.datasets.forEach(dataset => dataset.data.shift());
            }

            combinedChart.update();
        }

        // Lấy dữ liệu ban đầu và khởi tạo biểu đồ
        const initialData = await fetchInitialData();
        if (initialData && initialData.success) {
            initChart(initialData.data); // Khởi tạo biểu đồ với dữ liệu ban đầu
        }

        fetchSensorData(); // Bắt đầu cập nhật dữ liệu mỗi 5 giây
    });
        function updateDateTime() {
            const now = new Date();
            const dateTimeString = now.toLocaleString();
            document.getElementById('currentDateTime').textContent = dateTimeString;
        }
        setInterval(updateDateTime, 1000);
    
</script>



<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
</body>