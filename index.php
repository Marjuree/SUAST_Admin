<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Initializing...</title>
  <link rel="stylesheet" href="./Style/ImportantImport.css" />
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: "Poppins", sans-serif;
    }

    body {
      height: 100vh;
      background-color: #003366; /* Deep blue */
      display: flex;
      justify-content: center;
      align-items: center;
      color: white;
      text-align: center;
      padding: 20px;
    }

    .container {
      background: rgba(255 255 255 / 0.12);
      backdrop-filter: blur(12px);
      padding: 30px 40px;
      border-radius: 16px;
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.25);
      display: flex;
      flex-direction: column;
      align-items: center;
      gap: 25px;
      max-width: 320px;
    }

    .logo {
      width: 120px;
      height: auto;
      margin-bottom: 10px;
      user-select: none;
    }

    /* Spinner styles */
    .spinner {
      width: 64px;
      height: 64px;
      border-radius: 50%;
      position: relative;
      border: 6px solid rgba(255 255 255 / 0.3);
      border-top-color: #66b2ff; /* lighter blue for contrast */
      animation: spin 1.3s linear infinite;
      box-shadow:
        0 0 10px rgba(102, 178, 255, 0.2),
        inset 0 0 15px rgba(102, 178, 255, 0.4);
    }

    /* Inner pulse ring */
    .spinner::before {
      content: "";
      position: absolute;
      top: 10px;
      left: 10px;
      right: 10px;
      bottom: 10px;
      border-radius: 50%;
      border: 4px solid transparent;
      border-top-color: #66b2ff;
      animation: pulse 1.3s ease-in-out infinite;
    }

    @keyframes spin {
      0% {
        transform: rotate(0deg);
      }

      100% {
        transform: rotate(360deg);
      }
    }

    @keyframes pulse {
      0%,
      100% {
        transform: scale(1);
        opacity: 1;
      }

      50% {
        transform: scale(1.15);
        opacity: 0.6;
      }
    }

    h1 {
      font-weight: 700;
      font-size: 1.8rem;
      letter-spacing: 1.1px;
      user-select: none;
    }
  </style>
</head>

<body>
  <div class="container">
    <img src="img/uni.png" alt="SUAST Logo" class="logo" />
    <div class="spinner"></div>
    <h1>Welcome to UniReserve!</h1>
  </div>

  <script>
    const token = Math.random().toString(36).substring(2, 18);
    const redirectUrl = `./php/admin_page.php?welcome=Please login to access this page&token=${token}`;
    setTimeout(() => {
      window.location.href = redirectUrl;
    }, 1800);
  </script>
</body>

</html>
