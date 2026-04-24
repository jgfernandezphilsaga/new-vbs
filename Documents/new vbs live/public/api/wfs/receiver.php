<?php
include("config.php");

      header ("Access-Control-Allow-Origin: *");
      header ("Access-Control-Expose-Headers: Content-Length, X-JSON");
      header ("Access-Control-Allow-Methods: POST");
      header ("Access-Control-Allow-Headers: *");
// print_r($_POST); die();
$token = "base64:Hxle0o3dpTUGQlpJy3dBbMhlDu9Y98uMqZEqFe/Upcs="; // workflow app_key

$id                 = $_POST['trans_id'];
$workflow_token     = $_POST['workflow_token'];
$details            = $_POST['details'];
$approver           = $_POST['approver'];
$current_approver   = $_POST['current_approver'];
$approver_remarks   = $_POST['approver_remarks'];
$status             = $_POST['overallstatus'];
$overallstatus      = $_POST['status'];
$lastapprover       = $_POST['lastapprover'];
$nextapprover       = $_POST['nextapprover'];
$approver_fullname  = $_POST['approver_fullname']; // ?? 'none';
$approver_position  = $_POST['approver_position']; // ?? 'none';
$wfs_trans_stat = $_POST['wfs_trans_stat']; // ?? 'none';


