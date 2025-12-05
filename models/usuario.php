<?php
require_once "sistema.php";

class Usuario extends Sistema {

    /* ============================================================
       LOGIN
    ============================================================ */    
    public function login($email, $password) {
        $this->connect();

        $sql = "SELECT * FROM usuario WHERE email = :email AND activo = 1";
        $sth = $this->_DB->prepare($sql);
        $sth->bindParam(":email", $email);
        $sth->execute();

        if ($sth->rowCount() == 1) {
            $usuario = $sth->fetch(PDO::FETCH_ASSOC);

            if (password_verify($password, $usuario['hash_password'])) {

                // AGREGAMOS ROLES Y PRIVILEGIOS
                $usuario['roles'] = $this->getRoles($usuario['id_usuario']);
                $usuario['privilegios'] = $this->getPrivilegios($usuario['id_usuario']);

                return $usuario;
            }
        }
        return false;
    }


    /* ============================================================
       CRUD USUARIOS
    ============================================================ */

    // Obtener todos los usuarios
    public function read() {
        $this->connect();
        $sql = "SELECT * FROM usuario ORDER BY nombre";
        return $this->_DB->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function readOne($id) {
        $this->connect();
        $sql = "SELECT * FROM usuario WHERE id_usuario = :id";
        $sth = $this->_DB->prepare($sql);
        $sth->bindParam(":id", $id);
        $sth->execute();
        return $sth->fetch(PDO::FETCH_ASSOC);
    }

    // Crear usuario
    public function create($data) {
        $this->connect();

        // Evitar duplicados
        $check = $this->buscarPorEmail($data['email']);
        if ($check) {
            return -2; // email duplicado
        }

        $password = password_hash($data['password'], PASSWORD_BCRYPT);

        $sql = "INSERT INTO usuario(nombre, email, hash_password, activo, created_at)
                VALUES (:nombre, :email, :hash_password, :activo, NOW())";

        $sth = $this->_DB->prepare($sql);
        $sth->bindParam(":nombre", $data['nombre']);
        $sth->bindParam(":email", $data['email']);
        $sth->bindParam(":hash_password", $password);
        $sth->bindParam(":activo", $data['activo']);

        return $sth->execute() ? 1 : 0;
    }

    // Actualizar usuario
    public function update($data, $id) {
        $this->connect();

        // Si incluye nuevo password
        $cambiarPass = !empty($data['password']);

        if ($cambiarPass) {
            $hash = password_hash($data['password'], PASSWORD_BCRYPT);

            $sql = "UPDATE usuario SET 
                        nombre = :nombre,
                        email = :email,
                        hash_password = :hash_password,
                        activo = :activo
                    WHERE id_usuario = :id";

            $sth = $this->_DB->prepare($sql);
            $sth->bindParam(":hash_password", $hash);
        } else {
            // Sin cambiar password
            $sql = "UPDATE usuario SET 
                        nombre = :nombre,
                        email = :email,
                        activo = :activo
                    WHERE id_usuario = :id";

            $sth = $this->_DB->prepare($sql);
        }

        $sth->bindParam(":nombre", $data['nombre']);
        $sth->bindParam(":email", $data['email']);
        $sth->bindParam(":activo", $data['activo']);
        $sth->bindParam(":id", $id);

        return $sth->execute() ? 1 : 0;
    }

    // Eliminar usuario
    public function delete($id) {
        $this->connect();

        // Evitar borrar usuarios con roles asignados
        $sql = "SELECT COUNT(*) AS total FROM usuario_rol WHERE id_usuario = :id";
        $sth = $this->_DB->prepare($sql);
        $sth->bindParam(":id", $id);
        $sth->execute();
        $exists = $sth->fetch(PDO::FETCH_ASSOC);

        if ($exists['total'] > 0) {
            return -1; // No borrar
        }

        $sql = "DELETE FROM usuario WHERE id_usuario = :id";
        $sth = $this->_DB->prepare($sql);
        $sth->bindParam(":id", $id);
        return $sth->execute() ? 1 : 0;
    }

    /* ============================================================
       RECUPERACIÓN DE CONTRASEÑA
    ============================================================ */

    // Buscar usuario por correo
    public function buscarPorEmail($email) {
        $this->connect();
        $sql = "SELECT * FROM usuario WHERE email = :email LIMIT 1";
        $sth = $this->_DB->prepare($sql);
        $sth->bindParam(":email", $email);
        $sth->execute();
        return $sth->fetch(PDO::FETCH_ASSOC);
    }

    // Guardar token
    public function guardarToken($id_usuario, $token) {
        $this->connect();
        $sql = "UPDATE usuario SET 
                    reset_token = :token,
                    reset_expira = DATE_ADD(NOW(), INTERVAL 15 MINUTE)
                WHERE id_usuario = :id";

        $sth = $this->_DB->prepare($sql);
        $sth->bindParam(":token", $token);
        $sth->bindParam(":id", $id_usuario);
        return $sth->execute();
    }

    // Validar token
    public function validarToken($token) {
        $this->connect();
        $sql = "SELECT * FROM usuario 
                WHERE reset_token = :token 
                AND reset_expira > NOW()
                LIMIT 1";

        $sth = $this->_DB->prepare($sql);
        $sth->bindParam(":token", $token);
        $sth->execute();
        return $sth->fetch(PDO::FETCH_ASSOC);
    }

    // Cambiar contraseña
    public function actualizarPassword($id_usuario, $password) {
        $this->connect();

        $hash = password_hash($password, PASSWORD_BCRYPT);

        $sql = "UPDATE usuario SET 
                    hash_password = :hash,
                    reset_token = NULL,
                    reset_expira = NULL
                WHERE id_usuario = :id";

        $sth = $this->_DB->prepare($sql);
        $sth->bindParam(":hash", $hash);
        $sth->bindParam(":id", $id_usuario);

        return $sth->execute();
    }

    /* ============================================================
       VALIDACIÓN
    ============================================================ */
    public function validate($data) {
        if (!isset($data['nombre']) || trim($data['nombre']) === '') return false;
        if (!isset($data['email'])  || trim($data['email']) === '') return false;
        return true;
    }

    /* ============================================================
    OBTENER ROLES DEL USUARIO
    ============================================================ */
    public function getRoles($id_usuario) {
        $this->connect();

        $sql = "SELECT r.nombre
                FROM usuario_rol ur
                JOIN rol r ON ur.id_rol = r.id_rol
                WHERE ur.id_usuario = :id";

        $sth = $this->_DB->prepare($sql);
        $sth->bindParam(":id", $id_usuario, PDO::PARAM_INT);
        $sth->execute();

        return $sth->fetchAll(PDO::FETCH_COLUMN);
    }

    /* ============================================================
    OBTENER PRIVILEGIOS DEL USUARIO
    ============================================================ */
    public function getPrivilegios($id_usuario) {
        $this->connect();

        $sql = "SELECT DISTINCT p.privilegio
                FROM usuario_rol ur
                JOIN rol_privilegio rp ON ur.id_rol = rp.id_rol
                JOIN privilegio p ON rp.id_privilegio = p.id_privilegio
                WHERE ur.id_usuario = :id";

        $sth = $this->_DB->prepare($sql);
        $sth->bindParam(":id", $id_usuario, PDO::PARAM_INT);
        $sth->execute();

        return $sth->fetchAll(PDO::FETCH_COLUMN);
    }

}
?>
