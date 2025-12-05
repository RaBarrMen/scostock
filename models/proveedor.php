<?php
require_once("sistema.php");

class Proveedor extends Sistema {

    function create($data){
        $this->connect();
        try {
            $this->_DB->beginTransaction();

            $sql = "INSERT INTO proveedor (nombre, telefono, email, created_at)
                    VALUES (:nombre, :telefono, :email, NOW())";

            $sth = $this->_DB->prepare($sql);
            $sth->bindParam(":nombre", $data['nombre'], PDO::PARAM_STR);
            $sth->bindParam(":telefono", $data['telefono'], PDO::PARAM_STR);
            $sth->bindParam(":email", $data['email'], PDO::PARAM_STR);

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
        $sth = $this->_DB->prepare("SELECT * FROM proveedor ORDER BY nombre");
        $sth->execute();
        return $sth->fetchAll();
    }

    function readOne($id){
        $this->connect();
        $sth = $this->_DB->prepare("SELECT * FROM proveedor WHERE id_proveedor = :id");
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
                $sql = "UPDATE proveedor
                        SET nombre = :nombre,
                            telefono = :telefono,
                            email = :email
                        WHERE id_proveedor = :id";

                $sth = $this->_DB->prepare($sql);

                $sth->bindParam(":nombre", $data['nombre'], PDO::PARAM_STR);
                $sth->bindParam(":telefono", $data['telefono'], PDO::PARAM_STR);
                $sth->bindParam(":email", $data['email'], PDO::PARAM_STR);
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

        return null;
    }

    function delete($id){
        $this->connect();

        // Validar si está siendo usado en producto_proveedor
        $sth = $this->_DB->prepare("SELECT COUNT(*) AS total 
                                    FROM producto_proveedor
                                    WHERE id_proveedor = :id");
        $sth->bindParam(":id", $id, PDO::PARAM_INT);
        $sth->execute();
        $result = $sth->fetch(PDO::FETCH_ASSOC);

        if ($result['total'] > 0) {
            return -1; // No borrar
        }

        // Borrar proveedor
        $this->_DB->beginTransaction();

        try {
            $sql = "DELETE FROM proveedor WHERE id_proveedor = :id";
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
        if (!isset($data['telefono']) || trim($data['telefono']) === '') return false;
        if (!isset($data['email']) || trim($data['email']) === '') return false;
        return true;
    }
}
?>
