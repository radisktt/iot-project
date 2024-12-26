<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Action History</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<?php
include 'header.php';
?>
<body style="background-color: #E5E7EB;">
    <div class="container mt-4">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center">
            <div class="d-block mb-4 mb-md-0">
                <h2 class="h4">Action Searching</Data></h2>
                <p class="mb-0">Searching option</p>
            </div>
        </div>
        <div class="table-settings mt-4">
            <form action="../iot/api/get_data_sensor.php" method="GET">
                <div class="row justify-content-between align-items-center">
                    <div class="col-12 col-md-6 col-xl-4">
                        <div class="input-group me-2 me-lg-3 fmxw-400">
                            <span class="input-group-text">
                                <button type="button" id="search" class="btn btn-link p-0 input-group-text">
                                    <img src="assets/search.svg" alt="Search Icon" style="width: 25px;">
                                </button>                           
                            </span>
                            <input type="text" class="form-control" id="searchInput" placeholder="Search Action History (Device:State)">
                        </div>
                    </div>
                    <div class="btn-toolbar col col-md-6 col-lg-9 col-xl-8 mb-2 mb-md-0">
                        <!-- InputTime -->
                        <div class="btn-toolbar border-0">
                            <div class="input-group me-3">
                                <span class="input-group-text">From</span>
                                <input type="text" class="form-control" id="startTime" name="start-time" placeholder="yyyy-mm-dd HH:MM:SS">
                            </div>
                            <div class="input-group me-4">
                                <span class="input-group-text">To</span>
                                <input type="text" class="form-control" id="endTime" name="end-time" placeholder="yyyy-mm-dd HH:MM:SS">
                            </div>                                                        
                        </div>  
                        <!-- PageSize                       -->
                        <div class="dropdown">
                            <button class="btn btn-link text-dark m-0 p-1"  data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <img src="../iot/assets/setting.svg" alt="Row Setting" style="width: 30px;">
                            </button>
                            <ul class="dropdown-menu dropdown-menu-start" id="pageSizeDropdown">
                                <span class="small ps-3 fw-bold text-dark">Show</span>
                                <li>
                                    <a class="dropdown-item d-flex align-items-center active" href="#" onclick="updatePageSize(10, this)">10</a>
                                </li>
                                <li>
                                    <a class="dropdown-item d-flex align-items-center" href="#" onclick="updatePageSize(20, this)">20</a>
                                </li>
                                <li>
                                    <a class="dropdown-item d-flex align-items-center" href="#" onclick="updatePageSize(30, this)">30</a>
                                </li>
                            </ul>
                        </div>                          
                    </div>
                </div>        
            </form>
        </div>
        <!-- TableContent -->
        <div class="card card-body border-0 shadow table-wrapper table-responsive mt-4">
                <table class="table table-hover" id="actionHistoryTable">
                    <thead>
                        <tr>
                            <th class="border-gray-200 text-center">#</th>                            
                            <th class="border-gray-200 text-center">Device </th>
                            <th class="border-gray-200 text-center">Action </th>  
                            <th class="border-gray-200 text-center">Time
                                <button class="btn border-gray-200 text-center" onclick="sortTable()">
                                    <span>sort</span>
                                </button>
                            </th>
                        </tr>
                    </thead>
                    <tbody id="actionHistoryBody">
                        <!-- action data -->
                    </tbody>
                </table>
                <div class="card-footer px-3 border-0 d-flex flex-column flex-lg-row align-items-center justify-content-between text-dark">
                    <nav aria-label="Page navigation">
                        <ul class="pagination mb-0" id="paginationControls">
                            <!-- Pagination buttons -->
                        </ul>
                    </nav>
                    <div class="fw-normal small mt-4 mt-lg-0 text-dark" id="entriesInfo">
                        <!-- Entries info -->
                    </div>
                </div>
        </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>
    <script>
        let startTime = '1970-01-01 00:00:00',
            endTime = moment().format('YYYY-MM-DD HH:mm:ss'),
            currentPage = 1,
            pageSize = 10,
            order = "DESC",
            device = "",
            state = -1;
        document.querySelector('#search').addEventListener('click', function () {
            console.log('Button clicked!');
            searchData();
        });    
        function searchData(){
            if (document.getElementById("startTime").value) {
                startTime = parseToDatetimeLocal(document.getElementById("startTime").value);
            }else{startTime = '1970-01-01 00:00:00'}
            if (document.getElementById("endTime").value) {
                endTime = parseToDatetimeLocal(document.getElementById("endTime").value);
            } else{endTime = moment().format('YYYY-MM-DD HH:mm:ss')}
            if (document.getElementById("searchInput") && document.getElementById("searchInput").value) {
                var arr = (getInput(document.getElementById("searchInput").value));
                device = arr[0];state = arr[1];
            }else{device = ""; state = -1;}
            let speVal = "";
            if(device!="" && state !=-1){
                speVal = `device=${device}&state=${state}`; 
            }
            const apiUrl = `http://localhost/iot/api/search_action.php`;
            const fetchUrl = `${apiUrl}?start_time=${startTime}&end_time=${endTime}&page=${currentPage}&pageSize=${pageSize}&order=${order}&${speVal}`
            console.log(fetchUrl);
            fetch(fetchUrl)
                .then(async (response) => {
                    const res = await response.json();
                    console.log(res);
                    updateTable(res.data, res.page, res.totalRows);
                    renderPaginationControls(res.page, res.totalPages);
                })
                .catch((error) => console.error("Fetch error:", error));
            
        }
        function updateTable(sensorData, page, totalRows) {
            const tableBody = document.getElementById('actionHistoryBody');
            tableBody.innerHTML = '';
            sensorData.forEach((data) => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td class="text-center">${data.id}</td>
                    <td class="text-center">${data.device_name.toUpperCase()}</td>
                    <td class="text-center">${data.state===1?"ON" : "OFF"}</td>
                    <td class="text-center">${data.timestamp}</td>
                `;
                tableBody.appendChild(row);
            });
            const entriesInfo = document.getElementById('entriesInfo');
            entriesInfo.innerHTML = `Showing <b>${sensorData.length}</b> out of <b>${totalRows}</b> entries`;
        }
        function updatePageSize(newPageSize, element) {
            pageSize = newPageSize;
            const dropdownItems = document.querySelectorAll('#pageSizeDropdown .dropdown-item');
            dropdownItems.forEach(item => item.classList.remove('active'));
            element.classList.add('active');
            currentPage = 1;
            searchData();
        }

        function renderPaginationControls(page, totalPages) {
            const paginationControls = document.getElementById('paginationControls');
            paginationControls.innerHTML = ''; // Clear existing pagination buttons

            const maxVisiblePages = 10;
            const startPage = Math.max(1, page - Math.floor(maxVisiblePages / 2));
            const endPage = Math.min(totalPages, startPage + maxVisiblePages - 1);

            // Adjust startPage if there are fewer than maxVisiblePages at the end
            const adjustedStartPage = Math.max(1, endPage - maxVisiblePages + 1);

            // Previous button
            const prevButton = document.createElement('li');
            prevButton.classList.add('page-item');
            if (page > 1) {
                prevButton.innerHTML = `<a class="page-link text-dark" href="#" onclick="changePage(${page - 1})">Previous</a>`;
            } else {
                prevButton.classList.add('disabled');
                prevButton.innerHTML = `<a class="page-link text-dark">Previous</a>`;
            }
            paginationControls.appendChild(prevButton);

            // Page number buttons
            for (let i = adjustedStartPage; i <= endPage; i++) {
                const pageButton = document.createElement('li');
                pageButton.classList.add('page-item');
                if (i === page) pageButton.classList.add('active'); // Highlight the current page
                pageButton.innerHTML = `<a class="page-link text-dark" href="#" onclick="changePage(${i})">${i}</a>`;
                paginationControls.appendChild(pageButton);
            }

            // Next button
            const nextButton = document.createElement('li');
            nextButton.classList.add('page-item');
            if (page < totalPages) {
                nextButton.innerHTML = `<a class="page-link text-dark" href="#" onclick="changePage(${page + 1})">Next</a>`;
            } else {
                nextButton.classList.add('disabled');
                nextButton.innerHTML = `<a class="page-link text-dark">Next</a>`;
            }
            paginationControls.appendChild(nextButton);
        }

        // Change the current page
        function changePage(newPage) {
            currentPage = newPage;
            searchData();
        }
        function sortTable(){
            if(order === "DESC"){
                order = "ASC";
            }else order = "DESC";
            searchData();
        }
        function getInput(input) {            
            if(!input){
                return ["",""];
            }
            const validDevices = ["fan", "ac", "light", "led1", "led2"];
            const validStates = ["on", "off"];
            const [device, state] = input.split(":").map(part => part.trim().toLowerCase());
            if (!validDevices.includes(device)) {
                return ["",-1]
            }
            if (!validStates.includes(state)) {
                return ["",-1]
            }
            let x = 0;            
            if(state==='on'){
                x = 1;
            }else x = 0;
            return [device, x];
        } 
        function parseToDatetimeLocal(inputText) {
            const date = new Date(inputText);
            if (isNaN(date)) {
                return "";
            }
            const pad = (num) => String(num).padStart(2, '0');
            const year = date.getFullYear();
            const month = pad(date.getMonth() + 1); // Months are zero-based
            const day = pad(date.getDate());
            const hours = pad(date.getHours());
            const minutes = pad(date.getMinutes());
            const seconds = pad(date.getSeconds());
            // MySQL TIMESTAMP format
            return `${year}-${month}-${day} ${hours}:${minutes}:${seconds}`;
        }

        searchData();     
    </script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>

</body>