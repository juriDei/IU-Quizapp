function animateProgressBarQuestions() {
    let $progressValue = $('#progress-value-questions');
    let $progressCircle = $('#progress-circle-questions');
    let [completed, total] = $progressValue.text().split(' / ').map(Number);
    let currentProgress = 0;
    let targetValue = Math.floor((completed / total) * 100);
    let interval = setInterval(() => {
        if (currentProgress >= targetValue) {
            clearInterval(interval);
        } else {
            currentProgress++;
            $progressValue.text(`${completed} / ${total}`);
            $progressCircle.css('background', `conic-gradient(#0d6efd ${currentProgress * 3.6}deg, #e9ecef 0deg)`);
        }
    }, 20); // Geschwindigkeit der Animation
}

function animateProgressBarQuizzes() {
    let $progressValue = $('#progress-value-quizzes');
    let $progressCircle = $('#progress-circle-quizzes');
    let targetValue = parseInt($progressValue.text()); // Prozentzahl aus dem Text extrahieren
    let currentProgress = 0;
    let interval = setInterval(() => {
        if (currentProgress >= targetValue) {
            clearInterval(interval);
        } else {
            currentProgress++;
            $progressValue.text(`${currentProgress}%`);
            $progressCircle.css('background', `conic-gradient(#0d6efd ${currentProgress * 3.6}deg, #e9ecef 0deg)`);
        }
    }, 10); // Geschwindigkeit der Animation
}

function animateCompletedQuizzes() {
    let $completedQuizzes = $('#completed-quizzes');
    let targetValue = 15; // Beispielwert für absolvierte Quizspiele
    let currentProgress = 0;
    let interval = setInterval(() => {
        if (currentProgress >= targetValue) {
            clearInterval(interval);
        } else {
            currentProgress++;
            $completedQuizzes.text(currentProgress);
        }
    }, 35); // Geschwindigkeit der Animation
}

function setAverageGradeColor() {
    let $averageGrade = $('#average-grade');
    let gradeText = $averageGrade.text().split(': ')[1]; // Note aus dem Text extrahieren
    let grade = parseFloat(gradeText);

    if (grade < 3) {
        $averageGrade.removeClass().addClass('average-grade grade-green');
    } else if (grade < 4) {
        $averageGrade.removeClass().addClass('average-grade grade-yellow');
    } else if (grade <= 4) {
        $averageGrade.removeClass().addClass('average-grade grade-orange');
    } else {
        $averageGrade.removeClass().addClass('average-grade grade-red');
    }
}

function animateProgressBarRecentGames() {
    $('#recent-games-wrapper .progress-circle').each(function() {
        let $progressValue = $(this).find('.progress-value');
        let $progressCircle = $(this);
        let targetValue = parseInt($progressValue.text()); // Prozentzahl aus dem Text extrahieren
        let currentProgress = 0;
        let circleColor = targetValue > 50 ? '#28a745' : '#dc3545'; // Grün, wenn > 50%, sonst Rot

        let interval = setInterval(() => {
            if (currentProgress >= targetValue) {
                clearInterval(interval);
            } else {
                currentProgress++;
                $progressValue.text(`${currentProgress}%`);
                $progressCircle.css('background', `conic-gradient(${circleColor} ${currentProgress * 3.6}deg, #e0e0e0 0deg)`);
                $progressValue.css('color', circleColor); // Textfarbe ändern
            }
        }, 10); // Geschwindigkeit der Animation
    });
}


$(document).ready(() => {
    animateProgressBarQuestions();
    animateProgressBarQuizzes();
    animateCompletedQuizzes();
    setAverageGradeColor();
    animateProgressBarRecentGames();

    $('#toggle-friendlist').on('click', function() {
        $('#friendlist-modal').toggleClass('active');
    });

    $('#close-friendlist').on('click', function() {
        $('#friendlist-modal').removeClass('active');
    });

    $('#friend-search').on('input', function() {
        var filter = $(this).val().toLowerCase();
        $('.friendlist-modal .entry').each(function() {
            var name = $(this).find('.name').text().toLowerCase();
            var email = $(this).find('.email').text().toLowerCase();
            if (name.includes(filter) || email.includes(filter)) {
                $(this).css('display', 'flex');
            } else {
                $(this).css('display', 'none');
            }
        });
    });
});
