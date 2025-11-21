@extends('layouts.app')

@section('title', 'Kalender Kegiatan')

@push('style')
    <!-- CSS Libraries -->
    <link rel="stylesheet" href="{{ asset('library/fullcalendar/dist/fullcalendar.min.css') }}">
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Kalender Kegiatan</h1>
                <div class="section-header-button">
                    <a href="{{ route('schedules.index') }}" class="btn btn-outline-primary">
                        <i class="fas fa-list"></i> Tampilan List
                    </a>
                    @if ($isAdmin)
                        <a href="{{ route('schedules.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Tambah Jadwal
                        </a>
                    @endif
                </div>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                    <div class="breadcrumb-item"><a href="{{ route('schedules.index') }}">Jadwal Kegiatan</a></div>
                    <div class="breadcrumb-item">Kalender</div>
                </div>
            </div>
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Kalender Kegiatan Perusahaan</h4>
                            <div class="card-header-action">
                                <div class="btn-group" role="group">
                                    <button type="button" class="btn btn-primary" id="prev-btn">
                                        <i class="fas fa-chevron-left"></i>
                                    </button>
                                    <button type="button" class="btn btn-primary" id="next-btn">
                                        <i class="fas fa-chevron-right"></i>
                                    </button>
                                    <button type="button" class="btn btn-primary" id="today-btn">
                                        Hari Ini
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            @if (session('success'))
                                <div class="alert alert-success alert-dismissible show fade">
                                    <div class="alert-body">
                                        <button class="close" data-dismiss="alert">
                                            <span>&times;</span>
                                        </button>
                                        {{ session('success') }}
                                    </div>
                                </div>
                            @endif

                            <!-- Legend -->
                            <div class="row mb-4">
                                <div class="col-12">
                                    <div class="d-flex flex-wrap align-items-center">
                                        <span class="mr-3"><strong>Keterangan:</strong></span>
                                        <span class="badge badge-primary mr-2 mb-1">
                                            <i class="fas fa-handshake"></i> Meeting
                                        </span>
                                        <span class="badge badge-success mr-2 mb-1">
                                            <i class="fas fa-graduation-cap"></i> Training
                                        </span>
                                        <span class="badge badge-info mr-2 mb-1">
                                            <i class="fas fa-calendar-check"></i> Event
                                        </span>
                                        <span class="badge badge-danger mr-2 mb-1">
                                            <i class="fas fa-flag"></i> Deadline
                                        </span>
                                        <span class="badge badge-warning mr-2 mb-1">
                                            <i class="fas fa-asterisk"></i> Lainnya
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <!-- Calendar -->
                            <div id="calendar"></div>

                            <!-- Upcoming Events -->
                            <div class="row mt-5">
                                <div class="col-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <h4>Kegiatan Mendatang</h4>
                                        </div>
                                        <div class="card-body">
                                            @php
                                                $upcomingSchedules = \App\Models\Schedule::with('author')
                                                    ->where('start_date', '>=', now()->format('Y-m-d'))
                                                    ->where('status', 'scheduled')
                                                    ->orderBy('start_date')
                                                    ->orderBy('start_time')
                                                    ->limit(5)
                                                    ->get();
                                            @endphp

                                            @if ($upcomingSchedules->count() > 0)
                                                <div class="list-group">
                                                    @foreach ($upcomingSchedules as $schedule)
                                                        <a href="{{ route('schedules.show', $schedule->id) }}"
                                                            class="list-group-item list-group-item-action">
                                                            <div
                                                                class="d-flex w-100 justify-content-between align-items-center">
                                                                <div class="flex-grow-1">
                                                                    <h6 class="mb-1">{{ $schedule->title }}</h6>
                                                                    <p class="mb-1 text-muted">
                                                                        <i class="fas fa-calendar mr-1"></i>
                                                                        {{ $schedule->formatted_start_date_time }}
                                                                        @if ($schedule->location)
                                                                            • <i
                                                                                class="fas fa-map-marker-alt mr-1"></i>{{ $schedule->location }}
                                                                        @endif
                                                                    </p>
                                                                </div>
                                                                <div class="text-right">
                                                                    <span
                                                                        class="badge badge-{{ [
                                                                            'meeting' => 'primary',
                                                                            'training' => 'success',
                                                                            'event' => 'info',
                                                                            'deadline' => 'danger',
                                                                            'other' => 'warning',
                                                                        ][$schedule->type] }}">
                                                                        {{ $schedule->type_name }}
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </a>
                                                    @endforeach
                                                </div>
                                            @else
                                                <div class="text-center py-4">
                                                    <i class="fas fa-calendar-times fa-2x text-muted mb-3"></i>
                                                    <p class="text-muted">Tidak ada kegiatan mendatang</p>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@push('scripts')
    <!-- JS Libraies -->
    <script src="{{ asset('library/fullcalendar/dist/fullcalendar.min.js') }}"></script>
    <script src="{{ asset('library/fullcalendar/dist/locale/id.js') }}"></script>
    <script src="{{ asset('library/sweetalert/dist/sweetalert.min.js') }}"></script>

    <!-- Page Specific JS File -->
    <script>
        $(document).ready(function() {
            // Initialize calendar
            var calendar = $('#calendar').fullCalendar({
                header: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'month,agendaWeek,agendaDay'
                },
                locale: 'id',
                timeFormat: 'H:mm',
                eventLimit: true,
                events: {!! json_encode($calendarEvents) !!},
                eventRender: function(event, element) {
                    // Tambahkan pemisah antara waktu dan judul
                    if (event.start) {
                        var timeText = '';
                        if (event.allDay) {
                            timeText = 'All Day';
                        } else {
                            timeText = $.fullCalendar.formatDate(event.start, 'H:mm');
                        }

                        // Format ulang konten event dengan pemisah
                        element.find('.fc-content').html(
                            '<div class="fc-time">' + timeText + ' - </div>' +
                            '<div class="fc-title">' + event.title + '</div>'
                        );
                    }
                    element.attr('title', event.title);
                    element.attr('data-toggle', 'tooltip');
                },
                eventClick: function(calEvent, jsEvent, view) {
                    window.location.href = calEvent.url;
                },
                dayClick: function(date, jsEvent, view) {
                    if ({{ $isAdmin ? 'true' : 'false' }}) {
                        var dateStr = date.format('YYYY-MM-DD');
                        window.location.href = '{{ route('schedules.create') }}?date=' + dateStr;
                    }
                }
            });

            // Navigation buttons
            $('#prev-btn').on('click', function() {
                $('#calendar').fullCalendar('prev');
            });

            $('#next-btn').on('click', function() {
                $('#calendar').fullCalendar('next');
            });

            $('#today-btn').on('click', function() {
                $('#calendar').fullCalendar('today');
            });

            // Initialize tooltips
            $('[data-toggle="tooltip"]').tooltip();

            // Check if there's a date parameter for creating new event
            const urlParams = new URLSearchParams(window.location.search);
            const dateParam = urlParams.get('date');
            if (dateParam && {{ $isAdmin ? 'true' : 'false' }}) {
                $('#calendar').fullCalendar('gotoDate', dateParam);
            }
        });
    </script>

    <style>
        .fc-header-toolbar {
            padding: 1rem;
            margin-bottom: 0 !important;
            background: #f8f9fa;
            border-bottom: 1px solid #dee2e6;
        }

        .fc-toolbar h2 {
            font-size: 1.5rem;
            font-weight: 600;
        }

        .fc-day-header {
            background: #3498db;
            color: white;
            padding: 10px;
            font-weight: 600;
        }

        .fc-today {
            background-color: #e8f4fd !important;
        }

        .fc-event {
            border: none;
            border-radius: 4px;
            padding: 2px 4px;
            font-size: 0.85em;
            cursor: pointer;
        }

        .fc-event .fc-content {
            display: flex;
            align-items: center;
        }

        .fc-event .fc-title {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        /* Event colors based on type */
        .fc-event-meeting {
            background-color: #3498db;
            border-color: #3498db;
        }

        .fc-event-training {
            background-color: #2ecc71;
            border-color: #2ecc71;
        }

        .fc-event-event {
            background-color: #9b59b6;
            border-color: #9b59b6;
        }

        .fc-event-deadline {
            background-color: #e74c3c;
            border-color: #e74c3c;
        }

        .fc-event-other {
            background-color: #f39c12;
            border-color: #f39c12;
        }
    </style>
@endpush
