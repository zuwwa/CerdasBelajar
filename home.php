<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Selamat Datang - CerdasBelajar</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background: linear-gradient(to right, #02287a, #1b7fdb);
      height: 100vh; display: flex; justify-content: center; align-items: center;
    }
    .container {
      background: white;
      padding: 40px;
      border-radius: 16px;
      text-align: center;
      box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    }
    h1 { margin-bottom: 20px; color: #02287a; }
    .btn {
      display: block;
      margin: 10px auto;
      padding: 12px 20px;
      font-size: 16px;
      border: none;
      border-radius: 8px;
      cursor: pointer;
      width: 200px;
    }
    .siswa { background-color: #1b7fdb; color: white; }
    .guru { background-color: #28a745; color: white; }
    .ortu { background-color: #ff9800; color: white; }
    .kepsek { background-color: #9c27b0; color: white; }
  </style>
</head>
<body>
  <div class="container">
    <h1>Login Sebagai</h1>
    <a href="index.php?role=siswa"><button class="btn siswa">Siswa</button></a>
    <a href="index.php?role=guru"><button class="btn guru">Guru</button></a>
    <a href="index.php?role=ortu"><button class="btn ortu">Orang Tua</button></a>
    <a href="index.php?role=kepsek"><button class="btn kepsek">Kepala Sekolah</button></a>
  </div>
</body>
</html>
