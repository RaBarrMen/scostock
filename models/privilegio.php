<?php
require_once("sistema.php");

class Privilegio extends Sistema {

    function create($data){
        $this->connect();

        try {
            $this->_DB->beginTransaction();

            $sql = "INSERT INTO privilegio (privilegio) VALUES (:privilegio)";
            $sth = $this->_DB->prepare($sql);
            $sth->bindParam(":privilegio", $data['privilegio'], PDO::PARAM_STR);
            $sth->execute();

            $rows = $sth->rowCount();
            $this->_DB->commit();
            return $rows;

        } catch(Exception $ex){
            $this->_DB->rollback();
            return null;
        }
    }

    function read(){
        $this->connect();
        $sth = $this->_DB->prepare("SELECT * FROM privilegio ORDER BY privilegio");
        $sth->execute();
        return $sth->fetchAll();
    }

    function readOne($id){
        $this->connect();
        $sth = $this->_DB->prepare("SELECT * FROM privilegio WHERE id_privilegio = :id");
        $sth->bindParam(":id", $id, PDO::PARAM_INT);
        $sth->execute();
        return $sth->fetch(PDO::FETCH_ASSOC);
    }

    function update($data, $id){
        if(!is_numeric($id)) return null;
        if(!$this->validate($data)) return null;

        $this->connect();
        $this->_DB->beginTransaction();

        try {
            $sql = "UPDATE privilegio SET privilegio = :privilegio WHERE id_privilegio = :id";
            $sth = $this->_DB->prepare($sql);
            $sth->bindParam(":privilegio", $data['privilegio'], PDO::PARAM_STR);
            $sth->bindParam(":id", $id, PDO::PARAM_INT);
            $sth->execute();

            $rows = $sth->rowCount();
            $this->_DB->commit();
            return $rows;

        } catch(Exception $ex){
            $this->_DB->rollback();
            return null;
        }
    }

    function delete($id){
        $this->connect();

        // Validar si el privilegio está asignado a roles
        $sth = $this->_DB->prepare("SELECT COUNT(*) AS total 
                                    FROM rol_privilegio 
                                    WHERE id_privilegio = :id");
        $sth->bindParam(":id", $id, PDO::PARAM_INT);
        $sth->execute();

        $result = $sth->fetch(PDO::FETCH_ASSOC);
        if ($result['total'] > 0) {
            return -1; // No borrar
        }

        $this->_DB->beginTransaction();
        try {
            $sql = "DELETE FROM privilegio WHERE id_privilegio = :id";
            $sth = $this->_DB->prepare($sql);
            $sth->bindParam(":id", $id, PDO::PARAM_INT);
            $sth->execute();

            $rows = $sth->rowCount();
            $this->_DB->commit();
            return $rows;

        } catch(Exception $ex){
            $this->_DB->rollback();
            return null;
        }
    }

    function validate($data){
        if (!isset($data['privilegio']) || trim($data['privilegio']) === '') return false;
        if (mb_strlen($data['privilegio']) > 50) return false;
        return true;
    }
}
?>
