<!DOCTYPE HTML>
<html>

<head>
    <title>Controller</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Thư viện Font Awesome -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" crossorigin="anonymous">
    <link rel="icon" href="data:,">

    <style>
        html {
            font-family: Arial;
            text-align: center;
        }

        body {
            margin: 0;
        }

        .topnav {
            overflow: hidden;
            background-color: #0c6980;
            color: white;
            font-size: 1.2rem;
            padding: 10px;
        }

        /* Sử dụng Flexbox để sắp xếp biểu đồ và nội dung */
        .container {
            display: flex;
            align-items: flex-start;
            padding: 10px;
        }

        /* Biểu đồ nằm bên trái */
        .chart-container {
            width: 30%;
            margin-right: 20px;
        }

        .chart-container canvas {
            width: 100% !important;
            height: auto !important;
            margin-bottom: 10px;
            /* Khoảng cách giữa các biểu đồ */
        }

        /* Nội dung khác nằm bên phải */
        .content-container {
            width: 70%;
        }

        /* CSS của bạn */
        p {
            font-size: 1.2rem;
        }

        h4 {
            font-size: 0.8rem;
        }

        .content {
            padding: 5px;
        }

        .card {
            background-color: white;
            box-shadow: 0px 0px 10px 1px rgba(140, 140, 140, .5);
            border: 1px solid #0c6980;
            border-radius: 15px;
            margin-bottom: 20px;
        }

        .card.header {
            background-color: #0c6980;
            color: white;
            border-bottom-right-radius: 0px;
            border-bottom-left-radius: 0px;
            border-top-right-radius: 12px;
            border-top-left-radius: 12px;
        }

        .cards {
            display: grid;
            grid-gap: 2rem;
            grid-template-columns: 1fr;
        }

        .reading {
            font-size: 1.3rem;
        }

        .temperatureColor {
            color: #fd7e14;
        }

        .humidityColor {
            color: #1b78e2;
        }

        .LEDColor {
            color: #183153;
        }

        /* Toggle Switch */
        .switch {
            position: relative;
            display: inline-block;
            width: 50px;
            height: 24px;
        }

        .switch input {
            display: none;
        }

        .sliderTS {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #D3D3D3;
            transition: .4s;
            border-radius: 34px;
        }

        .sliderTS:before {
            position: absolute;
            content: "";
            height: 16px;
            width: 16px;
            left: 4px;
            bottom: 4px;
            background-color: #f7f7f7;
            transition: .4s;
            border-radius: 50%;
        }

        .led-status.ON {
            font-weight: bold;
            color: green;
            /* Màu chữ mặc định cho trạng thái LED Bật */
        }

        .led-status.OFF {
            color: red;
            /* Màu chữ cho trạng thái LED Tắt */
        }

        input:checked+.sliderTS {
            background-color: #00878F;
        }

        input:checked+.sliderTS:before {
            transform: translateX(26px);
        }

        .sliderTS:after {
            content: 'OFF';
            color: white;
            display: block;
            position: absolute;
            transform: translate(-50%, -50%);
            top: 50%;
            left: 70%;
            font-size: 10px;
            font-family: Verdana, sans-serif;
        }

        input:checked+.sliderTS:after {
            left: 25%;
            content: 'ON';
        }

        input:disabled+.sliderTS {
            opacity: 0.3;
            cursor: not-allowed;
            pointer-events: none;
        }
    </style>
</head>

