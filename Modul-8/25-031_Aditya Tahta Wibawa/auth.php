<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function wajib_login() {
    if (!isset($_SESSION['login']) || $_SESSION['login'] !== true) {
        header("Location: login.php");
        exit;
    }
}

function wajib_owner() {
    wajib_login();
    if ($_SESSION['level'] != 1) {
        echo "<script>alert('Akses ditolak! Halaman ini hanya untuk Owner.'); window.location='index.php';</script>";
        exit;
    }
}
?>