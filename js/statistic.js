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
        var obj = document.getElementById("averageScoreCounter"); // Holt das HTML-Element für die Anzeige der Punktzahl
        var range = Math.abs(end - start); // Berechnet die absolute Differenz zwischen Start- und Endwert
        var current = start; // Setzt den aktuellen Zähler auf den Startwert
        var increment = end > start ? 1 : -1; // Legt die Inkrement-Richtung fest (auf- oder abwärts)
        var stepTime = Math.max(Math.floor(duration / range), 10); // Berechnet die Schrittzeit pro Inkrement, mindestens jedoch 10ms

        clearInterval(scoreTimer); // Stoppt den laufenden Timer für die Punktzahl, um Doppeltimer zu verhindern

        // Setzt einen Timer, der den aktuellen Wert in regelmäßigen Schritten erhöht oder verringert
        scoreTimer = setInterval(function() {
            current += increment;
            obj.textContent = current; // Aktualisiert die Anzeige mit dem aktuellen Wert
            if ((increment > 0 && current >= end) || (increment < 0 && current <= end)) {
                clearInterval(scoreTimer); // Stoppt den Timer, wenn der Endwert erreicht ist
                obj.textContent = end; // Setzt die Anzeige sicherheitshalber auf den Endwert
            }
        }, stepTime);
    }

    // Funktion zum Hochzählen des Counters für Durchschnittliche Bearbeitungszeit
    function animateTimeCounter(start, end, duration) {
        var obj = document.getElementById("averageTimeCounter"); // Holt das HTML-Element für die Anzeige der Bearbeitungszeit
        var range = Math.abs(end - start); // Berechnet die absolute Differenz zwischen Start- und Endwert
        var current = start; // Setzt den aktuellen Zähler auf den Startwert
        var increment = end > start ? 1 : -1; // Legt die Inkrement-Richtung fest (auf- oder abwärts)
        var stepTime = Math.max(Math.floor(duration / range), 10); // Berechnet die Schrittzeit pro Inkrement, mindestens jedoch 10ms

        clearInterval(timeTimer); // Stoppt den laufenden Timer für die Bearbeitungszeit, um Doppeltimer zu verhindern

        // Setzt einen Timer, der den aktuellen Wert in regelmäßigen Schritten erhöht oder verringert
        timeTimer = setInterval(function() {
            current += increment;
            obj.textContent = current; // Aktualisiert die Anzeige mit dem aktuellen Wert
            if ((increment > 0 && current >= end) || (increment < 0 && current <= end)) {
                clearInterval(timeTimer); // Stoppt den Timer, wenn der Endwert erreicht ist
                obj.textContent = end; // Setzt die Anzeige sicherheitshalber auf den Endwert
            }
        }, stepTime);
    }

    // Initiale Anzeige für die ersten Kataloge
    animateScoreCounter(0, averageScoreData.catalog1.score, 500); // Startet die Animation für die Punktzahl des ersten Katalogs
    animateTimeCounter(0, averageTimeData.catalog1.time, 500); // Startet die Animation für die Bearbeitungszeit des ersten Katalogs

    // Event Listener für das Dropdown-Menü der Durchschnittlichen Punktzahl
    $('#averageScoreDropdown').change(function() {
        var selectedCatalog = $(this).val(); // Holt den ausgewählten Katalogwert aus dem Dropdown-Menü
        var selectedScore = averageScoreData[selectedCatalog].score; // Holt die Punktzahl des ausgewählten Katalogs
        animateScoreCounter(parseInt($('#averageScoreCounter').text()), selectedScore, 500); // Startet die Animation zum neuen Punktzahlwert
    });

    // Event Listener für das Dropdown-Menü der Durchschnittlichen Bearbeitungszeit
    $('#averageTimeDropdown').change(function() {
        var selectedCatalog = $(this).val(); // Holt den ausgewählten Katalogwert aus dem Dropdown-Menü
        var selectedTime = averageTimeData[selectedCatalog].time; // Holt die Bearbeitungszeit des ausgewählten Katalogs
        animateTimeCounter(parseInt($('#averageTimeCounter').text()), selectedTime, 500); // Startet die Animation zur neuen Bearbeitungszeit
    });

    // Chart für die Fragenkorrektheit (Donut-Diagramm)
    var accuracyCtx = $('#accuracyChart')[0].getContext('2d');
    new Chart(accuracyCtx, {
        type: 'doughnut',
        data: {
            labels: ['Richtig', 'Falsch'], // Labels für die Daten (Richtig/Falsch)
            datasets: [{
                label: 'Fragenkorrektheit', // Titel des Datasets
                data: [75, 25], // Daten (Anteil richtig/falsch)
                backgroundColor: [
                    'rgba(0, 163, 108, 1)', // Farbe für richtige Antworten
                    'rgba(255, 99, 132, 1)' // Farbe für falsche Antworten
                ],
                borderColor: [
                    'rgba(0, 163, 108, 1)',
                    'rgba(255, 99, 132, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true, // Passt die Größe der Grafik an das Browserfenster an
            maintainAspectRatio: true // Behält das Seitenverhältnis der Grafik bei
        }
    });

    // Chart für die Quiz-Abschluss-Rate (Tortendiagramm)
    var completionCtx = $('#completionChart')[0].getContext('2d');
    new Chart(completionCtx, {
        type: 'pie',
        data: {
            labels: ['Abgeschlossen', 'Nicht abgeschlossen'], // Labels für die Daten (Abgeschlossen/Nicht abgeschlossen)
            datasets: [{
                label: 'Quiz-Abschluss-Rate', // Titel des Datasets
                data: [60, 40], // Daten (Anteil abgeschlossen/nicht abgeschlossen)
                backgroundColor: [
                    'rgba(0, 163, 108, 1)', // Farbe für abgeschlossene Quiz
                    'rgba(201, 203, 207, 1)' // Farbe für nicht abgeschlossene Quiz
                ],
                borderColor: [
                    'rgba(0, 163, 108, 1)',
                    'rgba(201, 203, 207, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true, // Passt die Größe der Grafik an das Browserfenster an
            maintainAspectRatio: true // Behält das Seitenverhältnis der Grafik bei
        }
    });

    // Chart für Fragenkataloge mit der höchsten Fehlerquote (Balkendiagramm)
    var errorRateCtx = $('#errorRateChart')[0].getContext('2d');
    new Chart(errorRateCtx, {
        type: 'bar',
        data: {
            labels: ['Fehlerquote (%)'], // Gemeinsames Label für die x-Achse
            datasets: [{
                    label: 'Digitale Business-Modelle (DLBLODB01)', // Titel des ersten Datasets
                    data: [45], // Fehlerquote für den Katalog
                    backgroundColor: 'rgba(255, 99, 132, 1)', // Farbe für das Dataset
                    borderColor: 'rgba(255, 99, 132, 1)',
                    borderWidth: 1
                },
                {
                    label: 'BWL I (BBWL01-01)', // Titel des zweiten Datasets
                    data: [60], // Fehlerquote für den Katalog
                    backgroundColor: 'rgba(54, 162, 235, 1)', // Farbe für das Dataset
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                },
                {
                    label: 'Requirements Engineering (IREN01)', // Titel des dritten Datasets
                    data: [55], // Fehlerquote für den Katalog
                    backgroundColor: 'rgba(75, 192, 192, 1)', // Farbe für das Dataset
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                }
            ]
        },
        options: {
            responsive: true, // Passt die Größe der Grafik an das Browserfenster an
            maintainAspectRatio: false, // Passt die Größe dynamisch an, ohne das Seitenverhältnis beizubehalten
            scales: {
                y: {
                    beginAtZero: true, // Startet die y-Achse bei 0
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
                    enabled: true // Aktiviert die Tooltip-Anzeige
                }
            }
        }
    });

    // Chart für Fragenkataloge mit der besten Erfolgsquote (Balkendiagramm)
    var successRateCtx = $('#successRateChart')[0].getContext('2d');
    new Chart(successRateCtx, {
        type: 'bar',
        data: {
            labels: ['Erfolgsquote (%)'], // Gemeinsames Label für die x-Achse
            datasets: [{
                    label: 'BWL II (BBWL02-01)', // Titel des ersten Datasets
                    data: [80], // Erfolgsquote für den Katalog
                    backgroundColor: 'rgba(75, 192, 192, 1)', // Farbe für das Dataset
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                },
                {
                    label: 'Qualitätssicherung im Softwareprozess (IQSS01)', // Titel des zweiten Datasets
                    data: [90], // Erfolgsquote für den Katalog
                    backgroundColor: 'rgba(54, 162, 235, 1)', // Farbe für das Dataset
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                },
                {
                    label: 'Datenmodellierung und Datenbanksysteme (IDBS01)', // Titel des dritten Datasets
                    data: [85], // Erfolgsquote für den Katalog
                    backgroundColor: 'rgba(153, 102, 255, 1)', // Farbe für das Dataset
                    borderColor: 'rgba(153, 102, 255, 1)',
                    borderWidth: 1
                }
            ]
        },
        options: {
            responsive: true, // Passt die Größe der Grafik an das Browserfenster an
            maintainAspectRatio: false, // Passt die Größe dynamisch an, ohne das Seitenverhältnis beizubehalten
            scales: {
                y: {
                    beginAtZero: true, // Startet die y-Achse bei 0
                    max: 100 // Setzt das Maximum auf 100%, da es sich um eine Erfolgsquote handelt
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
                    enabled: true // Aktiviert die Tooltip-Anzeige
                }
            }
        }
    });

    // Initiale Daten für die Anzahl der Quizsessions für verschiedene Fragenkataloge
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

    // Erstellen der Chart für Anzahl der Quizsessions (Balkendiagramm)
    var quizSessionsCtx = $('#quizSessionsChart')[0].getContext('2d');
    var quizSessionsChart = new Chart(quizSessionsCtx, {
        type: 'bar',
        data: {
            labels: selectedQuizSessionsData.labels, // Labels für die x-Achse (Fragenkataloge)
            datasets: [{
                label: 'Anzahl der Sitzungen', // Titel des Datasets
                data: selectedQuizSessionsData.sessions, // Daten für die Anzahl der Quizsessions
                backgroundColor: 'rgba(153, 102, 255, 1)', // Farbe des Balkens
                borderColor: 'rgba(153, 102, 255, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true, // Passt die Größe der Grafik an das Browserfenster an
            maintainAspectRatio: false, // Passt die Größe dynamisch an, ohne das Seitenverhältnis beizubehalten
            scales: {
                y: {
                    beginAtZero: true, // Startet die y-Achse bei 0
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
                    display: true, // Zeigt die Legende für das Dataset an
                    labels: {
                        usePointStyle: true, // Verwende Punktstil für die Legende
                    }
                },
                tooltip: {
                    enabled: true // Aktiviert die Tooltip-Anzeige
                }
            }
        }
    });

    // Event Listener für das Dropdown-Menü zur Aktualisierung der Quizsessions-Chart
    $('#quizSessionsDropdown').change(function() {
        var selectedCatalog = $(this).val(); // Holt den ausgewählten Katalogwert aus dem Dropdown-Menü
        selectedQuizSessionsData = quizSessionsData[selectedCatalog]; // Aktualisiert die Daten basierend auf der Auswahl

        // Aktualisiert die Chart-Daten
        quizSessionsChart.data.labels = selectedQuizSessionsData.labels;
        quizSessionsChart.data.datasets[0].data = selectedQuizSessionsData.sessions;
        quizSessionsChart.update(); // Aktualisiert die Chart-Anzeige
    });

    // Daten für verschiedene Fragenkataloge (Teams vs. Einzelspieler)
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

    // Erstellen der Chart für Teams vs. Einzelspieler (Balkendiagramm)
    var teamVsSoloCtx = $('#teamVsSoloSuccessChart')[0].getContext('2d');
    var teamVsSoloChart = new Chart(teamVsSoloCtx, {
        type: 'bar',
        data: {
            labels: selectedData.labels, // Labels für die x-Achse (Fragenkataloge)
            datasets: [{
                    label: 'Teams', // Titel des ersten Datasets (Teams)
                    data: selectedData.teams, // Erfolgsdaten für Teams
                    backgroundColor: 'rgba(54, 162, 235, 1)', // Farbe des Balkens für Teams
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                },
                {
                    label: 'Einzelspieler', // Titel des zweiten Datasets (Einzelspieler)
                    data: selectedData.solo, // Erfolgsdaten für Einzelspieler
                    backgroundColor: 'rgba(255, 99, 132, 1)', // Farbe des Balkens für Einzelspieler
                    borderColor: 'rgba(255, 99, 132, 1)',
                    borderWidth: 1
                }
            ]
        },
        options: {
            responsive: true, // Passt die Größe der Grafik an das Browserfenster an
            maintainAspectRatio: false, // Passt die Größe dynamisch an, ohne das Seitenverhältnis beizubehalten
            scales: {
                y: {
                    beginAtZero: true, // Startet die y-Achse bei 0
                    max: 100 // Setzt das Maximum auf 100, da es sich um Punktzahlen handelt
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
                    enabled: true // Aktiviert die Tooltip-Anzeige
                }
            }
        }
    });

    // Event Listener für das Dropdown-Menü zur Aktualisierung der Teams vs. Einzelspieler-Chart
    $('#catalogDropdown').change(function() {
        var selectedCatalog = $(this).val(); // Holt den ausgewählten Katalogwert aus dem Dropdown-Menü
        selectedData = data[selectedCatalog]; // Aktualisiert die Daten basierend auf der Auswahl

        // Aktualisiert die Chart-Daten
        teamVsSoloChart.data.labels = selectedData.labels;
        teamVsSoloChart.data.datasets[0].data = selectedData.teams;
        teamVsSoloChart.data.datasets[1].data = selectedData.solo;
        teamVsSoloChart.update(); // Aktualisiert die Chart-Anzeige
    });

    // Funktion zum Hochzählen des Counters
    function animateCounter(id, start, end, duration) {
        var obj = document.getElementById(id); // Holt das HTML-Element für den Counter
        var range = end - start; // Berechnet die Differenz zwischen Start- und Endwert
        var current = start; // Setzt den aktuellen Zähler auf den Startwert
        var increment = end > start ? 1 : -1; // Legt die Inkrement-Richtung fest (auf- oder abwärts)
        var stepTime = Math.abs(Math.floor(duration / range)); // Berechnet die Schrittzeit pro Inkrement
        var timer = setInterval(function() {
            current += increment;
            obj.textContent = current; // Aktualisiert die Anzeige mit dem aktuellen Wert
            if (current == end) {
                clearInterval(timer); // Stoppt den Timer, wenn der Endwert erreicht ist
            }
        }, stepTime);
    }

    // Aufruf der Funktion zum Hochzählen des Counters
    animateCounter("collaborativeSessionsCounter", 0, 42, 2000); // Zählt von 0 bis 42 in 2 Sekunden hoch
});
