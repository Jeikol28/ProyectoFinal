var config = {
    type: Phaser.AUTO,
    width: 900,
    height: 600,
    physics: {
        default: 'arcade',
        arcade: {
            gravity: { y: 300 },
            debug: false
        }
    },
    scene: {
        preload: preload,
        create: create,
        update: update
    }
};

var game = new Phaser.Game(config);

// Variables globales
var puntuacion = 0;
var puntuacionText;
var gameOver = false;
var nivelActual = 1; // Nivel inicial
var gruposRecolectados = 0; // Grupos recolectados en el nivel actual
var maxGruposPorNivel = [3, 3, 3]; // Máximo de grupos necesarios por nivel
var estrellasPorGrupo = [4, 6, 12]; // Estrellas por grupo para cada nivel
var musicaFondo;

// Referencias a objetos
var platformas;
var jugador;
var cursorKeys;
var starts;
var bombas;
var escudoActivo = false;
var duracionEscudo = 5000; // Duración del escudo en milisegundos
var indicadorEscudo;

function preload() {
    this.load.image('escenario', 'img/Prueba.png');
    this.load.image('plataforma', 'img/PlataformasJuego.png');
    this.load.image('ground', 'img/ground.png');
    this.load.image('estrella', 'img/estrellaJuego.png');
    this.load.image('enemigo', 'img/bolaJuego.png');
    this.load.image('powerUpEscudo', 'img/PoweUp.png');
    this.load.spritesheet('personaje', 'img/personaje.png', { frameWidth: 32, frameHeight: 48 });

    // Archivos de audio
    this.load.audio('musicaFondo', 'aud/sonidoFondo.mp3');
    this.load.audio('sonidoEstrella', 'aud/recolectarEstrella.mp3');
    this.load.audio('sonidoNivel', 'aud/nivel.mp3');
    this.load.audio('sonidoPeligro', 'aud/peligro.mp3');
    this.load.audio('sonidoMuerte', 'aud/gameOver.wav');
}

function create() {
    this.add.image(450, 300, 'escenario');

    musicaFondo = this.sound.add('musicaFondo', { volume: 0.3, loop: true });
    musicaFondo.play();

    // Plataformas
    platformas = this.physics.add.staticGroup();
    platformas.create(450, 650, 'ground').refreshBody();
    platformas.create(730, 350, 'plataforma');
    platformas.create(120, 250, 'plataforma');
    platformas.create(840, 150, 'plataforma');

    // Jugador
    jugador = this.physics.add.sprite(100, 450, 'personaje');
    jugador.setCollideWorldBounds(true);
    jugador.setBounce(0.2);
    this.physics.add.collider(jugador, platformas);

    // Animaciones del jugador
    this.anims.create({
        key: 'left',
        frames: this.anims.generateFrameNumbers('personaje', { start: 0, end: 3 }),
        frameRate: 10,
        repeat: -1
    });
    this.anims.create({
        key: 'static',
        frames: [{ key: 'personaje', frame: 4 }],
        frameRate: 20
    });
    this.anims.create({
        key: 'right',
        frames: this.anims.generateFrameNumbers('personaje', { start: 5, end: 8 }),
        frameRate: 10,
        repeat: -1
    });

    // Controles
    cursorKeys = this.input.keyboard.createCursorKeys();

    // Estrellas
    starts = this.physics.add.group();
    inicializarEstrellas.call(this);

    this.physics.add.collider(starts, platformas);
    this.physics.add.overlap(jugador, starts, colectarEstrellas, null, this);

    // Bombas
    bombas = this.physics.add.group();
    this.physics.add.collider(bombas, platformas);
    this.physics.add.collider(jugador, bombas, golpeBomba, null, this);

    // Power-Up Escudo
    var poder = this.physics.add.group();
    generarPowerUpEscudo.call(this, poder);

    this.physics.add.collider(poder, platformas);
    this.physics.add.overlap(jugador, poder, recogerPowerUpEscudo, null, this);

    // Texto de puntuación
    puntuacionText = this.add.text(16, 16, 'Puntuación: 0', { fontSize: '32px', fill: 'white' });

    // Indicador del escudo
    indicadorEscudo = this.add.text(16, 50, '', { fontSize: '24px', fill: 'yellow' });
}

function update() {
    if (gameOver) return;

    if (cursorKeys.left.isDown) {
        jugador.setVelocityX(-160);
        jugador.anims.play('left', true);

    } else if (cursorKeys.right.isDown) {
        jugador.setVelocityX(160);
        jugador.anims.play('right', true);
    } else {
        jugador.setVelocityX(0);
        jugador.anims.play('static');
    }

    if (cursorKeys.up.isDown && jugador.body.touching.down) {
        jugador.setVelocityY(-370);
    }
}

