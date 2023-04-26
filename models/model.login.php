<?php
require_once __DIR__ . "/../config.php";
require_once CLASS_URL . "class.mysql.php";

class loginModel extends mysqlConex{
    public function login(string $email, string $pass){
        $data = ["status" => "fail process", "state" => "internal error"];
        if(filter_var($email, FILTER_VALIDATE_EMAIL)){
            $sql = "SELECT * FROM students WHERE email = '$email';";
            if($this->conectar())
                if($this->table = $this->query($sql, __FUNCTION__))
                    if($this->table->num_rows > 0){
                        $row = $this->table->fetch_assoc();
                        if(password_verify($pass, $row["password"])){
                            unset($row["password"]);
                            $_SESSION["user"] = $row;
                            $data = ["status" => "success", "state" => "logined"];
                        }else{
                            $data["state"] = "invalid password";
                        }
                    }else{
                        $data["state"] = "invalid email";
                    }
        }else{
            $data["state"] = "invalid email format";
        }
        $this->desconectar();
        return $data;
    }

    public function register(string $firstname, string $lastname, string $email, string $phone, string $birthday,string $pass){
        $data = ["status" => "fail process", "state" => "internal error"];
        if(strlen($firstname) < 2) $data['state'] = 'invalid firstname';
        if(strlen($lastname) < 2) $data['state'] = 'invalid lastname';
        if(preg_match("/^\+?[0-9]{9,14}$/", $phone) == 0) $data['state'] = 'invalid phone';
        if(!$this->validateDateString($birthday)) $data['state'] = 'invalid birthday';
        if(strlen($pass) < 8) $data['state'] = 'invalid password';
        if(!filter_var($email, FILTER_VALIDATE_EMAIL)) $data["state"] = "invalid email format";
        if($this->getAge($birthday) < 18) $data["state"] = "you must be of legal age";

        $sqlCheckEmail = "SELECT * FROM students WHERE email = '$email';";
        $sqlCheckPhone = "SELECT * FROM students WHERE phone = '$phone';";

        $passwordHash = password_hash($pass, PASSWORD_DEFAULT);
        $sql = "INSERT INTO students (firstname, lastname, email, phone, birthday, password) VALUES ('$firstname', '$lastname', '$email', '$phone', '$birthday', '$passwordHash');";
        if($this->conectar())
            if($this->table = $this->query($sqlCheckEmail, __FUNCTION__))
                if($this->table->num_rows == 0){
                    if($this->table = $this->query($sqlCheckPhone, __FUNCTION__))
                        if($this->table->num_rows == 0){
                            if($this->query($sql, __FUNCTION__)){
                                $data['status'] = 'success';
                                $data['state'] = 'successful registration';
                            }
                        }else{
                            $data['state'] = 'phone already exists';
                        }
                }else{
                    $data['state'] = 'email already exists';
                }
            
        $this->desconectar();
        return $data;
    }

    private function validateDateString($date){
        $array = explode('-', $date);
        return checkdate($array[1], $array[2], $array[0]);
    }

    private function getAge($birthdayString){
        $birthday = DateTime::createFromFormat('Y-m-d', $birthdayString);
        $now = new DateTime();
        $age = $now->diff($birthday)->y;
        return intval($age);
    }

}

?>