
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
 this.load.image('ground','img/ground.png')
 this.load.image('estrella','img/estrellaJuego.png')
 this.load.image('enemigo','img/bolaJuego.png')
 this.load.image('personaje','img/personajeJuego.png')
}

function create() {
    this.add.image(450,300, 'escenario');
    //Plataformas
    platforms = this.physics.add.staticGroup();

    platforms.create(450, 650, 'ground').refreshBody();

    platforms.create(730, 400, 'plataforma');
    platforms.create(170, 300, 'plataforma');
    platforms.create(770, 200, 'plataforma');

    //Jugador
    player= this.physics.add.image(100, 450, 'personaje')
    
    player.setCollideWorldBounds(true);
    player.setBounce(0.2);// rebote cuando cae

    player.body.setGravityY(300);//modificar gravedad solo personaje

    this.physics.add.collider(player, platforms);//detecta la colicion

   
}

function update() {
 
}