// Inicializar un nuevo grupo de estrellas
function inicializarEstrellas() {
    starts.clear(true, true);
    let posicionesTotales = [
        { x: 50, y: 0 }, { x: 150, y: 0 }, { x: 250, y: 0 },
        { x: 350, y: 0 }, { x: 450, y: 0 }, { x: 550, y: 0 },
        { x: 730, y: 250 }, { x: 830, y: 250 }, { x: 840, y: 50 }
    ];

    Phaser.Utils.Array.Shuffle(posicionesTotales);
    let totalEstrellas = estrellasPorGrupo[nivelActual - 1];
    let posicionesSeleccionadas = posicionesTotales.slice(0, totalEstrellas);

    posicionesSeleccionadas.forEach(pos => {
        let estrella = starts.create(pos.x, pos.y, 'estrella');
        estrella.setBounceY(Phaser.Math.FloatBetween(0.4, 0.8));
    });
}

// Generar el power-up escudo
function generarPowerUpEscudo(poder) {
    let x = Phaser.Math.Between(50, 850);
    let powerUp = poder.create(x, 16, 'powerUpEscudo');
    powerUp.setBounce(1);
    powerUp.setCollideWorldBounds(true);
    powerUp.setVelocity(Phaser.Math.Between(-100, 100), 20);
}

// Recoger el power-up escudo
function recogerPowerUpEscudo(jugador, powerUp) {
    powerUp.disableBody(true, true);
    activarEscudo.call(this);
}

// Activar el escudo
function activarEscudo() {
    escudoActivo = true;
    indicadorEscudo.setText('¡Escudo activo!');
    setTimeout(() => {
        escudoActivo = false;
        indicadorEscudo.setText('');
    }, duracionEscudo);
}

// Recolectar estrellas
function colectarEstrellas(jugador, estrella) {
    estrella.disableBody(true, true);
    this.sound.play('sonidoEstrella', { volume: 0.7 });
    puntuacion += 10;
    puntuacionText.setText('Puntuación: ' + puntuacion);

    if (starts.countActive(true) === 0) {
        gruposRecolectados++;
        agregarEnemigosPorNivel.call(this);

        if (gruposRecolectados < maxGruposPorNivel[nivelActual - 1]) {
            inicializarEstrellas.call(this);
        } else {
            if (nivelActual < estrellasPorGrupo.length) {
                nivelActual++;
                gruposRecolectados = 0;
                bombas.clear(true, true);
                inicializarEstrellas.call(this);
                reiniciarJugador();
                mostrarTransicion.call(this, nivelActual);
            } else {
                this.add.text(300, 300, '¡Juego completado!', { fontSize: '48px', fill: 'yellow' });
                gameOver = true;
            }
        }
    }
}

// Incrementar enemigos según nivel actual
function agregarEnemigosPorNivel() {
    this.sound.play('sonidoPeligro', { volume: 0.7 });
    let enemigosPorGrupo = nivelActual;
    for (let i = 0; i < enemigosPorGrupo; i++) {
        let x = Phaser.Math.Between(50, 850);
        let enemigo = bombas.create(x, 16, 'enemigo');
        enemigo.setBounce(1);
        enemigo.setCollideWorldBounds(true);
        enemigo.setVelocity(Phaser.Math.Between(-200, 200), 20);
    }
}

// Reiniciar posición del jugador
function reiniciarJugador() {
    jugador.setPosition(100, 450);
}

// Golpe de bomba
function golpeBomba(jugador, bomba) {
    if (escudoActivo) {
        bomba.disableBody(true, true);
    } else {
        this.sound.play('sonidoMuerte', { volume: 0.7 });
        this.physics.pause();
        jugador.setTint(0xff0000);
        jugador.anims.play('static');
        gameOver = true;
    }
}

// Mostrar transición de nivel
function mostrarTransicion(nivel) {
    this.sound.play('sonidoNivel', { volume: 0.7 });
    let mensaje = this.add.text(250, 300, '¡Nivel ' + nivel + '!', { fontSize: '48px', fill: 'yellow' });
    setTimeout(() => mensaje.destroy(), 2000);
}

//Pantalla horizontal
// Agrega un evento que se ejecuta cuando la ventana se redimensiona
window.addEventListener('resize', function() {
    var canvas = document.querySelector('canvas');
    // Establece el ancho del canvas al 100% del contenedor padre
    canvas.style.width = '100%'; 
    // Ajusta la altura del canvas a 'auto' para mantener la relación de aspecto original
    canvas.style.height = 'auto'; 
});
