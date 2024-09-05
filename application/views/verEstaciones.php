<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ver Estaciones</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" crossorigin="anonymous">
    <style>
        body {
            background-color: #000;
            color: #fff;
        }

        .card {
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
            background-color: #1e1e1e;
            position: relative;
            padding: 20px;
            margin: 20px 0;
        }

        .card:hover {
            transform: translateY(-10px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        }

        .card-body {
            text-align: center;
        }

        .card-title,
        .card-text {
            color: #e0e0e0;
        }

        .timer-display {
            font-size: 2rem;
            font-weight: bold;
            margin: 20px 0;
            padding: 10px;
            border-radius: 8px;
            background: #333;
            box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.2);
            color: #fff;
        }

        .btn {
            border-radius: 20px;
            padding: 10px 20px;
            margin: 5px;
        }

        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }

        .btn-secondary {
            background-color: #6c757d;
            border-color: #6c757d;
        }

        .btn-primary:disabled {
            background-color: #007bff;
            opacity: 0.5;
            cursor: not-allowed;
        }

        .btn-secondary:disabled {
            background-color: #6c757d;
            opacity: 0.5;
            cursor: not-allowed;
        }

        .container {
            max-width: 1200px;
        }

        .inactiva {
            opacity: 0.6;
            pointer-events: none;
        }

        .estado-activa {
            color: #28a745;
        }

        .estado-inactiva {
            color: #dc3545;
        }

        .mode-toggle {
            position: absolute;
            top: 10px;
            left: 10px;
        }

        .mode-toggle .dropdown-menu {
            min-width: 150px;
        }

        .btn-start-time {
            margin-top: 20px;
        }

        .btn-pause-time {
            display: none;
        }

        .dropdown-toggle::after {
            display: none;
        }

        .btn-small {
            padding: 5px 10px;
            font-size: 0.8rem;
        }
    </style>
</head>
<body>
<div class="container mt-5">
    <h2 class="text-center mb-4">Estaciones</h2>
    <div class="row">
        <?php foreach ($equipos as $index => $equipo) : ?>
            <div class="col-md-4 mb-4 <?php echo $equipo->estado == 0 ? 'inactiva' : ''; ?>">
                <div class="card" id="card-<?php echo $index; ?>">
                    <div class="mode-toggle">
                        <div class="dropdown">
                            <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton-<?php echo $index; ?>" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-bars"></i> <!-- Ícono de hamburguesa -->
                            </button>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton-<?php echo $index; ?>">
                                <a class="dropdown-item" href="#" data-index="<?php echo $index; ?>" data-mode="timer">Temporizador</a>
                                <a class="dropdown-item" href="#" data-index="<?php echo $index; ?>" data-mode="cronometro">Cronómetro</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <h5 class="card-title"><?php echo $equipo->nombre; ?></h5>
                        <p class="card-text">Descripción: <?php echo $equipo->descripcion; ?></p>
                        <p class="card-text">Estado: <span class="<?php echo $equipo->estado == 1 ? 'estado-activa' : 'estado-inactiva'; ?>">
                                <?php echo $equipo->estado == 1 ? 'activa' : 'inactiva'; ?>
                            </span></p>
                        <!-- Botones para iniciar, pausar y configurar el tiempo -->
                        <button class="btn btn-primary start-time btn-start-time" data-index="<?php echo $index; ?>" <?php echo $equipo->estado == 0 ? 'disabled' : ''; ?>>Iniciar Tiempo</button>
                        <button class="btn btn-secondary btn-pause-time" data-index="<?php echo $index; ?>">Pausar</button>
                        <div class="btn-group mt-3">
                            <button class="btn btn-secondary set-time btn-small" data-index="<?php echo $index; ?>" data-time="900">15 Min</button>
                            <button class="btn btn-secondary set-time btn-small" data-index="<?php echo $index; ?>" data-time="1800">30 Min</button>
                            <button class="btn btn-secondary add-time btn-small" data-index="<?php echo $index; ?>" data-time="300">+5</button>
                            <button class="btn btn-secondary add-time btn-small" data-index="<?php echo $index; ?>" data-time="600">+10</button>
                        </div>
                        <!-- Contenedor para mostrar el tiempo del temporizador/cronómetro -->
                        <div class="timer-display mt-3" id="timer-display-<?php echo $index; ?>">00:00:00</div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/js/all.min.js" crossorigin="anonymous"></script>
