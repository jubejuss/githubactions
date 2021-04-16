<?php
    
    function sign_up($name, $surname, $gender, $birth_date, $email, $password) {
        $notice = 0;
        $conn = new mysqli($GLOBALS["server_host"], $GLOBALS["server_user_name"], $GLOBALS["server_password"], $GLOBALS["database"]);
        $stmt = $conn->prepare("INSERT INTO vr21_users (vr21_users_firstname, vr21_users_lastname, vr21_users_birthdate, vr21_users_gender, vr21_users_email, vr21_users_password) VALUES (?,?,?,?,?,?)"); //küsimärkide asemele pannakse siis automaatselt väärtused
        echo $conn->error;
        // krüpteerime parooli vastava funktsiooniga
        $options = ["cost" => 12, "salt" => substr(sha1(rand()), 0, 22)]; // cost on iteratsioonide arv, ehk palju operatsioone tehakse. Salt on mingi lisand, mis võetakse abiks ja lisatakse mingeid fraase otsa vms, ehk parool muutub, ja random ja sealt veel jupp välja
        $pwd_hash = password_hash($password, PASSWORD_BCRYPT, $options);

        $stmt -> bind_param("sssiss", $name, $surname, $birth_date, $gender, $email, $pwd_hash);

        if($stmt -> execute()) {
            $notice = 1;
        }
        $stmt -> close();
		$conn -> close();
        return $notice;
    }