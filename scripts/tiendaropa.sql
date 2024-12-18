--a. Creación de la base de datos
CREATE DATABASE TiendaRopa;
USE TiendaRopa;

--b. Creación de las tablas

-- Tabla de Marcas
CREATE TABLE Marca (
    id_marca INT AUTO_INCREMENT PRIMARY KEY,
    nombre_marca VARCHAR(100) NOT NULL
);

-- Tabla de Prendas
CREATE TABLE Prenda (
    id_prenda INT AUTO_INCREMENT PRIMARY KEY,
    nombre_prenda VARCHAR(100) NOT NULL,
    talla VARCHAR(5) NOT NULL,
    color VARCHAR(50),
    stock INT NOT NULL,
    id_marca INT,
    FOREIGN KEY (id_marca) REFERENCES Marca(id_marca) ON DELETE CASCADE
);

-- Tabla de Ventas
CREATE TABLE Venta (
    id_venta INT AUTO_INCREMENT PRIMARY KEY,
    fecha_venta DATE NOT NULL,
    id_prenda INT,
    cantidad INT NOT NULL,
    FOREIGN KEY (id_prenda) REFERENCES Prenda(id_prenda) ON DELETE CASCADE
);


-- c. Inserción de datos de ejemplo

-- Inserción de Marcas
INSERT INTO Marca (nombre_marca) VALUES ('Nike');
INSERT INTO Marca (nombre_marca) VALUES ('Adidas');
INSERT INTO Marca (nombre_marca) VALUES ('Zara');
INSERT INTO Marca (nombre_marca) VALUES ('H&M');
INSERT INTO Marca (nombre_marca) VALUES ('Levi');

-- Inserción de Prendas
INSERT INTO Prenda (nombre_prenda, talla, color, stock, id_marca) VALUES ('Camiseta Deportiva', 'M', 'Rojo', 50, 1);
INSERT INTO Prenda (nombre_prenda, talla, color, stock, id_marca) VALUES ('Sudadera Casual', 'L', 'Negro', 30, 2);
INSERT INTO Prenda (nombre_prenda, talla, color, stock, id_marca) VALUES ('Jeans Ajustados', '32', 'Azul', 40, 5);
INSERT INTO Prenda (nombre_prenda, talla, color, stock, id_marca) VALUES ('Chaqueta', 'M', 'Verde', 15, 3);
INSERT INTO Prenda (nombre_prenda, talla, color, stock, id_marca) VALUES ('Falda', 'S', 'Rosa', 25, 4);

-- Inserción de Ventas
INSERT INTO Venta (fecha_venta, id_prenda, cantidad) VALUES ('2024-10-01', 1, 5);
INSERT INTO Venta (fecha_venta, id_prenda, cantidad) VALUES ('2024-10-02', 2, 3);
INSERT INTO Venta (fecha_venta, id_prenda, cantidad) VALUES ('2024-10-02', 3, 10);
INSERT INTO Venta (fecha_venta, id_prenda, cantidad) VALUES ('2024-10-03', 1, 8);
INSERT INTO Venta (fecha_venta, id_prenda, cantidad) VALUES ('2024-10-04', 4, 7);


--d. Eliminación de algún dato
-- Eliminación de una venta específica
DELETE FROM Venta WHERE id_venta = 3;


--e. Actualización de algún dato
-- Actualización de stock de una prenda
UPDATE Prenda
SET stock = stock - 3
WHERE id_prenda = 2;


--f. Consultas (SELECT de los datos)

-- Selección de todas las ventas
SELECT * FROM Venta;

-- Selección de todas las prendas y su stock
SELECT nombre_prenda, stock FROM Prenda;

-- Selección de marcas y sus prendas
SELECT M.nombre_marca, P.nombre_prenda 
FROM Marca M
JOIN Prenda P ON M.id_marca = P.id_marca;


--g. Obtener la cantidad vendida de prendas por fecha (filtro por fecha específica)
-- Cantidad de prendas vendidas el 2 de octubre de 2024

SELECT P.nombre_prenda, SUM(V.cantidad) AS total_vendido
FROM Venta V
JOIN Prenda P ON V.id_prenda = P.id_prenda
WHERE V.fecha_venta = '2024-10-02'
GROUP BY P.nombre_prenda;


--h. Creación de vistas
--i. Obtener la lista de todas las marcas que tienen al menos una venta

CREATE VIEW MarcasConVentas AS
SELECT DISTINCT M.nombre_marca
FROM Marca M
JOIN Prenda P ON M.id_marca = P.id_marca
JOIN Venta V ON P.id_prenda = V.id_prenda;


--ii. Obtener prendas vendidas y su cantidad restante en stock

CREATE VIEW PrendasVendidasConStock AS
SELECT P.nombre_prenda, SUM(V.cantidad) AS total_vendido, P.stock AS stock_restante
FROM Prenda P
JOIN Venta V ON P.id_prenda = V.id_prenda
GROUP BY P.nombre_prenda, P.stock;


--iii. Obtener el listado de las 5 marcas más vendidas y su cantidad de ventas

CREATE VIEW Top5MarcasMasVendidas AS
SELECT M.nombre_marca, SUM(V.cantidad) AS total_vendido
FROM Marca M
JOIN Prenda P ON M.id_marca = P.id_marca
JOIN Venta V ON P.id_prenda = V.id_prenda
GROUP BY M.nombre_marca
ORDER BY total_vendido DESC
LIMIT 5;


--Ejemplo de consultas sobre las vistas
-- Consulta de marcas con al menos una venta
SELECT * FROM MarcasConVentas;

-- Consulta de prendas vendidas con su stock restante
SELECT * FROM PrendasVendidasConStock;

-- Consulta de las 5 marcas más vendidas
SELECT * FROM Top5MarcasMasVendidas;