<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile Card</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        body {
            background: #eee;
        }
        .card {
            border: none;
            position: relative;
            overflow: hidden;
            border-radius: 8px;
            cursor: pointer;
        }
        .card:before {
            content: "";
            position: absolute;
            left: 0;
            top: 0;
            width: 4px;
            height: 100%;
            background-color: #fcce73;
            transform: scaleY(1);
            transition: all 0.5s;
            transform-origin: bottom;
        }
        .card:after {
            content: "";
            position: absolute;
            left: 0;
            top: 0;
            width: 4px;
            height: 100%;
            background-color: #daa53b;
            transform: scaleY(0);
            transition: all 0.5s;
            transform-origin: bottom;
        }
        .card:hover::after {
            transform: scaleY(1);
        }
        .fonts {
            font-size: 11px;
        }
        .social-list {
            display: flex;
            list-style: none;
            justify-content: center;
            padding: 0;
        }
        .social-list li {
            padding: 10px;
            color: #daa53b;
            font-size: 19px;
        }
        .buttons button:nth-child(1) {
            border: 1px solid #daa53b !important;
            color: #daa53b;
            height: 40px;
        }
        .buttons button:nth-child(1):hover {
            border: 1px solid #daa53b !important;
            color: #fff;
            height: 40px;
            background-color: #daa53b;
        }
        .buttons button:nth-child(2) {
            border: 1px solid #daa53b !important;
            background-color: #daa53b;
            color: #fff;
            height: 40px;
        }
    </style>
</head>
<body>

<div class="container">
    <div>
        <table border="0">
            <tr>
                <td rowspan="2">
                    <h1><img src="<?php echo htmlspecialchars($picture); ?>" alt="Profile Picture" class="user-img"></h1>
                </td>
                <td>
                    <h1>Hello <?php echo htmlspecialchars($username); ?><hr></h1>
                </td>
            </tr>
            <tr>
                <td align="right">
                    <h3><?php echo htmlspecialchars($role); ?></h3>
                </td>
            </tr>
        </table>
    </div>
</div>
<div class="container">
    <h1>repi</h1>
</div>

<div class="container mt-5">
    <div class="row d-flex justify-content-center">
        <div class="col-md-7">
            <div class="card p-3 py-4">
            <div class="text-center">
                    <img src="https://cdn.discordapp.com/attachments/1002310585448398938/1104672226604765184/Untitled-1.png?ex=66a1ffae&is=66a0ae2e&hm=e1f1a70e0d22973a96789a4318fc4fe94feecdf6fc4090c7d24cd00445852e5b&" width="100" class="rounded-circle">
                </div>
                <div class="text-center mt-3">
                    <span class="bg-secondary p-1 px-4 rounded text-white">RESTO SATE</span>
                    <h5 class="mt-2 mb-0">NAMA</h5>
                    <span>ROLE</span>
                    <div class="px-4 mt-1">
                        <p class="fonts">Halo, Brainies! Pernahkah kamu mencoba membuat teks deskripsi? Misalnya, teks deskripsi tentang seseorang yang kamu kagumi, hewan peliharaan, tempat wisata, tumbuhan, atau objek lain yang ada di sekitarmu. Nah, sebelum menulis teks deskripsi, kamu harus memahami tujuan dan ciri-ciri dari teks tersebut. Selanjutnya, kamu bisa menyusun teks deskripsi dengan struktur dan kaidah kebahasaan yang berlaku.</p>
                    </div>
                    <ul class="social-list">
                        <li><i class="fa fa-facebook"></i></li>
                        <li><i class="fa fa-dribbble"></i></li>
                        <li><i class="fa fa-instagram"></i></li>
                        <li><i class="fa fa-linkedin"></i></li>
                        <li><i class="fa fa-google"></i></li>
                    </ul>
                    <div class="buttons">
                        <button class="btn btn-outline-primary px-4">Message</button>
                        <button class="btn btn-primary px-4 ms-3">Contact</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>
