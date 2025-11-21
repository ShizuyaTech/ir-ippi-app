<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Validasi Kode User - {{ $assessment->title }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            padding: 20px;
        }

        .validation-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            padding: 2.5rem;
            max-width: 500px;
            width: 100%;
        }

        .btn-primary {
            background: linear-gradient(45deg, #0d6efd, #6f42c1);
            border: none;
            padding: 12px 30px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }

        .assessment-info {
            background: linear-gradient(45deg, #f8f9fa, #e9ecef);
            border-radius: 10px;
            padding: 1rem;
            margin-bottom: 1.5rem;
        }

        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }
    </style>
</head>

<body>
    <div class="validation-card">
        <div class="text-center mb-4">
            <div class="mb-3">
                <i class="bi bi-shield-check text-primary" style="font-size: 3rem;"></i>
            </div>
            <h2 class="text-primary fw-bold">Validasi Kode User</h2>
            <p class="text-muted">Silakan masukkan kode user untuk mengisi assessment</p>
        </div>

        <!-- Assessment Information -->
        <div class="assessment-info">
            <h5 class="fw-bold text-dark">{{ $assessment->title }}</h5>
            @if ($assessment->description)
                <p class="text-muted mb-1">{{ Str::limit($assessment->description, 100) }}</p>
            @endif
            @if ($assessment->start_date && $assessment->end_date)
                <small class="text-muted">
                    <i class="bi bi-calendar me-1"></i>
                    {{ $assessment->start_date->format('d M Y') }} - {{ $assessment->end_date->format('d M Y') }}
                </small>
            @endif
        </div>

        <!-- Alert Messages -->
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                <div>
                    {{ session('error') }}
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show d-flex align-items-center" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i>
                <div>
                    {{ session('success') }}
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Validation Errors -->
        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show">
                <h6 class="alert-heading">
                    <i class="bi bi-exclamation-triangle me-2"></i>Terjadi Kesalahan
                </h6>
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Validation Form -->
        <form action="{{ route('assessment.validate', $assessment) }}" method="POST">
            @csrf
            <div class="mb-4">
                <label for="user_code" class="form-label fw-semibold">
                    <i class="bi bi-key me-2"></i>Kode User
                </label>
                <input type="text" class="form-control form-control-lg @error('user_code') is-invalid @enderror"
                    id="user_code" name="user_code" value="{{ old('user_code') }}" required autofocus>
                @error('user_code')
                    <div class="invalid-feedback">
                        <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                    </div>
                @enderror
                <div class="form-text text-muted">
                    <small>
                        <i class="bi bi-info-circle me-1"></i>
                        Masukkan kode user yang telah diberikan.
                    </small>
                </div>
            </div>

            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-primary btn-lg">
                    <i class="bi bi-check-circle me-2"></i>Validasi Kode
                </button>
                <a href="{{ route('assessments.public') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-2"></i>Kembali ke Daftar Assessment
                </a>
            </div>
        </form>

        <!-- Help Information -->
        <div class="mt-4 pt-3 border-top">
            <div class="text-center">
                <small class="text-muted">
                    <i class="bi bi-question-circle me-1"></i>
                    Tidak memiliki kode user? Hubungi administrator untuk mendapatkan kode.
                </small>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Auto focus on user code input
            const userCodeInput = document.getElementById('user_code');
            if (userCodeInput) {
                userCodeInput.focus();
            }

            // Auto uppercase and format input
            userCodeInput.addEventListener('input', function(e) {
                let value = e.target.value.toUpperCase();

                // Remove any existing hyphens
                value = value.replace(/-/g, '');

                // Add hyphens at positions 3 and 7 if needed
                if (value.length > 3) {
                    value = value.substring(0, 3) + '-' + value.substring(3);
                }
                if (value.length > 7) {
                    value = value.substring(0, 7) + '-' + value.substring(7);
                }

                // Limit to 11 characters (XXX-XXX-XXX)
                if (value.length > 11) {
                    value = value.substring(0, 11);
                }

                e.target.value = value;
            });

            // Auto dismiss alerts after 5 seconds
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                setTimeout(function() {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                }, 5000);
            });
        });
    </script>
</body>

</html>
