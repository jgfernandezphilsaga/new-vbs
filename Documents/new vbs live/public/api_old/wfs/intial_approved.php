<?php

include("config.php");

header("Access-Control-Allow-Origin: *");
header("Access-Control-Expose-Headers: Content-Length, X-JSON");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: *");
header('Content-Type: application/json');

file_put_contents("log.txt", json_encode($_POST, JSON_PRETTY_PRINT) . "\n", FILE_APPEND);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
    exit;
}

$data = [
    'id'                 => $_POST['trans_id'] ?? null,
    'workflow_token'     => $_POST['workflow_token'] ?? null,
    'details'            => $_POST['details'] ?? null,
    'approver'           => $_POST['approver'] ?? null,
    'current_approver'   => $_POST['current_approver'] ?? null,
    'approver_remarks'   => $_POST['approver_remarks'] ?? null,
    'status'             => $_POST['overallstatus'] ?? null,
    'overallstatus'      => $_POST['status'] ?? null,
    'lastapprover'       => $_POST['lastapprover'] ?? null,
    'nextapprover'       => $_POST['nextapprover'] ?? null,
    'approver_fullname'  => $_POST['approver_fullname'] ?? 'none',
    'approver_position'  => $_POST['approver_position'] ?? 'none',
    'wfs_trans_stat'     => $_POST['wfs_trans_stat'] ?? 'none',
];

 //echo json_encode($data);

$wfs_trans_stat = $data['wfs_trans_stat'];

if (!$data['id']) {
    echo json_encode(['status' => 'error', 'message' => 'Missing transid']);
    exit;
}

function isNightDrive($conn, $id) {
    $sql = "SELECT 1 FROM request_headers 
            WHERE id = ? 
            AND is_nightdrive = '1'";
    $stmt = sqlsrv_query($conn, $sql, [$id]);
    return $stmt && sqlsrv_has_rows($stmt);
}

$isNightDriveFlag  = isNightDrive($vbs_conn1, $data['id']);

$existStmt = sqlsrv_query($vbs_conn, "SELECT * FROM transactions WHERE ref_req_no = ? AND details = ?", [$data['id'], 'VBS']);

if ($existStmt === false) {
    echo json_encode(['status' => 'error', 'message' => 'Query failed', 'sqlsrv_errors' => sqlsrv_errors()]);
    exit;
}

$existData = sqlsrv_fetch_array($existStmt, SQLSRV_FETCH_ASSOC);

//echo json_encode();

if ($existData) {

    $transactionStatus = $existData['status'];

    if ($transactionStatus === 'PENDING') {

        if (in_array(strtoupper($data['overallstatus']), ['CANCELLED', 'APPROVED', 'HOLD'])) {
            $mappedStatus = match (strtoupper($data['overallstatus'])) {
                'CANCELLED' => '1013',
                'APPROVED'  => '1007',
                'HOLD'      => '5',
            };
        }

        $updateQuery = "
            UPDATE [vbs_db].[dbo].[request_headers]
            SET [status] = ?, [updated_at] = GETDATE(), [dept_approver_fullname] = ?
            WHERE [id] = ?
        ";
        $params = [$mappedStatus, $data['approver_fullname'], $data['id']];
        sqlsrv_query($vbs_conn1, $updateQuery, $params);

        echo json_encode(['status' => 'success', 'message' => 'Approval partially updated (PENDING case)']);
        return;

    } elseif ($transactionStatus === 'IN-PROGRESS') {


    $checkManagerQuery = "SELECT gsd_manager_fullname FROM request_headers WHERE id = ?";
    $managerResult = sqlsrv_query($vbs_conn1, $checkManagerQuery, [$data['id']]);
    $managerRow = sqlsrv_fetch_array($managerResult, SQLSRV_FETCH_ASSOC);
    $currentGsdManager = $managerRow['gsd_manager_fullname'] ?? null;


    if (empty($currentGsdManager)) {

        $selectQuery = "SELECT 1 FROM request_headers WHERE id = ? 
            AND (
                (is_emergency = '1' AND is_confidential = '1') 
                OR (is_confidential = '1' AND is_emergency = '0') 
                OR (is_confidential = '0' AND is_emergency = '1')
            )";
        $checkResult = sqlsrv_query($vbs_conn1, $selectQuery, [$data['id']]);
        $isEmergencyOrConf = $checkResult && sqlsrv_has_rows($checkResult);

        $statusCode = ($isEmergencyOrConf && $isNightDriveFlag) ? '1010' : '1009';

        $updateGsd = "
            UPDATE [vbs_db].[dbo].[request_headers]
            SET [status] = ?, [gsd_manager_fullname] = ?, [updated_at] = GETDATE()
            WHERE [id] = ?
        ";
        sqlsrv_query($vbs_conn1, $updateGsd, [$statusCode, $data['approver_fullname'], $data['id']]);

        echo json_encode(['status' => 'success', 'message' => 'Approval updated by GSD Manager (IN-PROGRESS case)']);
        return;

    }

    if (!empty($currentGsdManager) && $isNightDriveFlag) {
        $updateDiv = "
            UPDATE [vbs_db].[dbo].[request_headers]
            SET [division_manager] = ?, [updated_at] = GETDATE()
            WHERE [id] = ?
        ";
        sqlsrv_query($vbs_conn1, $updateDiv, [$data['approver_fullname'], $data['id']]);

        echo json_encode(['status' => 'success', 'message' => 'Division Manager updated for night drive']);
        return;
    }

    echo json_encode(['status' => 'error', 'message' => 'No matching IN-PROGRESS update condition']);
    return;
}

}
