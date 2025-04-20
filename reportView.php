<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
$json = file_get_contents('http://iotserver.com/reportJSON.php');
$json2 = file_get_contents('http://iotserver.com/thresholdJSON.php');

$data = json_decode($json, true);
$data2 = json_decode($json2, true);

if (isset($data['record'], $data2['record'])) {
    $count = count($data['record']);
    $abnormalPower = 0;
    $latest = !empty($data2['record']) ? $data2['record'][0] : null;
    $latestThreshold = $latest['threshold'] ?? 'N/A';
    
    foreach ($data['record'] as $record) {
        if (isset($record['power']) && ($record['power'] > 100 || $record['power'] < 0)) {
            $abnormalPower++;
        }
    }

    echo "Total records: " . $count . "<br>";
    echo "Records with abnormal power states: " . $abnormalPower . "<br>";
    echo "Current Light Threshold: " . $latestThreshold;
    echo "<table>";
    echo "<tr><th>Device Timestamp</th><th>Server Timestamp</th><th>Power State</th><th>Collision</th></tr>";
    $found = false;
    foreach ($data['record'] as $record) {
        $power = $record['power'] ?? null;
        $collision = $record['collision'] ?? null;
        $devicetimestamp = $record['devicetimestamp'] ?? 'Unknown';
        $servertimestamp = $record['servertimestamp'] ?? 'Unknown';

        if ($power > 100) {
            $powerState = 'Power Surge';
        } elseif ($power < 0) {
            $powerState = 'Brownout';
        } else {
            $powerState = 'Normal';
        }

        if (($power > 100 || $power < 0) || $collision == 1) {
            echo "<tr>";
            echo "<td>$devicetimestamp</td>";
            echo "<td>$servertimestamp</td>";
            echo "<td>$powerState</td>";
            echo "<td>" . ($collision == 1 ? 'Yes' : 'No') . "</td>";
            echo "</tr>";
            $found = true;
        }
    }
    if (!$found) {
		echo "<tr><td colspan='3'>No abnormal power or collision events found.</td></tr>";
		}
		echo "</table>";
} else {
    echo "Invalid data format or no records found.";
}
?>
