<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>New Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .custom-card-height {
            height: 250px;
        }
        .custom-button-card-height {
            height: 116.25px;
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
</head>
<body style="background-color: #E5E7EB;">
    <?php include 'header.php'; ?>

    <div class="container mt-4">
        <div class="row">
            <!-- New Sensor Card -->
            <div class="col-12 col-sm-6 col-xl-4 mb-4">
                <div class="card border-0 shadow custom-card-height" id="bg-new">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col">
                                <h6 class="mb-0" style="font-size: xx-large;">New Sensor</h6>
                                <h3 class="mb-0" style="font-size: xxx-large;" id="newsensor">36</h3>
                            </div>
                        </div>
                        <div class="col-auto d-flex justify-content-between align-items-end">
                            <img src="../iot/assets/percent.svg" alt="Unit" style="width: 80px;">
                            <img src="../iot/assets/newsensor.svg" alt="New Sensor" style="width: 100px; height: 100px;">
                        </div>
                    </div>
                </div>
            </div>

            <!-- LED1 Card -->
            <div class="col-12 col-sm-6 col-xl-4 mb-4">
                <div class="card border-0 shadow custom-card-height">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="row">
                                <h6 class="mb-0" style="font-size: xx-large;">LED1</h6>
                            </div>
                            <div class="col-5 mt-5 ">
                                <img src="../iot/assets/led.svg" alt="LED1" style="width: 100px;">
                            </div>
                            <div class="col mt-4">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="led1ToggleButton" style="width: 150px; height: 75px;">
                                 </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- LED2 Card -->
            <div class="col-12 col-sm-6 col-xl-4 mb-4">
                <div class="card border-0 shadow custom-card-height">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="row">
                                <h6 class="mb-0" style="font-size: xx-large;">LED2</h6>
                            </div>
                            <div class="col-5 mt-5">
                                <img src="../iot/assets/led.svg" alt="LED2" style="width: 100px;">
                            </div>
                            <div class="col mt-4">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="led2ToggleButton" style="width: 150px; height: 75px;">
                                 </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Time and Chart -->
            <div class="row m-0 p-0">
                <div class="col">
                    <div class="card border-0 shadow">
                        <div class="card-header d-sm-flex flex-row align-items-center justify-content-between" style="background-color: bisque;">
                            <div class="d-block mb-3 mb-sm-0">
                                <div class="h5 d-none d-sm-flex">Live Time Metrics</div>
                            </div>
                            <div class="date-time" id="currentDateTime"></div>
                        </div>
                        <div class="card-body p-2">
                            <div class="chart-container">
                                <canvas id="newChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>                
            </div>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', async () => {
        const ctx = document.getElementById('newChart').getContext('2d');
        let newChart;

        // Initialize the chart
        function initChart(data) {
            const temperatures = data.map(entry => entry.newsensor || 0).reverse();
            const labels = data.map((_, i) => `-${(data.length - i) * 5}s`).reverse() ;

            newChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: Array(10).fill('').map((_, i) => `-${(9 - i) * 5}s`).concat([' ']),
                    datasets: [
                        {
                            label: 'New Sensor (%)',
                            data: temperatures,
                            borderColor: 'rgba(255, 99, 132, 1)',
                            backgroundColor: 'rgba(255, 99, 132, 0.2)',
                            borderWidth: 2,
                            yAxisID: 'y-left',
                            spanGaps: true,
                            cubicInterpolationMode: 'monotone',
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
                                text: 'New Sensor (%)'
                            }
                        }
                    }
                }
            });
        }

        // Fetch initial data
        async function fetchInitialData() {
            try {
                const response = await fetch('http://localhost/iot/api/get_data_sensor.php');
                const result = await response.json();
                if (result.success && Array.isArray(result.data)) {
                    return result.data;
                } else {
                    console.error('Error fetching initial data:', result.message || 'Invalid data format');
                    return [];
                }
            } catch (error) {
                console.error('Error fetching initial data:', error);
                return [];
            }
        }

        // Fetch latest data
        async function fetchSensorData() {
            try {
                const response = await fetch('http://localhost/iot/api/get_lastest_data.php');
                const result = await response.json();

                if (result.success && result.data) {
                    //console.log(result.data);
                    updateCards(result.data);
                    updateChart(result.data);
                } else {
                    console.error('Error fetching data:', result.message || 'No data available');
                }
            } catch (error) {
                console.error('Error fetching sensor data:', error);
            }

            setTimeout(fetchSensorData, 5000); // Repeat every 5 seconds
        }

        // Update chart with new data
        function updateChart(data) {
            const newsensor = data.newsensor || 0;

            // Push the new value to the dataset
            if (newChart && newChart.data.datasets.length > 0) {
                newChart.data.datasets[0].data.push(newsensor);

                // Limit the number of data points to 10
                if (newChart.data.datasets[0].data.length > 10) {
                    newChart.data.datasets[0].data.shift();
                }

                // Add a new label (e.g., timestamp or relative time)
                //newChart.data.labels.push(`${new Date().toLocaleTimeString()}`);

                // Update the chart
                newChart.update();
            } else {
                console.error('Chart not initialized or dataset missing.');
            }
        }
        function updateCards(data) {
            document.getElementById('newsensor').innerText = data.newsensor || 'N/A';
            updateBackgroundColors(data);
        }
        function updateBackgroundColors(data) {
            const newsensor = parseFloat(data.newsensor);
            document.getElementById('bg-new').style.backgroundColor = `hsl(199, 100%, ${100 - (newsensor / 100) * 50}%)`;
        }
        
        // Initialize the chart with fetched data
        const initialData = await fetchInitialData();
        if (initialData && initialData.length > 0) {
            initChart(initialData); // Initialize the chart with initial data
        } else {
            console.error('No initial data available to render the chart.');
        }

        // Start fetching sensor data
        fetchSensorData();

        // Update current date and time
        
    });
        function updateDateTime() {
            const now = new Date();
            const dateTimeString = now.toLocaleString();
            document.getElementById('currentDateTime').textContent = dateTimeString;
        }
        setInterval(updateDateTime, 1000);    
</script>
<script>
    let led1 = 0, led2 = 0;

    // Attach event listeners
    document.getElementById("led1ToggleButton").addEventListener("change", () => toggleDevice("led1", "led1ToggleButton"));
    document.getElementById("led2ToggleButton").addEventListener("change", () => toggleDevice("led2", "led2ToggleButton"));

    // Function to toggle devices
    function toggleDevice(device, toggleButtonId) {
        const toggleButton = document.getElementById(toggleButtonId);
        const state = toggleButton.checked ? "1" : "0"; // Determine state (1 for on, 0 for off)
        sendPostRequest(device, state);
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

    // Function to get device states on load
    function getState() {
        const devices = ["led1", "led2"];
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
                        if (device === "led1") led1 = state;
                        if (device === "led2") led2 = state;

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

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
</body>
