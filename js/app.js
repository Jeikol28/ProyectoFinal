
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

var puntuacion = 0;
var puntuacionText;
var gameOver = false;

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
    bombas = this.physics.add.group();
   this.physics.add.collider(starts, platformas)

   this.physics.add.collider(jugador, bombas, golpeBomba, null, this);
  
   

 

   bombas.children.iterate(function(child){
    child.setBounceY(Phaser.Math.FloatBetween(0.4, 0.8));

   });

   this.physics.add.collider(bombas, platformas);

    //poder
    poder = this.physics.add.image(730, 450, 'poder');
    this.physics.add.collider(poder, platformas);
   
    this.physics.add.overlap(jugador,starts,colectarEstrellas, null, true);

    puntuacionText = this.add.text(16, 16, 'Puntuacion: 0 ',{fontSize: '32px', fill: 'white'});
}

function update() {
    if(gameOver) {
        return
    }

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

function colectarEstrellas(jugador, estrella){
    estrella.disableBody(true,true);
    puntuacion += 10;
    puntuacionText.setText('Puntuacion: ' + puntuacion);
    if(starts.countActive(true) === 0){
        starts.children.iterate(function(child){
        child.enableBody(true, child.x, child.y, true, true);
    });
    var x = (jugador.x ) ? Phaser.Math.Between(400, 800) : Phaser.Math.Between(0, 400);

    var bomba = bombas.create(x, 16, 'enemigo');
    bomba.setBounce(1);
    bomba.setCollideWorldBounds(true);
    bomba.setVelocity(Phaser.Math.Between(-200, 200), 20);
    }

   
}

function golpeBomba(jugador, bomba){
    this.physics.pause();
    jugador.setTint(0xff0000);
    jugador.anims.play('turn');
    gameOver = true;
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
