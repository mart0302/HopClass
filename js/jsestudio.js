const display = document.getElementById('display');
const playPauseButton = document.getElementById('play-pause');
const contenedor = document.getElementById("contenedor");

let displayInterval;
let runningTime = 0;
let isPomodoro = 1;
let marcasp = 1;
let marcasd = 1;

const playPause = () => {
    const esPausa = !playPauseButton.classList.contains('bx-pause-circle');
    if (esPausa) {
        playPauseButton.classList.replace('bx-play-circle','bx-pause-circle');
        start();
    } else {
        playPauseButton.classList.replace('bx-pause-circle', 'bx-play-circle');
        pause();
    }
}

const pause = () => {
    clearInterval(displayInterval);
}

const stopall = () => {
    playPauseButton.classList.replace('bx-pause-circle', 'bx-play-circle');
    runningTime = 0;
    isPomodoro = 1;
    marcasp = 1;
    marcasd = 1;
    contenedor.innerHTML = "";
    clearInterval(displayInterval);
    display.textContent = '00:00';
}

const stop = () => {
    playPauseButton.classList.replace('bx-pause-circle', 'bx-play-circle');
    runningTime = 0;
    clearInterval(displayInterval);
    display.textContent = '00:00';
}

const start = () => {    
    let startTime = Date.now() - runningTime;
    displayInterval = setInterval( () => {
        runningTime = Date.now() - startTime;
        display.textContent = calculateTime(runningTime);
    }, 1000)
}

const calculateTime = runningTime => {
    const total_seconds = Math.floor(runningTime / 1000);
    const total_minutes = Math.floor(total_seconds / 60);

    const display_seconds = (total_seconds % 60).toString().padStart(2, "0");
    const display_minutes = total_minutes.toString().padStart(2, "0");

    if(total_minutes == 25 && isPomodoro == 1) {
        const $li = document.createElement("div");
        $li.classList.add("text-center");
        $li.classList.add("mt-5");
        $li.innerHTML = `<div class="card border-success text-success"><div class="card-body"><h5 class="card-title">Se ha completado Pomodoro ${marcasp}</h5><p class="card-text">¡25 minutos de trabajo completados!</p></div></div>`;
        mostrarNotificacion("Pomodoro Completo", 0, "");
        contenedor.append($li);
        isPomodoro = 0;
        marcasp = marcasp + 1;
        stop();
        playPause();
    }
    else if(total_minutes == 5 && isPomodoro == 0) {
        const $li = document.createElement("div");
        $li.classList.add("text-center");
        $li.classList.add("mt-5");
        $li.innerHTML = `<div class="card border-secondary text-secondary"><div class="card-body"><h5 class="card-title">Se ha completado Descanso ${marcasd}</h5><p class="card-text">¡5 minutos completados!</p></div></div>`;
        mostrarNotificacion("Descanso Completo", 0, "");
        contenedor.append($li);
        isPomodoro = 1;
        marcasd = marcasd + 1;
        stop();
        playPause();
    }

    return `${display_minutes}:${display_seconds}`
}

function mostrarNotificacion(texto, isNoti, link) {
        if (Notification.permission === 'granted') {
            var notification = new Notification(texto);
            if(isNoti == 1) {
                notification.onclick = function() {
                window.location.href = link;
                };
            }
        }
}