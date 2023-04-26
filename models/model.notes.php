<?php
require_once __DIR__ . "/../config.php";
require_once CLASS_URL . "class.mysql.php";

class notesModel extends mysqlConex{
    public function addNote(string $matter, int $score, int $userID){
        $data = ["status" => "fail process", "state" => "internal error"];
        $sqlCheck = "SELECT * FROM students_notes WHERE matter = '$matter';";
        $sql = "INSERT INTO students_notes (studentID, matter, score) VALUES ($userID, '$matter', $score);";
        if($this->conectar())
            if($this->table = $this->query($sqlCheck, __FUNCTION__))
                if($this->table->num_rows == 0){
                    if($this->query($sql, __FUNCTION__)){
                        $data['status'] = 'success';
                        $data['state'] = 'successful registration';
                    }
                }else{
                    $data['state'] = 'matter already exists';
                }
        $this->desconectar();
        return $data;
    }

    public function editNote(int $id, int $score){
        $data = ["status" => "fail process", "state" => "internal error"];
        $sql = "UPDATE students_notes SET score = $score WHERE id = $id;";
        if($this->conectar())
            if($this->query($sql, __FUNCTION__)){
                $data['status'] = 'success';
                $data['state'] = 'successful registration';
            }
        $this->desconectar();
        return $data;
    }

    public function delNote(int $id){
        $data = ["status" => "fail process", "state" => "internal error"];
        $sql = "DELETE FROM students_notes WHERE id = $id;";
        if($this->conectar())
            if($this->query($sql, __FUNCTION__)){
                $data['status'] = 'success';
                $data['state'] = 'successful registration';
            }
        $this->desconectar();
        return $data;
    }

    public function getNotes(int $userID){
        $data = ["status" => "fail process", "state" => "internal error"];
        $sql = "SELECT id, matter, score  FROM students_notes WHERE studentID = $userID;";
        if($this->conectar())
            if($this->table = $this->query($sql, __FUNCTION__)){
                $data = [];
                while($row = $this->table->fetch_assoc())
                    $data[] = $row;
            }
        $this->desconectar();
        return $data;
    }

}

?>