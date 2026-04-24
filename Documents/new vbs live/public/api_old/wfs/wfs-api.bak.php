<?php

include(__DIR__ . '/config.php');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Expose-Headers: Content-Length, X-JSON");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: *");

// $transaction_type =  $data['type']; // VBS
// $transid = $data['transid']; // RequestHeader ID

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $response = [
        'token' => $_POST['token'] ?? null, 
        'type' => $_POST['type'] ?? null, 
        'refno' => $_POST['refno'] ?? null, 
        'sourceapp' => $_POST['sourceapp'] ?? null, 
        'sourceurl' => $_POST['sourceurl'] ?? null, 
        'requestor' => $_POST['requestor'] ?? null, 
        'department' => $_POST['department'] ?? null, 
        'transid' => $_POST['transid'] ?? null, 
        'email' => $_POST['email'] ?? null,
        'purpose' => $_POST['purpose'] ?? null, 
        'name' => $_POST['name'] ?? null, 
        'approval_url' => $_POST['approval_url'] ?? null,
        'status' => 'success',
        'message' => 'Request sent to WFS',
    ];
    
    $data = $response;

    $transid = $data['transid']; // RequestHeader ID

} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
}
    
$data_result = sqlsrv_fetch_array(sqlsrv_query($conn, "select * from allowed_transactions where name = '" . $response['type'] . "' ")); // OSTR'"));

