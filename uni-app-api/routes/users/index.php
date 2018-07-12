<?php
// get access rights
$app->get('/users/access-rights', function ($request, $response) {
    require 'config/config.php';
    $query = "SELECT * FROM `access_rights`";
    $result = $mysqli->query($query);

    while($row = $result->fetch_assoc()) {
        $data[] = $row;
    }

    if (isset($data)) {
        $newResponse = $response->withJson($data, 200);
    }

    return $newResponse;
});

// get all users
$app->get('/users', function ($request, $response) {
    require 'config/config.php';
    $query = "SELECT u.`id`, u.`first_name`, u.`last_name`, u.`username`, u.`email`, u.`access_right`, u.`status`,
    u.`date_added`, u.`last_modified`, ar.`access_code`, ar.`access_name`
    FROM `users` AS u INNER JOIN `access_rights` AS ar ON u.`access_right` = ar.`id`";
    $result = $mysqli->query($query);

    while($row = $result->fetch_assoc()) {
        $data[] = $row;
    }

    if (isset($data)) {
        $newResponse = $response->withJson($data, 200);
    }

    return $newResponse;
});

// login user
$app->post('/users/login', function ($request, $response) {
    require 'config/config.php';
    $receivedData = $request->getParsedBody();
    // $username = $receivedData['username'];
    // $password = $receivedData['password'];
    $username = $request->getParsedBody()['username'];
    $password = $request->getParsedBody()['password'];
    // echo 'Username: ' . $username;
    $newResponse = null;

    $query = "SELECT u.`username`, u.`email`, u.`access_right`, u.`status`, ar.`access_code`, ar.`access_name`
    FROM `users` AS u INNER JOIN `access_rights` AS ar ON u.`access_right` = ar.`id`
    WHERE `username`= '$username' AND `password`= '$password'";
    // $query = "SELECT username, email, access_right, token, status FROM `admin_users` WHERE `username`='" . $username . "' AND `password`='" . $password . "'";
    // $result = mysqli_num_rows($mysqli->query($query));
    $result = $mysqli->query($query);

    if ( $result ) {
        while($row = $result->fetch_assoc()) {
            $data[] = $row;
        }

        // if (isset($data)) {
        //     $newResponse = $response->withJson($data, 200);
        // }
        $output['loginStatus'] = true;
        $output['user'] = $data;
        $newResponse = $response->withJson($output, 200);
    } else {
        $output['loginStatus'] = false;
        $output['user'] = null;
        $newResponse = $response->withJson($output, 200);
    }

    return $newResponse;
});

// add user
$app->post('/users/addUser', function ($request, $response) {
    require 'config/config.php';
    $receivedData = $request->getParsedBody();
    $first_name = $request->getParsedBody()['first_name'];
    $last_name = $request->getParsedBody()['last_name'];
    $email = $request->getParsedBody()['email'];
    $username = $request->getParsedBody()['username'];
    $password = $request->getParsedBody()['password'];
    $access_right = $request->getParsedBody()['access_right'];
    $status = $request->getParsedBody()['status'];
    $newResponse = null;
    // echo $data;
    $query = "INSERT INTO `users` (`first_name`, `last_name`, `email`, `username`, `password`, `access_right`, `status`) VALUES (?,?,?,?,?,?,?)";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("sssssss", $first_name, $last_name, $email, $username, $password, $access_right, $status);

    $stmt->execute();

    $output['message'] = 'User Created Successfully!';
    $output['success'] = true;
    $newResponse = $response->withJson($output, 200);

    return $newResponse;
});
?>
