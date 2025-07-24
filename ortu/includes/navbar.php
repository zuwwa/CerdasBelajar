<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Tagihan Anak</title>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
  <style>
    :root {
      --primary-color: rgb(2, 40, 122);
      --secondary-color: rgb(27, 127, 219);
      --navbar-height: 75px;
      --sidebar-width: 250px;
    }

    body {
      font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
      background-color: #f5f5f5;
      overflow-x: hidden;
      padding-top: var(--navbar-height);

    }

    .navbar {
      position: fixed;
      background: linear-gradient(90deg, rgb(2, 40, 122), #004080);
      top: 0;
      left: 0;
      right: 0;
      height: var(--navbar-height);
      z-index: 1001;
      padding-left: 1rem;
      padding-right: 1rem;
    }

    .navbar-brand {
    display: flex;
    align-items: center;
    text-decoration: none;
    }

    .navbar-brand .brand-text h2 {
    font-size: 1.2rem;
    font-weight: bold;
    color: white;
    line-height: 1.2;
    }

    .navbar-brand .brand-text p {
    font-size: 0.95rem;
    font-weight: normal;
    color: white;
    margin: 0;
    }

    .navbar .navbar-brand img {
      height: 50px;
      object-fit: contain;
      margin-right: 10px;
    }

    .navbar .user-info {
      display: flex;
      align-items: center;
      color: white;
    }

    .navbar .user-info img {
      width: 40px;
      height: 40px;
      border-radius: 50%;
      margin-right: 10px;
      border: 1px solid white;
    }

    .navbar .user-info span {
      font-weight: 400;
    }

    .status-indicator {
    font-size: 0.9rem;
    margin-top: 1px;
    display: flex;
    align-items: center;
    color: #bfbfbf;
    justify-content: flex-end; 
    }

    .status-indicator .dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background-color: #28a745; /* Hijau */
    display: inline-block;
    margin-right: 6px;
    }

  </style>
</head>
<body>
  <nav class="navbar d-flex align-items-center">
    <a class="text-decoration-none" href="/CerdasBelajar/ortu/">    
        <div class="navbar-brand d-flex align-items-center ms-2">
            <img src="/CerdasBelajar/images/sma.png" alt="logo" />
            <div class="brand-text ms-2">
                <h2 class="mb-0">DASHBOARD ORANG TUA</h2>
                <p class="mb-0">SMA NEGERI 1 KOTA SUKABUMI</p>
            </div>
        </div>
    </a>

    <div class="user-info d-flex align-items-center ms-auto text-end">
        <div class="me-2">
            <div class="name fw-bold">Nama Orang Tua</div>
            <div class="status-indicator justify-content-end">
            <span class="dot"></span> Aktif
            </div>
        </div>
        <a href="/CerdasBelajar/ortu/modules/profil/">
            <img src="/CerdasBelajar/ortu/images/profile.png" alt="User" />
        </a>
    </div>
  </nav>
</body>
</html>
