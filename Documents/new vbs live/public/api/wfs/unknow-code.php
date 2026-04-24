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
 ];

 //$token = 'base64:1kyGTbxxsXC1qv2DsKd1GMNJLQTnA2YzkkoRMMZgf7Y=';
 //$dataid= "VBS-000000002";

if (!$data['token'] || !$data['transid']) {
    echo json_encode(['status' => 'error', 'message' => 'Missing token or transid']);
    exit;
}

$existStmt = sqlsrv_query($conn, "SELECT * FROM transactions WHERE transid = ?", [$data['transid']]);

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
    sqlsrv_query($conn, "UPDATE transactions SET status = ? WHERE transid = ?", [$status, $data['transid']]);
    sqlsrv_query($conn, "UPDATE approval_status SET status = 'PENDING' WHERE transaction_id = ?", [$existData['id']]);
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

$insert_stmt = sqlsrv_query($conn, $insert, $params);

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

$sql = "SELECT * FROM allowed_transactions WHERE token = ?";
$params = [$data['token']];
$stmt = sqlsrv_query($conn, $sql, $params);


 $data_result = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
 if (!$data_result) {
     echo json_encode(['status' => 'error', 'message' => 'Invalid token']);
     exit;
 }

$template_id = $data_result['template_id'];
 
 
$query = sqlsrv_query($conn, "SELECT * FROM template_approvers WHERE template_id = ?", [$template_id]); //7

$isCurrentSet = false; 
 while ($qry = sqlsrv_fetch_array($query, SQLSRV_FETCH_ASSOC)) {
      if ($qry['is_dynamic'] == 'YES' && $qry['designation'] == 'MANAGER') {
         // $gdept = sqlsrv_fetch_array(sqlsrv_query($conn, "SELECT department FROM transactions WHERE transid = ?", [$dataid])); //this will get the department name from this we can get the name of department manager from users table --ICT (tama)

         // $gdivision = sqlsrv_fetch_array(sqlsrv_query($conn, "SELECT DISTINCT(division) FROM users WHERE department LIKE ?", ['%' . $gdept['department'] . '%'])); //from gdepart variable we can get the division --ADMINISTRATION DIVISION (tama)

         // $gmanager = sqlsrv_fetch_array(sqlsrv_query($conn, "SELECT * FROM users WHERE division LIKE ? AND department LIKE ? AND is_alternate = 0", ['%' . $gdivision['division'] . '%', '%' . $gdivision['department'] . '%'])); //from gdivision variable AND with department field we can get the name of the name of the manager as long as is_alternate = 0 --ICT AND ADMINISTRATION DIVISION AND is_alternate=0  --Regiland S. Regalado (tama)

         // $alt_gm = sqlsrv_fetch_array(sqlsrv_query($conn, "SELECT * FROM users WHERE division LIKE ? AND department LIKE ? AND is_alternate = 1", ['%' . $gdivision['division'] . '%', '%' . $gdivision['department'] . '%'])) ?? ['id' => 0]; //we can get the alternate approver or manager as long as is_alternate = 1 --ICT AND ADMINISTRATION DIVISION AND is_alternate=1  --Dante D. Llana Jr.

         // sqlsrv_query($conn, "INSERT INTO approval_status (transaction_id, approver_id, alternate_approver_id, sequence_number, status, created_at, is_current) 
         //     VALUES (?, ?, ?, ?, 'PENDING', GETDATE(), 1)", [$insertedID, $gmanager['id'], $alt_gm['id'], $qry['sequence_number']]);

        $gdept = sqlsrv_fetch_array(sqlsrv_query($conn,"select department from transactions where transid = '".$data['transid']."' "));

        $gdivision = sqlsrv_fetch_array(sqlsrv_query($conn,"select * from users where department like '%".$gdept['department']."%' "));
                                    
        $gmanager = sqlsrv_fetch_array(sqlsrv_query($conn,"select * from users where  division like '%".$gdivision['division']."%' AND department like '%".$gdivision['department']."%' AND is_alternate = 0"));

        $alt_gm = sqlsrv_fetch_array(sqlsrv_query($conn,"select * from users where  division like '%".$gdivision['division']."%' AND department like '%".$gdivision['department']."%' AND is_alternate = 1"));

        if(is_null($alt_gm)) { $alt_gm = ['id'  => 0]; }

        sqlsrv_query($conn,"insert into approval_status (transaction_id,approver_id,alternate_approver_id,sequence_number,status,created_at,is_current) values (".$insertedID.",'".$gmanager['id']."','".$alt_gm['id']."','".$qry['sequence_number']."','PENDING',GETDATE(),1) ");

         sqlsrv_query($conn,"insert into approval_status (transaction_id,approver_id,alternate_approver_id,sequence_number,status,created_at,is_current) values (".$insertedID.",43,0,'".$qry['sequence_number']."','PENDING',GETDATE(),0) ");
            
     }
     elseif (empty($qry['condition']) && $qry['designation'] === 'MANAGER') {
        $isCurrent = $isCurrentSet ? 0 : 1;
         sqlsrv_query($conn, "INSERT INTO approval_status (transaction_id, approver_id, alternate_approver_id, seqce_number, status, created_at, is_current) 
             VALUES (?, ?, ?, ?, 'PENDING', GETDATE(),?)", [$insertedID, $qry['approver_id'], $qry['alternate_approver_id'], $qry['sequence_number'],$isCurrent]);
            $isCurrentSet = true;
    
            if ($data['locsite'] === 'DAVAO') {
             sqlsrv_query($conn, "INSERT INTO approval_status (transaction_id, approver_id, alternate_approver_id, sequence_number, status, created_at, is_current)
                 VALUES ($insertedID, 112, 0, 3, 'PENDING', GETDATE(), 0)");
             sqlsrv_query($conn, "INSERT INTO approval_status (transaction_id, approver_id, alternate_approver_id, sequence_number, status, created_at, is_current)
                 VALUES ($insertedID, 7, 0, 4, 'PENDING', GETDATE(), 0)");
             break;
         }
     }

 }
 
 echo json_encode(['status' => 'success', 'message' => 'Transaction INSERTED', 'exists' => false]);