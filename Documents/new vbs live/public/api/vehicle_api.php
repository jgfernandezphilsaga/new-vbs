<?php


// $conn_a['local']['type'] = 'sqlsrv';
// $conn_a['local']['host'] = ' 172.16.1.39';
// $conn_a['local']['name'] = 'vms_db';
// $conn_a['local']['uname'] = 'sa';
// $conn_a['local']['pword'] = 'M@st3radm1n12345';

// $conn_b['local']['type'] = 'sqlsrv';
// $conn_b['local']['host'] = ' 172.16.1.39';
// $conn_b['local']['name'] = 'driver-monitoring';
// $conn_b['local']['uname'] = 'sa';
// $conn_b['local']['pword'] = 'M@st3radm1n12345';


// $conn_c['local']['type'] = 'sqlsrv';
// $conn_c['local']['host'] = ' 172.16.1.39';
// $conn_c['local']['name'] = 'vbs_db';
// $conn_c['local']['uname'] = 'sa';
// $conn_c['local']['pword'] = 'M@st3radm1n12345';

$conn_a['local']['type'] = 'sqlsrv';
$conn_a['local']['host'] = '192.168.1.55';
$conn_a['local']['name'] = 'vms_db';
$conn_a['local']['uname'] = 'sa';
$conn_a['local']['pword'] = 'M@st3radm1n12345';

$conn_b['local']['type'] = 'sqlsrv';
$conn_b['local']['host'] = '192.168.1.55';
$conn_b['local']['name'] = 'driver-monitoring';
$conn_b['local']['uname'] = 'sa';
$conn_b['local']['pword'] = 'M@st3radm1n12345';


$conn_c['local']['type'] = 'sqlsrv';
$conn_c['local']['host'] = '192.168.1.55';
$conn_c['local']['name'] = 'vbs_db';
$conn_c['local']['uname'] = 'sa';
$conn_c['local']['pword'] = 'M@st3radm1n12345';


$vehicle_data = [];

if (isset($_GET['vehicle'])) {
    $vehicle = $_GET['vehicle'];

    try {
        $pdo = new PDO(
            $conn_a['local']['type'] . ":server=" . $conn_a['local']['host'] . ";Database=" . $conn_a['local']['name'],
            $conn_a['local']['uname'],
            $conn_a['local']['pword']
        );
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

       $sql = "SELECT t1.MODEL,[PLATE No#] as PLATE_NO
        FROM [vms_db].[dbo].[masters] t1
        LEFT JOIN [vbs_db].[dbo].[driver_vehicle_details] t2 ON t1.[PLATE No#] = t2.vehicle_details
        WHERE t2.vehicle_details IS NULL AND (MODEL LIKE ? OR [PLATE No#] LIKE ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(["%$vehicle%", "%$vehicle%"]);
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $vehicle_data[] = [
               'MODEL' => $row['MODEL'],
                'PLATE_NO' => $row['PLATE_NO']
            ];
        }

        echo json_encode($vehicle_data);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => $e->getMessage()]);
    }
}

$driver_data = [];

if (isset($_GET['driver_details'])) {
    $driver_details = $_GET['driver_details'];

    try {
        $pdo = new PDO(
            $conn_c['local']['type'] . ":server=" . $conn_c['local']['host'] . ";Database=" . $conn_c['local']['name'],
            $conn_c['local']['uname'],
            $conn_c['local']['pword']
        );
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

       $sql = "SELECT t1.employee_id,last_name,first_name
                FROM [driver-monitoring].[dbo].[driver_details] t1
                LEFT JOIN [vbs_db].[dbo].[driver_vehicle_details] t2 ON t1.employee_id = t2.driver_details
                WHERE t2.driver_details IS NULL and (last_name LIKE ? OR employee_id LIKE ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(["%$driver_details%", "%$driver_details%"]);
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $driver_data[] = [
               'first_name' => $row['first_name'],
                'last_name' => $row['last_name'],
                'employee_id' => $row['employee_id']
            ];
        }

        echo json_encode($driver_data);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => $e->getMessage()]);
    }
}

   
?>