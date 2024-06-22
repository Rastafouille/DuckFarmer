<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>Catch the Ducks Game</title>
    <style>
        @font-face {
            font-family: 'Minecraft';
            src: url('assets/Minecraft.ttf') format('truetype');
        }
        body {
            margin: 0;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            height: 100vh;
            font-family: 'Minecraft', Arial, sans-serif;
        }
        canvas {
            display: block;
            flex-grow: 1;
        }
        .button-bar {
            display: flex;
            justify-content: space-around;
            align-items: center;
            background-color: #17212B;
            padding: 10px 0;
            position: fixed;
            bottom: 0;
            width: 100%;
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
        }
        .button-bar button, .button-bar a {
            padding: 10px 20px;
            font-size: 24px;
            color: white;
            text-decoration: none;
            background-color: #4CAF50;
            border: none;
            border-radius: 12px;
            cursor: pointer;
            transition: background-color 0.3s;
            font-family: 'Minecraft', Arial, sans-serif;
        }
        .button-bar button:hover, .button-bar a:hover {
            background-color: #45a049;
        }
        .quest-button, .boost-button {
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .quest-button img, .boost-button img {
            margin-right: 10px;
        }
        .score-board {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            background-color:  #17212B;
            padding: 10px;
            border-radius: 12px;
            position: fixed;
            top: 10px;
            left: 50%;
            transform: translateX(-50%);
            color: white;
            font-family: 'Minecraft', Arial, sans-serif;
            font-size: 25px;
            text-align: center;
        }
        .score-board .score-line {
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .score-board .score-line img {
            margin-left: 10px;
        }
        .score-icon {
            position: absolute;
            top: 20px; /* Ajustez cette valeur selon votre préférence */
            width: 50px; /* Ajustez la taille de l'icône selon votre préférence */
            height: 50px;
        }

        #fat-icon {
            left: 10px; /* Ajustez cette valeur selon votre préférence */
        }
        
        #speed-icon {
            right: 10px; /* Ajustez cette valeur selon votre préférence */
        }
        
        .combo-board {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            background-color:  #17212B;
            padding: 5px;
            border-radius: 12px;
            position: fixed;
            top: 110px;
            left: 50%;
            transform: translateX(-50%);
            color: #FF5733;
            font-family: 'Minecraft', Arial, sans-serif;
            font-size: 15px;
            text-align: center;
        
        }
        .combo-progress {
            height: 20px;
            width: 0%;
            background-color: #45a049; /* Couleur de progression */
            border-radius: 12px;
            margin-top: 0px;
            position: absolute;
            top: 3px; /* Ajustez la position verticale */
            left: 5px;
            max-width: calc(100% - 10px); /* Empêche la barre de dépasser le cadre parent */
        }
        
        .combo-score {
            margin-bottom: 0px;
            position: relative; /* Définir comme position relative pour les éléments positionnés absolument */
            z-index: 1; /* Assurez-vous que le texte est au-dessus de la barre de progression */
        }
        .bonus-text {
            position: absolute;
            top: 40px;
            left: 50%;
            
            font-size: 70px;
            color: #FF5733;
            opacity: 0;
            animation: fadeMove 2s ease-in-out;
        }
        .point-text {
            position: absolute;
            font-size: 30px;
            color: #FF5733;
            opacity: 0;
            animation: fadeMove 0.5s ease-in-out;
            pointer-events: none;
        }
        @keyframes fadeMove {
            0% {
                opacity: 1;
                transform: translateY(0);
            }
            100% {
                opacity: 0;
                transform: translateY(-20px);
            }
        }
        
       #gif {
            width: 300px; /* Ajustez la taille de l'icône selon votre préférence */
            height: 300px;
            position: absolute;
            top: 0px; /* Ajustez la position verticale selon votre préférence */
            left: 50%;
           // transform: translateX(-50%);
            z-index: 1; /* Assurez-vous que le gif est au-dessus du bouton de score */
        }
        
        .hidden {
            display: none;
        }
        
        .user-info {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }
        .user-info img {
            margin-left: 5px;
        }
       
        
    </style>
