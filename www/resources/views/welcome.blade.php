<!DOCTYPE html>
<html lang="nl" class="overflow-hidden">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, viewport-fit=cover">
    <title>Pants Quest - Viktor Aankleden!</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=comic-neue:400,700&display=swap" rel="stylesheet" />
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        * {
            font-family: 'Comic Neue', cursive;
            box-sizing: border-box;
        }

        html, body {
            height: 100dvh;
            max-height: 100dvh;
            overflow: hidden;
            margin: 0;
            padding: 0;
            touch-action: manipulation;
            overscroll-behavior: none;
        }

        /* Landscape mobile scaling */
        :root {
            --floor-height: 50px;
            --viktor-scale: 0.65;
            --chair-scale: 0.6;
        }

        /* Taller screens can have bigger elements */
        @media (min-height: 400px) {
            :root {
                --floor-height: 60px;
                --viktor-scale: 0.75;
                --chair-scale: 0.7;
            }
        }

        @media (min-height: 500px) {
            :root {
                --floor-height: 70px;
                --viktor-scale: 0.85;
                --chair-scale: 0.8;
            }
        }

        .game-container {
            height: 100dvh;
            max-height: 100dvh;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        .game-area {
            background: linear-gradient(180deg, #87CEEB 0%, #98D8C8 100%);
            position: relative;
            overflow: hidden;
            flex: 1;
            touch-action: manipulation;
        }

        .floor {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: var(--floor-height);
            background: linear-gradient(180deg, #8B4513 0%, #654321 100%);
        }

        .floor::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 10px;
            background: linear-gradient(180deg, #DEB887 0%, #8B4513 100%);
        }

        .rocking-chair {
            position: absolute;
            bottom: var(--floor-height);
            width: calc(120px * var(--chair-scale));
            height: calc(100px * var(--chair-scale));
        }

        .viktor {
            position: absolute;
            cursor: pointer;
            user-select: none;
            -webkit-tap-highlight-color: transparent;
            transform-origin: bottom center;
        }

        .viktor:active {
            filter: brightness(0.9);
        }

        @keyframes stomp {
            0%, 100% { transform: translateY(0) rotate(-2deg); }
            50% { transform: translateY(-5px) rotate(2deg); }
        }

        .catch-effect {
            animation: catch-pop 0.3s ease-out forwards;
        }

        @keyframes catch-pop {
            0% { transform: scale(1); opacity: 1; }
            50% { transform: scale(1.3); }
            100% { transform: scale(1); opacity: 1; }
        }

        .hearts {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            pointer-events: none;
            overflow: hidden;
        }

        .heart {
            position: absolute;
            font-size: 1.5rem;
            animation: float-up 4s ease-in infinite;
        }

        @keyframes float-up {
            0% { transform: translateY(100vh) rotate(0deg); opacity: 1; }
            100% { transform: translateY(-100px) rotate(360deg); opacity: 0; }
        }

        .clothing-item {
            animation: bounce-in 0.5s ease-out;
        }

        @keyframes bounce-in {
            0% { transform: scale(0) rotate(-180deg); }
            60% { transform: scale(1.2) rotate(10deg); }
            100% { transform: scale(1) rotate(0deg); }
        }

        .message-card {
            animation: fade-in-up 0.5s ease-out;
        }

        @keyframes fade-in-up {
            0% { opacity: 0; transform: translateY(20px); }
            100% { opacity: 1; transform: translateY(0); }
        }

        /* Compact header for landscape */
        .game-header {
            background: rgba(255,255,255,0.9);
            padding: 6px 12px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 8px;
            font-size: 14px;
            flex-shrink: 0;
        }

        .game-header .progress-bar {
            flex: 1;
            max-width: 200px;
            height: 8px;
            background: #e5e7eb;
            border-radius: 4px;
            overflow: hidden;
        }

        .game-header .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, #f472b6, #ef4444);
            transition: width 0.3s;
        }
    </style>
</head>
<body class="bg-pink-100">
    <div x-data="pantsQuest()" x-init="init()" class="game-container">

        {{-- Start Screen --}}
        <div x-show="gameState === 'start'" x-cloak
             class="h-[100dvh] flex flex-col items-center justify-center p-4 bg-gradient-to-b from-pink-200 to-red-200">
            <div class="text-center">
                <h1 class="text-4xl sm:text-5xl font-bold text-red-600 mb-2">👖 Pants Quest</h1>
                <p class="text-xl sm:text-2xl text-red-500 mb-1">Viktor Aankleden!</p>
                <p class="text-sm text-red-400 mb-4">Tik op Viktor om hem aan te kleden</p>

                <div class="mb-4">
                    <svg class="w-20 h-20 sm:w-24 sm:h-24 mx-auto" viewBox="0 0 100 100">
                        <circle cx="50" cy="30" r="20" fill="#FFE4C4" stroke="#8B4513" stroke-width="2"/>
                        <circle cx="43" cy="26" r="3" fill="#4A4A4A"/>
                        <circle cx="57" cy="26" r="3" fill="#4A4A4A"/>
                        <path d="M 45 35 Q 50 40 55 35" stroke="#4A4A4A" stroke-width="2" fill="none"/>
                        <path d="M 35 15 Q 50 5 65 15" stroke="#8B4513" stroke-width="8" fill="none" stroke-linecap="round"/>
                        <rect x="40" y="48" width="20" height="25" rx="5" fill="#FFE4C4" stroke="#8B4513" stroke-width="2"/>
                        <rect x="35" y="50" width="8" height="20" rx="3" fill="#FFE4C4" stroke="#8B4513" stroke-width="2"/>
                        <rect x="57" y="50" width="8" height="20" rx="3" fill="#FFE4C4" stroke="#8B4513" stroke-width="2"/>
                        <rect x="42" y="70" width="7" height="15" rx="3" fill="#FFE4C4" stroke="#8B4513" stroke-width="2"/>
                        <rect x="51" y="70" width="7" height="15" rx="3" fill="#FFE4C4" stroke="#8B4513" stroke-width="2"/>
                    </svg>
                </div>

                <button
                    @click="startGame()"
                    class="bg-red-500 hover:bg-red-600 text-white text-xl font-bold py-3 px-10 rounded-full shadow-lg transform hover:scale-105 transition-all"
                >
                    🎮 Start!
                </button>
            </div>
        </div>

        {{-- Game Screen --}}
        <div x-show="gameState === 'playing'" x-cloak class="h-[100dvh] flex flex-col overflow-hidden">
            {{-- Compact Header --}}
            <div class="game-header">
                <div class="font-bold text-gray-700">
                    Lvl <span x-text="currentLevel"></span>/5
                </div>
                <div class="progress-bar">
                    <div class="progress-fill" :style="`width: ${(currentLevel - 1) * 20}%`"></div>
                </div>
                <div class="font-bold text-red-500" x-text="clothingItems[currentLevel - 1]?.emoji || ''"></div>
                <div class="text-gray-600">
                    ⏱️ <span x-text="Math.floor(timer)"></span>s
                </div>
            </div>

            {{-- Game Area --}}
            <div class="game-area" @click="handleMiss($event)">
                <div class="floor"></div>

                {{-- Rocking Chair --}}
                <div class="rocking-chair" :style="`left: ${chairPosition}px`">
                    <svg viewBox="0 0 120 100" class="w-full h-full">
                        <ellipse cx="60" cy="95" rx="55" ry="8" fill="#654321"/>
                        <rect x="20" y="30" width="80" height="8" rx="2" fill="#8B4513"/>
                        <rect x="15" y="30" width="8" height="60" rx="2" fill="#8B4513"/>
                        <rect x="97" y="30" width="8" height="60" rx="2" fill="#8B4513"/>
                        <rect x="25" y="35" width="70" height="40" rx="3" fill="#DEB887"/>
                        <path d="M 20 75 Q 60 85 100 75" stroke="#8B4513" stroke-width="6" fill="none"/>
                    </svg>
                </div>

                {{-- Viktor --}}
                <div
                    class="viktor"
                    :class="{ 'catch-effect': justCaught }"
                    :style="`left: ${viktorX}px; bottom: ${getFloorHeight() + viktorY}px; transition: bottom ${isJumping ? '0.15s' : '0.05s'} ease-out; transform: scale(${getViktorScale()});`"
                    @click.stop="catchViktor()"
                >
                    <svg
                        class="w-20 h-24"
                        :style="isCrouching ? 'transform: scaleY(0.6);' : (isMoving && !isJumping ? 'animation: stomp 0.2s infinite;' : '')"
                        viewBox="0 0 100 120">

                        {{-- Head --}}
                        <circle cx="50" cy="25" r="20" fill="#FFE4C4" stroke="#8B4513" stroke-width="2"/>
                        {{-- Hair --}}
                        <path d="M 32 12 Q 50 0 68 12" stroke="#8B4513" stroke-width="10" fill="none" stroke-linecap="round"/>
                        {{-- Eyes --}}
                        <g x-show="!isCrouching">
                            <circle cx="42" cy="22" r="4" fill="#4A4A4A"/>
                            <circle cx="58" cy="22" r="4" fill="#4A4A4A"/>
                            <circle cx="43" cy="21" r="1.5" fill="white"/>
                            <circle cx="59" cy="21" r="1.5" fill="white"/>
                        </g>
                        <g x-show="isCrouching">
                            <path d="M 38 22 L 46 22" stroke="#4A4A4A" stroke-width="2" stroke-linecap="round"/>
                            <path d="M 54 22 L 62 22" stroke="#4A4A4A" stroke-width="2" stroke-linecap="round"/>
                        </g>
                        {{-- Smile --}}
                        <path d="M 42 32 Q 50 40 58 32" stroke="#4A4A4A" stroke-width="2" fill="none"/>
                        {{-- Blush --}}
                        <circle cx="35" cy="30" r="4" fill="#FFB6C1" opacity="0.6"/>
                        <circle cx="65" cy="30" r="4" fill="#FFB6C1" opacity="0.6"/>
                        {{-- Body --}}
                        <rect x="38" y="43" width="24" height="35" rx="8" fill="#FFE4C4" stroke="#8B4513" stroke-width="2"/>
                        {{-- Arms --}}
                        <rect x="28" y="48" width="12" height="25" rx="4" fill="#FFE4C4" stroke="#8B4513" stroke-width="2"/>
                        <rect x="60" y="48" width="12" height="25" rx="4" fill="#FFE4C4" stroke="#8B4513" stroke-width="2"/>
                        {{-- Legs --}}
                        <rect x="40" y="75" width="9" height="25" rx="4" fill="#FFE4C4" stroke="#8B4513" stroke-width="2"/>
                        <rect x="51" y="75" width="9" height="25" rx="4" fill="#FFE4C4" stroke="#8B4513" stroke-width="2"/>

                        {{-- Clothing layers --}}
                        <g x-show="currentLevel > 1">
                            <rect x="36" y="65" width="28" height="15" rx="5" fill="white" stroke="#E0E0E0" stroke-width="1"/>
                        </g>
                        <g x-show="currentLevel > 2">
                            <rect x="38" y="68" width="24" height="18" rx="3" fill="#4169E1"/>
                            <rect x="40" y="82" width="9" height="18" rx="3" fill="#4169E1"/>
                            <rect x="51" y="82" width="9" height="18" rx="3" fill="#4169E1"/>
                        </g>
                        <g x-show="currentLevel > 3">
                            <rect x="36" y="43" width="28" height="28" rx="5" fill="#FF6347"/>
                            <rect x="28" y="48" width="12" height="20" rx="4" fill="#FF6347"/>
                            <rect x="60" y="48" width="12" height="20" rx="4" fill="#FF6347"/>
                            <text x="50" y="62" text-anchor="middle" fill="white" font-size="10">❤️</text>
                        </g>
                        <g x-show="currentLevel > 4">
                            <rect x="39" y="92" width="11" height="10" rx="3" fill="#FFD700"/>
                            <rect x="50" y="92" width="11" height="10" rx="3" fill="#FFD700"/>
                        </g>
                    </svg>
                </div>

                {{-- Catch feedback --}}
                <div x-show="showCatchText" x-transition
                     class="absolute top-1/4 left-1/2 transform -translate-x-1/2 -translate-y-1/2 text-center">
                    <div class="text-3xl font-bold text-green-500 drop-shadow-lg">Gepakt! 🎉</div>
                    <div class="text-2xl mt-1" x-text="clothingItems[currentLevel - 2]?.name || ''"></div>
                </div>

                {{-- Miss feedback --}}
                <div x-show="showMissText" x-transition class="absolute text-2xl"
                     :style="`left: ${missX}px; top: ${missY}px;`">❌</div>
            </div>
        </div>

        {{-- Win Screen --}}
        <div x-show="gameState === 'won'" x-cloak
             class="h-[100dvh] flex flex-col items-center justify-center p-3 bg-gradient-to-b from-pink-300 to-red-300 relative overflow-hidden">
            <div class="hearts">
                <template x-for="i in 15" :key="i">
                    <div class="heart" :style="`left: ${Math.random() * 100}%; animation-delay: ${Math.random() * 4}s;`">💕</div>
                </template>
            </div>

            <div class="message-card bg-white/90 backdrop-blur rounded-2xl shadow-xl p-4 max-w-sm w-full text-center relative z-10">
                {{-- Dressed Viktor --}}
                <svg class="w-16 h-20 mx-auto mb-2" viewBox="0 0 100 130">
                    <circle cx="50" cy="25" r="20" fill="#FFE4C4" stroke="#8B4513" stroke-width="2"/>
                    <path d="M 32 12 Q 50 0 68 12" stroke="#8B4513" stroke-width="10" fill="none" stroke-linecap="round"/>
                    <circle cx="42" cy="22" r="4" fill="#4A4A4A"/>
                    <circle cx="58" cy="22" r="4" fill="#4A4A4A"/>
                    <circle cx="43" cy="21" r="1.5" fill="white"/>
                    <circle cx="59" cy="21" r="1.5" fill="white"/>
                    <path d="M 42 32 Q 50 40 58 32" stroke="#4A4A4A" stroke-width="2" fill="none"/>
                    <circle cx="35" cy="30" r="4" fill="#FFB6C1" opacity="0.6"/>
                    <circle cx="65" cy="30" r="4" fill="#FFB6C1" opacity="0.6"/>
                    <rect x="36" y="43" width="28" height="28" rx="5" fill="#FF6347"/>
                    <rect x="28" y="48" width="12" height="20" rx="4" fill="#FF6347"/>
                    <rect x="60" y="48" width="12" height="20" rx="4" fill="#FF6347"/>
                    <text x="50" y="62" text-anchor="middle" fill="white" font-size="10">❤️</text>
                    <rect x="38" y="68" width="24" height="18" rx="3" fill="#4169E1"/>
                    <rect x="40" y="82" width="9" height="18" rx="3" fill="#4169E1"/>
                    <rect x="51" y="82" width="9" height="18" rx="3" fill="#4169E1"/>
                    <rect x="39" y="97" width="11" height="8" rx="3" fill="#FFD700"/>
                    <rect x="50" y="97" width="11" height="8" rx="3" fill="#FFD700"/>
                    <ellipse cx="44" cy="108" rx="8" ry="5" fill="#2F4F4F"/>
                    <ellipse cx="56" cy="108" rx="8" ry="5" fill="#2F4F4F"/>
                </svg>

                <h2 class="text-xl font-bold text-red-600 mb-1">Viktor is aangekleed! 🎉</h2>
                <p class="text-sm text-gray-600 mb-2">Tijd: <span x-text="Math.floor(finalTime)"></span> seconden</p>

                <div class="border-t border-pink-200 pt-2 mt-2">
                    <p class="text-lg font-bold text-red-500 mb-1">💕 Fijne Valentijnsdag Jessica! 💕</p>
                    <p class="text-xs text-gray-600 leading-relaxed">
                        Door luiers, slapeloze nachten, en broekloze ochtenden...
                        Ik zou deze chaos met niemand anders willen delen.
                    </p>
                    <p class="text-base font-bold text-red-500 mt-2">Ik hou van je! ❤️</p>
                </div>

                <button @click="resetGame()"
                    class="mt-3 bg-red-500 hover:bg-red-600 text-white text-lg font-bold py-2 px-6 rounded-full shadow-lg">
                    🔄 Nog een keer!
                </button>
            </div>
        </div>
    </div>

    <script>
        function pantsQuest() {
            return {
                gameState: 'start',
                currentLevel: 1,
                timer: 0,
                finalTime: 0,
                timerInterval: null,

                viktorX: 100,
                viktorY: 0,
                viktorDirection: 1,
                isCrouching: false,
                isJumping: false,
                isMoving: true,
                justCaught: false,

                chairPosition: 200,

                showCatchText: false,
                showMissText: false,
                missX: 0,
                missY: 0,

                moveInterval: null,
                behaviorInterval: null,

                clothingItems: [
                    { name: '👶 Luier', emoji: '👶' },
                    { name: '👖 Broek', emoji: '👖' },
                    { name: '👕 Shirt', emoji: '👕' },
                    { name: '🧦 Sokken', emoji: '🧦' },
                    { name: '👟 Schoenen', emoji: '👟' }
                ],

                getViktorScale() {
                    // Scale based on viewport height
                    const vh = window.innerHeight;
                    if (vh < 350) return 0.55;
                    if (vh < 400) return 0.65;
                    if (vh < 500) return 0.75;
                    return 0.85;
                },

                getFloorHeight() {
                    const vh = window.innerHeight;
                    if (vh < 350) return 40;
                    if (vh < 400) return 50;
                    if (vh < 500) return 60;
                    return 70;
                },

                getViktorWidth() {
                    return 80 * this.getViktorScale();
                },

                init() {
                    this.chairPosition = window.innerWidth * 0.4;
                    window.addEventListener('resize', () => {
                        this.chairPosition = window.innerWidth * 0.4;
                    });
                },

                startGame() {
                    this.gameState = 'playing';
                    this.currentLevel = 1;
                    this.timer = 0;
                    this.viktorX = window.innerWidth / 2 - (this.getViktorWidth() / 2);

                    this.timerInterval = setInterval(() => {
                        this.timer += 0.1;
                    }, 100);

                    this.startViktorBehavior();
                },

                startViktorBehavior() {
                    const gameWidth = window.innerWidth;
                    const viktorWidth = this.getViktorWidth();
                    const speed = 1 + (this.currentLevel * 3);

                    this.moveInterval = setInterval(() => {
                        if (this.justCaught) return;

                        this.viktorX += this.viktorDirection * speed;

                        if (this.viktorX > gameWidth - viktorWidth - 20) {
                            this.viktorDirection = -1;
                        } else if (this.viktorX < 20) {
                            this.viktorDirection = 1;
                        }

                        if (Math.random() < 0.02 + (this.currentLevel * 0.01)) {
                            this.viktorDirection *= -1;
                        }
                    }, 30);

                    if (this.currentLevel >= 3) {
                        const behaviorFrequency = Math.max(600, 1500 - (this.currentLevel * 300));

                        this.behaviorInterval = setInterval(() => {
                            if (this.justCaught) return;

                            if (this.isJumping) {
                                this.isJumping = false;
                                this.isCrouching = true;
                                this.viktorY = 0;
                                setTimeout(() => { this.isCrouching = false; }, 400);
                                return;
                            }

                            const nearChair = Math.abs(this.viktorX - this.chairPosition) < 80;
                            const baseChance = 0.15 + (this.currentLevel * 0.1);
                            const actionChance = nearChair ? baseChance + 0.2 : baseChance;

                            if (Math.random() < actionChance) {
                                if (Math.random() < 0.5) {
                                    this.isCrouching = true;
                                    setTimeout(() => { this.isCrouching = false; }, 600 + Math.random() * 600);
                                } else {
                                    this.isJumping = true;
                                    this.viktorY = 50;
                                    setTimeout(() => {
                                        if (this.isJumping) {
                                            this.isJumping = false;
                                            this.viktorY = 0;
                                        }
                                    }, 500 + Math.random() * 400);
                                }
                            }
                        }, behaviorFrequency);
                    }
                },

                stopViktorBehavior() {
                    clearInterval(this.moveInterval);
                    clearInterval(this.behaviorInterval);
                },

                catchViktor() {
                    if (this.justCaught) return;

                    this.justCaught = true;
                    this.showCatchText = true;
                    this.isMoving = false;
                    this.isCrouching = false;
                    this.isJumping = false;
                    this.viktorY = 0;

                    if (this.currentLevel < 5) {
                        this.currentLevel++;
                    } else {
                        this.currentLevel++;
                        setTimeout(() => { this.winGame(); }, 1000);
                        return;
                    }

                    setTimeout(() => {
                        this.showCatchText = false;
                        this.justCaught = false;
                        this.isMoving = true;
                        this.stopViktorBehavior();
                        this.startViktorBehavior();
                    }, 1200);
                },

                handleMiss(event) {
                    this.missX = event.clientX - 20;
                    this.missY = event.clientY - 20;
                    this.showMissText = true;
                    setTimeout(() => { this.showMissText = false; }, 300);
                },

                winGame() {
                    this.stopViktorBehavior();
                    clearInterval(this.timerInterval);
                    this.finalTime = this.timer;
                    this.gameState = 'won';
                },

                resetGame() {
                    this.stopViktorBehavior();
                    clearInterval(this.timerInterval);
                    this.timerInterval = null;

                    this.gameState = 'start';
                    this.currentLevel = 1;
                    this.timer = 0;
                    this.finalTime = 0;

                    this.viktorX = window.innerWidth / 2 - (this.getViktorWidth() / 2);
                    this.viktorY = 0;
                    this.viktorDirection = 1;
                    this.isCrouching = false;
                    this.isJumping = false;
                    this.isMoving = true;
                    this.justCaught = false;

                    this.showCatchText = false;
                    this.showMissText = false;
                }
            }
        }
    </script>
</body>
</html>
