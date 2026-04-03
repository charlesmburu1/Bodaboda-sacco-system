<?php

function loginUser($pdo, $input, $password) {
    $stmt = $pdo->prepare("SELECT * FROM members WHERE email = ? OR membership_id = ?");
    $stmt->execute([$input, $input]);

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if($user && password_verify($password, $user['password'])) {
        return $user;
    }

    return false;
}