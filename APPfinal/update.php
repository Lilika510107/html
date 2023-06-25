<?php
header('Content-Type: application/json; charset=UTF-8');

// 检查请求方法是否为PUT
if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    // 解析PUT请求的数据
    parse_str(file_get_contents('php://input'), $putData);

    // 获取要更新的资源的ID和新数据
    $id = isset($putData['id']) ? $putData['id'] : null;
    $amount = isset($putData['amount']) ? $putData['amount'] : null;

    // 进行资源更新的逻辑
    if ($id && $amount) {
        // 连接数据库
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "xuan";
        $conn = new mysqli($servername, $username, $password, $dbname);

        // 检查数据库连接是否成功
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // 更新资源的操作，这里以更新chat表中指定ID的数据为例
        $sql = "UPDATE `chat` SET `amount` = '$amount', `timestamp` = NOW() WHERE `id` = '$id'";
        if ($conn->query($sql) === TRUE) {
            $response = [
                "status" => "success",
                "message" => "Resource updated successfully"
            ];
            http_response_code(200);
            echo json_encode($response);
        } else {
            $response = [
                "status" => "error",
                "message" => "Failed to update resource: " . $conn->error
            ];
            http_response_code(500);
            echo json_encode($response);
        }

        // 关闭数据库连接
        $conn->close();

        // 结束脚本
        exit;
    }
}

// 如果没有执行PUT请求的逻辑，则继续原有的代码逻辑

// 读取数据库的操作...
?>
