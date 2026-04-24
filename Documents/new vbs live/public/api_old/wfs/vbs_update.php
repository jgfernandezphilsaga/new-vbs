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


$transIds = $_POST['transid'] ?? [];

if (!is_array($transIds)) {
    $transIds = [$transIds]; 
}


if (empty($transIds)) {
    echo json_encode(['status' => 'error', 'message' => 'Missing transid(s)']);
    exit;
}

$results = [];

foreach ($transIds as $transid) {
    $transid = trim($transid);
    if (!$transid) continue;

    $existStmt = sqlsrv_query(
        $vbs_conn, 
        "SELECT * FROM transactions WHERE transid = ?", 
        [$transid]
    );
    $existData = sqlsrv_fetch_array($existStmt, SQLSRV_FETCH_ASSOC);

    if ($existData) {

        sqlsrv_query(
            $vbs_conn,
            "UPDATE approval_status 
             SET status = 'APPROVED', is_current = 0 
             WHERE transaction_id = ? AND is_current = 1",
            [$existData['id']]
        );


        $nextApproverStmt = sqlsrv_query(
            $vbs_conn,
            "SELECT TOP 1 id FROM approval_status 
             WHERE transaction_id = ? AND status = 'PENDING' 
             ORDER BY sequence_number ASC",
            [$existData['id']]
        );

        if ($nextApprover = sqlsrv_fetch_array($nextApproverStmt, SQLSRV_FETCH_ASSOC)) {
            sqlsrv_query(
                $vbs_conn,
                "UPDATE approval_status SET is_current = 1 WHERE id = ?",
                [$nextApprover['id']]
            );
        }

        $results[] = ['transid' => $transid, 'status' => 'success'];
    } else {
        $results[] = ['transid' => $transid, 'status' => 'error', 'message' => 'Transaction not found'];
    }
}

echo json_encode(['status' => 'done', 'results' => $results]);
