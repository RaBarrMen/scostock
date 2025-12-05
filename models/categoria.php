<?php
require_once("sistema.php");

class Categoria extends Sistema {

    function create($data){
        $this->connect();
        try {
            $this->_DB->beginTransaction();

            $sql = "INSERT INTO categoria (nombre, descripcion, imagen)
                    VALUES (:nombre, :descripcion, :imagen)";
            $sth = $this->_DB->prepare($sql);

            $sth->bindParam(":nombre", $data['nombre'], PDO::PARAM_STR);
            $sth->bindParam(":descripcion", $data['descripcion'], PDO::PARAM_STR);

            $imagen = null;
            if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === 0) {
                $imagen = $this->cargarFotografia('categoria', 'imagen');
            }
            $sth->bindParam(":imagen", $imagen, PDO::PARAM_STR);

            $sth->execute();
            $rowsAffected = $sth->rowCount();
            $this->_DB->commit();

            return $rowsAffected;
        } catch (Exception $ex) {
            $this->_DB->rollback();
        }
        return null;
    }

    function read() {
        $this->connect();
        $sth = $this->_DB->prepare("SELECT * FROM categoria ORDER BY nombre");
        $sth->execute();
        return $sth->fetchAll();
    }

    function readOne($id) {
        $this->connect();
        $sth = $this->_DB->prepare("SELECT * FROM categoria WHERE id_categoria = :id_categoria");
        $sth->bindParam(":id_categoria", $id, PDO::PARAM_INT);
        $sth->execute();
        return $sth->fetch(PDO::FETCH_ASSOC);
    }

    function update($data, $id) {
        if (!is_numeric($id)) return null;

        if ($this->validate($data)) {
            $this->connect();
            $this->_DB->beginTransaction();
            try {
                // Por defecto, solo actualiza nombre y descripcion
                $sql = "UPDATE categoria
                           SET nombre = :nombre,
                               descripcion = :descripcion
                         WHERE id_categoria = :id_categoria";

                $subeImagen = (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === 0);
                if ($subeImagen) {
                    // Si viene imagen nueva, también actualizamos el campo imagen
                    $sql = "UPDATE categoria
                               SET nombre = :nombre,
                                   descripcion = :descripcion,
                                   imagen = :imagen
                             WHERE id_categoria = :id_categoria";
                    $nuevaImagen = $this->cargarFotografia('categoria','imagen');
                }

                $sth = $this->_DB->prepare($sql);
                $sth->bindParam(":nombre", $data['nombre'], PDO::PARAM_STR);
                $sth->bindParam(":descripcion", $data['descripcion'], PDO::PARAM_STR);
                $sth->bindParam(":id_categoria", $id, PDO::PARAM_INT);

                if ($subeImagen) {
                    $sth->bindParam(":imagen", $nuevaImagen, PDO::PARAM_STR);
                }

                $sth->execute();
                $rowsAffected = $sth->rowCount();

                $this->_DB->commit();
                return $rowsAffected;
            } catch (Exception $ex) {
                $this->_DB->rollback();
            }
        }
        return null;
    }

    function delete($id) {
        $this->connect();

        // Validar si la categoría está en uso
        $sth = $this->_DB->prepare("SELECT COUNT(*) AS total FROM producto WHERE id_categoria = :id");
        $sth->bindParam(":id", $id, PDO::PARAM_INT);
        $sth->execute();
        $result = $sth->fetch(PDO::FETCH_ASSOC);

        if ($result['total'] > 0) {
            return 0; // No borrar
        }

        // Ahora sí intenta borrar
        $this->_DB->beginTransaction();
        try {
            $sql = "DELETE FROM categoria WHERE id_categoria = :id_categoria";
            $sth = $this->_DB->prepare($sql);
            $sth->bindParam(":id_categoria", $id, PDO::PARAM_INT);
            $sth->execute();
            $rowsAffected = $sth->rowCount();
            $this->_DB->commit();
            return $rowsAffected;

        } catch(Exception $ex) {
            $this->_DB->rollback();
            return null;
        }
    }


    function validate($data) {
        if (!isset($data['nombre']) || trim($data['nombre']) === '') return false;
        if (mb_strlen($data['nombre']) > 80) return false;
        if (isset($data['descripcion']) && mb_strlen($data['descripcion']) > 255) return false;
        return true;
    }
}
