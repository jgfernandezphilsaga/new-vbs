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
    'reference_id' => $_POST['reference_id'] ?? null,
    'id' => $_POST['trans_id'] ?? null,
    'workflow_token' => $_POST['workflow_token'] ?? null,
    'details' => $_POST['details'] ?? null,
    'approver' => $_POST['approver'] ?? null,
    'current_approver' => $_POST['current_approver'] ?? null,
    'approver_remarks' => $_POST['approver_remarks'] ?? null,
    'status' => $_POST['overallstatus'] ?? null,
    'overallstatus' => $_POST['status'] ?? null,
    'lastapprover' => $_POST['lastapprover'] ?? null,
    'nextapprover' => $_POST['nextapprover'] ?? null,
    'approver_fullname' => $_POST['approver_fullname'] ?? 'none',
    'approver_position' => $_POST['approver_position'] ?? 'none',
    'wfs_trans_stat' => $_POST['wfs_trans_stat'] ?? 'none',
];


function checkQuery($stmt) {
    if ($stmt === false) {
        if (($errors = sqlsrv_errors()) != null) {
            foreach ($errors as $error) {
                echo "SQLSTATE: " . $error['SQLSTATE'] . "<br />";
                echo "Code: " . $error['code'] . "<br />";
                echo "Message: " . $error['message'] . "<br />";
            }
        } else {
            echo "Unknown SQL error occurred.<br />";
        }
        exit;
    }
}

if (strpos($data['reference_id'], 'D-VBS') !== false) {

    $existStmt = sqlsrv_query(
        $vbs_conn1,
        "SELECT header_id FROM dispatch_table WHERE dispatch_reference = ?",
        [$data['reference_id']]
    );
    checkQuery($existStmt);

    $statusInput = trim(strtoupper($data['overallstatus']));

    $mappedStatus = match ($statusInput) {
        'CANCELLED' => '1013',
        'HOLD'      => '5',
        default     => null,
    };

    $headerIds = [];
    while ($row = sqlsrv_fetch_array($existStmt, SQLSRV_FETCH_ASSOC)) {
        if (!empty($row['header_id'])) {
            $ids = explode(',', $row['header_id']);
            foreach ($ids as $id) {
                $headerIds[] = (int)trim($id);
            }
        }
    }

    if (!empty($headerIds)) {
        $placeholders = implode(',', array_fill(0, count($headerIds), '?'));
        $sql = "SELECT * FROM request_headers WHERE id IN ($placeholders)";
        $request_headers = sqlsrv_query($vbs_conn1, $sql, $headerIds);
        checkQuery($request_headers);

        $pendingIds = [];
        $approvedIds = [];
        $nightdriveApprovedIds = [];

        while ($row = sqlsrv_fetch_array($request_headers, SQLSRV_FETCH_ASSOC)) {
            $rowId = (int)$row['id'];
            $nightdrive = (int)$row['is_nightdrive'];

            if ($data['status'] === 'PARTIALLY APPROVED' && $nightdrive === 1) {
                $pendingIds[] = $rowId;
            }

            if ($data['status'] === 'FULLY APPROVED' && $nightdrive === 0) {
                $approvedIds[] = $rowId;
            }

            if ($data['status'] === 'FULLY APPROVED' && $nightdrive === 1) {
                $nightdriveApprovedIds[] = $rowId;
            }
        }


        if (!empty($pendingIds)) {
            $stmt = sqlsrv_query(
                $vbs_conn1,
                "UPDATE dispatch_table SET status = ? WHERE dispatch_reference = ?",
                ["PENDING", $data['reference_id']]
            );
            checkQuery($stmt);

            $placeholders = implode(',', array_fill(0, count($pendingIds), '?'));
            $sql = "UPDATE request_headers SET status = ?, gsd_manager_fullname = ? WHERE id IN ($placeholders)";
            $params = array_merge([1011, $data['approver_fullname']], $pendingIds);
            $stmt = sqlsrv_query($vbs_conn1, $sql, $params);
            checkQuery($stmt);

            echo json_encode([
                'status' => 'success',
                'message' => 'Transaction UPDATED (PENDING)',
                'exists'  => true
            ]);
            exit;
        }

        if (!empty($approvedIds)) {
            $stmt = sqlsrv_query(
                $vbs_conn1,
                "UPDATE dispatch_table SET status = ? WHERE dispatch_reference = ?",
                ["APPROVED", $data['reference_id']]
            );
            checkQuery($stmt);

            $placeholders = implode(',', array_fill(0, count($approvedIds), '?'));
            $sql = "UPDATE request_headers SET status = ?, gsd_manager_fullname = ? WHERE id IN ($placeholders)";
            $params = array_merge([1010, $data['approver_fullname']], $approvedIds);
            $stmt = sqlsrv_query($vbs_conn1, $sql, $params);
            checkQuery($stmt);

            echo json_encode([
                'status' => 'success',
                'message' => 'Transaction UPDATED (FULLY APPROVED)',
                'exists'  => true
            ]);
            exit;
        }

        if (!empty($nightdriveApprovedIds)) {
            $stmt = sqlsrv_query(
                $vbs_conn1,
                "UPDATE dispatch_table SET status = ? WHERE dispatch_reference = ?",
                ["APPROVED", $data['reference_id']]
            );
            checkQuery($stmt);

            $placeholders = implode(',', array_fill(0, count($nightdriveApprovedIds), '?'));
            $sql = "UPDATE request_headers SET status = ?, division_manager = ? WHERE id IN ($placeholders)";
            $params = array_merge([1010, $data['approver_fullname']], $nightdriveApprovedIds);
            $stmt = sqlsrv_query($vbs_conn1, $sql, $params);
            checkQuery($stmt);

            echo json_encode([
                'status' => 'success',
                'message' => 'Transaction UPDATED (APPROVED + NIGHTDRIVE)',
                'exists'  => true
            ]);
            exit;
        }

        if ($statusInput === 'HOLD') {
    $stmt = sqlsrv_query(
        $vbs_conn1,
        "UPDATE dispatch_table SET status = ? WHERE dispatch_reference = ?",
        ["HOLD", $data['reference_id']]
    );
    checkQuery($stmt);

    $placeholders = implode(',', array_fill(0, count($headerIds), '?'));
    $sql = "UPDATE request_headers SET status = ? WHERE id IN ($placeholders)";
    $params = array_merge([$mappedStatus], $headerIds);
    $stmt = sqlsrv_query($vbs_conn1, $sql, $params);
    checkQuery($stmt);

    echo json_encode([
        'status' => 'success',
        'message' => 'Transaction UPDATED (HOLD)',
        'exists'  => true
    ]);
    exit;
}

if ($statusInput === 'CANCELLED') {
    $stmt = sqlsrv_query(
        $vbs_conn1,
        "UPDATE dispatch_table SET status = ? WHERE dispatch_reference = ?",
        ["CANCELLED", $data['reference_id']]
    );
    checkQuery($stmt);

    $placeholders = implode(',', array_fill(0, count($headerIds), '?'));
    $sql = "UPDATE request_headers SET status = ? WHERE id IN ($placeholders)";
    $params = array_merge([$mappedStatus], $headerIds);
    $stmt = sqlsrv_query($vbs_conn1, $sql, $params);
    checkQuery($stmt);

    echo json_encode([
        'status' => 'success',
        'message' => 'Transaction UPDATED (CANCELLED)',
        'exists'  => true
    ]);
    exit;
}

    }
}