</head>
<body>

    <img src="assets/nofat.png" alt="Fat Icon" class="score-icon" id="fat-icon">
    <img src="assets/nospeed.png" alt="Speed Icon" class="score-icon" id="speed-icon">
    <div id="confetti-container">
    <img id="gif" src="assets/confetti_2.gif" class="gif Icon" style="pointer-events: none; position: absolute; top: -50px; left: 50%; transform: translateX(-50%);display: none;" loop="false">
    
 <div class="score-board">
        <div class="user-info">
            <span id="username">Guest</span>
            <img src="assets/Animate_Duck.gif" alt="Duck Icon" width="35" height="35">
        </div>
        <div class="score-line">
            Score&nbsp;<span id="score">0</span>
            <img src="assets/coin_petit.png" alt="Coin Icon" width="20" height="20">
        </div>
    </div>

    <canvas id="gameCanvas"></canvas>
    <div class="button-bar">
        <button class="quest-button" onclick="window.location.href='quest.html'">
            <img src="assets/rocket.png" alt="Quest Icon" width="36" height="36"> Quest
        </button>
        <button class="boost-button" onclick="window.location.href='boost.html'">
            <img src="assets/dollar.png" alt="Boost Icon" width="36" height="36"> Boost 
        </button>
    </div>

    <div id="point-texts-container"></div> <!-- Container for point texts -->

    
    
<div class="combo-board">
    <div class="combo-score">Combo Bonus</div>
    <div class="combo-progress"></div>
    <div class="bonus-text hidden">+100</div> <!-- Ajoutez l'élément pour le texte du bonus -->