<body>
    <div class="topnav">
        <h3>Controller</h3>
    </div>

    <div class="container">
        <!-- Biểu đồ nằm bên trái -->
        <div class="chart-container">
            <canvas id="temperatureChart"></canvas>
            <canvas id="humidityChart"></canvas>
            <canvas id="soilChart"></canvas>
            <canvas id="lightChart"></canvas>
        </div>

        <!-- Nội dung khác nằm bên phải -->
        <div class="content-container">
            <!-- Nội dung trang của bạn -->
            <div class="content">
                <div class="cards">

                    <!-- == MONITORING ======================================================================================== -->
                    <div class="card">
                        <div class="card header">
                            <h3 style="font-size: 1rem;">Show</h3>
                        </div>

                        <!-- Hiển thị các giá trị -->
                        <h4 class="temperatureColor"><i class="fas fa-thermometer-half"></i>Temperature</h4>
                        <p class="temperatureColor"><span class="reading"><span id="ESP32_01_Temp"></span> &deg;C</span></p>
                        <h4 class="humidityColor"><i class="fas fa-tint"></i>Humidity</h4>
                        <p class="humidityColor"><span class="reading"><span id="ESP32_01_Humd"></span> &percnt;</span></p>
                        <h4 class="humidityColor">Soil Humidity</h4>
                        <p class="humidityColor"><span class="reading"><span id="ESP32_01_soil"></span> &percnt;</span></p>
                        <h4 class="humidityColor">Light</h4>
                        <p class="humidityColor"><span class="reading"><span id="ESP32_01_light"></span></p>
                    </div>
                    <!-- ======================================================================================================= -->

                    <!-- == CONTROLLING ======================================================================================== -->
                    <div class="card">
                        <div class="card header">
                            <h3 style="font-size: 1rem;">Controller</h3>
                        </div>
                        <p>PUMP STATUS: <span id="led-status">OFF</span></p>

                        <!-- Nút điều khiển -->
                        <h4 class="LEDColor">Pump 1</h4>
                        <label class="switch">
                            <input type="checkbox" id="ESP32_01_TogLED_01" onclick="GetTogBtnLEDState('ESP32_01_TogLED_01')">
                            <div class="sliderTS"></div>
                        </label>
                        <h4 class="LEDColor">Pump 2</h4>
                        <label class="switch">
                            <input type="checkbox" id="ESP32_01_TogLED_02" onclick="GetTogBtnLEDState('ESP32_01_TogLED_02')">
                            <div class="sliderTS"></div>
                        </label>
                    </div>
                    <!-- ======================================================================================================= -->

                </div>
            </div>

            <div class="content">
                <div class="cards">
                    <div class="card header" style="border-radius: 15px;">
                        <h3 style="font-size: 0.7rem;">Sensor Data [ <span id="ESP32_01_LTRD"></span> ]</h3>
                        <button onclick="window.open('recordtable.php', '_blank');">Open Record Table</Table></button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- == INPUT SENSOR PARAMETERS ================================================= -->
    <div class="card">
        <div class="card header">
            <h3 style="font-size: 1rem;">Set Auto Data</h3>
        </div>

        <form id="sensorLimitsForm">
            <table style="width: 100%;">
                <tr>
                    <th>Data</th>
                    <th>Min</th>
                    <th>Max</th>
                </tr>
                <tr>
                    <td>Temperature (&deg;C)</td>
                    <td><input type="number" id="tempMin" name="tempMin" step="0.1"></td>
                    <td><input type="number" id="tempMax" name="tempMax" step="0.1"></td>
                </tr>
                <tr>
                    <td>Humidity (%)</td>
                    <td><input type="number" id="humidityMin" name="humidityMin" step="1"></td>
                    <td><input type="number" id="humidityMax" name="humidityMax" step="1"></td>
                </tr>
                <tr>
                    <td>Soil Humidity (%)</td>
                    <td><input type="number" id="soilMin" name="soilMin" step="1"></td>
                    <td><input type="number" id="soilMax" name="soilMax" step="1"></td>
                </tr>
                <tr>
                    <td>Light</td>
                    <td><input type="number" id="lightMin" name="lightMin" step="1"></td>
                    <td><input type="number" id="lightMax" name="lightMax" step="1"></td>
                </tr>
            </table>
            <button type="button" onclick="saveSensorLimits()">Save</button>
        </form>
    </div>
    <!-- =========================================================================== -->

    <!-- Chèn script Chart.js và mã JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const temperatureCtx = document.getElementById('temperatureChart').getContext('2d');
        const humidityCtx = document.getElementById('humidityChart').getContext('2d');
        const soilCtx = document.getElementById('soilChart').getContext('2d');
        const lightCtx = document.getElementById('lightChart').getContext('2d');

        const temperatureChart = new Chart(temperatureCtx, {
            type: 'line',
            data: {
                labels: [],
                datasets: [{
                    label: 'Nhiệt độ',
                    borderColor: 'red',
                    data: [],
                }]
            },
            options: {
                responsive: true,
                scales: {
                    x: {
                        display: true,
                        title: {
                            display: true,
                            text: 'Thời gian'
                        }
                    },
                    y: {
                        display: true,
                        title: {
                            display: true,
                            text: 'Giá trị'
                        }
                    }
                }
            }
        });

        const humidityChart = new Chart(humidityCtx, {
            type: 'line',
            data: {
                labels: [],
                datasets: [{
                    label: 'Độ ẩm',
                    borderColor: 'blue',
                    data: [],
                }]
            },
            options: {
                responsive: true,
                scales: {
                    x: {
                        display: true,
                        title: {
                            display: true,
                            text: 'Thời gian'
                        }
                    },
                    y: {
                        display: true,
                        title: {
                            display: true,
                            text: 'Giá trị'
                        }
                    }
                }
            }
        });

        const soilChart = new Chart(soilCtx, {
            type: 'line',
            data: {
                labels: [],
                datasets: [{
                    label: 'Độ ẩm đất',
                    borderColor: 'green',
                    data: [],
                }]
            },
            options: {
                responsive: true,
                scales: {
                    x: {
                        display: true,
                        title: {
                            display: true,
                            text: 'Thời gian'
                        }
                    },
                    y: {
                        display: true,
                        title: {
                            display: true,
                            text: 'Giá trị'
                        }
                    }
                }
            }
        });

        const lightChart = new Chart(lightCtx, {
            type: 'line',
            data: {
                labels: [],
                datasets: [{
                    label: 'Ánh sáng',
                    borderColor: 'yellow',
                    data: [],
                }]
            },
            options: {
                responsive: true,
                scales: {
                    x: {
                        display: true,
                        title: {
                            display: true,
                            text: 'Thời gian'
                        }
                    },
                    y: {
                        display: true,
                        title: {
                            display: true,
                            text: 'Giá trị'
                        }
                    }
                }
            }
        });

        // Kết nối SSE và cập nhật biểu đồ
        if (typeof(EventSource) !== "undefined") {
            const source = new EventSource("sse.php");
            source.onmessage = function(event) {
                const data = JSON.parse(event.data);

                //AUTO CONTROL(LED01)
                const ledStatusElement = document.getElementById("led-status");
                ledStatusElement.innerText = data.LED_01 == "ON" ? "ON" : "OFF";
                ledStatusElement.className = data.LED_01 == "ON" ? "ON" : "OFF"; //
                // Sử dụng thời gian từ cơ sở dữ liệu
                const timeFromDatabase = data.time;

                // Cập nhật dữ liệu vào các biểu đồ
                updateChart(temperatureChart, timeFromDatabase, data.temperature);
                updateChart(humidityChart, timeFromDatabase, data.humidity);
                updateChart(soilChart, timeFromDatabase, data.soil);
                updateChart(lightChart, timeFromDatabase, data.light);
            };
        } else {
            console.log("Trình duyệt của bạn không hỗ trợ SSE.");
        }

        function updateChart(chart, time, value) {
            chart.data.labels.push(time);
            chart.data.datasets[0].data.push(value);

            // Giới hạn số lượng nhãn để tránh quá tải
            if (chart.data.labels.length > 8) {
                chart.data.labels.shift();
                chart.data.datasets.forEach(dataset => dataset.data.shift());
            }

            // Cập nhật biểu đồ
            chart.update();
        }
    </script>

    <!-- Các script khác  -->
    <script>
        //------------------------------------------------------------
        document.getElementById("ESP32_01_Temp").innerHTML = "0";
        document.getElementById("ESP32_01_Humd").innerHTML = "0";
        document.getElementById("ESP32_01_soil").innerHTML = "0";
        document.getElementById("ESP32_01_light").innerHTML = "0";
        //------------------------------------------------------------

        Get_Data("esp32_01");

        setInterval(myTimer, 1000); //timeUpdateSensorRecord

        //------------------------------------------------------------
        function myTimer() {
            Get_Data("esp32_01");
        }
        //------------------------------------------------------------

        function Get_Data(id) {
            var xmlhttp;
            if (window.XMLHttpRequest) {
                xmlhttp = new XMLHttpRequest();
            } else {
                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
            }
            xmlhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    const myObj = JSON.parse(this.responseText);
                    if (myObj.id == "esp32_01") {
                        document.getElementById("ESP32_01_Temp").innerHTML = myObj.temperature;
                        document.getElementById("ESP32_01_Humd").innerHTML = myObj.humidity;
                        document.getElementById("ESP32_01_soil").innerHTML = myObj.soil;
                        document.getElementById("ESP32_01_light").innerHTML = myObj.light;
                        updatePumpStatus(myObj.LED_01);
                        //document.getElementById("ESP32_01_LTRD").innerHTML = "Time : " + myObj.ls_time + " | Date : " + myObj.ls_date + " (dd-mm-yyyy)";
                        if (myObj.LED_01 == "ON") {
                            document.getElementById("ESP32_01_TogLED_01").checked = true;
                        } else {
                            document.getElementById("ESP32_01_TogLED_01").checked = false;
                        }
                        if (myObj.LED_02 == "ON") {
                            document.getElementById("ESP32_01_TogLED_02").checked = true;
                        } else {
                            document.getElementById("ESP32_01_TogLED_02").checked = false;
                        }
                    }
                }
            };
            xmlhttp.open("POST", "getdata.php", true);
            xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xmlhttp.send("id=" + id);
        }
        //------------------------------------------------------------

        function GetTogBtnLEDState(togbtnid) {
            var togbtnchecked = document.getElementById(togbtnid).checked;
            var togbtncheckedsend = togbtnchecked ? "ON" : "OFF";
            if (togbtnid == "ESP32_01_TogLED_01") {
                Update_LEDs("esp32_01", "LED_01", togbtncheckedsend);
            }
            if (togbtnid == "ESP32_01_TogLED_02") {
                Update_LEDs("esp32_01", "LED_02", togbtncheckedsend);
            }
        }
        //-----------------------------
        function updatePumpStatus(ledState) {
            // Cập nhật trạng thái máy bơm dựa trên giá trị LED_01
            if (ledState == "ON") {
                document.getElementById("ESP32_01_TogLED_01").checked = true;
                document.getElementById("led-status").innerHTML = "ON"; // Cập nhật trạng thái máy bơm
            } else {
                document.getElementById("ESP32_01_TogLED_01").checked = false;
                document.getElementById("led-status").innerHTML = "OFF"; // Cập nhật trạng thái máy bơm
            }
        }
        //------------------------------------------------------------

        function Update_LEDs(id, lednum, ledstate) {
            var xmlhttp;
            if (window.XMLHttpRequest) {
                xmlhttp = new XMLHttpRequest();
            } else {
                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
            }
            xmlhttp.open("POST", "updateLEDs.php", true);
            xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xmlhttp.send("id=" + id + "&lednum=" + lednum + "&ledstate=" + ledstate);
        }
        //------------------------------------------------------------
        // Lưu giới hạn cảm biến
        function saveSensorLimits() {
            const limits = {
                tempMin: document.getElementById("tempMin").value,
                tempMax: document.getElementById("tempMax").value,
                humidityMin: document.getElementById("humidityMin").value,
                humidityMax: document.getElementById("humidityMax").value,
                soilMin: document.getElementById("soilMin").value,
                soilMax: document.getElementById("soilMax").value,
                lightMin: document.getElementById("lightMin").value,
                lightMax: document.getElementById("lightMax").value,
            };
            localStorage.setItem('sensorLimits', JSON.stringify(limits));
        }

        // Kiểm tra giá trị và bật máy bơm
        let pumpState = false; // Biến theo dõi trạng thái máy bơm
        let pumpTimeout; // Biến lưu timeout cho máy bơm
        function checkAndControlPump(data) {
            const limits = JSON.parse(localStorage.getItem('sensorLimits'));
            // Chuyển đổi các giới hạn sang số
            const tempMin = parseFloat(limits.tempMin);
            const tempMax = parseFloat(limits.tempMax);
            const humidityMin = parseFloat(limits.humidityMin);
            const humidityMax = parseFloat(limits.humidityMax);
            const soilMin = parseFloat(limits.soilMin);
            const soilMax = parseFloat(limits.soilMax);
            const lightMin = parseFloat(limits.lightMin);
            const lightMax = parseFloat(limits.lightMax);
            //
            // Nếu máy bơm đang bật, không kiểm tra điều kiện trong 5 giây
            if (pumpState) {
                return; // Không làm gì nếu máy bơm đã bật
            }
            //
            if (data.temperature >= limits.tempMin && data.temperature <= limits.tempMax &&
                data.humidity >= limits.humidityMin && data.humidity <= limits.humidityMax &&
                data.soil >= limits.soilMin && data.soil <= limits.soilMax &&
                data.light >= limits.lightMin && data.light <= limits.lightMax) {

                // Bật máy bơm
                Update_LEDs('esp32_01', 'LED_01', 'ON');
                pumpState = true; // Đánh dấu máy bơm là đang bật
                // Đặt timeout để tắt máy bơm sau 5 giây
                pumpTimeout = setTimeout(() => {
                    Update_LEDs('esp32_01', 'LED_01', 'OFF');
                    pumpState = false; // Đánh dấu máy bơm là đã tắt
                }, 5000); // Máy bơm bật 5 giây

                // Đặt timeout để không xét điều kiện trong 5 giây tiếp theo
                setTimeout(() => {
                    pumpState = false; // Sau 5 giây, cho phép kiểm tra lại
                }, 5000); // 5 giây
            }
        }

        // Cập nhật hàm để kiểm tra máy bơm mỗi lần cập nhật dữ liệu
        function Get_Data(id) {
            var xmlhttp;
            if (window.XMLHttpRequest) {
                xmlhttp = new XMLHttpRequest();
            } else {
                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
            }
            xmlhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    const myObj = JSON.parse(this.responseText);
                    if (myObj.id == "esp32_01") {
                        document.getElementById("ESP32_01_Temp").innerHTML = myObj.temperature;
                        document.getElementById("ESP32_01_Humd").innerHTML = myObj.humidity;
                        document.getElementById("ESP32_01_soil").innerHTML = myObj.soil;
                        document.getElementById("ESP32_01_light").innerHTML = myObj.light;

                        // Kiểm tra máy bơm sau khi cập nhật dữ liệu
                        checkAndControlPump(myObj);
                    }
                }
            };
            xmlhttp.open("POST", "getdata.php", true);
            xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xmlhttp.send("id=" + id);
        }
    </script>
</body>

</html>