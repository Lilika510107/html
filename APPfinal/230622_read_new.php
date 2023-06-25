<?php
header('Content-Type: application/json; charset=UTF-8'); //设置数据类型为json，编码为utf-8

if (@$_GET["id"] != "") {
    $id = $_GET['id'];
} else {
    $id = "null";
}

if (@$_GET["limit"] != "") {
    $limit = $_GET["limit"];
} else {
    $limit = "5";
}

//------------读取数据库--------------//	
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "xuan";
$conn = new mysqli($servername, $username, $password, $dbname);

//------------------------------------//
// 建立数据库连接
$conn = new mysqli($servername, $username, $password, $dbname);
// 确认数据是否正常连接
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// 构建查询语句，根据指定的"id"参数进行筛选
if ($id != "null") {
    $sql = "SELECT * FROM `chat` WHERE id = '$id' ORDER BY no DESC LIMIT $limit";
} else {
    $sql = "SELECT * FROM `chat` ORDER BY no DESC LIMIT $limit";
}

$result = $conn->query($sql);
$messageArr = array();
$dataarray = array();

if ($result->num_rows > 0) {
    // 输出每行数据
    while ($row = $result->fetch_assoc()) {
        $amount = $row["amount"];
        $timestamp = $row["timestamp"];
        $dataarray[] = $row;
    }
}

$conn->close();
//------------------------------------------------
// 输出结果	
$messageArr["data"] = $dataarray;

//------------------------------------------------------
// 添加时间格式
$messageArr["status"] = array();
date_default_timezone_set('America/La_Paz');
$today = date('Y-m-d\TH:i:sP');//RFC3339格式
$datetime = array(
    "code" => "0",
    "message" => "Success",
    "datetime" => $today
);
$messageArr["status"] = $datetime;

if (!empty($_GET['id'])) {
    http_response_code(200);
    echo json_encode($messageArr);
} else {
    http_response_code(404);
    $messageArr["data"] = [];
    $messageArr["status"] = array();
    date_default_timezone_set('America/La_Paz');
    $today = date('Y-m-d\TH:i:sP');//RFC3339格式
    $datetime = array(
        "code" => "404",
        "message" => "Error id is null",
        "datetime" => $today
    );
    $messageArr["status"] = $datetime;
    echo json_encode($messageArr);
}
?>
