use householdaccounting_v1;
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

