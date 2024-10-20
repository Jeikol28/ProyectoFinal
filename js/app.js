
var config = {
    type: Phaser.AUTO,
    width: 900,
    height: 600,
    physics:{
        default: 'arcade',
        arcade: {
            gravity: {y:300},
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

function preload() {
 this.load.image('escenario','img/Prueba.png');
 this.load.image('plataforma','img/PlataformasJuego.png')
 this.load.image('estrella','img/estrellaJuego.png')
 this.load.image('enemigo','img/bolaJuego.png')
 this.load.image('personaje','img/personajeJuego.png')
}

function create() {
    this.add.image(450,300, 'escenario');

    platforms = this.physics.add.staticGroup();


    platforms.create(730, 360, 'plataforma');

    platforms.create(170, 250, 'plataforma');

    platforms.create(770, 150, 'plataforma');

    
}

function update() {
 
}
