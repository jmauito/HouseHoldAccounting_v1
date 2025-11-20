SET NAMES utf8mb4;
/*
FT = FICHA TECNICA COMPROBANTES ELECTRÓNICOS ESQUEMA OFFLINE
*/
/*
See table 3 in FT
    */
insert into voucher_type (name, code)
values ('FACTURA','01');

INSERT INTO deductible(name)
values ('VIVIENDA'),('EDUCACIÓN, ARTE Y CULTURA'),('SALUD'),('VESTIMENTA'),
       ('ALIMENTACIÓN'),('TURISMO');

INSERT INTO expense(name)
VALUES ('AUTOMÓVIL'),('DIVERSIÓN'),('SALUD'),('COMUNICACIÓN'),('SERVICIOS BÁSICOS');

-- =========================
-- Tabla: tax
-- =========================
INSERT INTO tax (id, name, code, active) VALUES
  (1, 'IVA',    2, 1),
  (2, 'ICE',    3, 1),
  (3, 'IRBPNR', 5, 1);

-- =========================
-- Tabla: tax_rate (tarifas de IVA – TABLA 17)
-- taxId referencia a tax.id (IVA = 1)
-- =========================
INSERT INTO tax_rate (id, name, taxId, code, percentage, active) VALUES
  (1, 'IVA 0%',         1, 0,  0.00, 1),
  (2, 'IVA 12%',        1, 2, 12.00, 1),
  (3, 'IVA 14%',        1, 3, 14.00, 1),
  (4, 'IVA No objeto',  1, 6,  0.00, 1),
  (5, 'IVA Exento',     1, 7,  0.00, 1);