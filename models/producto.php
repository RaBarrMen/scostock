<?php
require_once("sistema.php");

class Producto extends Sistema {

    /* ============================================================
       CREAR PRODUCTO
    ============================================================ */
    public function create($data) {
        $this->connect();

        try {
            $this->_DB->beginTransaction();

            // SKU único
            $check = $this->_DB->prepare("SELECT COUNT(*) as total FROM producto WHERE sku = :sku");
            $check->bindParam(":sku", $data['sku']);
            $check->execute();
            $exist = $check->fetch(PDO::FETCH_ASSOC);

            if ($exist['total'] > 0) {
                return -2; // SKU duplicado
            }

            // Imagen
            $imagen = null;
            if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === 0) {
                $imagen = $this->cargarFotografia('producto', 'imagen');
            }

            $sql = "INSERT INTO producto
                        (nombre, sku, id_categoria, unidad_medida, precio_costo, precio_venta, min_stock, activo, imagen, created_at)
                    VALUES
                        (:nombre, :sku, :id_categoria, :unidad_medida, :precio_costo, :precio_venta, :min_stock, :activo, :imagen, NOW())";

            $sth = $this->_DB->prepare($sql);

            $sth->bindParam(":nombre", $data['nombre']);
            $sth->bindParam(":sku", $data['sku']);
            $sth->bindParam(":id_categoria", $data['id_categoria'], PDO::PARAM_INT);
            $sth->bindParam(":unidad_medida", $data['unidad_medida']);
            $sth->bindParam(":precio_costo", $data['precio_costo']);
            $sth->bindParam(":precio_venta", $data['precio_venta']);
            $sth->bindParam(":min_stock", $data['min_stock'], PDO::PARAM_INT);
            $sth->bindParam(":activo", $data['activo'], PDO::PARAM_INT);
            $sth->bindParam(":imagen", $imagen, PDO::PARAM_STR);

            $sth->execute();
            $this->_DB->commit();

            return 1;

        } catch (Exception $e) {
            $this->_DB->rollback();
            return 0;
        }
    }

    /* ============================================================
       LEER TODOS
    ============================================================ */
    public function read() {
        $this->connect();

        $sql = "SELECT p.*, c.nombre AS categoria
                FROM producto p
                INNER JOIN categoria c ON p.id_categoria = c.id_categoria
                ORDER BY p.nombre";

        return $this->_DB->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    /* ============================================================
       LEER UNO
    ============================================================ */
    public function readOne($id) {
        $this->connect();

        $sql = "SELECT * FROM producto WHERE id_producto = :id";
        $sth = $this->_DB->prepare($sql);
        $sth->bindParam(":id", $id);
        $sth->execute();

        return $sth->fetch(PDO::FETCH_ASSOC);
    }

    /* ============================================================
       ACTUALIZAR PRODUCTO
    ============================================================ */
    public function update($data, $id) {
        $this->connect();

        try {
            $this->_DB->beginTransaction();

            // Si cambia imagen
            $nuevaImagen = null;
            $incluyeImagen = (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === 0);

            if ($incluyeImagen) {
                $nuevaImagen = $this->cargarFotografia("producto", "imagen");
            }

            $sql = "UPDATE producto SET
                        nombre = :nombre,
                        sku = :sku,
                        id_categoria = :id_categoria,
                        unidad_medida = :unidad_medida,
                        precio_costo = :precio_costo,
                        precio_venta = :precio_venta,
                        min_stock = :min_stock,
                        activo = :activo";

            if ($incluyeImagen) {
                $sql .= ", imagen = :imagen";
            }

            $sql .= " WHERE id_producto = :id";

            $sth = $this->_DB->prepare($sql);

            $sth->bindParam(":nombre", $data['nombre']);
            $sth->bindParam(":sku", $data['sku']);
            $sth->bindParam(":id_categoria", $data['id_categoria']);
            $sth->bindParam(":unidad_medida", $data['unidad_medida']);
            $sth->bindParam(":precio_costo", $data['precio_costo']);
            $sth->bindParam(":precio_venta", $data['precio_venta']);
            $sth->bindParam(":min_stock", $data['min_stock']);
            $sth->bindParam(":activo", $data['activo']);
            $sth->bindParam(":id", $id);

            if ($incluyeImagen) {
                $sth->bindParam(":imagen", $nuevaImagen);
            }

            $sth->execute();
            $this->_DB->commit();
            return 1;

        } catch (Exception $e) {
            $this->_DB->rollback();
            return 0;
        }
    }

    /* ============================================================
       ELIMINAR PRODUCTO
    ============================================================ */
    public function delete($id) {
        $this->connect();

        // Evitar borrar si tiene movimientos
        $check = $this->_DB->prepare("SELECT COUNT(*) as total FROM detalle_movimiento WHERE id_producto = :id");
        $check->bindParam(":id", $id);
        $check->execute();
        $exist = $check->fetch(PDO::FETCH_ASSOC);

        if ($exist['total'] > 0) {
            return -1; // No borrar
        }

        // Eliminar
        $sql = "DELETE FROM producto WHERE id_producto = :id";
        $sth = $this->_DB->prepare($sql);
        $sth->bindParam(":id", $id);

        return $sth->execute() ? 1 : 0;
    }

    /* ============================================================
    MÉTODOS PARA CATÁLOGO VENDEDOR
    ============================================================ */

    // Obtener solo productos activos
    public function readActivos() {
        $this->connect();
        $sql = "SELECT p.*, c.nombre as categoria 
                FROM producto p
                LEFT JOIN categoria c ON p.id_categoria = c.id_categoria
                WHERE p.activo = 1
                ORDER BY p.nombre";
        return $this->_DB->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener productos activos por categoría
    public function readByCategoria($id_categoria) {
        $this->connect();
        $sql = "SELECT p.*, c.nombre as categoria 
                FROM producto p
                LEFT JOIN categoria c ON p.id_categoria = c.id_categoria
                WHERE p.activo = 1 AND p.id_categoria = :id_categoria
                ORDER BY p.nombre";
        $sth = $this->_DB->prepare($sql);
        $sth->bindParam(":id_categoria", $id_categoria, PDO::PARAM_INT);
        $sth->execute();
        return $sth->fetchAll(PDO::FETCH_ASSOC);
    }

}
?>