</div>


    <script src="https://telegram.org/js/telegram-web-app.js"></script> 

   <!-- <script src="//cdn.jsdelivr.net/npm/eruda"></script>
    <script>eruda.init()</script> -->

    <script>
        const canvas = document.getElementById('gameCanvas');
        const ctx = canvas.getContext('2d');

        function resizeCanvas() {
            canvas.width = window.innerWidth;
            canvas.height = window.innerHeight // - document.querySelector('.button-bar').offsetHeight;
        }

        window.addEventListener('resize', resizeCanvas);
        resizeCanvas();
        let ducks = [];

        
        let telegramID = 123;
        let telegramUser ="Guest"
        let telegramFirst ="Guest"
        let score = 0;
        let speedDate = new Date('2024-01-01T12:00:00');
        let speedDate24h = new Date('2024-01-01T12:00:00');
        let speedBoost = 1;
        let speedBoostMax = 2.5;
        let fatDate = new Date('2024-01-01T12:00:00');
        let fatDate24h = new Date('2024-01-01T12:00:00');
        let fatBoost=1;
        let fatBoostMax = 3;
        let tg = 0;
        let twitter = 0;
        let site = 0;
        let parrainUsername = '';
        let inviteCount=0;
        let invite_count_n2=0;
        let invite_count_n3=0;
        let spare3=0;
        let newparrainUsername='';

        

        let FarmerWidth = 100 ;
        let FarmerHeight = 100;
        let FarmerX = (canvas.width - FarmerWidth) / 2;
        let FarmerY = canvas.height - FarmerHeight - document.querySelector('.button-bar').offsetHeight;
        const duckWidth = 50;
        const duckHeight = 50;

        const FarmerImg = new Image();
        FarmerImg.src = 'assets/farmer.png';
        var duckImg = new Image();
        duckImg.src = 'assets/Animate_Duck.gif';
        const bgImg = new Image();
        bgImg.src = 'assets/background2.png';
        const confettiGif = document.getElementById('gif');
        confettiGif.style.display = 'none'; // Afficher le gif de confettis
        
        
        
        let gameRunning = true; // Variable pour vérifier si le jeu est en cours
        let animationFrameId; // ID de l'animation frame pour pouvoir l'annuler
        
        let combo = 0;
        const comboThreshold = 100; // Combo atteint quand combo = 100
        const comboBonus = 100; // Bonus de score pour le combo
        
        // Variable pour stocker l'identifiant de l'intervalle
        let createDuckInterval;
        let setDataInterval;

        // Gérer la visibilité de la page
        document.addEventListener('visibilitychange', function() {
            if (document.hidden) {
                gameRunning = false;
            } else {
                gameRunning = true;
            }
        });
        
       function createDuck() {
        if (gameRunning) {
            const duckX = Math.random() * (canvas.width - duckWidth);
            ducks.push({ x: duckX, y: 0 });
        }
    }

        function update() {
            
            //FarmerWidth = 100 //* fatBoost;
            //FarmerHeight = 100//50+23* fatBoost;

            ducks.forEach((duck, index) => {
            duck.y += 5; // Modifier cette ligne si nécessaire
            if (duck.y > (FarmerY + FarmerHeight)) {
                ducks.splice(index, 1);
                resetCombo(); // Réinitialiser le combo si un canard est manqué
            }
            if (
                duck.x + duckWidth/2 < FarmerX + FarmerWidth &&
                duck.x + duckWidth/2 > FarmerX &&
                duck.y < FarmerY + FarmerHeight &&
                duck.y + duckHeight > FarmerY
            ) {
                ducks.splice(index, 1);
                showPointText(duck.x + duckWidth / 2, duck.y + duckHeight / 2)
               // navigator.vibrate([200, 100, 200]);
                
                score += 1;
                combo++;
                if (combo >= comboThreshold) {
                    score += comboBonus;
                    showBonusText()
                    Confetti();
                    resetCombo();
                }
                updateComboBar();
                document.getElementById('score').textContent = score;
                setData();
                
                nowDate = new Date();
                if ( (nowDate.getTime() > (speedDate.getTime() + 1 * 60 * 60 * 1000)) && (nowDate.getTime() > (speedDate24h.getTime() + 24 * 60 * 60 * 1000)) ) {
                    speedBoost = 1;
                    document.getElementById('speed-icon').src = 'assets/nospeed.png';
                    clearInterval(createDuckInterval);
                    createDuckInterval = setInterval(createDuck, parseInt(500 / speedBoost, 10));
                }
                if ( (nowDate.getTime() > (fatDate.getTime() + 1 * 60 * 60 * 1000)) && (nowDate.getTime() > (fatDate24h.getTime() + 24 * 60 * 60 * 1000)) ) {
                    fatBoost = 1;
                    document.getElementById('fat-icon').src = 'assets/nofat.png';
                    FarmerImg.src = 'assets/farmer.png';
                    FarmerWidth = 100;
                    FarmerHeight = 100;
                    FarmerY = canvas.height - FarmerHeight  - document.querySelector('.button-bar').offsetHeight;
                }
            }
        });

        }

        function draw() {
            ctx.drawImage(bgImg, 0, 0, canvas.width, canvas.height);
            ducks.forEach(duck => {
                ctx.drawImage(duckImg, duck.x, duck.y, duckWidth, duckHeight);
            });
            ctx.drawImage(FarmerImg, FarmerX, FarmerY, FarmerWidth, FarmerHeight);
            //requestAnimationFrame(draw); // Dessiner en boucle pour une animation fluide
        }

        function gameLoop() {
            if (gameRunning == true) {
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            update();
            draw();}
            requestAnimationFrame(gameLoop);
        }

        canvas.addEventListener('mousemove', (event) => {
            FarmerX = event.clientX - FarmerWidth / 2;
        });

        canvas.addEventListener('touchstart', (event) => {
            event.preventDefault();
            const touch = event.touches[0];
            FarmerX = touch.clientX - FarmerWidth / 2;
        });

        canvas.addEventListener('touchmove', (event) => {
            event.preventDefault();
            const touch = event.touches[0];
            FarmerX = touch.clientX - FarmerWidth / 2;
        });
      
      function resetCombo() {
        combo = 0;
        updateComboBar();
    }

        function updateComboBar() {
            const comboProgress = (combo / comboThreshold) * 100;
            document.querySelector('.combo-progress').style.width = comboProgress + '%';
        }
        
        function showBonusText() {
            const bonusText = document.querySelector('.bonus-text');
            bonusText.classList.remove('hidden');
            bonusText.style.opacity = '1'; // Délai pour que la transition d'opacité soit prise en compte
            setTimeout(() => {  bonusText.style.opacity = '0';}, 2000); // Délai pour la disparition progressive
            setTimeout(() => { pointText.remove(); }, 3000); // Délai total pour que le texte disparaisse complètement
        }
        
  function showPointText(x, y) {
            const pointTextsContainer = document.getElementById('point-texts-container');
            const pointText = document.createElement('div');
            pointText.classList.add('point-text');
            pointText.textContent = '+1';
            pointText.style.left = x + 'px';
            pointText.style.top = y + 'px';
            pointTextsContainer.appendChild(pointText);

            pointText.style.opacity = '1'; // Delay to apply opacity transition
            setTimeout(() => { pointText.style.opacity = '0'; },100); // Delay for gradual disappearance
            setTimeout(() => { pointText.remove(); }, 1000); // Remove the element after it disappears
        }

        
        function Confetti() {
            const confettiContainer = document.getElementById('confetti-container'); // Le conteneur où se trouve le GIF
            const confettiGif = document.getElementById('gif');
        
            // Supprimer le GIF existant
            confettiContainer.removeChild(confettiGif);
        
            // Créer un nouveau GIF
            const newConfettiGif = document.createElement('img');
            newConfettiGif.src = 'assets/confetti_2.gif'; // Chemin vers votre GIF
            newConfettiGif.id = 'gif'; // Rétablir l'ID pour le nouveau GIF
            newConfettiGif.style.display = 'block'; // Assurez-vous que le GIF est visible
            newConfettiGif.style.position = 'absolute';
            newConfettiGif.style.top = '-30px';
            newConfettiGif.style.left = '50%';
            newConfettiGif.style.transform = 'translateX(-50%)';
           // style="pointer-events: none; position: absolute; top: -30px; left: 50%; transform: translateX(-50%);
        
            // Ajouter le nouveau GIF au conteneur
            confettiContainer.appendChild(newConfettiGif);
        
            // Cacher le gif de confettis après 3 secondes
            setTimeout(() => {
                newConfettiGif.style.display = 'none';
            }, 3000);
        }
   
