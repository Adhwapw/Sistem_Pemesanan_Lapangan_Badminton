<?php include 'koneksi.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gor Dewi</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 0; }
        nav ul { display: flex; gap: 20px; list-style: none; background: #333; padding: 10px; }
        nav ul li { color: white; cursor: pointer; }
        section { padding: 20px; }
        .lapangan-container {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
        }
        .lapangan-card {
            width: 200px;
            border: 1px solid #ccc;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .lapangan-card img {
            width: 100%;
            height: 120px;
            object-fit: cover;
        }
        .lapangan-card .nama {
            padding: 10px;
            text-align: center;
            background-color: #f9f9f9;
            font-weight: bold;
        }
        footer {
            text-align: center;
            padding: 10px;
            background: #eee;
        }
    </style>
</head>
<body>
    <nav>
        <h1>Gor Dewi</h1>
        <ul>
            <li>Home</li>
            <li>Lapangan</li>
            <li>Booking</li>
            <li>Profile</li>
        </ul>
    </nav>

    <section>
        <h1>Selamat Datang</h1>
        <h3>di Website Gor Dewi</h3>
    </section>

    <section>
        <h2>Daftar Lapangan</h2>
        <div class="lapangan-container">
            <?php
            $status_lapangan;
            $data = mysqli_query($conn, "SELECT * FROM lapangan");
            while($d = mysqli_fetch_array($data)) {
                if($d['status_aktif'] == 1){
                    $status_lapangan = 'Aktif';
                }else{
                    $status_lapangan = 'Tidak Aktif';
                }
                echo "
                <div class='lapangan-card'>
                    <img src='assets/lapangan_badminton.jpg' alt='Lapangan'>
                    <div class='nama'>{$d['nama_lapangan']}</div>
                    <div class='nama'>{$status_lapangan}</div>
                </div>
                ";
            }
            ?>
        </div>
    </section>

    <footer>
        <p>By Kelompok 4</p>
    </footer>
</body>
</html>
