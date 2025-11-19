<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Terima Kasih - Assessment Selesai</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            margin: 0;
            padding: 0;
        }

        .vertical-center {
            min-height: 100vh;
            display: flex;
            align-items: center;
        }

        .thank-you-card {
            background: white;
            border-radius: 20px;
            padding: 50px 40px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            text-align: center;
            margin: 0 auto;
        }

        .success-icon {
            font-size: 80px;
            color: #28a745;
            margin-bottom: 25px;
        }

        .btn-custom {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 25px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin: 5px;
            transition: all 0.3s ease;
            min-width: 180px;
        }

        .btn-custom:hover {
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
        }

        .btn-outline-custom {
            border: 2px solid #667eea;
            color: #667eea;
            background: transparent;
            padding: 10px 28px;
            border-radius: 25px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin: 5px;
            transition: all 0.3s ease;
            min-width: 180px;
        }

        .btn-outline-custom:hover {
            background: #667eea;
            color: white;
            transform: translateY(-2px);
        }
    </style>
</head>

<body>
    <div class="vertical-center">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-6 col-lg-5">
                    <div class="thank-you-card">
                        <div class="success-icon">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <h2 class="mb-3">Terima Kasih!</h2>
                        <p class="text-muted mb-4">
                            Assessment Anda telah berhasil diselesaikan dan data telah tersimpan.
                        </p>

                        <div class="d-flex flex-column flex-sm-row justify-content-center gap-3">
                            <a href="{{ url('/') }}" class="btn-custom">
                                <i class="fas fa-home me-2"></i>Beranda
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