// Fonction pour envoyer l'ID Telegram au backend via AJAX
function getData() {
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "get_data.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function() {
        if (xhr.readyState == 4 && xhr.status == 200) {
            //alert(xhr.responseText);
            var response = JSON.parse(xhr.responseText);
            if (response.status === 'success' || response.status === 'added') {
                    score = response.score;
                    speedDate = new Date(response.speed_boost);
                    speedDate24h = new Date(response.speed_boost_24h);
                    fatDate = new Date(response.fat_boost);
                    fatDate24h = new Date(response.fat_boost_24h);
                    tg=response.telegram;
                    twitter=response.twitter;
                    site=response.site;
                    parrainUsername=response.parrain_username;
                    inviteCount=response.invite_count;
                    invite_count_n2=response.invite_count_n2;
                    invite_count_n3=response.invite_count_n3;
                    spare3=response.spare3;
                    
                    
                    document.getElementById('score').textContent = score;
                    document.getElementById('username').textContent = telegramFirst;
                     nowDate = new Date();
                    if ((nowDate.getTime() < (speedDate.getTime() + 1 * 60 * 60 * 1000)) || (nowDate.getTime() < (speedDate24h.getTime() + 24 * 60 * 60 * 1000)) ){
                        speedBoost=speedBoostMax;
                        document.getElementById('speed-icon').src = 'assets/speed.png';
                    }
                    if ((nowDate.getTime() < (fatDate.getTime() + 1 * 60 * 60 * 1000)) || (nowDate.getTime() < (fatDate24h.getTime() + 24 * 60 * 60 * 1000)) ) {
                        fatBoost=fatBoostMax;
                        document.getElementById('fat-icon').src = 'assets/fat.png';
                        FarmerWidth = 300 //* fatBoost;
                        FarmerHeight = 250 //* fatBoost;
                        FarmerY = canvas.height - FarmerHeight - document.querySelector('.button-bar').offsetHeight;
                        FarmerImg.src = 'assets/fatfarmer.png';

                    }
                    createDuckInterval=setInterval(createDuck,parseInt(500/speedBoost,10));
                    //alert ('getdata'+score +' '+speedDate+' '+speedDate24h+' '+fatDate+' '+fatDate24h+' '+tg+' '+twitter+' '+site);
            } else {
                 console.log("Erreur: " + response.message);
                //callback(0, 1, 1, 0, 0, 0);
           }
        } else if (xhr.readyState == 4) {
            console.log("getData Erreur lors de la requête AJAX: " + xhr.status);
            //callback(0, 1, 1, 0, 0, 0);
        }
    };
    xhr.send("telegramID=" + encodeURIComponent(telegramID) +
            "&telegramUser=" + encodeURIComponent(telegramUser) +
            "&telegramFirst=" + encodeURIComponent(telegramFirst) +
            "&parrain_username=" + encodeURIComponent(newparrainUsername) );
}



        // Fonction pour mettre à jour les données dans la base de données via AJAX
        function setData() {
        
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "set_data.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = function() {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    var response = JSON.parse(xhr.responseText);
                    if (response.status === 'success') {    }
                }
            };
            xhr.send(
            "telegramID=" + encodeURIComponent(telegramID) + 
            "&telegramUser=" + encodeURIComponent(telegramUser) +
            "&telegramFirst=" + encodeURIComponent(telegramFirst) +
            "&score=" + encodeURIComponent(score) + 
            "&speedBoost=" + encodeURIComponent(speedDate.toISOString()) + 
            "&speedBoost24h=" + encodeURIComponent(speedDate24h.toISOString()) + 
            "&fatBoost=" + encodeURIComponent(fatDate.toISOString()) + 
            "&fatBoost24h=" + encodeURIComponent(fatDate24h.toISOString()) + 
            "&tg=" + encodeURIComponent(tg) + 
            "&twitter=" + encodeURIComponent(twitter) + 
            "&site=" + encodeURIComponent(site) +
            "&parrain_username=" + encodeURIComponent(parrainUsername) +
            "&invite_count=" + encodeURIComponent(inviteCount) +
            "&invite_count_n2=" + encodeURIComponent(invite_count_n2) +
            "&invite_count_n3=" + encodeURIComponent(invite_count_n3) +
            "&spare3=" + encodeURIComponent(spare3)
        );
                //alert ('setdata'+score +' '+speedBoost+' '+fatBoost+' '+tg+' '+twitter+' '+site+' '+parrainUsername+' '+inviteCount+' '+invite_count_n2+' '+invite_count_n3+' '+spare3);
        
        }
        
        
        

        document.addEventListener('DOMContentLoaded', function(){
            // Vérifier si l'API Telegram Web App est disponible
            if (typeof Telegram !== 'undefined' && Telegram.WebApp && Telegram.WebApp.initDataUnsafe) {
                var user = Telegram.WebApp.initDataUnsafe.user;
                newparrainUsername = window.Telegram.WebApp.initDataUnsafe.start_param
             
                if (user) {
                    telegramID = user.id;
                    telegramUser = user.username ? user.username : user.id;
                    telegramFirst = user.first_name;
                    //alert ('telegram User: '+telegramUser + ', telegram ID: '+telegramID + ', telegram First: '+telegramFirst + ', parrain '+ newparrainUsername);
                    getData()
                    setDataInterval=setInterval(setData, 1000);
                    }
                 else {
                    createDuckInterval=setInterval(createDuck,parseInt(1000/speedBoost,10));
                }
            } else {
                document.getElementById("username").textContent = "API Telegram Web App non disponible.";
            }
        })
            

        if (window.Telegram.WebApp) {
            Telegram.WebApp.ready();
            Telegram.WebApp.expand(); // Request maximum available height
        }
        duckImg.onload = function() {
            // Démarrez le jeu une fois que le GIF du canard est chargé
            gameLoop();
        };
    </script>

</body>
</html>