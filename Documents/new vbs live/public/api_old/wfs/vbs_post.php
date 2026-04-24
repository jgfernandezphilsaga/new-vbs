<?php

include("config.php");

 header("Access-Control-Allow-Origin: *");
 header("Access-Control-Expose-Headers: Content-Length, X-JSON");
 header("Access-Control-Allow-Methods: POST");
 header("Access-Control-Allow-Headers: *");

 file_put_contents("log.txt", json_encode($_POST, JSON_PRETTY_PRINT));

 if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
     echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
     exit;
 }

// $data = [
//     'token'          => 'base64:1kyGTbxxsXC1qv2DsKd1GMNJLQTnA2YzkkoRMMZgf7Y=',
//     'type'           => 'VBS',
//     'refno'          => 'VBS20250716001',
//     'sourceapp'      => 'VEHICLE BOOKING SYSTEM',
//     'sourceurl'      => 'https:vbs.example.com',
//     'requestor'      => 'Juan Dela Cruz',
//     'department'     => 'ICT',
//     'transid'        => 'VBS20250716001',
//     'email'          => 'dummy@example.com',
//     'purpose'        => 'Company field inspection',
//     'name'           => 'Juan Dela Cruz',
//     'approval_url'   => 'https:vbs.example.com/approval/VBS20250716001',
//     'is_resubmitted' => 0,
//     'locsite'        => 'Main Office',
// ];


 $data = [
     'token'          => $_POST['token'] ?? null,
     'type'           => $_POST['type'] ?? null,
     'refno'          => $_POST['refno'] ?? null,
     'sourceapp'      => $_POST['sourceapp'] ?? null,
     'sourceurl'      => $_POST['sourceurl'] ?? null,
     'requestor'      => $_POST['requestor'] ?? null,
     'department'     => $_POST['department'] ?? 'IT Department',
     'transid'        => $_POST['transid'] ?? null,
     'email'          => $_POST['email'] ?? null,
     'purpose'        => $_POST['purpose'] ?? null,
     'name'           => $_POST['name'] ?? null,
     'approval_url'   => $_POST['approval_url'] ?? null,
     'is_resubmitted' => $_POST['is_resubmitted'] ?? 0,
     'locsite'        => $_POST['locsite'] ?? null,
     'nightdrive' => $_POST['nightdrive'] ?? null,
 ];

 //$token = 'base64:1kyGTbxxsXC1qv2DsKd1GMNJLQTnA2YzkkoRMMZgf7Y=';
 //$dataid= "VBS-000000002";

if (!$data['token'] || !$data['transid']) {
    echo json_encode(['status' => 'error', 'message' => 'Missing token or transid']);
    exit;
}


$existStmt = sqlsrv_query($vbs_conn, "SELECT * FROM transactions WHERE transid = ?", [$data['transid']]);

if ($existStmt === false) {

    if (($errors = sqlsrv_errors()) != null) {
        foreach ($errors as $error) {
            echo "SQLSTATE: " . $error['SQLSTATE'] . "<br />";
            echo "Code: " . $error['code'] . "<br />";
            echo "Message: " . $error['message'] . "<br />";
        }
    }
    exit; 
}

$existData = sqlsrv_fetch_array($existStmt, SQLSRV_FETCH_ASSOC);

if ($existData) {
    $status = ($data['is_resubmitted'] == 1) ? 'RESUBMITTED' : 'PENDING';
    sqlsrv_query($vbs_conn, "UPDATE transactions SET status = ? WHERE transid = ?", [$status, $data['transid']]);
    sqlsrv_query($vbs_conn, "UPDATE approval_status SET status = 'PENDING' WHERE transaction_id = ?", [$existData['id']]);
    echo json_encode(['status' => 'success', 'message' => 'Transaction UPDATED', 'exists' => true]);
    exit;
}

$insert = "INSERT INTO transactions(ref_req_no, source_app, source_url, details, requestor,totalamount, 
converted_amount, department, transid, email,status, created_at, currency, purpose, name, approval_url) 
VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'PENDING', GETDATE(), 'PHP', ?, ?, ?);SELECT SCOPE_IDENTITY() AS id;";

$params = [
    $data['refno'],
    $data['sourceapp'],
    $data['sourceurl'],
    $data['type'],
    $data['requestor'],
    0,
    0,
    $data['department'],
    $data['transid'],
    $data['email'],
    $data['purpose'],
    $data['name'],
    $data['approval_url']
];

$insert_stmt = sqlsrv_query($vbs_conn, $insert, $params);

if ($insert_stmt === false) {
    echo json_encode(['status' => 'error','message' => sqlsrv_errors()]);
    exit;
}

sqlsrv_next_result($insert_stmt);

sqlsrv_fetch($insert_stmt);
$insertedID = sqlsrv_get_field($insert_stmt, 0);

if (is_numeric($insertedID)) {
    echo json_encode(['status' => 'success', 'id' => (int)$insertedID]);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid ID returned.']);
}

$sql = "SELECT * FROM allowed_transactions WHERE token = ? and template_id='11'";
$params = [$data['token']];
$stmt = sqlsrv_query($vbs_conn, $sql, $params);


 $data_result = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
 if (!$data_result) {
     echo json_encode(['status' => 'error', 'message' => 'Invalid token']);
     exit;
 }

$template_id = '11';
 
 
$query = sqlsrv_query($vbs_conn, "SELECT * FROM template_approvers WHERE template_id = ?", [$template_id]);

$isCurrentSet = false; 
while ($qry = sqlsrv_fetch_array($query, SQLSRV_FETCH_ASSOC)) {

    if (empty($qry['condition']) && $qry['designation'] === 'MANAGER') {
        $isCurrent = $isCurrentSet ? 0 : 1;
        sqlsrv_query(
            $vbs_conn,
            "INSERT INTO approval_status (transaction_id, approver_id, alternate_approver_id, sequence_number, status, created_at, is_current) 
             VALUES (?, ?, ?, ?, 'PENDING', GETDATE(), ?)",
            [$insertedID, $qry['approver_id'], $qry['alternate_approver_id'], $qry['sequence_number'], $isCurrent]
        );
        $isCurrentSet = true;
    }

    // if($data['nightdrive']==='1' && $qry['condition'] === 'nightdrive') {
    //     $isCurrent = $isCurrentSet ? 0 : 1;
    //     sqlsrv_query(
    //         $vbs_conn,
    //         "INSERT INTO approval_status (transaction_id, approver_id, alternate_approver_id, sequence_number, status, created_at, is_current) 
    //          VALUES (?, ?, ?, ?, 'PENDING', GETDATE(), ?)",
    //         [$insertedID, $qry['approver_id'], $qry['alternate_approver_id'], $qry['sequence_number'], $isCurrent]
    //     );
    //     $isCurrentSet = true;
    // }
}

 
 echo json_encode(['status' => 'success', 'message' => 'Transaction INSERTED', 'exists' => false]);