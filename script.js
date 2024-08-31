let stationCount = 2; // Inicialmente hay 2 estaciones

function adjustTime(elementId, seconds) {
    let timeElement = document.getElementById(elementId);
    let currentTime = timeElement.textContent.split(':').map(Number);
    let totalSeconds = currentTime[0] * 3600 + currentTime[1] * 60 + currentTime[2] + seconds;

    if (totalSeconds < 0) totalSeconds = 0;

    let hours = Math.floor(totalSeconds / 3600).toString().padStart(2, '0');
    let minutes = Math.floor((totalSeconds % 3600) / 60).toString().padStart(2, '0');
    let sec = (totalSeconds % 60).toString().padStart(2, '0');

    timeElement.textContent = `${hours}:${minutes}:${sec}`;

    // Eliminar el atributo 'data-notified' si se agrega tiempo (es decir, si 'seconds' es positivo)
    if (seconds > 0) {
        timeElement.removeAttribute('data-notified');
    }

    // Inicia el temporizador si es necesario
    if (seconds > 0 && !timeElement.hasAttribute('data-timer-id')) {
        startTimer(elementId);
    }

    // Si el tiempo llega a cero y la notificación no se ha mostrado, mostrar la notificación
    if (totalSeconds === 0 && seconds < 0 && !timeElement.hasAttribute('data-notified')) {
        showNotification(elementId);
        timeElement.setAttribute('data-notified', 'true'); // Marca como notificado
    }
}

function startTimer(elementId) {
    let timeElement = document.getElementById(elementId);
    // Detiene el temporizador si ya está en marcha
    if (timeElement.hasAttribute('data-timer-id')) {
        clearInterval(parseInt(timeElement.getAttribute('data-timer-id')));
    }
    let timer = setInterval(() => {
        adjustTime(elementId, -1);
    }, 1000);

    timeElement.setAttribute('data-timer-id', timer);
}

function stopTimer(elementId) {
    let timeElement = document.getElementById(elementId);
    if (timeElement.hasAttribute('data-timer-id')) {
        clearInterval(parseInt(timeElement.getAttribute('data-timer-id')));
        timeElement.removeAttribute('data-timer-id');
    }
}

function showNotification(elementId) {
    let stationId = elementId.split('-')[1];
    alert(`El tiempo en la Estación ${stationId} ha llegado a cero.`);
}

function removeStation(stationId) {
    let station = document.getElementById(stationId);
    let timeElement = station.querySelector('p');

    if (timeElement.hasAttribute('data-timer-id')) {
        clearInterval(parseInt(timeElement.getAttribute('data-timer-id')));
    }

    station.remove();

    // Actualiza el conteo de estaciones
    stationCount--;
}

function addStation() {
    stationCount++;
    const stationContainer = document.getElementById('stations-container');
    const newStation = document.createElement('div');
    newStation.className = 'station';
    newStation.id = `station-${stationCount}`;

    newStation.innerHTML = `
        <h2>Estación ${stationCount}</h2>
        <p id="time-${stationCount}">00:00:00</p>
        <button class="add-time add-time-10" onclick="adjustTime('time-${stationCount}', 600)">+10 Minutos</button>
        <button class="add-time add-time-30" onclick="adjustTime('time-${stationCount}', 1800)">+30 Minutos</button>
        <button class="subtract-time" onclick="adjustTime('time-${stationCount}', -60)">-1 Minuto</button>
        <button class="set-time" onclick="setTime('time-${stationCount}')">
            <img src="TecladoImg.png" alt="Establecer Tiempo" />
        </button>
        <button class="remove-station" onclick="removeStation('station-${stationCount}')">Eliminar Estación</button>
    `;

    stationContainer.appendChild(newStation);
}

function setTime(elementId) {
    let input = prompt("Introduce el tiempo en formato HH:MM:SS (ejemplo: 01:30:00):");
    if (input) {
        let [hours, minutes, seconds] = input.split(':').map(Number);
        if (!isNaN(hours) && !isNaN(minutes) && !isNaN(seconds)) {
            let totalSeconds = hours * 3600 + minutes * 60 + seconds;
            adjustTime(elementId, totalSeconds - getTotalSeconds(document.getElementById(elementId).textContent));
        } else {
            alert("Formato de tiempo inválido. Usa HH:MM:SS.");
        }
    }
}

function getTotalSeconds(timeString) {
    let [hours, minutes, seconds] = timeString.split(':').map(Number);
    return hours * 3600 + minutes * 60 + seconds;
}