if($token == $workflow_token){


	// $sql_insert = "INSERT INTO transactions (trans_id
 //      ,details
 //      ,status
 //      ,approver
 //      ,approver_remarks
 //      ,created_at
 //      ,updated_at
 //       )
 //      VALUES ('".$id."'
 //      ,'".$details."'
 //      ,'".$status."'
 //      ,'".$approver."'
 //      ,'".$approver_remarks."'
 //      ,GETDATE()
 //      ,GETDATE())";

      // die($sql_insert);

//	$insert = sqlsrv_query($conn,$sql_insert);

    if ($details == 'OREM CASH ADVANCE' || $details =='DAVAO OREM CASH ADVANCE'){
        $table ="caheaders";
    }elseif ($details=='OREM TRAVEL ORDER' || $details =='DAVAO OREM TRAVEL ORDER'){
        $table ="toheaders";
    }elseif ($details =='OREM LIQUIDATION' || $details =='DAVAO OREM LIQUIDATION'){
        $table ="liqheaders";
    }else{
      
      // TESTING
      // $table ="rfpheaders";
        $table ="request_headers";
    }

    $test_str = "";

    $is_previously_hold = false;

    $sql_update = "UPDATE ".$table." SET updated_at=GETDATE()";

    if($status!='CANCELLED'){
        if ($details == 'VBS') { //  && $status != 'FULLY APPROVED'
            $test_str = 'no';
            // Initial sql query
            // $sql_update = "UPDATE ".$table." SET  
            //                 updated_at=GETDATE()";
            if ($status == 'HOLD') {
                $sql_update .= ", status=5, is_resubmitted=1";

            }else{
                switch ($current_approver) {    
                    case "MANAGER":
                        if ($approver_position == 'GENERAL SERVICES MANAGER' || $wfs_trans_stat == 'FULLY APPROVED') { // If request is from GSD managers                     
                            $test_str = 'genmngr';
    
                            $sql_update .= ", gsd_manager_fullname='". $approver_fullname ."', status=3";
            
                        } else { // If request is from department managers
                            $test_str = 'deptmngr';
    
                            $sql_update .= ", dept_approver_fullname='". $approver_fullname ."', status=2"; 
                        }
                        
                        break;
    
                    case "DISPATCHER":
                        // If a status is added to VBS for when a dispatcher approves, add the status_code here 
                        $sql_update .= ", status=2";
                        
                        break;
    
                    default:
                        break;
                }

                $hold_qry = "SELECT TOP 1 * FROM remarks WHERE request_header_id = ". $id ." AND
                                                        sender_name LIKE '%". $approver_fullname ."%' AND
                                                        status LIKE '%HOLD%' 
                                                        ORDER BY id DESC";
                                                        
                $hold_remark = sqlsrv_query($vbs_conn, $hold_qry);
                $hold_remark_result = sqlsrv_fetch_array($hold_remark, SQLSRV_FETCH_ASSOC);

                if($hold_remark_result != false){
                    $is_previously_hold = true;
                    
                    // $hold_remark_result = sqlsrv_fetch_array($hold_remark, SQLSRV_FETCH_ASSOC);
                } 
            }
        } 
        // else if (($details!='OREM LIQUIDATION' || $details!='DAVAO OREM LIQUIDATION') && $status=='FULLY APPROVED') {
        //     $test_str = "OREM LIQUIDATION AND DAVAO OREM LIQUIDATION";

        //     $saccounting='Processing Request';

        //     // $sql_update = "UPDATE ".$table." SET status='".$status."', overallstatus='".$overallstatus."', saccounting='".$saccounting."' ,current_approver='".$approver."', next_approver='".$nextapprover."', approver_remarks='".$approver_remarks."', stages=CONCAT(stages,'->','".$approver."',' ',FORMAT (GETDATE(), 'MM-dd-yyyy hh:mm:ss ')) WHERE id=".$id;

        //     $sql_update = "UPDATE ".$table." SET status='".$status."', overallstatus='".$overallstatus."', saccounting='".$saccounting."' ,current_approver='".$approver."', next_approver='".$nextapprover."', approver_remarks='".$approver_remarks."', stages=CONCAT(stages,'->','".$status."','>','".$approver."', ' ','(',FORMAT (GETDATE(), 'MM-dd-yyyy hh:mm:ss '),')') WHERE id=".$id;

        // } elseif (($details!='OREM LIQUIDATION' || $details!='DAVAO OREM LIQUIDATION') && $status!='FULLY APPROVED') {

        //     // $sql_update = "UPDATE ".$table." SET status='".$status."', overallstatus='".$overallstatus."', current_approver='".$approver."', next_approver='".$nextapprover."', approver_remarks='".$approver_remarks."', stages=CONCAT(stages,'->','".$approver."',' ',FORMAT (GETDATE(), 'MM-dd-yyyy hh:mm:ss ')) WHERE id=".$id;

        //     $sql_update = "UPDATE ".$table." SET status='".$status."', overallstatus='".$overallstatus."', current_approver='".$approver."', next_approver='".$nextapprover."', approver_remarks='".$approver_remarks."', stages=CONCAT(stages,'->','".$status."','>','".$approver."', ' ','(',FORMAT (GETDATE(), 'MM-dd-yyyy hh:mm:ss '),')') WHERE id=".$id;

        // } else {
        //     $test_str = "NOT OREM AND VBS ";
        //     $saccounting='Liquidated';

        //     // $sql_update = "UPDATE ".$table." SET status='".$status."', overallstatus='".$overallstatus."', saccounting='".$saccounting."', current_approver='".$approver."', next_approver='".$nextapprover."', approver_remarks='".$approver_remarks."', stages=CONCAT(stages,'->','".$approver."', ' ',FORMAT (GETDATE(), 'MM-dd-yyyy hh:mm:ss ')) WHERE id=".$id;

        //     $sql_update = "UPDATE ".$table." SET status='".$status."', overallstatus='".$overallstatus."', saccounting='".$saccounting."', current_approver='".$approver."', next_approver='".$nextapprover."', approver_remarks='".$approver_remarks."',  stages=CONCAT(stages,'->','".$status."','>','".$approver."', ' ','(',FORMAT (GETDATE(), 'MM-dd-yyyy hh:mm:ss '),')')  WHERE id=".$id;
        // }

    } else {
        // Use status ids from vbs_db

        $sql_update .= ", status=6"; // status 6 is CANCELLED(WFS) / DISAPPROVED(VBS)
    }

    if ($is_previously_hold) {
        $sql_remarks = "UPDATE remarks SET 
                                        remarks = '". $approver_remarks ."'
                                        , status = '". $status ."' 
                                    WHERE id = ". $hold_remark_result['id'];
    } else {
        $sql_remarks = "INSERT INTO remarks (
                                                request_header_id,
                                                remarks,
                                                sender_name,
                                                sender_position,
                                                is_read,
                                                created_at,
                                                status
                                            ) VALUES ("
                                                . $id .",
                                                '". $approver_remarks ."',
                                                '". $approver_fullname ."',
                                                '". $approver_position ."',
                                                0,
                                                GETDATE(),
                                                '". $status ."'
                                            );";
                                            
    }

    // $sql_remarks = 'commented';
    
    // $remarks = 'commented';
    $remarks = sqlsrv_query($vbs_conn, $sql_remarks);

    $sql_update .= " WHERE id=".$id;
         
    // $update = 'commented';
    $update = sqlsrv_query($vbs_conn,$sql_update); // update connection to use vbs_db table

    // echo $sql_update; die();
    // echo $id."<br>";
    // echo $workflow_token."<br>";
    // echo $approver."<br>";
    // echo $current_approver."<br>";
    // echo $approver_remarks."<br>";
    // echo $details."<br>";
    // echo $status."<br>";
    // echo $table."<br>";

    // For testing
    // echo "TEST: ".$test_str."| update query: " . $sql_update . "| update status: " . $update . "| remarks query: " . $sql_remarks . "| remarks status: " . $remarks . "| hold_remarks: ". $hold_remark;

    echo "Succesfully updated status!";
}
else{
	echo "invalid token";
}

?>