if (strpos($data['reference_id'], 'VBS') !== false) {

    $existStmt = sqlsrv_query($vbs_conn, "SELECT * FROM transactions WHERE ref_req_no = ? AND details = ?", [$data['id'], 'VBS']);

if ($existStmt === false) {
    echo json_encode(['status' => 'error', 'message' => 'Query failed', 'sqlsrv_errors' => sqlsrv_errors()]);
    exit;
}

$existData = sqlsrv_fetch_array($existStmt, SQLSRV_FETCH_ASSOC);


if ($existData) {

    $transactionStatus = $existData['status'];

    if ($transactionStatus === 'PENDING') {
        $statusInput = trim(strtoupper($data['overallstatus']));

        $mappedStatus = match ($statusInput) {
            'CANCELLED' => '1013',
            'APPROVED'  => '1007',
            'HOLD'      => '5',
            default     => null,
        };

        if ($mappedStatus !== null) {
            if ($statusInput === 'APPROVED') {

                $updateQuery = "
                    UPDATE [vbs_db].[dbo].[request_headers]
                    SET [status] = ?, [updated_at] = GETDATE(), [dept_approver_fullname] = ?
                    WHERE [id] = ?
                ";
                $params = [$mappedStatus, $data['approver_fullname'], $data['id']];
            } else {

                $updateQuery = "
                    UPDATE [vbs_db].[dbo].[request_headers]
                    SET [status] = ?, [updated_at] = GETDATE()
                    WHERE [id] = ?
                ";
                $params = [$mappedStatus, $data['id']];
            }

            sqlsrv_query($vbs_conn1, $updateQuery, $params);

            echo json_encode(['status' => 'success', 'message' => 'Approval partially updated (PENDING case)']);
            return;
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Invalid overallstatus value']);
            return;
        }
    }
}

}