<script>
    $(document).ready(function() {
        const timers = {};

        function initializeTimer(index) {
            timers[index] = {
                timerInterval: null,
                isRunning: false,
                isPaused: false,
                remainingTime: 0,
                mode: ''
            };
        }

        $('.dropdown-item').click(function() {
            const index = $(this).data('index');
            const newMode = $(this).data('mode');
            const card = $(`#card-${index}`);
            const startTimeButton = card.find('.start-time');
            const pauseButton = card.find('.btn-pause-time');
            const timerDisplay = $(`#timer-display-${index}`);

            if (!timers[index]) {
                initializeTimer(index);
            }

            const timer = timers[index];

            // Reiniciar tiempo al cambiar de modo
            if (timer.mode && timer.mode !== newMode) {
                if (timer.isRunning) {
                    clearInterval(timer.timerInterval);
                    timer.isRunning = false;
                    timer.isPaused = false;
                }
                timer.remainingTime = 0;
                updateDisplay(timerDisplay, timer.remainingTime);
                startTimeButton.text(newMode === 'timer' ? 'Iniciar Temporizador' : 'Iniciar Cronómetro');
                pauseButton.hide();
            }

            timer.mode = newMode;
            pauseButton.toggle(timer.isRunning);
        });

        $('.start-time').click(function() {
            const index = $(this).data('index');
            const card = $(`#card-${index}`);
            const timerDisplay = $(`#timer-display-${index}`);
            const pauseButton = card.find('.btn-pause-time');

            if (!timers[index]) {
                initializeTimer(index);
            }

            const timer = timers[index];

            if (timer.mode === 'timer') {
                if (timer.isRunning && timer.isPaused) {
                    startTimer(index, timerDisplay, $(this), pauseButton);
                } else if (!timer.isRunning) {
                    startTimer(index, timerDisplay, $(this), pauseButton);
                }
            } else if (timer.mode === 'cronometro') {
                if (timer.isRunning && timer.isPaused) {
                    startCronometro(index, timerDisplay, $(this), pauseButton);
                } else if (!timer.isRunning) {
                    startCronometro(index, timerDisplay, $(this), pauseButton);
                }
            }
        });

        $('.set-time').click(function() {
            const index = $(this).data('index');
            const timeInSeconds = $(this).data('time');
            const card = $(`#card-${index}`);
            const timerDisplay = $(`#timer-display-${index}`);
            const startTimeButton = card.find('.start-time');
            const pauseButton = card.find('.btn-pause-time');

            if (!timers[index]) {
                initializeTimer(index);
            }

            const timer = timers[index];

            if (timer.isRunning) {
                clearInterval(timer.timerInterval);
                timer.isRunning = false;
            }

            timer.remainingTime = timeInSeconds;
            updateDisplay(timerDisplay, timer.remainingTime);
            startTimeButton.text('Iniciar Temporizador');
            timer.isPaused = false;
            pauseButton.show();
        });

        $('.add-time').click(function() {
            const index = $(this).data('index');
            const timeToAdd = $(this).data('time');

            if (timers[index] && timers[index].isRunning && timers[index].mode === 'timer') {
                timers[index].remainingTime += timeToAdd;
                updateDisplay($(`#timer-display-${index}`), timers[index].remainingTime);
            }
        });

        $('.btn-pause-time').click(function() {
            const index = $(this).data('index');
            const card = $(`#card-${index}`);
            const startTimeButton = card.find('.start-time');
            const timerDisplay = $(`#timer-display-${index}`);

            if (timers[index] && timers[index].isRunning) {
                clearInterval(timers[index].timerInterval);
                timers[index].isRunning = false;
                startTimeButton.text('Reanudar');
            }
            timers[index].isPaused = true;
            $(this).hide();
        });

        function startTimer(index, timerDisplay, startTimeButton, pauseButton) {
            if (timers[index].isRunning && !timers[index].isPaused) return;

            timers[index].timerInterval = setInterval(function() {
                if (timers[index].remainingTime <= 0) {
                    clearInterval(timers[index].timerInterval);
                    timers[index].isRunning = false;
                    timerDisplay.text('00:00:00');
                    startTimeButton.text('Iniciar Temporizador');
                    pauseButton.hide();
                } else {
                    timers[index].remainingTime--;
                    updateDisplay(timerDisplay, timers[index].remainingTime);
                }
            }, 1000);

            timers[index].isRunning = true;
            timers[index].isPaused = false;
            pauseButton.show();
        }

        function startCronometro(index, timerDisplay, startTimeButton, pauseButton) {
            if (timers[index].isRunning && !timers[index].isPaused) return;

            let time = parseTime(timerDisplay.text());
            timers[index].timerInterval = setInterval(function() {
                time++;
                updateDisplay(timerDisplay, time);
            }, 1000);

            timers[index].isRunning = true;
            timers[index].isPaused = false;
            pauseButton.show();
        }

        function parseTime(timeStr) {
            const parts = timeStr.split(':');
            return parseInt(parts[0]) * 3600 + parseInt(parts[1]) * 60 + parseInt(parts[2]);
        }

        function updateDisplay(timerDisplay, time) {
            const hours = String(Math.floor(time / 3600)).padStart(2, '0');
            const minutes = String(Math.floor((time % 3600) / 60)).padStart(2, '0');
            const seconds = String(time % 60).padStart(2, '0');
            timerDisplay.text(`${hours}:${minutes}:${seconds}`);
        }
    });
</script>
</body>
</html>