// echo json_encode($transid);
try{
    if (isset($data_result) && isset($data['token'])) {
        // echo json_encode($data_result);
        if ($data_result['token'] == $data['token']) {
            $existData = sqlsrv_fetch_array(sqlsrv_query($conn, "select * from transactions where transid = '" . $transid . "' ")); // $data['transid']

            if(!$existData){
                // echo json_encode($existData);

                sqlsrv_query($conn, "update transactions set status = 'PENDING' where transid = '" . $transid . "' ");
                sqlsrv_query($conn, "update approval_status set status = 'PENDING' where transaction_id = '" . $existData['id'] . "' ");    

                echo json_encode(['status' => 'success', 'message' => 'Transaction UPDATED', 'exists' => isset($existData)]);    
            }else{
                // $insert = "insert into transactions (
                //                 ref_req_no,
                //                 source_app,
                //                 source_url,
                //                 details,
                //                 requestor,
                //                 totalamount,
                //                 converted_amount,
                //                 department,
                //                 transid,
                //                 email,status,
                //                 created_at,
                //                 currency,
                //                 purpose,
                //                 name,
                //                 approval_url
                //             ) values (
                //                 '" . $data['refno'] . "',
                //                 '" . $data['sourceapp'] . "',
                //                 '" . $data['sourceurl'] . "',
                //                 '" . $data['type'] . "',
                //                 '" . $data['requestor'] . "', 
                //                 0,0,
                //                 'IT Department',
                //                 '" . $data['transid'] . "',
                //                 '" . $data['email'] . "',
                //                 'PENDING', 
                //                 GETDATE(),
                //                 'PHP',
                //                 '" . $data['purpose'] . "',
                //                 '" . $data['name'] . "',
                //                 '".$data['approval_url']."'
                //             ); SELECT SCOPE_IDENTITY()";
                // '" . $data['department'] . "',

                // $result = sqlsrv_query($conn, $insert);
                // sqlsrv_next_result($result);
                // sqlsrv_fetch($result);

                // $insertedID = sqlsrv_get_field($result, 0);
                $insertedID = '35103';
                if (!isset($result)) {// ($result) {

                    $query = sqlsrv_query($conn, "select * from template_approvers where template_id = " . $data_result['template_id']);
                    // echo json_encode($insertedID);
                    
                    $count = 0;
                    $desigs = [];
                    $qrys = [];
                    $condition = [];
                    // $test_query = sqlsrv_fetch_array($query);
                    while ($qry = sqlsrv_fetch_array($query)) {

                        // print_r('pumasok');

                        if($qry['is_dynamic']=='YES' && $qry['designation']=='MANAGER') {
                            
                        // echo json_encode('pumasok');
                        // die;

                            $gdept = sqlsrv_fetch_array(sqlsrv_query($conn, "select department from transactions where transid = '" . $transid . "' "));
                            $gdivision = sqlsrv_fetch_array(sqlsrv_query($conn, "select * from users where department like '%" . $gdept['department'] . "%' "));
                            $gmanager = sqlsrv_fetch_array(sqlsrv_query($conn, "select * from users where  division like '%" . $gdivision['division'] . "%' AND department like '%" . $gdivision['department'] . "%' AND is_alternate = 0"));
                            $alt_gm = sqlsrv_fetch_array(sqlsrv_query($conn, "select * from users where  division like '%" . $gdivision['division'] . "%' AND department like '%" . $gdivision['department'] . "%' AND is_alternate = 1"));
                            $alt_gm_id = 0;

                            if(isset($alt_gm)){
                                $alt_gm_id = $alt_gm['id'];
                            }
                            
                            $query_result = sqlsrv_query($conn, "insert into approval_status (
                                                                    transaction_id,
                                                                    approver_id,
                                                                    alternate_approver_id,
                                                                    sequence_number,
                                                                    status,
                                                                    created_at,
                                                                    is_current
                                                                ) values (
                                                                    " . $insertedID . ",'
                                                                    " . $gmanager['id'] . "','
                                                                    " . $alt_gm_id . "','
                                                                    " . $qry['sequence_number'] . "',
                                                                    'PENDING',
                                                                    GETDATE(),
                                                                    1
                                                                ) ");
                            // sqlsrv_next_result($query_result);
                            // sqlsrv_fetch($query_result);

                        // }elseif($qry['is_dynamic']=='YES' && $qry['designation']=='DIVISION MANAGER') {

						// 	$gdept = sqlsrv_fetch_array(sqlsrv_query($conn,"select department,locsite from transactions where transid = '".$transid."' "));

						// 	$gdivision = sqlsrv_fetch_array(sqlsrv_query($conn,"select division from users where department like '%".$gdept['department']."%' "));							
						// 	if (empty($gdept['locsite'])) {
						// 		$gdmanager = sqlsrv_fetch_array(sqlsrv_query($conn,"select * from users where  designation like '%".$gdivision['division']."%' "));

						// 		$alt_gdm = sqlsrv_fetch_array(sqlsrv_query($conn,"select * from users where  designation like '%".$gdivision['division']."%' AND is_alternate = 1"));
								
						// 	} else {
						// 		$gdmanager = sqlsrv_fetch_array(sqlsrv_query($conn,"select * from users where  designation like '%DAVAO HEAD OFFICE DIVISION%' "));

						// 		$alt_gdm = sqlsrv_fetch_array(sqlsrv_query($conn,"select * from users where  designation like '%DAVAO HEAD OFFICE DIVISION%' AND is_alternate = 1"));
						// 	}

						// 	if(is_null($alt_gdm)) { $alt_gdm = ['id'=> 0]; }

						// 	$query_result = sqlsrv_query($conn,"insert into approval_status (
                        //                             transaction_id,
                        //                             approver_id,
                        //                             alternate_approver_id,
                        //                             sequence_number,
                        //                             status,
                        //                             created_at,
                        //                             is_current
                        //                         ) values (
                        //                             ".$insertedID.",
                        //                             '".$gdmanager['id']."',
                        //                             '".$alt_gdm['id']."',
                        //                             '".$qry['sequence_number']."',
                        //                             'PENDING',
                        //                             GETDATE(),
                        //                             0
                        //                          ) ");

                        }elseif($qry['designation']=='DISPATCHER'){ //($qry['condition']==null || $qry['condition']=='') && 

                            $query_result = sqlsrv_query($conn,"insert into approval_status (
                                                    transaction_id,
                                                    approver_id,
                                                    alternate_approver_id,
                                                    sequence_number,
                                                    status,
                                                    created_at,
                                                    is_current
                                                ) values (
                                                    ".$insertedID.",
                                                    '".$qry['approver_id']."',
                                                    '".$qry['alternate_approver_id']."',
                                                    '".$qry['sequence_number']."',
                                                    'PENDING',
                                                    GETDATE(),
                                                    0
                                                ) ");

                            if (isset($_POST['locsite']) && $_POST['locsite']=='DAVAO') {

                                sqlsrv_query($conn,"insert into approval_status (
                                                        transaction_id,
                                                        approver_id,
                                                        alternate_approver_id,
                                                        sequence_number,
                                                        status,
                                                        created_at,
                                                        is_current
                                                    ) values (
                                                        ".$insertedID.",
                                                        112,
                                                        0,
                                                        3,
                                                        'PENDING',
                                                        GETDATE(),
                                                        0
                                                    ) ");

                                sqlsrv_query($conn,"insert into approval_status (
                                                        transaction_id,
                                                        approver_id,
                                                        alternate_approver_id,
                                                        sequence_number,
                                                        status,
                                                        created_at,
                                                        is_current
                                                    ) values (
                                                        ".$insertedID.",
                                                        7,
                                                        0,
                                                        4,
                                                        'PENDING',
                                                        GETDATE(),
                                                        0
                                                    ) ");

                                break;
                            }else {
                                // echo json_encode($qry['designation']); //$qry['is_dynamic']=='YES' && 
    
                                // die;
                            }
                        } 
                        $count++;
                        array_push($desigs, $qry['designation']);
                        // array_push($qrys, $qry['is_dynamic']);
                        // array_push($condition, $qry['condition']);
                    }
                }
                
                // echo json_encode(['status' => 'success', 'message' => 'Transaction INSERTED', 'exists' => isset($existData), 'query' => $test_query, 'count' => $count, 'desigs' => $desigs, 'qrys' => $qrys, 'condition' => $condition]);
                echo json_encode(['status' => 'success', 'message' => 'Transaction INSERTED', 'exists' => isset($existData), 'count' => $count, 'desigs' => $desigs, 'qrys' => $qrys, 'condition' => $condition]);
                // echo json_encode($qry['is_dynamic']=='YES' && $qry['designation']=='MANAGER');
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Transaction not allowed']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Transaction not approved']);
    }

}catch(Exception $e){
    echo json_encode('Error: '.$e->getMessage());
}