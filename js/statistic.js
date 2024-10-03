$(document).ready(function() {
    // Daten für Durchschnittliche Punktzahl und Bearbeitungszeit für verschiedene Fragenkataloge
    const averageScoreData = {
        catalog1: {
            score: 85
        },
        catalog2: {
            score: 25
        },
        catalog3: {
            score: 70
        },
        catalog4: {
            score: 95
        },
        catalog5: {
            score: 0
        },
        catalog6: {
            score: 15
        }
    };

    const averageTimeData = {
        catalog1: {
            time: 5
        },
        catalog2: {
            time: 2
        },
        catalog3: {
            time: 15
        },
        catalog4: {
            time: 25
        },
        catalog5: {
            time: 20
        },
        catalog6: {
            time: 0
        }
    };

    // Separate Timer-Variablen für jeden Counter
    let scoreTimer = null;
    let timeTimer = null;

    // Funktion zum Hochzählen des Counters für Durchschnittliche Punktzahl
    function animateScoreCounter(start, end, duration) {
        var obj = document.getElementById("averageScoreCounter");
        var range = Math.abs(end - start);
        var current = start;
        var increment = end > start ? 1 : -1;
        var stepTime = Math.max(Math.floor(duration / range), 10);

        clearInterval(scoreTimer); // Stoppt den laufenden Timer für Punktzahl

        scoreTimer = setInterval(function() {
            current += increment;
            obj.textContent = current;
            if ((increment > 0 && current >= end) || (increment < 0 && current <= end)) {
                clearInterval(scoreTimer);
                obj.textContent = end;
            }
        }, stepTime);
    }

    // Funktion zum Hochzählen des Counters für Durchschnittliche Bearbeitungszeit
    function animateTimeCounter(start, end, duration) {
        var obj = document.getElementById("averageTimeCounter");
        var range = Math.abs(end - start);
        var current = start;
        var increment = end > start ? 1 : -1;
        var stepTime = Math.max(Math.floor(duration / range), 10);

        clearInterval(timeTimer); // Stoppt den laufenden Timer für Bearbeitungszeit

        timeTimer = setInterval(function() {
            current += increment;
            obj.textContent = current;
            if ((increment > 0 && current >= end) || (increment < 0 && current <= end)) {
                clearInterval(timeTimer);
                obj.textContent = end;
            }
        }, stepTime);
    }

    // Initiale Anzeige für die ersten Kataloge
    animateScoreCounter(0, averageScoreData.catalog1.score, 500);
    animateTimeCounter(0, averageTimeData.catalog1.time, 500);

    // Event Listener für das Dropdown-Menü der Durchschnittlichen Punktzahl
    $('#averageScoreDropdown').change(function() {
        var selectedCatalog = $(this).val();
        var selectedScore = averageScoreData[selectedCatalog].score;
        animateScoreCounter(parseInt($('#averageScoreCounter').text()), selectedScore, 500);
    });

    // Event Listener für das Dropdown-Menü der Durchschnittlichen Bearbeitungszeit
    $('#averageTimeDropdown').change(function() {
        var selectedCatalog = $(this).val();
        var selectedTime = averageTimeData[selectedCatalog].time;
        animateTimeCounter(parseInt($('#averageTimeCounter').text()), selectedTime, 500);
    });

    // Chart für die Fragenkorrektheit
    var accuracyCtx = $('#accuracyChart')[0].getContext('2d');
    new Chart(accuracyCtx, {
        type: 'doughnut',
        data: {
            labels: ['Richtig', 'Falsch'],
            datasets: [{
                label: 'Fragenkorrektheit',
                data: [75, 25],
                backgroundColor: [
                    'rgba(0, 163, 108, 1)',
                    'rgba(255, 99, 132, 1)'
                ],
                borderColor: [
                    'rgba(0, 163, 108, 1)',
                    'rgba(255, 99, 132, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true
        }
    });

    // Chart für die Quiz-Abschluss-Rate
    var completionCtx = $('#completionChart')[0].getContext('2d');
    new Chart(completionCtx, {
        type: 'pie',
        data: {
            labels: ['Abgeschlossen', 'Nicht abgeschlossen'],
            datasets: [{
                label: 'Quiz-Abschluss-Rate',
                data: [60, 40],
                backgroundColor: [
                    'rgba(0, 163, 108, 1)',
                    'rgba(201, 203, 207, 1)'
                ],
                borderColor: [
                    'rgba(0, 163, 108, 1)',
                    'rgba(201, 203, 207, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true
        }
    });

    // Chart für Fragenkataloge mit der höchsten Fehlerquote
    var errorRateCtx = $('#errorRateChart')[0].getContext('2d');
    new Chart(errorRateCtx, {
        type: 'bar',
        data: {
            labels: ['Fehlerquote (%)'], // Gemeinsames Label für die x-Achse
            datasets: [{
                    label: 'Digitale Business-Modelle (DLBLODB01)',
                    data: [45], // Fehlerquote für Fragenkatalog 1
                    backgroundColor: 'rgba(255, 99, 132, 1)',
                    borderColor: 'rgba(255, 99, 132, 1)',
                    borderWidth: 1
                },
                {
                    label: 'BWL I (BBWL01-01)',
                    data: [60], // Fehlerquote für Fragenkatalog 2
                    backgroundColor: 'rgba(54, 162, 235, 1)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                },
                {
                    label: 'Requirements Engineering (IREN01)',
                    data: [55], // Fehlerquote für Fragenkatalog 3
                    backgroundColor: 'rgba(75, 192, 192, 1)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    max: 100 // Setzt das Maximum auf 100%, da es sich um eine Fehlerquote handelt
                }
            },
            plugins: {
                legend: {
                    display: true, // Zeigt die Legende für jedes Dataset an
                    labels: {
                        usePointStyle: true, // Verwende Punktstil für die Legende
                    }
                },
                tooltip: {
                    enabled: true
                }
            }
        }
    });

    // Chart für Fragenkataloge mit der besten Erfolgsquote
    var successRateCtx = $('#successRateChart')[0].getContext('2d');
    new Chart(successRateCtx, {
        type: 'bar',
        data: {
            labels: ['Erfolgsquote (%)'],
            datasets: [{
                    label: 'BWL II (BBWL02-01)',
                    data: [80],
                    backgroundColor: 'rgba(75, 192, 192, 1)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                },
                {
                    label: 'Qualitätssicherung im Softwareprozess (IQSS01)',
                    data: [90],
                    backgroundColor: 'rgba(54, 162, 235, 1)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                },
                {
                    label: 'Datenmodellierung und Datenbanksysteme (IDBS01)',
                    data: [85],
                    backgroundColor: 'rgba(153, 102, 255, 1)',
                    borderColor: 'rgba(153, 102, 255, 1)',
                    borderWidth: 1
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    max: 100 // Setzt das Maximum auf 100%, da es sich um eine Erfolgsquote handelt
                }
            },
            plugins: {
                legend: {
                    display: true,
                    labels: {
                        usePointStyle: true,
                    }
                },
                tooltip: {
                    enabled: true
                }
            }
        }
    });
    // Daten für Anzahl der Quizsessions für verschiedene Fragenkataloge
    const quizSessionsData = {
        catalog1: {
            labels: ['BWL I (BBWL01-01)'],
            sessions: [25]
        },
        catalog2: {
            labels: ['BWL II (BBWL02-01)'],
            sessions: [5]
        },
        catalog3: {
            labels: ['Digitale Business-Modelle (DLBLODB01)'],
            sessions: [10]
        },
        catalog4: {
            labels: ['Requirements Engineering (IREN01)'],
            sessions: [0]
        },
        catalog5: {
            labels: ['Datenmodellierung und Datenbanksysteme (IDBS01)'],
            sessions: [5]
        },
        catalog6: {
            labels: ['Qualitätssicherung im Softwareprozess (IQSS01)'],
            sessions: [30]
        }
    };

    // Initiale Daten für den ersten Katalog
    let selectedQuizSessionsData = quizSessionsData.catalog1;

    // Erstellen der Chart für Anzahl der Quizsessions
    var quizSessionsCtx = $('#quizSessionsChart')[0].getContext('2d');
    var quizSessionsChart = new Chart(quizSessionsCtx, {
        type: 'bar',
        data: {
            labels: selectedQuizSessionsData.labels,
            datasets: [{
                label: 'Anzahl der Sitzungen',
                data: selectedQuizSessionsData.sessions,
                backgroundColor: 'rgba(153, 102, 255, 1)',
                borderColor: 'rgba(153, 102, 255, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1, // Schrittweite auf 1 setzen, um nur ganze Zahlen anzuzeigen
                        callback: function(value) {
                            if (Number.isInteger(value)) {
                                return value; // Nur ganze Zahlen zurückgeben
                            }
                        }
                    }
                }
            },
            plugins: {
                legend: {
                    display: true,
                    labels: {
                        usePointStyle: true,
                    }
                },
                tooltip: {
                    enabled: true
                }
            }
        }
    });

    // Event Listener für das Dropdown-Menü
    $('#quizSessionsDropdown').change(function() {
        var selectedCatalog = $(this).val(); // Ermittelt den ausgewählten Wert
        selectedQuizSessionsData = quizSessionsData[selectedCatalog]; // Aktualisiert die Daten basierend auf der Auswahl

        // Aktualisiert die Chart-Daten
        quizSessionsChart.data.labels = selectedQuizSessionsData.labels;
        quizSessionsChart.data.datasets[0].data = selectedQuizSessionsData.sessions;
        quizSessionsChart.update(); // Aktualisiert die Chart-Anzeige
    });

    // Daten für verschiedene Fragenkataloge
    const data = {
        catalog1: {
            labels: ['BWL I (BBWL01-01)'],
            teams: [85],
            solo: [50]
        },
        catalog2: {
            labels: ['BWL II (BBWL02-01)'],
            teams: [25],
            solo: [75]
        },
        catalog3: {
            labels: ['Digitale Business-Modelle (DLBLODB01)'],
            teams: [50],
            solo: [50]
        },
        catalog4: {
            labels: ['Requirements Engineering (IREN01)'],
            teams: [60],
            solo: [100]
        },
        catalog5: {
            labels: ['Datenmodellierung und Datenbanksysteme (IDBS01)'],
            teams: [60],
            solo: [100]
        },
        catalog6: {
            labels: ['Qualitätssicherung im Softwareprozess (IQSS01)'],
            teams: [60],
            solo: [100]
        }
    };

    // Initiale Daten für den ersten Katalog
    let selectedData = data.catalog1;

    // Erstellen der Chart
    var teamVsSoloCtx = $('#teamVsSoloSuccessChart')[0].getContext('2d');
    var teamVsSoloChart = new Chart(teamVsSoloCtx, {
        type: 'bar',
        data: {
            labels: selectedData.labels,
            datasets: [{
                    label: 'Teams',
                    data: selectedData.teams,
                    backgroundColor: 'rgba(54, 162, 235, 1)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                },
                {
                    label: 'Einzelspieler',
                    data: selectedData.solo,
                    backgroundColor: 'rgba(255, 99, 132, 1)',
                    borderColor: 'rgba(255, 99, 132, 1)',
                    borderWidth: 1
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    max: 100 // Setzt das Maximum auf 100, da es sich um Punktzahlen handelt
                }
            },
            plugins: {
                legend: {
                    display: true,
                    labels: {
                        usePointStyle: true,
                    }
                },
                tooltip: {
                    enabled: true
                }
            }
        }
    });

    // Event Listener für das Dropdown-Menü
    $('#catalogDropdown').change(function() {
        var selectedCatalog = $(this).val(); // Ermittelt den ausgewählten Wert
        selectedData = data[selectedCatalog]; // Aktualisiert die Daten basierend auf der Auswahl

        // Aktualisiert die Chart-Daten
        teamVsSoloChart.data.labels = selectedData.labels;
        teamVsSoloChart.data.datasets[0].data = selectedData.teams;
        teamVsSoloChart.data.datasets[1].data = selectedData.solo;
        teamVsSoloChart.update(); // Aktualisiert die Chart-Anzeige
    });

    // Funktion zum Hochzählen des Counters
    function animateCounter(id, start, end, duration) {
        var obj = document.getElementById(id);
        var range = end - start;
        var current = start;
        var increment = end > start ? 1 : -1;
        var stepTime = Math.abs(Math.floor(duration / range));
        var timer = setInterval(function() {
            current += increment;
            obj.textContent = current;
            if (current == end) {
                clearInterval(timer);
            }
        }, stepTime);
    }

    // Aufruf der Funktion mit den gewünschten Werten
    animateCounter("collaborativeSessionsCounter", 0, 42, 2000); // Zählt von 0 bis 42 in 2 Sekunden
});