// Initialize charts when document is ready
'use strict';

document.addEventListener('DOMContentLoaded', function() {
    try {
        console.log('Dashboard charts initializing...');

        // Make sure Chart.js is loaded
        if (typeof Chart === 'undefined') {
            console.error('Chart.js is not loaded!');
            return;
        }

        // Make sure we have our data
        if (!window.chartData) {
            console.error('Chart data is not loaded!');
            return;
        }

        // Register the plugin
        if (typeof ChartDataLabels !== 'undefined') {
            Chart.register(ChartDataLabels);
        } else {
            console.warn('ChartDataLabels plugin not loaded');
        }

        const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        const data = window.chartData;

        console.log('Creating charts with data:', data);

        // Function to create chart
        function createChart(canvasId, datasets) {
            const canvas = document.getElementById(canvasId);
            if (!canvas) {
                console.error('Canvas not found:', canvasId);
                return null;
            }

            console.log(`Creating chart for ${canvasId} with datasets:`, datasets);

            const ctx = canvas.getContext('2d');
            const config = {
                type: 'bar',
                data: {
                    labels: months,
                    datasets: datasets
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: true,
                            position: 'top'
                        },
                        tooltip: {
                            enabled: true,
                            mode: 'index',
                            intersect: false
                        },
                        datalabels: {
                            anchor: 'end',
                            align: 'top',
                            formatter: function(value) {
                                return Math.round(value);
                            },
                            font: {
                                weight: 'bold'
                            },
                            color: '#000'
                        }
                    }
                }
            };

            // Actually create and return the chart instance
            return new Chart(ctx, config);
        }

        try {
            // Debug data
            console.log('IR Assessment Data:', data.irAssessmentData);
            console.log('Feedback Data:', data.feedbackData);

            // IR Assessment Chart - Total Assessments per month
            const irDataset = [{
                label: 'Total Assessments',
                data: Array.from({ length: 12 }, function(_, i) {
                    const month = i + 1;
                    return data.irAssessmentData && data.irAssessmentData[month] ? data.irAssessmentData[month] : 0;
                }),
                backgroundColor: 'rgba(75, 192, 192, 0.7)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1,
                borderRadius: 4
            }];
            console.log('IR Dataset:', irDataset);
            createChart('irAssessmentChart', irDataset);

            // Feedback Chart - Status breakdown per month
            const feedbackDatasets = [
                {
                    label: 'Terkirim',
                    data: Array.from({ length: 12 }, function(_, i) {
                        const month = i + 1;
                        return data.feedbackData && data.feedbackData[month] && data.feedbackData[month].terkirim ? 
                               data.feedbackData[month].terkirim : 0;
                    }),
                    backgroundColor: 'rgba(0, 123, 255, 0.7)',
                    borderColor: 'rgba(0, 123, 255, 1)',
                    borderWidth: 1,
                    borderRadius: 4
                },
                {
                    label: 'Dalam Review',
                    data: Array.from({ length: 12 }, function(_, i) {
                        const month = i + 1;
                        return data.feedbackData && data.feedbackData[month] && data.feedbackData[month].dalam_review ? 
                               data.feedbackData[month].dalam_review : 0;
                    }),
                    backgroundColor: 'rgba(255, 193, 7, 0.7)',
                    borderColor: 'rgba(255, 193, 7, 1)',
                    borderWidth: 1,
                    borderRadius: 4
                },
                {
                    label: 'Ditanggapi',
                    data: Array.from({ length: 12 }, function(_, i) {
                        const month = i + 1;
                        return data.feedbackData && data.feedbackData[month] && data.feedbackData[month].ditanggapi ? 
                               data.feedbackData[month].ditanggapi : 0;
                    }),
                    backgroundColor: 'rgba(40, 167, 69, 0.7)',
                    borderColor: 'rgba(40, 167, 69, 1)',
                    borderWidth: 1,
                    borderRadius: 4
                },
                {
                    label: 'Ditutup',
                    data: Array.from({ length: 12 }, function(_, i) {
                        const month = i + 1;
                        return data.feedbackData && data.feedbackData[month] && data.feedbackData[month].ditutup ? 
                               data.feedbackData[month].ditutup : 0;
                    }),
                    backgroundColor: 'rgba(220, 53, 69, 0.7)',
                    borderColor: 'rgba(220, 53, 69, 1)',
                    borderWidth: 1,
                    borderRadius: 4
                }
            ];
            createChart('feedbackChart', feedbackDatasets);

            // Schedule Chart - Total schedules per month
            const scheduleDataset = [{
                label: 'Total Schedules',
                data: months.map(function(_, i) {
                    const month = i + 1;
                    return data.scheduleData && data.scheduleData[month] ? data.scheduleData[month] : 0;
                }),
                backgroundColor: 'rgba(0, 123, 255, 0.7)',
                borderColor: 'rgba(0, 123, 255, 1)',
                borderWidth: 1,
                borderRadius: 4
            }];
            createChart('scheduleChart', scheduleDataset);

            // LKS Chart - Status breakdown per month
            const lksDataset = [
                {
                    label: 'Review',
                    data: months.map(function(_, i) {
                        const month = i + 1;
                        return data.lksData && data.lksData[month] && data.lksData[month].review ? 
                               data.lksData[month].review : 0;
                    }),
                    backgroundColor: 'rgba(255, 193, 7, 0.7)',
                    borderColor: 'rgba(255, 193, 7, 1)',
                    borderWidth: 1,
                    borderRadius: 4
                },
                {
                    label: 'On Progress',
                    data: months.map(function(_, i) {
                        const month = i + 1;
                        return data.lksData && data.lksData[month] && data.lksData[month].on_progress ? 
                               data.lksData[month].on_progress : 0;
                    }),
                    backgroundColor: 'rgba(0, 123, 255, 0.7)',
                    borderColor: 'rgba(0, 123, 255, 1)',
                    borderWidth: 1,
                    borderRadius: 4
                },
                {
                    label: 'Done',
                    data: months.map(function(_, i) {
                        const month = i + 1;
                        return data.lksData && data.lksData[month] && data.lksData[month].done ? 
                               data.lksData[month].done : 0;
                    }),
                    backgroundColor: 'rgba(40, 167, 69, 0.7)',
                    borderColor: 'rgba(40, 167, 69, 1)',
                    borderWidth: 1,
                    borderRadius: 4
                }
            ];
            createChart('lksChart', lksDataset);
        } catch (error) {
            console.error('Error creating charts:', error);
        }
    } catch (error) {
        console.error('Error in dashboard charts initialization:', error);
    }
});