<?php
require_once("sistema.php");

class Rol extends Sistema {

    function create($data){
        $this->connect();
        try {
            $this->_DB->beginTransaction();

            $sql = "INSERT INTO rol (nombre) VALUES (:nombre)";
            $sth = $this->_DB->prepare($sql);
            $sth->bindParam(":nombre", $data['nombre'], PDO::PARAM_STR);
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
        $sth = $this->_DB->prepare("SELECT * FROM rol ORDER BY nombre");
        $sth->execute();
        return $sth->fetchAll();
    }

    function readOne($id){
        $this->connect();
        $sth = $this->_DB->prepare("SELECT * FROM rol WHERE id_rol = :id");
        $sth->bindParam(":id", $id, PDO::PARAM_INT);
        $sth->execute();
        return $sth->fetch(PDO::FETCH_ASSOC);
    }

    function update($data, $id){
        if(!is_numeric($id)) return null;

        if($this->validate($data)){
            $this->connect();
            $this->_DB->beginTransaction();

            try {
                $sql = "UPDATE rol SET nombre = :nombre WHERE id_rol = :id";
                $sth = $this->_DB->prepare($sql);
                $sth->bindParam(":nombre", $data['nombre'], PDO::PARAM_STR);
                $sth->bindParam(":id", $id, PDO::PARAM_INT);
                $sth->execute();

                $rows = $sth->rowCount();
                $this->_DB->commit();
                return $rows;

            } catch(Exception $ex){
                $this->_DB->rollback();
            }
        }

        return null;
    }

    function delete($id){
        $this->connect();

        // Validar si el rol está siendo usado por algún usuario
        $sth = $this->_DB->prepare("SELECT COUNT(*) AS total FROM usuario_rol WHERE id_rol = :id");
        $sth->bindParam(":id", $id, PDO::PARAM_INT);
        $sth->execute();
        $result = $sth->fetch(PDO::FETCH_ASSOC);

        if ($result['total'] > 0) {
            return -1; // No borrar
        }

        // Eliminar rol
        $this->_DB->beginTransaction();
        try {
            $sql = "DELETE FROM rol WHERE id_rol = :id";
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
        if (!isset($data['nombre']) || trim($data['nombre']) === '') return false;
        if (mb_strlen($data['nombre']) > 50) return false;
        return true;
    }
}
?>
