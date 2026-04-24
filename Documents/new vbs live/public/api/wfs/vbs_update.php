<?php
include("config.php");

header("Access-Control-Allow-Origin: *");
header("Access-Control-Expose-Headers: Content-Length, X-JSON");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: *");

file_put_contents("log.txt", json_encode($_POST, JSON_PRETTY_PRINT) . "\n", FILE_APPEND);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
    exit;
}

$data = [
    'transid' => $_POST['transid'] ?? null,
];

if (!$data['transid']) {
    echo json_encode(['status' => 'error', 'message' => 'Missing transid']);
    exit;
}

$existStmt = sqlsrv_query($conn, "SELECT * FROM transactions WHERE transid = ?", [$data['transid']]);
$existData = sqlsrv_fetch_array($existStmt, SQLSRV_FETCH_ASSOC);

if ($existData) {
    sqlsrv_query($conn, "UPDATE approval_status SET status = 'APPROVED', is_current = 0 WHERE transaction_id = ? AND is_current = 1", [$existData['id']]);

    $nextApproverStmt = sqlsrv_query($conn,"SELECT TOP 1 id FROM approval_status WHERE transaction_id = ? AND status = 'PENDING' ORDER BY sequence_number ASC",[$existData['id']]);

    if ($nextApprover = sqlsrv_fetch_array($nextApproverStmt, SQLSRV_FETCH_ASSOC)) {
        sqlsrv_query($conn, "UPDATE approval_status SET is_current = 1 WHERE id = ?", [$nextApprover['id']]);
    }

    echo json_encode(['status' => 'success', 'message' => 'Approval updated']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Transaction not found']);
}
