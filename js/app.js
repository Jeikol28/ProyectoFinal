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
    this.load.audio('sonidoPowerUp', 'aud/sonidoPowerUp.mp3');

   // this.load.json('datos', 'data/datos.json');
   //cargar el json desde el backend
   this.load.json('datos', 'http://localhost/proyectofinal/editor/api.php?id=2');
}

/*************  ✨ Codeium Command ⭐  *************/
/**
 * Función de creación del juego.
 *
 * Aquí se crea el escenario, se cargan los datos de las plataformas y se crean
 * los objetos del juego como el jugador, las estrellas, las bombas y el power-up
 * del escudo. Se establecen las colisiones entre los objetos y se crean las
 * animaciones del jugador.
 */
/******  799f2fcd-d69e-4d86-bfd0-983f4cc450df  *******/
function create() {
    this.add.image(450, 300, 'escenario');

    musicaFondo = this.sound.add('musicaFondo', { volume: 0.3, loop: true });
    musicaFondo.play();

   // Cargar datos desde JSON
   let datos = this.cache.json.get('datos');
   console.log(datos, this.datos);

   // Plataformas desde JSON
   platformas = this.physics.add.staticGroup();
   datos.plataformas.forEach(plataforma => {
       platformas.create(plataforma.x, plataforma.y, plataforma.imagen).refreshBody();
   });

    // Jugador
    let posInicial = datos.personaje;
    jugador = this.physics.add.sprite(posInicial.x, posInicial.y, 'personaje');
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

    let movimientos = this.cache.json.get('datos').personaje.movimientos;

    if (cursorKeys.left.isDown) {
        jugador.setVelocityX(movimientos.izquierda.velocidadX);
        jugador.anims.play('left', true);

    } else if (cursorKeys.right.isDown) {
        jugador.setVelocityX(movimientos.derecha.velocidadX);
        jugador.anims.play('right', true);
    } else {
        jugador.setVelocityX(0);
        jugador.anims.play('static');
    }

    if (cursorKeys.up.isDown && jugador.body.touching.down) {
        jugador.setVelocityY(movimientos.salto.velocidadY);
    }
}
// Inicializar un nuevo grupo de estrellas
function inicializarEstrellas() {
    // Limpiar las estrellas previas
    starts.clear(true, true);

    // Obtener los datos de las estrellas desde el JSON
    let datos = this.cache.json.get('datos');
    let posicionesTotales = datos.estrellas; 

    // Mezclar las posiciones aleatoriamente
    Phaser.Utils.Array.Shuffle(posicionesTotales);

    // Determinar el número de estrellas por grupo, dependiendo del nivel actual
    let totalEstrellas = estrellasPorGrupo[nivelActual - 1];

    // Seleccionar las posiciones según el total de estrellas que necesitamos
    let posicionesSeleccionadas = posicionesTotales.slice(0, totalEstrellas);

    // Crear las estrellas en las posiciones seleccionadas
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
    this.sound.play('sonidoPowerUp', { volume: 0.7 });
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
    // Reproducir sonido de peligro
    this.sound.play('sonidoPeligro', { volume: 0.7 });

    // Obtener los enemigos del JSON para el nivel actual
    let datos = this.cache.json.get('datos');
    let enemigosPorNivel = datos.enemigos.find(nivel => nivel.nivel === nivelActual);

    if (enemigosPorNivel) {
        // Crear el grupo de enemigos
        enemigosPorNivel.enemigos.forEach(enemigoData => {
            // Crear el enemigo con sus características
            let enemigo = bombas.create(enemigoData.x, enemigoData.y, 'enemigo');
            enemigo.setBounce(1);
            enemigo.setCollideWorldBounds(true);
            enemigo.setVelocity(Phaser.Math.Between(-200, 200), enemigoData.velocidadY);
        });
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
