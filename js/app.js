
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
 this.load.image('poder','img/PoweUp.png')
}

function create() {
    this.add.image(450,300, 'escenario');
    //Plataformas
    platforms = this.physics.add.staticGroup();

    platforms.create(450, 650, 'ground').refreshBody();

    platforms.create(730, 350, 'plataforma');
    platforms.create(120, 250, 'plataforma');
    platforms.create(840, 150, 'plataforma');

    //Jugador
    player= this.physics.add.image(100, 450, 'personaje')
    
    player.setCollideWorldBounds(true);
    player.setBounce(0.2);// rebote cuando cae

    player.body.setGravityY(300);//modificar gravedad solo personaje

    this.physics.add.collider(player, platforms);//detecta la colicion

   //estrellas
   starts = this.physics.add.group({
    key: 'estrella',
    repeat: 11,
    setXY:{x: 12, y: 0, stepX:80}

   });

   //da valorr de rebote
   starts.children.iterate(function(child){
    child.setBounceY(Phaser.Math.FloatBetween(0.4, 0.8));

   });

   this.physics.add.collider(starts, platforms)

   //enemigos(bombas)
   bombas = this.physics.add.group({
    key: 'enemigo',
    repeat: 1,
    setXY:{x: 520, y: 100, stepX:80}

   });

   bombas.children.iterate(function(child){
    child.setBounceY(Phaser.Math.FloatBetween(0.4, 0.8));

   });

   this.physics.add.collider(bombas, platforms);

    //poder
    poder = this.physics.add.image(730, 450, 'poder');
    this.physics.add.collider(poder, platforms);
   
}

function update() {
 
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
