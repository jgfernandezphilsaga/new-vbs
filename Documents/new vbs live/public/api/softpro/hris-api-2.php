<?php

include(__DIR__ . '/config.php');

function get_array_emp(){
	global $conn_d;

	$array_emp = array();

	$pdoempdata = new PDO($conn_a['agusan']['type'].":server=".$conn_a['agusan']['host'].";Database=".$conn_a['agusan']['name'], $conn_a['agusan']['uname'], $conn_a['agusan']['pword']);
	$pdoempdata->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

	//$empdatasql = $pdoempdata->prepare("SELECT EmpID, FullName FROM ViewHREmpMaster WHERE Active = 1");

	$empdatasql = $pdoempdata->prepare("SELECT e.EmpID, e.FullName, e.FName, e.MName, e.LName, d.DeptDesc, d.deptid FROM ViewHREmpMaster e left join hrdepartment d on d.deptid=e.deptid WHERE e.Active = 1");

	$empdatasql->execute();
	$empdatasql->setFetchMode(PDO::FETCH_ASSOC);

	for($i=0; $rowempdata = $empdatasql->fetch(); $i++){   
		$array_emp[] = ["fullnamewithdept" => str_replace(',',' ',$rowempdata['FullName']).' : '.$rowempdata['DeptDesc'], "fullname" => $rowempdata['FName'].'*'.$rowempdata['MName'].'*'.$rowempdata['LName']];
	}

	$pdoempdata = new PDO($conn_d['davao']['type'].":server=".$conn_d['davao']['host'].";Database=".$conn_d['davao']['name'], $conn_d['davao']['uname'], $conn_d['davao']['pword']);
	$pdoempdata->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

	$empdatasql = $pdoempdata->prepare("SELECT e.EmpID, e.FullName, e.FName, e.MName, e.LName, d.DeptDesc, d.deptid FROM ViewHREmpMaster e left join hrdepartment d on d.deptid=e.deptid WHERE e.Active = 1");

	$empdatasql->execute();
	$empdatasql->setFetchMode(PDO::FETCH_ASSOC);

	for($i=0; $rowempdata = $empdatasql->fetch(); $i++){     
		$array_emp[] = ["fullnamewithdept" => str_replace(',',' ',$rowempdata['FullName']).' : '.$rowempdata['DeptDesc'], "fullname" => $rowempdata['FName'].'*'.$rowempdata['MName'].'*'.$rowempdata['LName']];
	}

	echo json_encode($array_emp);
}

function get_requestor(){
	global $conn_d;
	$dept = get_satellite($_POST["dept"]);
	$requestors = array();

	$pdoempdata = new PDO($conn_d['davao']['type'].":server=".$conn_d['davao']['host'].";Database=".$conn_d['davao']['name'], $conn_d['davao']['uname'], $conn_d['davao']['pword']);
	$pdoempdata->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

	$empdatasql = $pdoempdata->prepare("SELECT DISTINCT e.FullName FROM ViewHREmpMaster e LEFT JOIN hrdepartment d ON d.deptid=e.deptid WHERE e.Active = 1 AND d.DeptDesc = :dept");
	$empdatasql->bindParam(':dept', $dept);

	$empdatasql->execute();
	$empdatasql->setFetchMode(PDO::FETCH_ASSOC);

	for($i=0; $rowempdata = $empdatasql->fetch(); $i++){     
		$requestors[] = str_replace(',',' ',$rowempdata['FullName']);
	}

	echo json_encode($requestors);
}

function get_departments(){
	global $conn_d;

	$depts = array();

	$pdoempdata = new PDO($conn_d['davao']['type'].":server=".$conn_d['davao']['host'].";Database=".$conn_d['davao']['name'], $conn_d['davao']['uname'], $conn_d['davao']['pword']);
	$pdoempdata->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

	$empdatasql = $pdoempdata->prepare("SELECT DISTINCT DeptDesc FROM dbo.HRDepartment WHERE DeptDesc NOT IN ('MATERIAL CONTROL DEPARTMENT', 'MATERIALS CONTROL', 'MATERIALS CONTROL OFFICE') ORDER BY DeptDesc ASC");

	$empdatasql->execute();
	$empdatasql->setFetchMode(PDO::FETCH_ASSOC);

	for($i=0; $rowempdata = $empdatasql->fetch(); $i++){     
		$depts[] = $rowempdata['DeptDesc'];
	}

	echo json_encode($depts);

}

function get_satellite($dept){
	$satellites = array(
		'IT/COMMUNICATIONS'=>'INFORMATION AND COMMUNICATIONS TECHNOLOGY'
	);

	return (array_key_exists($dept,$satellites)) ? $satellites[$dept] : $dept;
}

function set_wfs_status(){
	global $conn;

	$refno = $_POST["refno"];
	$update = "update transactions set status = 'CANCELLED' where ref_req_no = '".$refno."'";
    sqlsrv_query($conn, $update);

	echo json_encode("okay");
}

$call_func= $_POST["call_func"];

switch($call_func){
	case "get_array_emp":
		get_array_emp();
		break;
	case "get_requestor":
		get_requestor();
		break;
	case "get_departments":
		get_departments();
		break;
	case "set_wfs_status":
		set_wfs_status();
		break;
}

// new column schema
// no column on SofPro "Active" on table "HREmpMaster"
// SELECT e.EmpID , e.LName , d.DeptDesc , d.DeptID FROM HREmpMaster as e left join HRDepartment as d on d.DeptID = e.EmpID WHERE e.Active = 1


// new query for epmloyee
// SELECT u.id , u.first_name, u.last_name , d.department_name , d.id FROM users as u left join departments as d on d.id = u.department_id WHERE u.status <> 4


?>
