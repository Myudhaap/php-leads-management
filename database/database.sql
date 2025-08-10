CREATE DATABASE IF NOT EXISTS db_leads;

USE db_leads;

CREATE TABLE IF NOT EXISTS produk (
    id_produk INT AUTO_INCREMENT PRIMARY KEY,
    nama_produk VARCHAR(255) NOT NULL
);

CREATE TABLE IF NOT EXISTS sales (
  id_sales INT AUTO_INCREMENT PRIMARY KEY,
  nama_sales VARCHAR(255) NOT NULL
);

CREATE TABLE IF NOT EXISTS leads (
    id_leads INT AUTO_INCREMENT PRIMARY KEY,
    tanggal DATE NOT NULL,
    id_sales INT NOT NULL,
    id_produk INT NOT NULL,
    no_wa VARCHAR(20),
    nama_lead VARCHAR(255),
    kota VARCHAR(255),
    id_user INT,

    CONSTRAINT fk_leads_id_sales
    FOREIGN KEY (id_sales)
    REFERENCES sales(id_sales)
    ON DELETE CASCADE
    ON UPDATE CASCADE,

    CONSTRAINT fk_leads_id_produk
    FOREIGN KEY (id_produk)
    REFERENCES produk(id_produk)
    ON DELETE CASCADE
    ON UPDATE CASCADE
);


-- DML
INSERT INTO produk (nama_produk)
VALUES
('Cipta Residence 2'),
('The Rich'),
('Namorambe City'),
('Grand Bantem'),
('Turi Mansion'),
('Cipta Residence 1');

INSERT INTO sales (nama_sales)
VALUES
('Sales 1'),
('Sales 2'),
('Sales 3');

INSERT INTO leads (tanggal, id_sales, id_produk, no_wa, nama_lead, kota, id_user)
VALUES
('2025-08-9', 1, 1, '081311901903', 'Yudha', 'Makassar', null);