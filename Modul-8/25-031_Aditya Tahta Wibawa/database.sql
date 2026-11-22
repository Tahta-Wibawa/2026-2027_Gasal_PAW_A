CREATE DATABASE IF NOT EXISTS db_penjualan2;
USE db_penjualan2;

-- Tabel User (Level 1 = Owner, Level 2 = Kasir)
CREATE TABLE user (
    id_user INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    nama VARCHAR(100) NOT NULL,
    level TINYINT(1) NOT NULL COMMENT '1=Owner, 2=Kasir'
);

-- Tabel Supplier
CREATE TABLE supplier (
    id_supplier INT AUTO_INCREMENT PRIMARY KEY,
    nama_supplier VARCHAR(100) NOT NULL,
    telp VARCHAR(20) NOT NULL,
    alamat TEXT NOT NULL
);

-- Tabel Barang
CREATE TABLE barang (
    id_barang INT AUTO_INCREMENT PRIMARY KEY,
    kode_barang VARCHAR(20) NOT NULL UNIQUE,
    nama_barang VARCHAR(100) NOT NULL,
    harga INT NOT NULL,
    stok INT NOT NULL,
    id_supplier INT,
    FOREIGN KEY (id_supplier) REFERENCES supplier(id_supplier) ON DELETE SET NULL
);

-- Tabel Pelanggan
CREATE TABLE pelanggan (
    id_pelanggan INT AUTO_INCREMENT PRIMARY KEY,
    nama_pelanggan VARCHAR(100) NOT NULL,
    jenis_kelamin ENUM('L','P') NOT NULL,
    telp VARCHAR(20) NOT NULL,
    alamat TEXT NOT NULL
);

-- Tabel Transaksi (Header)
CREATE TABLE transaksi (
    id_transaksi INT AUTO_INCREMENT PRIMARY KEY,
    tgl_transaksi DATE NOT NULL,
    id_pelanggan INT,
    id_user INT,
    total INT NOT NULL,
    FOREIGN KEY (id_pelanggan) REFERENCES pelanggan(id_pelanggan) ON DELETE SET NULL,
    FOREIGN KEY (id_user) REFERENCES user(id_user) ON DELETE SET NULL
);

-- Tabel Detail Transaksi
CREATE TABLE transaksi_detail (
    id_detail INT AUTO_INCREMENT PRIMARY KEY,
    id_transaksi INT NOT NULL,
    id_barang INT,
    qty INT NOT NULL,
    subtotal INT NOT NULL,
    FOREIGN KEY (id_transaksi) REFERENCES transaksi(id_transaksi) ON DELETE CASCADE,
    FOREIGN KEY (id_barang) REFERENCES barang(id_barang) ON DELETE SET NULL
);

-- Data Awal untuk Pengujian
INSERT INTO user (username, password, nama, level) VALUES
('owner', MD5('owner123'), 'Budi Santoso (Owner)', 1),
('kasir', MD5('kasir123'), 'Siti Aminah (Kasir)', 2);

INSERT INTO supplier (nama_supplier, telp, alamat) VALUES
('PT Sumber Makmur', '081234567890', 'Jl. Raya Darmo No. 10, Surabaya');

INSERT INTO barang (kode_barang, nama_barang, harga, stok, id_supplier) VALUES
('BRG001', 'Keyboard Mechanical', 350000, 20, 1),
('BRG002', 'Mouse Wireless', 150000, 35, 1);

INSERT INTO pelanggan (nama_pelanggan, jenis_kelamin, telp, alamat) VALUES
('Pelanggan Umum', 'L', '-', '-'),
('Andi Wijaya', 'L', '089876543210', 'Jl. Ahmad Yani No. 45');