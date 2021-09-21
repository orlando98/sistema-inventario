DROP EVENT IF EXISTS productos_por_vencer;

        DELIMITER $$
        CREATE EVENT productos_por_vencer
        ON SCHEDULE EVERY 1 DAY STARTS '2000-01-01 23:59:59'
        ON COMPLETION PRESERVE ENABLE
        DO BEGIN
            SET @dia_actual = DATE_FORMAT(now(), '%Y-%m-%d');
            SET @dia_actual_2 = DATE_FORMAT(DATE_ADD(@dia_actual, INTERVAL 30 DAY), '%Y-%m-%d');

            SET @cantidad =  (SELECT COUNT(*) FROM (SELECT COUNT(*) as cantidades FROM producto_area
            WHERE producto_area.fecha_c <= @dia_actual_2
            GROUP by producto_area.producto_fk, producto_area.area_fk, producto_area.tipo_area_fk, producto_area.lote, producto_area.fecha_c) as Cantidades);

            IF @cantidad > 0 THEN
                INSERT INTO notificaciones(titulo, detalle, created_at) VALUES('Productos por vencer', concat('Hay ', @cantidad, ' producto(s) por vencer'), now());

                SET @ultimo_registro = (SELECT idNotificacion FROM notificaciones ORDER BY idNotificacion DESC LIMIT 1);

                INSERT INTO notificaciones_pivote(notificacion_fk, producto_fk, producto_area_fk, created_at)
                    SELECT @ultimo_registro, producto_area.producto_fk, producto_area.idProductoArea, now() FROM producto_area
                    WHERE producto_area.fecha_c <= @dia_actual_2
                    GROUP by producto_area.producto_fk, producto_area.area_fk, producto_area.tipo_area_fk, producto_area.lote, producto_area.fecha_c;
            END IF;
        END;$$

        DELIMITER ;
