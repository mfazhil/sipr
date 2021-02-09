><?php
// fungsi untuk memulai session > perhatikan lokasi penempatan session juga karena telah di include pada navbar
session_start();
 
// variabel kosong untuk menyimpan pesan error
$form_error = '';
 
// cek apakah tombol sumit sudah di klik atau belum
if(isset($_POST['submit'])){
 
    // menyimpan data yang dikirim dari metode POST ke masing-masing variabel
    $username = $_POST['username'];
    $password = $_POST['password'];
 
    // validasi login benar atau salah
    if($username == 'admin' AND $password == 'admin' OR $username == 'user' AND $password == 'user'){
 
        // jika login benar maka email akan disimpan ke session kemudian akan di redirect ke halaman profil
        $_SESSION['username'] = $username;
        header('Location: index.php');
    }else{
 
        // jika login salah maka variabel form_error diisi value seperti dibawah
        // nilai variabel ini akan ditampilkan di halaman login jika salah
        $form_error = '<p>Password atau email yang kamu masukkan salah</p>';
    }
}
 
?>