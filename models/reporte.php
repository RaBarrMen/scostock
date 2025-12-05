<?php
require_once("sistema.php");

class Reporte extends Sistema {

    /* ============================
       REPORTES GENERALES
    ============================ */
    
    public function getCategorias() {
        $this->connect();
        $sql = "SELECT id_categoria, nombre, descripcion, imagen, created_at 
                FROM categoria 
                ORDER BY nombre";
        $sth = $this->_DB->prepare($sql);
        $sth->execute();
        return $sth->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getProductos() {
        $this->connect();
        $sql = "SELECT p.id_producto, p.nombre, p.sku, c.nombre as categoria, 
                       p.unidad_medida, p.precio_costo, p.precio_venta, 
                       p.min_stock, p.activo, p.imagen
                FROM producto p
                LEFT JOIN categoria c ON p.id_categoria = c.id_categoria
                ORDER BY p.nombre";
        $sth = $this->_DB->prepare($sql);
        $sth->execute();
        return $sth->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getProveedores() {
        $this->connect();
        $sql = "SELECT id_proveedor, nombre, telefono, email, created_at 
                FROM proveedor 
                ORDER BY nombre";
        $sth = $this->_DB->prepare($sql);
        $sth->execute();
        return $sth->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getUsuarios() {
        $this->connect();
        $sql = "SELECT u.id_usuario, u.nombre, u.email, u.activo, u.created_at,
                       GROUP_CONCAT(r.nombre SEPARATOR ', ') as roles
                FROM usuario u
                LEFT JOIN usuario_rol ur ON u.id_usuario = ur.id_usuario
                LEFT JOIN rol r ON ur.id_rol = r.id_rol
                GROUP BY u.id_usuario
                ORDER BY u.nombre";
        $sth = $this->_DB->prepare($sql);
        $sth->execute();
        return $sth->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getRolesPrivilegios() {
        $this->connect();
        $sql = "SELECT r.nombre as rol, 
                       GROUP_CONCAT(p.privilegio SEPARATOR ', ') as privilegios
                FROM rol r
                LEFT JOIN rol_privilegio rp ON r.id_rol = rp.id_rol
                LEFT JOIN privilegio p ON rp.id_privilegio = p.id_privilegio
                GROUP BY r.id_rol
                ORDER BY r.nombre";
        $sth = $this->_DB->prepare($sql);
        $sth->execute();
        return $sth->fetchAll(PDO::FETCH_ASSOC);
    }

    /* ============================
       REPORTES ESPECIALES
    ============================ */

    public function getProductosStockBajo() {
        $this->connect();
        $sql = "SELECT p.id_producto, p.nombre, p.sku, c.nombre as categoria,
                       p.min_stock, 
                       COALESCE(
                           (SELECT SUM(CASE 
                               WHEN m.tipo = 'ENTRADA' THEN dm.cantidad
                               WHEN m.tipo = 'SALIDA' THEN -dm.cantidad
                               WHEN m.tipo = 'AJUSTE' THEN dm.cantidad
                           END)
                           FROM detalle_movimiento dm
                           JOIN movimiento m ON dm.id_mov = m.id_mov
                           WHERE dm.id_producto = p.id_producto), 0
                       ) as stock_actual
                FROM producto p
                LEFT JOIN categoria c ON p.id_categoria = c.id_categoria
                HAVING stock_actual < p.min_stock
                ORDER BY stock_actual ASC";
        $sth = $this->_DB->prepare($sql);
        $sth->execute();
        return $sth->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getProductosPorCategoria() {
        $this->connect();
        $sql = "SELECT c.nombre as categoria,
                       COUNT(p.id_producto) as total_productos,
                       SUM(CASE WHEN p.activo = 1 THEN 1 ELSE 0 END) as activos,
                       SUM(CASE WHEN p.activo = 0 THEN 1 ELSE 0 END) as inactivos,
                       AVG(p.precio_venta) as precio_promedio
                FROM categoria c
                LEFT JOIN producto p ON c.id_categoria = p.id_categoria
                GROUP BY c.id_categoria
                ORDER BY total_productos DESC";
        $sth = $this->_DB->prepare($sql);
        $sth->execute();
        return $sth->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getMovimientos($limit = 50) {
        $this->connect();
        $sql = "SELECT m.id_mov, m.tipo, m.fecha_hora, u.nombre as usuario, m.nota,
                       COUNT(dm.id_producto) as productos_afectados
                FROM movimiento m
                JOIN usuario u ON m.id_usuario = u.id_usuario
                LEFT JOIN detalle_movimiento dm ON m.id_mov = dm.id_mov
                GROUP BY m.id_mov
                ORDER BY m.fecha_hora DESC
                LIMIT :limit";
        $sth = $this->_DB->prepare($sql);
        $sth->bindParam(':limit', $limit, PDO::PARAM_INT);
        $sth->execute();
        return $sth->fetchAll(PDO::FETCH_ASSOC);
    }
}