<?php
header('Content-Type: text/event-stream');
header('Cache-Control: no-cache');
include 'database.php';

// Kết nối cơ sở dữ liệu
$pdo = Database::connect();

// Kiểm tra kết nối
if (!$pdo) {
    echo "data: " . json_encode(['error' => 'Không thể kết nối đến cơ sở dữ liệu']) . "\n\n";
    exit();
}

// Vòng lặp gửi dữ liệu liên tục mỗi giây
while (true) {
    // SQL Query để lấy dữ liệu mới nhất từ bảng
    $sql = 'SELECT * FROM esp32_dht11_leds_update ORDER BY id DESC LIMIT 1';

    try {
        foreach ($pdo->query($sql) as $row) {
            // Lấy giá trị thời gian từ cơ sở dữ liệu
            $data = [
                'temperature' => $row['temperature'],
                'humidity' => $row['humidity'],
                'soil' => $row['soil'],
                'light' => $row['light'],
                'time' => $row['time'], // Thời gian từ cơ sở dữ liệu
                'LED_01' => $row['LED_01']
            ];

            // Gửi dữ liệu dưới dạng JSON
            echo "data: " . json_encode($data) . "\n\n";
            ob_flush();
            flush();
        }
    } catch (PDOException $e) {
        echo "data: " . json_encode(['error' => $e->getMessage()]) . "\n\n";
        ob_flush();
        flush();
    }

    // Đợi 1 giây trước khi lặp lại
    sleep(1);
}

// Đóng kết nối cơ sở dữ liệu
Database::disconnect();
