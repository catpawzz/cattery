<?php
function generateMeowText() {
    $meows = ['Meow', 'Purr', 'Mrow', 'Mrrp', 'Prrr', 'Miau', 'Nyaa', 'Mew'];
    $length = rand(1, 3);
    $text = '';
    
    for ($i = 0; $i < $length; $i++) {
        $text .= $meows[array_rand($meows)] . ' ';
    }
    
    return trim($text);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
    <meta name="theme-color" content="#1e1e2e">
    <title>404 Not Found - Cattery</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .error-container {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            justify-content: center;
            min-height: 60vh;
            padding: 2rem 1rem;
            max-width: 800px;
            margin: 0 auto;
        }
        
        .error-code {
            font-size: clamp(4rem, 15vw, 8rem);
            color: var(--ctp-mocha-mauve);
            margin: 0;
            line-height: 0.9;
            text-shadow: 0 0 15px rgba(203, 166, 247, 0.4);
            background: linear-gradient(to right, var(--ctp-mocha-mauve), var(--ctp-mocha-blue));
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }
        
        .error-message {
            font-size: clamp(1.1rem, 4vw, 1.5rem);
            color: var(--ctp-mocha-text);
            margin: 1rem 0;
        }
        
        .meow-message {
            font-size: clamp(1.2rem, 4vw, 2rem);
            color: var(--ctp-mocha-peach);
            font-style: italic;
            margin: 1.5rem 0;
            background: rgba(250, 179, 135, 0.1);
            padding: 1rem;
            border-radius: 15px;
            border: 1px solid rgba(250, 179, 135, 0.2);
            backdrop-filter: blur(15px);
            align-self: flex-start;
            max-width: 90%;
            transform: rotate(-1deg);
            animation: floatAnimation 3s ease-in-out infinite;
        }
        
        .return-button {
            padding: 0.8rem 2rem;
            background: rgba(180, 190, 254, 0.1);
            color: var(--ctp-mocha-blue);
            border-radius: 10px;
            cursor: pointer;
            border: 1px solid rgba(180, 190, 254, 0.2);
            transition: all 0.2s ease;
            text-decoration: none;
            font-size: clamp(1rem, 3vw, 1.1rem);
            margin-top: 2rem;
            display: inline-block;
            font-weight: 600;
            letter-spacing: 0.02em;
            backdrop-filter: blur(10px);
        }
        
        .return-button:hover {
            background: rgba(180, 190, 254, 0.2);
            border-color: rgba(180, 190, 254, 0.3);
            transform: translateY(-2px);
        }
        
        .cat-trail {
            position: absolute;
            bottom: 20px;
            left: 0;
            width: 100%;
            height: 30px;
            display: flex;
            opacity: 0.3;
        }
        
        .paw-print {
            width: 20px;
            height: 20px;
            margin: 0 15px;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 512 512' fill='%23cba6f7'%3E%3Cpath d='M226.5 92.9c14.9 0 27-12.1 27-27s-12.1-27-27-27-27 12.1-27 27 12.1 27 27 27zm-47.4 30.4c0-11.2-9.1-20.3-20.3-20.3s-20.3 9.1-20.3 20.3 9.1 20.3 20.3 20.3 20.3-9.1 20.3-20.3zm-65.1 10.7c11.2 0 20.3-9.1 20.3-20.3s-9.1-20.3-20.3-20.3-20.3 9.1-20.3 20.3 9.1 20.3 20.3 20.3zm180.8 51.7c-11.2 0-20.3 9.1-20.3 20.3s9.1 20.3 20.3 20.3 20.3-9.1 20.3-20.3-9.1-20.3-20.3-20.3zm17 84.5c11.2 0 20.3-9.1 20.3-20.3s-9.1-20.3-20.3-20.3-20.3 9.1-20.3 20.3 9.1 20.3 20.3 20.3zm-60.1-20.3c0-11.2-9.1-20.3-20.3-20.3s-20.3 9.1-20.3 20.3 9.1 20.3 20.3 20.3 20.3-9.1 20.3-20.3zm-122.7 0c0-11.2-9.1-20.3-20.3-20.3s-20.3 9.1-20.3 20.3 9.1 20.3 20.3 20.3 20.3-9.1 20.3-20.3zm122.7 0c0-11.2-9.1-20.3-20.3-20.3s-20.3 9.1-20.3 20.3 9.1 20.3 20.3 20.3 20.3-9.1 20.3-20.3z'/%3E%3C/svg%3E");
            background-size: contain;
            background-repeat: no-repeat;
            transform: rotate(var(--rotate));
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <h1>Cattery</h1>
            <p>A futuristic gallery featuring Yumi the cat</p>
        </header>
        
        <div class="error-container">
            <h2 class="error-code">404</h2>
            <p class="error-message">This page is hiding under the couch.</p>
            
            <div class="meow-message">
                "<?php echo generateMeowText(); ?>!"
            </div>
            
            <p>The page you're looking for might be playing with a toy mouse somewhere else.</p>
            
            <a href="/" class="return-button">Return to Gallery</a>
        </div>
        
        <div class="cat-trail" id="catTrail">
            <!-- Paw prints will be added via JS -->
        </div>
    </div>
    
    <script>
        function randomizeMessage() {
            const meowMessages = [
                "This page is hiding under the couch",
                "I think I knocked that page off the shelf",
                "That page is playing with a toy mouse somewhere else",
                "Maybe check behind the curtains?",
                "That URL is like a laser pointer - nothing's really there"
            ];
            
            const messageDiv = document.querySelector('.error-message');
            const randomIndex = Math.floor(Math.random() * meowMessages.length);
            messageDiv.textContent = meowMessages[randomIndex];
        }
        
        function createPawTrail() {
            const trail = document.getElementById('catTrail');
            const pawCount = Math.floor(window.innerWidth / 50);
            
            for (let i = 0; i < pawCount; i++) {
                const paw = document.createElement('div');
                paw.className = 'paw-print';
                paw.style.setProperty('--rotate', `${Math.random() * 360}deg`);
                trail.appendChild(paw);
            }
        }
        
        window.addEventListener('load', () => {
            randomizeMessage();
            createPawTrail();
        });
    </script>
</body>
</html>
