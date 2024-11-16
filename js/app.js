
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
 this.load.spritesheet('personaje','img/personaje.png', {frameWidth: 32, frameHeight: 48})

 this.load.image('poder','img/PoweUp.png')
}

function create() {
    this.add.image(450,300, 'escenario');
    //Plataformas
    platformas = this.physics.add.staticGroup();
    platformas.create(450, 650, 'ground').refreshBody();
    platformas.create(730, 350, 'plataforma');
    platformas.create(120, 250, 'plataforma');
    platformas.create(840, 150, 'plataforma');

    //Jugador
    jugador = this.physics.add.sprite(100, 450, 'personaje')
    
    jugador .setCollideWorldBounds(true);
    jugador .setBounce(0.2);// rebote cuando cae

   

    this.physics.add.collider(jugador, platformas);//detecta la colicion

    this.anims.create({
        key: 'left',
        frames: this.anims.generateFrameNumbers('personaje', {start: 0, end: 3}),
        frameRate: 10,
        repeat: -1
    });

    this.anims.create({
        key: 'static',
        frames: [{key: 'personaje', frame: 4 }], 
        frameRate: 20
       
    });
    
    this.anims.create({
        key: 'right',
        frames: this.anims.generateFrameNumbers('personaje', {start: 5, end: 8}),
        frameRate: 10,
        repeat: -1
    });
    //contro personaje
    cursorKeys = this.input.keyboard.createCursorKeys();
    
    

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

   this.physics.add.collider(starts, platformas)

   //enemigos(bombas)
   bombas = this.physics.add.group({
    key: 'enemigo',
    repeat: 1,
    setXY:{x: 520, y: 100, stepX:80}

   });

   bombas.children.iterate(function(child){
    child.setBounceY(Phaser.Math.FloatBetween(0.4, 0.8));

   });

   this.physics.add.collider(bombas, platformas);

    //poder
    poder = this.physics.add.image(730, 450, 'poder');
    this.physics.add.collider(poder, platformas);
   
}

function update() {
    if (cursorKeys.left.isDown) {
        jugador.setVelocityX(-160);
        jugador.anims.play('left', true);
    }
    else if (cursorKeys.right.isDown) {
        jugador.setVelocityX(160);
        jugador.anims.play('right', true);
    }else{
        jugador.setVelocityX(0);
        jugador.anims.play('static');
    }

    if (cursorKeys.up.isDown && jugador.body.touching.down) {
        jugador.setVelocityY(-370);
    }
    
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
