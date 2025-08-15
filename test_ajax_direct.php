<?php
// Simple test to check what the AJAX endpoint returns
// Access this via: http://localhost:8000/test_ajax_direct.php

echo "<h2>Testing AJAX Response</h2>";
echo "<p>This will make a request to the attendance data endpoint and show the response.</p>";

// Make a curl request to the endpoint
$url = 'http://localhost:8000/admin/absensi/data?month=8&year=2025&cabang=';

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_COOKIE, session_name() . '=' . session_id());

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "<h3>HTTP Status: $httpCode</h3>";
echo "<h3>Response:</h3>";
echo "<pre>";
echo htmlspecialchars($response);
echo "</pre>";

// Try to decode JSON
if ($httpCode == 200) {
    $data = json_decode($response, true);
    if ($data) {
        echo "<h3>Parsed Data:</h3>";
        echo "<p>Number of employees: " . count($data) . "</p>";
        
        if (count($data) > 0) {
            echo "<h4>First Employee:</h4>";
            $first = $data[0];
            echo "<ul>";
            echo "<li>Name: " . ($first['nama'] ?? 'N/A') . "</li>";
            echo "<li>Branch: " . ($first['cabang'] ?? 'N/A') . "</li>";
            echo "<li>Position: " . ($first['golongan'] ?? 'N/A') . "</li>";
            echo "</ul>";
            
            echo "<h4>Attendance for days 8-12:</h4>";
            for ($day = 8; $day <= 12; $day++) {
                if (isset($first['attendance'][$day])) {
                    $att = $first['attendance'][$day];
                    echo "<p>Day $day: ";
                    echo "jam_masuk=" . ($att['jam_masuk'] ?? 'null') . ", ";
                    echo "status=" . ($att['status'] ?? 'null') . ", ";
                    echo "source=" . ($att['source'] ?? 'null') . ", ";
                    echo "is_weekend=" . ($att['is_weekend'] ? 'true' : 'false') . ", ";
                    echo "is_holiday=" . ($att['is_holiday'] ? 'true' : 'false');
                    echo "</p>";
                }
            }
        }
    } else {
        echo "<p>Failed to parse JSON response</p>";
    }
}
?>