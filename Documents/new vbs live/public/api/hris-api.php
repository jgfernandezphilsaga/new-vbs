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



$conn_a['agusan']['type'] = 'sqlsrv';
$conn_a['agusan']['host'] = ' 172.16.20.43';
$conn_a['agusan']['name'] = 'SyncHRIS';
$conn_a['agusan']['uname'] = 'db_synchris';
$conn_a['agusan']['pword'] = 'xushuX9k';
*/


$conn_a['local']['type'] = 'sqlsrv';
$conn_a['local']['host'] = '172.16.1.39';
$conn_a['local']['name'] = 'HRIS-DIMS';
$conn_a['local']['uname'] = 'sa';
$conn_a['local']['pword'] = 'M@st3radm1n12345';

/*
$result = '';
$empdata=array();
// $_GET['emp'] = 'tano';
// echo $_GET['emp'];
if(isset($_GET['emp'])){
	
	$emp = $_GET['emp'];
	$pdoempdata = new PDO(
							$conn_a['agusan']['type'].":server=".$conn_a['agusan']['host'].";
							Database=".$conn_a['agusan']['name'], $conn_a['agusan']['uname'], $conn_a['agusan']['pword']
						);
	$pdoempdata->setAttribute(
								PDO::ATTR_ERRMODE, 
								PDO::ERRMODE_EXCEPTION
							);
							
	$empdatasql = $pdoempdata->prepare('SELECT DISTINCT (e.FullName),e.EmpID, e.LName, e.EmailAdd, e.EmailAdd2, e.Active,d.DeptDesc FROM ViewHREmpMaster e LEFT JOIN hrposition p ON p.PositionID = e.PositionID LEFT JOIN hrdepartment d ON d.deptid = e.deptid WHERE e.Active = 1 AND e.EmpID LIKE ? OR e.LName LIKE ?');
	$empdatasql->execute(array("%$emp%", "%$emp%"));
	
	$empdatasql->execute();
	$results = $empdatasql->fetchAll(PDO::FETCH_ASSOC);
	$json = json_encode($results);

	echo $json;
}
*/

$empdata = [];

if (isset($_GET['emp'])) {
    $emp = $_GET['emp'];

    try {
        $pdo = new PDO(
            $conn_a['local']['type'] . ":server=" . $conn_a['local']['host'] . ";Database=" . $conn_a['local']['name'],
            $conn_a['local']['uname'],
            $conn_a['local']['pword']
        );
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sql = "SELECT DISTINCT e.FullName, e.EmpID, e.LName, e.Active, p.PositionDesc FROM ViewHREmpMaster as e 
            LEFT JOIN hrposition as p ON p.PositionID = e.PositionID 
            LEFT JOIN hrdepartment as d ON d.deptid = e.deptid 
            WHERE e.Active = 1 AND (e.EmpID LIKE ? OR e.LName LIKE ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(["%$emp%", "%$emp%"]);
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $empdata[] = [
                'EmpID' => $row['EmpID'],
                'FullName' => $row['FullName'],
                'Position' => $row['PositionDesc']
            ];
        }

        echo json_encode($empdata);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => $e->getMessage()]);
    }
}
if(isset($_GET['dept'])){

	$dept = $_GET['dept'];
	$pdoempdata = new PDO($conn_a['local']['type'].":server=".$conn_a['local']['host'].";Database=".$conn_a['local']['name'], $conn_a['local']['uname'], $conn_a['local']['pword']);
	$pdoempdata->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

	$empdatasql = $pdoempdata->prepare('SELECT DISTINCT DeptDesc FROM HRDepartment WHERE DeptDesc LIKE ? ');
	$empdatasql->bindValue(1, "%$dept%", PDO::PARAM_STR);
	$empdatasql->execute();

	for($i=0; $rowempdata = $empdatasql->fetch(); $i++){   
		$result .= "<li class='dept_li'><a href='#'>".$rowempdata['DeptDesc'].'</a></li>|';
	}

	if($result == '')
		{
			echo "no department found";

		}else{
			echo $result;
		}	

}
   
?>