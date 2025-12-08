<?php
require_once "sistema.php";

class Movimiento extends Sistema {

    /* ============================================================
       REGISTRAR MOVIMIENTO
    ============================================================ */
    public function registrarMovimiento($data) {
        $this->connect();

        $sql = "INSERT INTO movimiento (
                    id_producto, 
                    id_usuario, 
                    tipo, 
                    cantidad, 
                    stock_anterior, 
                    stock_nuevo, 
                    motivo, 
                    created_at
                ) VALUES (
                    :id_producto, 
                    :id_usuario, 
                    :tipo, 
                    :cantidad, 
                    :stock_anterior, 
                    :stock_nuevo, 
                    :motivo, 
                    NOW()
                )";

        $sth = $this->_DB->prepare($sql);
        $sth->bindParam(":id_producto", $data['id_producto']);
        $sth->bindParam(":id_usuario", $data['id_usuario']);
        $sth->bindParam(":tipo", $data['tipo']);
        $sth->bindParam(":cantidad", $data['cantidad']);
        $sth->bindParam(":stock_anterior", $data['stock_anterior']);
        $sth->bindParam(":stock_nuevo", $data['stock_nuevo']);
        $sth->bindParam(":motivo", $data['motivo']);

        return $sth->execute() ? 1 : 0;
    }

    /* ============================================================
       OBTENER STOCK ACTUAL DE UN PRODUCTO
    ============================================================ */
    public function getStockActual($id_producto) {
        $this->connect();

        // Calcular stock sumando entradas y restando salidas
        $sql = "SELECT 
                    COALESCE(SUM(CASE WHEN tipo = 'ENTRADA' THEN cantidad ELSE 0 END), 0) -
                    COALESCE(SUM(CASE WHEN tipo = 'SALIDA' THEN cantidad ELSE 0 END), 0) +
                    COALESCE(SUM(CASE WHEN tipo = 'AJUSTE' THEN cantidad ELSE 0 END), 0) AS stock_actual
                FROM movimiento
                WHERE id_producto = :id_producto";

        $sth = $this->_DB->prepare($sql);
        $sth->bindParam(":id_producto", $id_producto);
        $sth->execute();
        $result = $sth->fetch(PDO::FETCH_ASSOC);

        return $result['stock_actual'] ?? 0;
    }

    /* ============================================================
       HISTORIAL DE MOVIMIENTOS DE UN PRODUCTO
    ============================================================ */
    public function getHistorial($id_producto, $limit = 10) {
        $this->connect();

        $sql = "SELECT m.*, u.nombre as usuario
                FROM movimiento m
                JOIN usuario u ON m.id_usuario = u.id_usuario
                WHERE m.id_producto = :id_producto
                ORDER BY m.created_at DESC
                LIMIT :limit";

        $sth = $this->_DB->prepare($sql);
        $sth->bindParam(":id_producto", $id_producto, PDO::PARAM_INT);
        $sth->bindParam(":limit", $limit, PDO::PARAM_INT);
        $sth->execute();

        return $sth->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>