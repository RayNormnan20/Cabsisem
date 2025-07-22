CREATE OR REPLACE VIEW vista_planillas_recaudador AS
SELECT 
    CONCAT(c.id_cliente, '-', cr.id_credito, '-', IFNULL(u.id, 0)) AS id_unico,
    c.id_cliente,
    CONCAT(c.nombre, ' ', c.apellido) AS cliente_completo,
    c.celular AS telefono,
    cr.id_credito,
    cr.valor_credito,
    cr.saldo_actual,
    cr.numero_cuotas,
    cr.valor_cuota,
    cr.fecha_credito,
    cr.fecha_proximo_pago,
    cr.fecha_vencimiento,
    cr.id_ruta,
    (
        SELECT a.fecha_pago 
        FROM abonos a 
        WHERE a.id_credito = cr.id_credito 
        ORDER BY a.fecha_pago DESC 
        LIMIT 1
    ) AS ultima_fecha_pago,
    (
        SELECT a.monto_abono 
        FROM abonos a 
        WHERE a.id_credito = cr.id_credito 
        ORDER BY a.fecha_pago DESC 
        LIMIT 1
    ) AS ultimo_monto_pagado,
    r.nombre AS ruta,
    u.name AS recaudador,
    u.id AS id_recaudador,
    CASE 
        WHEN cr.saldo_actual <= 0 THEN 'PAGADO'
        WHEN cr.saldo_actual > 0 AND DATEDIFF(CURRENT_DATE, cr.fecha_proximo_pago) > 0 THEN 'MOROSO'
        ELSE 'AL DÍA'
    END AS estado_credito
FROM 
    clientes c
JOIN 
    creditos cr ON cr.id_cliente = c.id_cliente
JOIN 
    ruta r ON r.id_ruta = cr.id_ruta
JOIN 
    usuario_ruta ur ON ur.id_ruta = r.id_ruta
JOIN 
    users u ON u.id = ur.user_id
ORDER BY 
    r.nombre, u.name, c.nombre;
