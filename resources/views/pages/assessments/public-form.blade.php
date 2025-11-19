<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $assessment->title }} - PT. IPPI</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .assessment-container {
            max-width: 1000px;
            margin: 0 auto;
        }

        .assessment-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            overflow: hidden;
        }

        .assessment-header {
            background: linear-gradient(45deg, #0d6efd, #6f42c1);
            color: white;
            padding: 2rem;
        }

        .question-card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 1.5rem;
            transition: all 0.3s ease;
        }

        .question-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.15);
        }

        .rating-option {
            flex: 1;
            margin: 0 3px;
        }

        .rating-checkbox {
            display: none;
        }

        .rating-label {
            padding: 12px 8px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
            background: white;
            text-align: center;
        }

        .rating-label:hover {
            border-color: #0d6efd;
            background: #f8f9fa;
        }

        .rating-checkbox:checked+.rating-label {
            background-color: #0d6efd;
            border-color: #0d6efd;
            color: white;
            transform: scale(1.05);
        }

        .rating-number {
            font-weight: bold;
            font-size: 1.1rem;
            margin-bottom: 3px;
        }

        .progress {
            height: 8px;
            background: #e9ecef;
        }

        .progress-bar {
            background: linear-gradient(45deg, #0d6efd, #6f42c1);
            transition: width 0.5s ease;
        }

        .btn-primary {
            background: linear-gradient(45deg, #0d6efd, #6f42c1);
            border: none;
            padding: 12px 30px;
            font-weight: 600;
        }

        .btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 5px 15px rgba(13, 110, 253, 0.3);
        }
    </style>
</head>

<body>
    <div class="assessment-container py-4">
        <!-- Header -->
        <div class="assessment-card mb-4">
            <div class="assessment-header">
                <div class="d-flex justify-content-between align-items-center">
                    <a href="{{ route_encrypted('assessments.public') }}" class="btn btn-outline-light btn-sm">
                        <i class="bi bi-arrow-left me-2"></i>Kembali
                    </a>
                    <div class="text-center">
                        <h3 class="mb-2">{{ $assessment->title }}</h3>
                        <p class="mb-0">
                            Halaman {{ $questions->currentPage() }} dari {{ $questions->lastPage() }}
                        </p>
                    </div>
                    <div class="text-end">
                        <small>
                            Progress:
                            {{ ($questions->currentPage() - 1) * $questions->perPage() + $questions->count() }} /
                            {{ $totalQuestions }}
                        </small>
                    </div>
                </div>
            </div>

            <!-- Progress Bar -->
            <div class="p-3">
                @php
                    $currentProgress = ($questions->currentPage() - 1) * $questions->perPage() + $questions->count();
                    $progress = $totalQuestions > 0 ? ($currentProgress / $totalQuestions) * 100 : 0;
                @endphp
                <div class="progress">
                    <div class="progress-bar" role="progressbar" style="width: {{ $progress }}%">
                        {{ round($progress) }}%
                    </div>
                </div>
            </div>
        </div>

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show">
                <i class="bi bi-exclamation-triangle me-2"></i>
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Form Kuesioner -->
        <div class="assessment-card">
            <div class="card-body p-4">
                <form action="{{ route_encrypted('assessment.submit', $assessment) }}" method="POST" id="assessmentForm">
                    @csrf
                    <input type="hidden" name="user_code" value="{{ $validatedCode }}">
                    <input type="hidden" name="current_page" value="{{ $questions->currentPage() }}">

                    @foreach ($questions as $index => $question)
                        <div class="question-card card mb-4">
                            <div class="card-body">
                                <div class="question-header mb-3">
                                    <h5 class="text-primary mb-2">
                                        <i class="bi bi-question-circle me-2"></i>
                                        Pertanyaan
                                        {{ ($questions->currentPage() - 1) * $questions->perPage() + $index + 1 }}
                                    </h5>
                                    <p class="fs-6 mb-0 text-dark">{{ $question->question_text }}</p>
                                </div>

                                <!-- Rating Options -->
                                <div class="rating-section">
                                    <input type="hidden" name="responses[{{ $index }}][question_id]"
                                        value="{{ $question->id }}">

                                    <div class="rating-scale mb-3">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <small class="text-muted">Sangat Buruk</small>
                                            <small class="text-muted">Sangat Baik</small>
                                        </div>

                                        <div class="rating-options d-flex justify-content-between align-items-center">
                                            @php
                                                $ratings = [
                                                    'sangat_buruk' => ['value' => 1, 'label' => 'Sangat Buruk'],
                                                    'buruk' => ['value' => 2, 'label' => 'Buruk'],
                                                    'cukup' => ['value' => 3, 'label' => 'Cukup'],
                                                    'baik' => ['value' => 4, 'label' => 'Baik'],
                                                    'sangat_baik' => ['value' => 5, 'label' => 'Sangat Baik'],
                                                ];
                                            @endphp

                                            @foreach ($ratings as $key => $rating)
                                                <div class="rating-option text-center">
                                                    <input type="radio" name="responses[{{ $index }}][rating]"
                                                        value="{{ $key }}"
                                                        id="rating_{{ $question->id }}_{{ $key }}"
                                                        class="rating-checkbox" required
                                                        {{ old("responses.{$index}.rating") == $key ? 'checked' : '' }}>
                                                    <label class="rating-label d-block"
                                                        for="rating_{{ $question->id }}_{{ $key }}"
                                                        title="{{ $rating['label'] }}">
                                                        <div class="rating-number">{{ $rating['value'] }}</div>
                                                        <small class="d-none d-sm-block">{{ $rating['label'] }}</small>
                                                    </label>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach

                    <!-- Navigation Buttons -->
                    <div class="d-flex justify-content-between mt-4">
                        @if ($questions->currentPage() > 1)
                            <a href="{{ route_encrypted('assessment.form', ['assessment' => $assessment->id, 'page' => $questions->currentPage() - 1]) }}"
                                class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left me-2"></i>Sebelumnya
                            </a>
                        @else
                            <div></div> <!-- Spacer -->
                        @endif

                        <button type="submit" class="btn btn-primary">
                            @if ($questions->currentPage() == $questions->lastPage())
                                <i class="bi bi-send-check me-2"></i>Submit Assessment
                            @else
                                <i class="bi bi-arrow-right me-2"></i>Lanjutkan
                            @endif
                        </button>
                    </div>

                    <!-- Information -->
                    <div class="alert alert-info mt-3">
                        <small>
                            <i class="bi bi-info-circle me-2"></i>
                            Halaman {{ $questions->currentPage() }} dari {{ $questions->lastPage() }}.
                            Pastikan semua pertanyaan di halaman ini telah dijawab sebelum melanjutkan.
                        </small>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Form validation
            const form = document.getElementById('assessmentForm');
            form.addEventListener('submit', function(e) {
                let allAnswered = true;
                let unansweredQuestions = [];

                document.querySelectorAll('.question-card').forEach((card, index) => {
                    const questionNumber = (
                        {{ ($questions->currentPage() - 1) * $questions->perPage() }} + index +
                        1);
                    const hasAnswer = card.querySelector('input[type="radio"]:checked') !== null;

                    if (!hasAnswer) {
                        allAnswered = false;
                        unansweredQuestions.push(questionNumber);
                        card.style.border = '2px solid #dc3545';
                    } else {
                        card.style.border = '';
                    }
                });

                if (!allAnswered) {
                    e.preventDefault();
                    const pageType = {{ $questions->currentPage() }} === {{ $questions->lastPage() }} ?
                        'submit' : 'lanjutkan';
                    const questionList = unansweredQuestions.join(', ');
                    alert(
                        `Silakan jawab semua pertanyaan sebelum ${pageType}. \n\nPertanyaan yang belum dijawab: ${questionList}`);

                    // Scroll ke pertanyaan pertama yang belum dijawab
                    const firstUnanswered = document.querySelector(
                        '.question-card[style*="border: 2px solid #dc3545"]');
                    if (firstUnanswered) {
                        firstUnanswered.scrollIntoView({
                            behavior: 'smooth',
                            block: 'center'
                        });
                    }
                }
            });

            // Highlight selected ratings and show labels on hover
            document.querySelectorAll('input[type="radio"]').forEach(radio => {
                radio.addEventListener('change', function() {
                    // Remove border from all question cards
                    document.querySelectorAll('.question-card').forEach(card => {
                        card.style.border = '';
                    });

                    // Show label for selected rating
                    const label = this.nextElementSibling;
                    label.querySelector('small').classList.remove('d-none');
                });
            });

            // Show rating labels on hover
            document.querySelectorAll('.rating-label').forEach(label => {
                label.addEventListener('mouseenter', function() {
                    this.querySelector('small').classList.remove('d-none');
                });

                label.addEventListener('mouseleave', function() {
                    const radio = this.previousElementSibling;
                    if (!radio.checked) {
                        this.querySelector('small').classList.add('d-none');
                    }
                });
            });

            // Auto-save progress to localStorage (per page)
            function autoSaveProgress() {
                const formData = new FormData(form);
                const pageData = {
                    page: {{ $questions->currentPage() }},
                    responses: {}
                };

                document.querySelectorAll('input[type="radio"]:checked').forEach(radio => {
                    const name = radio.name;
                    pageData.responses[name] = radio.value;
                });

                localStorage.setItem(`assessment_{{ $assessment->id }}_page_{{ $questions->currentPage() }}`, JSON
                    .stringify(pageData));
            }

            // Load saved progress for current page
            function loadSavedProgress() {
                const saved = localStorage.getItem(
                    `assessment_{{ $assessment->id }}_page_{{ $questions->currentPage() }}`);
                if (saved) {
                    const pageData = JSON.parse(saved);

                    Object.keys(pageData.responses).forEach(name => {
                        const element = form.querySelector(`[name="${name}"]`);
                        if (element && element.type === 'radio') {
                            const radio = form.querySelector(
                                `[name="${name}"][value="${pageData.responses[name]}"]`);
                            if (radio) {
                                radio.checked = true;
                                // Trigger change event to update UI
                                radio.dispatchEvent(new Event('change'));
                            }
                        }
                    });
                }
            }

            // Auto-save events
            form.addEventListener('change', autoSaveProgress);

            // Load saved progress on page load
            loadSavedProgress();
        });
    </script>
</body>

</html>
