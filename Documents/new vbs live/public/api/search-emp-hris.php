<?php
/*
$conn_d['davao']['type'] = 'sqlsrv';
$conn_d['davao']['host'] = '172.16.10.42\philsaga_db';
$conn_d['davao']['name'] = 'PMC-DAVAO';
$conn_d['davao']['uname'] = 'sa';
$conn_d['davao']['pword'] = '@Temp123!';

$conn_a['agusan']['type'] = 'sqlsrv';
$conn_a['agusan']['host'] = '172.16.20.42\agusan_db';
$conn_a['agusan']['name'] = 'PMC-AGUSAN-NEW';
$conn_a['agusan']['uname'] = 'sa';
$conn_a['agusan']['pword'] = '@Temp123!';
*/
$conn_a['local']['type'] = 'sqlsrv';
$conn_a['local']['host'] = '172.16.1.39';
$conn_a['local']['name'] = 'HRIS-DIMS';
$conn_a['local']['uname'] = 'sa';
$conn_a['local']['pword'] = 'M@st3radm1n12345';

$result = '';

$empdata=array();
$pdoempdata = new PDO($conn_a['local']['type'].":server=".$conn_a['local']['host'].";Database=".$conn_a['local']['name'], $conn_a['local']['uname'], $conn_a['local']['pword']);
$pdoempdata->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

//$empdatasql = $pdoempdata->prepare("SELECT e.EmpID, e.FullName,p.positiondesc,d.DeptDesc FROM ViewHREmpMaster e left join hrposition p on p.PositionID=e.PositionID
 //left join hrdepartment d on d.deptid=e.deptid WHERE e.Active = 1 ORDER BY e.FullName ASC");

 $empdatasql = $pdoempdata->prepare('SELECT DISTINCT (e.FullName),e.EmpID, e.LName, e.Active,p.PositionDesc FROM ViewHREmpMaster as e 
	LEFT JOIN hrposition as p ON p.PositionID = e.PositionID 
	LEFT JOIN hrdepartment as d ON d.deptid = e.deptid 
	WHERE e.Active = 1 ORDER BY e.FullName ASC');

$empdatasql->execute();
$empdatasql->setFetchMode(PDO::FETCH_ASSOC);

for($i=0; $rowempdata = $empdatasql->fetch(); $i++){   
	$result .= '<option value="'.$rowempdata['FullName'].'|'.$rowempdata['DeptDesc'].'" >'.$rowempdata['FullName'].'<option>';
}



$pdoempdata = new PDO($conn_d['local']['type'].":server=".$conn_d['local']['host'].";Database=".$conn_d['local']['name'], $conn_d['local']['uname'], $conn_d['local']['pword']);
$pdoempdata->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

//$empdatasql = $pdoempdata->prepare("SELECT e.EmpID, e.FullName,p.positiondesc,d.DeptDesc FROM ViewHREmpMaster e left join hrposition p on p.PositionID=e.PositionID left join hrdepartment d on d.deptid=e.deptid WHERE e.Active = 1 ORDER BY e.FullName ASC");

$empdatasql = $pdoempdata->prepare('SELECT DISTINCT (e.FullName),e.EmpID, e.LName, e.Active,p.PositionDesc FROM ViewHREmpMaster as e 
	LEFT JOIN hrposition as p ON p.PositionID = e.PositionID 
	LEFT JOIN hrdepartment as d ON d.deptid = e.deptid 
	WHERE e.Active = 1 ORDER BY e.FullName ASC');

$empdatasql->execute();
$empdatasql->setFetchMode(PDO::FETCH_ASSOC);

for($i=0; $rowempdata = $empdatasql->fetch(); $i++){     
	$result .= '<option value="'.$rowempdata['FullName'].'|'.$rowempdata['DeptDesc'].'" >'.$rowempdata['FullName'].'<option>';
}

	echo $result;
?>

