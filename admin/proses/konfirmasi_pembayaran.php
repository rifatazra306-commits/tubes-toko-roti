<?php
include '../../koneksi/koneksi.php';

if (isset($_GET['inv'])) {
    $inv = mysqli_real_escape_string($conn, $_GET['inv']);
    
    // Update status_pembayaran to 'Lunas'
    $query = mysqli_query($conn, "UPDATE produksi SET status_pembayaran = 'Lunas' WHERE invoice = '$inv'");
    
    if ($query) {
        echo "
        <script>
        alert('Pembayaran Invoice " . $inv . " Berhasil Dikonfirmasi!');
        window.location = '../produksi.php';
        </script>
        ";
    } else {
        echo "
        <script>
        alert('Gagal mengonfirmasi pembayaran: " . mysqli_error($conn) . "');
        window.location = '../produksi.php';
        </script>
        ";
    }
} else {
    header("Location: ../produksi.php");
}
?>
