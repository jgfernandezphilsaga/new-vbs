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

$existStmt = sqlsrv_query($conn, "SELECT * FROM transactions WHERE ref_req_no = ? AND details = ?", [$data['id'], 'VBS']);

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

        $updateQuery = "UPDATE [vbs_db_new].[dbo].[request_headers] SET 
                [status] = ?, [updated_at] = GETDATE(), [dept_approver_fullname] = ? WHERE [id] = ?";
        $params = [$mappedStatus, $data['approver_fullname'], $data['id']];
        sqlsrv_query($vbs_conn1, $updateQuery, $params);

        echo json_encode(['status' => 'success', 'message' => 'Approval partially updated (PENDING case)']);
    } elseif ($transactionStatus === 'IN-PROGRESS') {
        $updateQuery = "UPDATE [vbs_db_new].[dbo].[request_headers]
            SET [status] = ?, [updated_at] = GETDATE(), [gsd_manager_fullname] = ? WHERE [id] = ?";
        $params = ['1009', $data['approver_fullname'], $data['id']];
        sqlsrv_query($vbs_conn1, $updateQuery, $params);

        echo json_encode(['status' => 'success', 'message' => 'Approval fully updated (FULLY APPROVED case)']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Unhandled transaction status: ' . $transactionStatus]);
    }
}