<?php
require_once("sistema.php");

class RolPrivilegio extends Sistema {

    /* ============================================================
       LISTAR TODOS (JOIN)
    ============================================================ */
    public function read() {
        $this->connect();

        $sql = "SELECT rp.id_rol, rp.id_privilegio,
                       r.nombre AS rol,
                       p.privilegio
                FROM rol_privilegio rp
                INNER JOIN rol r ON rp.id_rol = r.id_rol
                INNER JOIN privilegio p ON rp.id_privilegio = p.id_privilegio
                ORDER BY r.nombre, p.privilegio";

        $sth = $this->_DB->prepare($sql);
        $sth->execute();

        return $sth->fetchAll(PDO::FETCH_ASSOC);
    }

    /* ============================================================
       LISTAR PRIVILEGIOS DE UN ROL
    ============================================================ */
    public function readByRol($id_rol) {
        $this->connect();

        $sql = "SELECT rp.id_rol, rp.id_privilegio,
                       p.privilegio
                FROM rol_privilegio rp
                INNER JOIN privilegio p ON rp.id_privilegio = p.id_privilegio
                WHERE rp.id_rol = :id";

        $sth = $this->_DB->prepare($sql);
        $sth->bindParam(":id", $id_rol);
        $sth->execute();

        return $sth->fetchAll(PDO::FETCH_ASSOC);
    }

    /* ============================================================
       ASIGNAR PRIVILEGIO A ROL
    ============================================================ */
    public function create($id_rol, $id_privilegio) {
        $this->connect();

        // prevenir duplicado
        $checkSql = "SELECT COUNT(*) AS total 
                     FROM rol_privilegio 
                     WHERE id_rol = :r AND id_privilegio = :p";

        $sthCheck = $this->_DB->prepare($checkSql);
        $sthCheck->bindParam(":r", $id_rol);
        $sthCheck->bindParam(":p", $id_privilegio);
        $sthCheck->execute();

        $exists = $sthCheck->fetch(PDO::FETCH_ASSOC);

        if ($exists['total'] > 0) {
            return -1; // ya existe
        }

        $sql = "INSERT INTO rol_privilegio(id_rol, id_privilegio) 
                VALUES (:r, :p)";

        $sth = $this->_DB->prepare($sql);
        $sth->bindParam(":r", $id_rol);
        $sth->bindParam(":p", $id_privilegio);

        return $sth->execute() ? 1 : 0;
    }

    /* ============================================================
       ELIMINAR PRIVILEGIO DE ROL
    ============================================================ */
    public function delete($id_rol, $id_privilegio) {
        $this->connect();

        $sql = "DELETE FROM rol_privilegio 
                WHERE id_rol = :r AND id_privilegio = :p";

        $sth = $this->_DB->prepare($sql);
        $sth->bindParam(":r", $id_rol);
        $sth->bindParam(":p", $id_privilegio);

        return $sth->execute() ? 1 : 0;
    }
}
?>
