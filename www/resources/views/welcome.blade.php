<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <title>Pants Quest - Viktor Aankleden!</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=comic-neue:400,700&display=swap" rel="stylesheet" />
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        * { font-family: 'Comic Neue', cursive; }

        .game-area {
            background: linear-gradient(180deg, #87CEEB 0%, #98D8C8 100%);
            position: relative;
            overflow: hidden;
        }

        .floor {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 80px;
            background: linear-gradient(180deg, #8B4513 0%, #654321 100%);
        }

        .floor::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 15px;
            background: linear-gradient(180deg, #DEB887 0%, #8B4513 100%);
        }

        .rocking-chair {
            position: absolute;
            bottom: 80px;
            width: 120px;
            height: 100px;
        }

        .viktor {
            position: absolute;
            cursor: pointer;
            transition: transform 0.1s;
            user-select: none;
            -webkit-tap-highlight-color: transparent;
        }

        .viktor:active {
            transform: scale(0.95);
        }

        .viktor.crouching {
            transform: scaleY(0.5) translateY(50%);
        }

        .viktor.stomping {
            animation: stomp 0.2s infinite;
        }

        @keyframes stomp {
            0%, 100% { transform: translateY(0) rotate(-2deg); }
            50% { transform: translateY(-5px) rotate(2deg); }
        }

        .viktor.crouching.stomping {
            animation: stomp-crouch 0.2s infinite;
        }

        @keyframes stomp-crouch {
            0%, 100% { transform: scaleY(0.5) translateY(50%) rotate(-2deg); }
            50% { transform: scaleY(0.5) translateY(45%) rotate(2deg); }
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
            font-size: 2rem;
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
            animation: fade-in-up 1s ease-out;
        }

        @keyframes fade-in-up {
            0% { opacity: 0; transform: translateY(30px); }
            100% { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body class="min-h-screen bg-pink-100">
    <div x-data="pantsQuest()" x-init="init()" class="min-h-screen flex flex-col">

        {{-- Start Screen --}}
        <div x-show="gameState === 'start'" x-cloak class="min-h-screen flex flex-col items-center justify-center p-6 bg-gradient-to-b from-pink-200 to-red-200">
            <div class="text-center">
                <h1 class="text-5xl md:text-7xl font-bold text-red-600 mb-4">👖 Pants Quest</h1>
                <p class="text-2xl md:text-3xl text-red-500 mb-2">Viktor Aankleden!</p>
                <p class="text-lg text-red-400 mb-8">Tik op Viktor om hem aan te kleden</p>

                <div class="mb-8">
                    <svg class="w-32 h-32 mx-auto" viewBox="0 0 100 100">
                        {{-- Naked Viktor for start screen --}}
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
                    class="bg-red-500 hover:bg-red-600 text-white text-2xl font-bold py-4 px-12 rounded-full shadow-lg transform hover:scale-105 transition-all"
                >
                    🎮 Start!
                </button>
            </div>
        </div>

        {{-- Game Screen --}}
        <div x-show="gameState === 'playing'" x-cloak class="flex-1 flex flex-col">
            {{-- Header --}}
            <div class="bg-white/80 backdrop-blur p-4 shadow-md">
                <div class="max-w-lg mx-auto flex justify-between items-center">
                    <div class="text-lg font-bold text-gray-700">
                        Level <span x-text="currentLevel"></span>/5
                    </div>
                    <div class="text-xl font-bold text-red-500" x-text="clothingItems[currentLevel - 1]?.name || ''"></div>
                    <div class="text-lg text-gray-600">
                        ⏱️ <span x-text="Math.floor(timer)"></span>s
                    </div>
                </div>
                {{-- Progress bar --}}
                <div class="max-w-lg mx-auto mt-2">
                    <div class="h-3 bg-gray-200 rounded-full overflow-hidden">
                        <div class="h-full bg-gradient-to-r from-pink-400 to-red-500 transition-all duration-300" :style="`width: ${(currentLevel - 1) * 20}%`"></div>
                    </div>
                </div>
            </div>

            {{-- Game Area --}}
            <div class="flex-1 game-area relative" @click="handleMiss($event)">
                <div class="floor"></div>

                {{-- Rocking Chair (hiding spot) --}}
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
                    :class="{ 'crouching': isCrouching, 'stomping': isMoving && !isJumping, 'catch-effect': justCaught }"
                    :style="`left: ${viktorX}px; bottom: ${80 + viktorY}px; transition: bottom ${isJumping ? '0.15s' : '0.05s'} ease-out;`"
                    @click.stop="catchViktor()"
                >
                    <svg :class="isCrouching ? 'w-16 h-16' : 'w-20 h-24'" viewBox="0 0 100 120">
                        {{-- Viktor with current clothing --}}

                        {{-- Head --}}
                        <circle cx="50" cy="25" r="20" fill="#FFE4C4" stroke="#8B4513" stroke-width="2"/>

                        {{-- Hair --}}
                        <path d="M 32 12 Q 50 0 68 12" stroke="#8B4513" stroke-width="10" fill="none" stroke-linecap="round"/>

                        {{-- Eyes (open when not crouching) --}}
                        <g x-show="!isCrouching">
                            <circle cx="42" cy="22" r="4" fill="#4A4A4A"/>
                            <circle cx="58" cy="22" r="4" fill="#4A4A4A"/>
                            <circle cx="43" cy="21" r="1.5" fill="white"/>
                            <circle cx="59" cy="21" r="1.5" fill="white"/>
                        </g>
                        {{-- Eyes (squinting when crouching) --}}
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

                        {{-- Clothing layers based on progress --}}

                        {{-- Diaper (level 1 complete) --}}
                        <g x-show="currentLevel > 1">
                            <rect x="36" y="65" width="28" height="15" rx="5" fill="white" stroke="#E0E0E0" stroke-width="1" class="clothing-item"/>
                        </g>

                        {{-- Pants (level 2 complete) --}}
                        <g x-show="currentLevel > 2" class="clothing-item">
                            <rect x="38" y="68" width="24" height="18" rx="3" fill="#4169E1"/>
                            <rect x="40" y="82" width="9" height="18" rx="3" fill="#4169E1"/>
                            <rect x="51" y="82" width="9" height="18" rx="3" fill="#4169E1"/>
                        </g>

                        {{-- Shirt (level 3 complete) --}}
                        <g x-show="currentLevel > 3" class="clothing-item">
                            <rect x="36" y="43" width="28" height="28" rx="5" fill="#FF6347"/>
                            <rect x="28" y="48" width="12" height="20" rx="4" fill="#FF6347"/>
                            <rect x="60" y="48" width="12" height="20" rx="4" fill="#FF6347"/>
                            <text x="50" y="62" text-anchor="middle" fill="white" font-size="10" font-weight="bold">❤️</text>
                        </g>

                        {{-- Socks (level 4 complete) --}}
                        <g x-show="currentLevel > 4" class="clothing-item">
                            <rect x="39" y="92" width="11" height="10" rx="3" fill="#FFD700"/>
                            <rect x="50" y="92" width="11" height="10" rx="3" fill="#FFD700"/>
                        </g>

                        {{-- Shoes (level 5 complete - won't show during game) --}}
                    </svg>
                </div>

                {{-- Catch feedback --}}
                <div
                    x-show="showCatchText"
                    x-transition
                    class="absolute top-1/4 left-1/2 transform -translate-x-1/2 -translate-y-1/2 text-center"
                >
                    <div class="text-4xl font-bold text-green-500 drop-shadow-lg">Gepakt! 🎉</div>
                    <div class="text-3xl mt-2 animate-bounce" x-text="clothingItems[currentLevel - 2]?.name || ''"></div>
                </div>

                {{-- Miss feedback --}}
                <div
                    x-show="showMissText"
                    x-transition
                    class="absolute text-3xl"
                    :style="`left: ${missX}px; top: ${missY}px;`"
                >
                    ❌
                </div>
            </div>
        </div>

        {{-- Win Screen --}}
        <div x-show="gameState === 'won'" x-cloak class="min-h-screen flex flex-col items-center justify-center p-6 bg-gradient-to-b from-pink-300 to-red-300 relative">
            {{-- Floating hearts background --}}
            <div class="hearts">
                <template x-for="i in 20" :key="i">
                    <div class="heart" :style="`left: ${Math.random() * 100}%; animation-delay: ${Math.random() * 4}s;`">
                        💕
                    </div>
                </template>
            </div>

            <div class="message-card bg-white/90 backdrop-blur rounded-3xl shadow-2xl p-8 max-w-md mx-auto text-center relative z-10">
                {{-- Dressed Viktor --}}
                <div class="mb-6">
                    <svg class="w-32 h-40 mx-auto" viewBox="0 0 100 130">
                        <circle cx="50" cy="25" r="20" fill="#FFE4C4" stroke="#8B4513" stroke-width="2"/>
                        <path d="M 32 12 Q 50 0 68 12" stroke="#8B4513" stroke-width="10" fill="none" stroke-linecap="round"/>
                        <circle cx="42" cy="22" r="4" fill="#4A4A4A"/>
                        <circle cx="58" cy="22" r="4" fill="#4A4A4A"/>
                        <circle cx="43" cy="21" r="1.5" fill="white"/>
                        <circle cx="59" cy="21" r="1.5" fill="white"/>
                        <path d="M 42 32 Q 50 40 58 32" stroke="#4A4A4A" stroke-width="2" fill="none"/>
                        <circle cx="35" cy="30" r="4" fill="#FFB6C1" opacity="0.6"/>
                        <circle cx="65" cy="30" r="4" fill="#FFB6C1" opacity="0.6"/>

                        {{-- Shirt --}}
                        <rect x="36" y="43" width="28" height="28" rx="5" fill="#FF6347"/>
                        <rect x="28" y="48" width="12" height="20" rx="4" fill="#FF6347"/>
                        <rect x="60" y="48" width="12" height="20" rx="4" fill="#FF6347"/>
                        <text x="50" y="62" text-anchor="middle" fill="white" font-size="10">❤️</text>

                        {{-- Pants --}}
                        <rect x="38" y="68" width="24" height="18" rx="3" fill="#4169E1"/>
                        <rect x="40" y="82" width="9" height="18" rx="3" fill="#4169E1"/>
                        <rect x="51" y="82" width="9" height="18" rx="3" fill="#4169E1"/>

                        {{-- Socks --}}
                        <rect x="39" y="97" width="11" height="8" rx="3" fill="#FFD700"/>
                        <rect x="50" y="97" width="11" height="8" rx="3" fill="#FFD700"/>

                        {{-- Shoes --}}
                        <ellipse cx="44" cy="108" rx="8" ry="5" fill="#2F4F4F"/>
                        <ellipse cx="56" cy="108" rx="8" ry="5" fill="#2F4F4F"/>
                    </svg>
                </div>

                <h2 class="text-3xl font-bold text-red-600 mb-4">Viktor is aangekleed! 🎉</h2>

                <div class="text-lg text-gray-700 space-y-4 mb-6">
                    <p class="text-xl">Tijd: <span x-text="Math.floor(finalTime)"></span> seconden</p>

                    <div class="border-t border-pink-200 pt-4 mt-4">
                        <p class="text-2xl font-bold text-red-500 mb-3">💕 Fijne Valentijnsdag Jessica! 💕</p>
                        <p class="text-gray-600 leading-relaxed">
                            Door luiers, slapeloze nachten, en broekloze ochtenden...
                        </p>
                        <p class="text-gray-600 leading-relaxed mt-2">
                            Ik zou deze chaos met niemand anders willen delen dan met jou.
                        </p>
                        <p class="text-xl font-bold text-red-500 mt-4">
                            Ik hou van je! ❤️
                        </p>
                    </div>
                </div>

                <button
                    @click="resetGame()"
                    class="bg-red-500 hover:bg-red-600 text-white text-xl font-bold py-3 px-8 rounded-full shadow-lg transform hover:scale-105 transition-all"
                >
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

                init() {
                    this.chairPosition = window.innerWidth * 0.3;
                },

                startGame() {
                    this.gameState = 'playing';
                    this.currentLevel = 1;
                    this.timer = 0;
                    this.viktorX = window.innerWidth / 2 - 40;

                    this.timerInterval = setInterval(() => {
                        this.timer += 0.1;
                    }, 100);

                    this.startViktorBehavior();
                },

                startViktorBehavior() {
                    const gameWidth = window.innerWidth;
                    // Speed ramps up quicker: 4, 7, 10, 13, 16
                    const speed = 1 + (this.currentLevel * 3);

                    // Movement
                    this.moveInterval = setInterval(() => {
                        if (this.justCaught) return;

                        this.viktorX += this.viktorDirection * speed;

                        // Bounce off walls
                        if (this.viktorX > gameWidth - 100) {
                            this.viktorDirection = -1;
                        } else if (this.viktorX < 20) {
                            this.viktorDirection = 1;
                        }

                        // Random direction changes (more erratic at higher levels)
                        if (Math.random() < 0.02 + (this.currentLevel * 0.01)) {
                            this.viktorDirection *= -1;
                        }
                    }, 30);

                    // Crouching/jumping behavior - only from level 3+
                    if (this.currentLevel >= 3) {
                        // Frequency increases with level: 1200ms at L3, 900ms at L4, 600ms at L5
                        const behaviorFrequency = Math.max(600, 1500 - (this.currentLevel * 300));

                        this.behaviorInterval = setInterval(() => {
                            if (this.justCaught) return;

                            // If jumping and crouch triggered, slam down fast
                            if (this.isJumping) {
                                this.isJumping = false;
                                this.isCrouching = true;
                                this.viktorY = 0;
                                setTimeout(() => {
                                    this.isCrouching = false;
                                }, 400);
                                return;
                            }

                            // Check if near chair - higher chance to crouch
                            const nearChair = Math.abs(this.viktorX - this.chairPosition) < 80;
                            // Chance increases with level
                            const baseChance = 0.15 + (this.currentLevel * 0.1);
                            const actionChance = nearChair ? baseChance + 0.2 : baseChance;

                            if (Math.random() < actionChance) {
                                // 50% crouch, 50% jump
                                if (Math.random() < 0.5) {
                                    this.isCrouching = true;
                                    setTimeout(() => {
                                        this.isCrouching = false;
                                    }, 600 + Math.random() * 600);
                                } else {
                                    this.isJumping = true;
                                    this.viktorY = 60;
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

                    // First: increment level to show new clothing
                    if (this.currentLevel < 5) {
                        this.currentLevel++;
                    } else {
                        this.currentLevel++;
                        setTimeout(() => {
                            this.winGame();
                        }, 1000);
                        return;
                    }

                    // Then: pause to admire the new clothes, then continue
                    setTimeout(() => {
                        this.showCatchText = false;
                        this.justCaught = false;
                        this.isMoving = true;
                        // Restart behavior with increased difficulty
                        this.stopViktorBehavior();
                        this.startViktorBehavior();
                    }, 1200);
                },

                handleMiss(event) {
                    this.missX = event.clientX - 20;
                    this.missY = event.clientY - 20;
                    this.showMissText = true;

                    setTimeout(() => {
                        this.showMissText = false;
                    }, 300);
                },

                winGame() {
                    this.stopViktorBehavior();
                    clearInterval(this.timerInterval);
                    this.finalTime = this.timer;
                    this.gameState = 'won';
                },

                resetGame() {
                    // Stop all running intervals first
                    this.stopViktorBehavior();
                    clearInterval(this.timerInterval);
                    this.timerInterval = null;

                    // Reset game state
                    this.gameState = 'start';
                    this.currentLevel = 1;
                    this.timer = 0;
                    this.finalTime = 0;

                    // Reset Viktor state
                    this.viktorX = window.innerWidth / 2 - 40;
                    this.viktorY = 0;
                    this.viktorDirection = 1;
                    this.isCrouching = false;
                    this.isJumping = false;
                    this.isMoving = true;
                    this.justCaught = false;

                    // Reset UI state
                    this.showCatchText = false;
                    this.showMissText = false;
                }
            }
        }
    </script>
</body>
</html>
