<!DOCTYPE html>
<html lang="de">
<?php require_once("component/head.php"); ?>
<body class="overflow-hidden">
    <!-- NAVBAR-->
    <?php include("component/navbar.php"); ?>
    <div id="main-content" class="container-fluid py-4 overflow-y-auto">
        <div class="container">
            <div class="row mb-3">
                <div class="col-12">
                    <div class="p-3 bg-white shadow-sm border large-container rounded card">
                        <h4 class="text-center p-3 mb-4">Quiz-Leistungsstatistik</h4>
                        <div class="row">
                            <div class="col-md-6 col-sm-12 mb-5">
                                <div class="counter-container bg-white">
                                    <h5 class="text-center mb-2 text-secondary">Durchschn. Punktzahl je Fragenkatalog</h5>
                                    <select id="averageScoreDropdown" class="form-select mb-3" aria-label="Fragenkatalog Auswahl">
                                        <option value="catalog1">BWL I (BBWL01-01)</option>
                                        <option value="catalog2">BWL II (BBWL02-01)</option>
                                        <option value="catalog3">Digitale Business-Modelle (DLBLODB01)</option>
                                        <option value="catalog4">Requirements Engineering (IREN01)</option>
                                        <option value="catalog5">Datenmodellierung und Datenbanksysteme (IDBS01)</option>
                                        <option value="catalog6">Qualitätssicherung im Softwareprozess (IQSS01)</option>
                                    </select>
                                    <div id="averageScoreCounter" class="counter">0</div>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-12 mb-5">
                                <div class="counter-container bg-white">
                                    <h5 class="text-center mb-2">Durchschnittliche Bearbeitungszeit (Minuten)</h5>
                                    <select id="averageTimeDropdown" class="form-select mb-3" aria-label="Fragenkatalog Auswahl">
                                        <option value="catalog1">BWL I (BBWL01-01)</option>
                                        <option value="catalog2">BWL II (BBWL02-01)</option>
                                        <option value="catalog3">Digitale Business-Modelle (DLBLODB01)</option>
                                        <option value="catalog4">Requirements Engineering (IREN01)</option>
                                        <option value="catalog5">Datenmodellierung und Datenbanksysteme (IDBS01)</option>
                                        <option value="catalog6">Qualitätssicherung im Softwareprozess (IQSS01)</option>
                                    </select>
                                    <div id="averageTimeCounter" class="counter">0</div>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-12 mb-5">
                                <h5 class="text-center mb-2 text-secondary">Fragenkorrektheit</h5>
                                <div class="chart-container">
                                    <canvas id="accuracyChart"></canvas>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-12 mb-5">
                                <h5 class="text-center mb-2 text-secondary">Abschlussquote</h5>
                                <div class="chart-container">
                                    <canvas id="completionChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-12">
                    <div class="p-3 bg-white shadow-sm border large-container rounded card">
                        <h4 class="text-center p-3 mb-4">Fragen-Statistik</h4>
                        <div class="row">
                            <div class="col-md-6 col-sm-12 mb-5">
                                <h5 class="text-center mb-2 text-secondary">Fragenkataloge mit den höchsten Fehlerquoten</h5>
                                <div class="chart-container">
                                    <canvas id="errorRateChart"></canvas>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-12 mb-5">
                                <h5 class="text-center mb-2 text-secondary">Fragenkataloge mit den höchsten Erfolgsquoten</h5>
                                <div class="chart-container">
                                    <canvas id="successRateChart"></canvas>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-12 mb-5">
                                <h5 class="text-center mb-2 text-secondary">Anzahl der Quizsessions</h5>
                                <select id="quizSessionsDropdown" class="form-select mb-3" aria-label="Fragenkatalog Auswahl">
                                    <option value="catalog1">BWL I (BBWL01-01)</option>
                                    <option value="catalog2">BWL II (BBWL02-01)</option>
                                    <option value="catalog3">Digitale Business-Modelle (DLBLODB01)</option>
                                    <option value="catalog4">Requirements Engineering (IREN01)</option>
                                    <option value="catalog5">Datenmodellierung und Datenbanksysteme (IDBS01)</option>
                                    <option value="catalog6">Qualitätssicherung im Softwareprozess (IQSS01)</option>
                                </select>
                                <div class="chart-container">
                                    <canvas id="quizSessionsChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-12">
                    <div class="p-3 bg-white shadow-sm border large-container rounded card">
                        <h4 class="text-center p-3 mb-4">Kollaborative Statistik</h4>
                        <div class="row">
                            <div class="col-md-6 col-sm-12 mb-5">
                                <div class="counter-parent">
                                    <div class="counter-container bg-white">
                                        <h3 class="text-center text-secondary">Anzahl der kollaborativen Sitzungen</h3>
                                        <div id="collaborativeSessionsCounter" class="counter">0</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-12 mb-5">
                                <h5 class="text-center mb-2 text-secondary">Erfolgsquote in Teams vs. Einzelspieler</h5>
                                <select id="catalogDropdown" class="form-select mb-3" aria-label="Fragenkatalog Auswahl">
                                    <option value="catalog1">BWL I (BBWL01-01)</option>
                                    <option value="catalog2">BWL II (BBWL02-01)</option>
                                    <option value="catalog3">Digitale Business-Modelle (DLBLODB01)</option>
                                    <option value="catalog4">Requirements Engineering (IREN01)</option>
                                    <option value="catalog5">Datenmodellierung und Datenbanksysteme (IDBS01)</option>
                                    <option value="catalog6">Qualitätssicherung im Softwareprozess (IQSS01)</option>
                                </select>
                                <div class="chart-container">
                                    <canvas id="teamVsSoloSuccessChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php require_once("component/modal/friendListModal.php"); ?>
    <?php require_once("component/quizapp-scripts.php"); ?>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="js/statistic.js"></script>
    <script src="js/friendlist.js"></script>
</body>

</html>
<!-- end document-->