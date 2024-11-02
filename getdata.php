<?php
include 'database.php';
session_start(); // Bắt đầu phiên làm việc

// Khởi tạo giá trị trước đó nếu chưa tồn tại trong session
if (!isset($_SESSION['last_valid'])) {
    $_SESSION['last_valid'] = [
        'temperature' => null,
        'humidity' => null,
        'soil' => null,
        'light' => null,
    ];
}

//---------------------------------------- Condition to check that POST value is not empty.
if (!empty($_POST)) {
    // Keep track post values
    $id = $_POST['id'];

    $myObj = (object)array();

    $pdo = Database::connect();
    $sql = 'SELECT * FROM esp32_dht11_leds_update WHERE id="' . $id . '"';

    foreach ($pdo->query($sql) as $row) {
        $date = date_create($row['date']);
        $dateFormat = date_format($date, "d-m-Y");

        // Kiểm tra và cập nhật giá trị nhiệt độ
        if ($row['temperature'] >= 1 && $row['temperature'] <= 99) {
            $_SESSION['last_valid']['temperature'] = $row['temperature'];
            $myObj->temperature = $row['temperature'];
        } else {
            $myObj->temperature = $_SESSION['last_valid']['temperature']; // Giữ lại giá trị cũ
        }

        // Kiểm tra và cập nhật giá trị độ ẩm
        if ($row['humidity'] >= 1 && $row['humidity'] <= 99) {
            $_SESSION['last_valid']['humidity'] = $row['humidity'];
            $myObj->humidity = $row['humidity'];
        } else {
            $myObj->humidity = $_SESSION['last_valid']['humidity']; // Giữ lại giá trị cũ
        }

        // Kiểm tra và cập nhật giá trị độ ẩm đất
        if ($row['soil'] >= 2 && $row['soil'] <= 99) {
            $_SESSION['last_valid']['soil'] = $row['soil'];
            $myObj->soil = $row['soil'];
        } else {
            $myObj->soil = $_SESSION['last_valid']['soil']; // Giữ lại giá trị cũ
        }

        // Kiểm tra và cập nhật giá trị ánh sáng
        if ($row['light'] >= 1 && $row['light'] <= 3000) {
            $_SESSION['last_valid']['light'] = $row['light'];
            $myObj->light = $row['light'];
        } else {
            $myObj->light = $_SESSION['last_valid']['light']; // Giữ lại giá trị cũ
        }

        $myObj->id = $row['id'];
        $myObj->status_read_sensor_dht11 = $row['status_read_sensor_dht11'];
        $myObj->LED_01 = $row['LED_01'];
        $myObj->LED_02 = $row['LED_02'];
        $myObj->ls_time = $row['time'];
        $myObj->ls_date = $dateFormat;

        $myJSON = json_encode($myObj);
        echo $myJSON;
    }
    Database::disconnect();
}
//---------------------------------------- 
