<?php
require_once("sistema.php");

class ProductoProveedor extends Sistema {

    /* ============================================================
    LEER LISTA COMPLETA CON TODOS LOS DATOS
    ============================================================ */
    public function read() {
        $this->connect();

        $sql = "SELECT pp.id_producto, pp.id_proveedor, 
                    p.nombre AS producto,
                    p.sku AS producto_sku,
                    p.precio_costo,
                    p.precio_venta,
                    p.stock,
                    p.min_stock,
                    p.unidad_medida,
                    p.activo,
                    p.imagen,
                    c.nombre AS categoria,
                    pr.nombre AS proveedor,
                    pr.telefono AS proveedor_telefono,
                    pr.email AS proveedor_email
                FROM producto_proveedor pp
                INNER JOIN producto p ON pp.id_producto = p.id_producto
                LEFT JOIN categoria c ON p.id_categoria = c.id_categoria
                INNER JOIN proveedor pr ON pp.id_proveedor = pr.id_proveedor
                ORDER BY pr.nombre, p.nombre";

        $sth = $this->_DB->prepare($sql);
        $sth->execute();

        return $sth->fetchAll(PDO::FETCH_ASSOC);
    }

    /* ============================================================
       OBTENER PROVEEDORES DE UN PRODUCTO
    ============================================================ */
    public function readByProducto($id_producto){
        $this->connect();

        $sql = "SELECT pp.id_producto, pp.id_proveedor, 
                       pr.nombre, pr.telefono, pr.email
                FROM producto_proveedor pp
                INNER JOIN proveedor pr ON pp.id_proveedor = pr.id_proveedor
                WHERE pp.id_producto = :id";

        $sth = $this->_DB->prepare($sql);
        $sth->bindParam(":id", $id_producto);
        $sth->execute();

        return $sth->fetchAll(PDO::FETCH_ASSOC);
    }

    /* ============================================================
    OBTENER PRODUCTOS DE UN PROVEEDOR (CON TODOS LOS CAMPOS)
    ============================================================ */
    public function readByProveedor($id_proveedor){
        $this->connect();

        $sql = "SELECT p.id_producto,
                    p.nombre, 
                    p.sku, 
                    p.precio_costo, 
                    p.precio_venta, 
                    p.stock,
                    p.min_stock,
                    p.unidad_medida,
                    p.activo,
                    p.imagen,
                    p.id_categoria,
                    c.nombre as categoria
                FROM producto_proveedor pp
                INNER JOIN producto p ON pp.id_producto = p.id_producto
                LEFT JOIN categoria c ON p.id_categoria = c.id_categoria
                WHERE pp.id_proveedor = :id AND p.activo = 1
                ORDER BY p.nombre";

        $sth = $this->_DB->prepare($sql);
        $sth->bindParam(":id", $id_proveedor, PDO::PARAM_INT);
        $sth->execute();

        return $sth->fetchAll(PDO::FETCH_ASSOC);
    }

    /* ============================================================
       ASIGNAR PROVEEDOR A PRODUCTO
    ============================================================ */
    public function create($id_producto, $id_proveedor){
        $this->connect();

        // Evitar duplicados
        $sql = "SELECT COUNT(*) AS total 
                FROM producto_proveedor 
                WHERE id_producto = :p AND id_proveedor = :pr";

        $sth = $this->_DB->prepare($sql);
        $sth->bindParam(":p", $id_producto);
        $sth->bindParam(":pr", $id_proveedor);
        $sth->execute();

        $res = $sth->fetch(PDO::FETCH_ASSOC);

        if ($res['total'] > 0){
            return -1; // duplicado
        }

        // Insertar
        $sql = "INSERT INTO producto_proveedor(id_producto, id_proveedor)
                VALUES(:p, :pr)";

        $sth = $this->_DB->prepare($sql);
        $sth->bindParam(":p", $id_producto);
        $sth->bindParam(":pr", $id_proveedor);

        return $sth->execute() ? 1 : 0;
    }

    /* ============================================================
       ELIMINAR RELACIÓN PRODUCTO-PROVEEDOR
    ============================================================ */
    public function delete($id_producto, $id_proveedor){
        $this->connect();

        $sql = "DELETE FROM producto_proveedor 
                WHERE id_producto = :p AND id_proveedor = :pr";

        $sth = $this->_DB->prepare($sql);
        $sth->bindParam(":p", $id_producto);
        $sth->bindParam(":pr", $id_proveedor);

        return $sth->execute() ? 1 : 0;
    }

    /* ============================================================
       OBTENER LISTA DE PROVEEDORES (para dropdown)
    ============================================================ */
    public function getProveedores() {
        $this->connect();
        $sql = "SELECT id_proveedor, nombre FROM proveedor ORDER BY nombre";
        $sth = $this->_DB->prepare($sql);
        $sth->execute();
        return $sth->fetchAll(PDO::FETCH_ASSOC);
    }

}
?>