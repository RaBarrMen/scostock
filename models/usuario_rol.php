<?php
require_once("sistema.php");

class UsuarioRol extends Sistema {

    /* ============================================================
       LEER LISTA COMPLETA
    ============================================================ */
    public function read() {
        $this->connect();

        $sql = "SELECT ur.id_usuario, ur.id_rol, 
                       u.nombre AS usuario,
                       r.nombre AS rol
                FROM usuario_rol ur
                INNER JOIN usuario u ON ur.id_usuario = u.id_usuario
                INNER JOIN rol r ON ur.id_rol = r.id_rol
                ORDER BY u.nombre, r.nombre";

        $sth = $this->_DB->prepare($sql);
        $sth->execute();

        return $sth->fetchAll(PDO::FETCH_ASSOC);
    }

    /* ============================================================
       OBTENER LISTA DE ROLES DEL USUARIO
    ============================================================ */
    public function readByUsuario($id_usuario){
        $this->connect();

        $sql = "SELECT ur.id_usuario, ur.id_rol, r.nombre
                FROM usuario_rol ur
                INNER JOIN rol r ON ur.id_rol = r.id_rol
                WHERE ur.id_usuario = :id";

        $sth = $this->_DB->prepare($sql);
        $sth->bindParam(":id", $id_usuario);
        $sth->execute();

        return $sth->fetchAll(PDO::FETCH_ASSOC);
    }

    /* ============================================================
       ASIGNAR ROL
    ============================================================ */
    public function create($id_usuario, $id_rol){
        $this->connect();

        // Evitar duplicados
        $sql = "SELECT COUNT(*) AS total 
                FROM usuario_rol 
                WHERE id_usuario = :u AND id_rol = :r";

        $sth = $this->_DB->prepare($sql);
        $sth->bindParam(":u", $id_usuario);
        $sth->bindParam(":r", $id_rol);
        $sth->execute();

        $res = $sth->fetch(PDO::FETCH_ASSOC);

        if ($res['total'] > 0){
            return -1; // duplicado
        }

        // Insertar
        $sql = "INSERT INTO usuario_rol(id_usuario, id_rol)
                VALUES(:u, :r)";

        $sth = $this->_DB->prepare($sql);
        $sth->bindParam(":u", $id_usuario);
        $sth->bindParam(":r", $id_rol);

        return $sth->execute() ? 1 : 0;
    }

    /* ============================================================
       ELIMINAR ROL DE UN USUARIO
    ============================================================ */
    public function delete($id_usuario, $id_rol){
        $this->connect();

        $sql = "DELETE FROM usuario_rol 
                WHERE id_usuario = :u AND id_rol = :r";

        $sth = $this->_DB->prepare($sql);
        $sth->bindParam(":u", $id_usuario);
        $sth->bindParam(":r", $id_rol);

        return $sth->execute() ? 1 : 0;
    }

}
?>
