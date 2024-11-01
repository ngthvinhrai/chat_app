<html>

<head>
    <title>Ubiquitous Compute</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,600,700" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        body {
            font-family: 'Open Sans', sans-serif;
            background: radial-gradient(circle, #e0e0e0, #ffffff);
            overflow: hidden;
            position: relative;
            width: 100vw;
            height: 100vh;
        }

        .color-circle {
            position: absolute;
            border-radius: 50%;
            opacity: 0.9;
            animation: move 60s infinite alternate ease-in-out;
            box-shadow: 0 15px 25px rgba(0, 0, 0, 0.3);
            transition: transform 0.5s ease, border-radius 10s ease;
        }

        @keyframes move {
            0% {
                transform: translate(0, 0) scale(1);
            }

            25% {
                transform: translate(400px, 300px) scale(1.4);
            }

            50% {
                transform: translate(-400px, -300px) scale(1.2);
            }

            75% {
                transform: translate(300px, -200px) scale(1.4);
            }

            100% {
                transform: translate(0, 0) scale(1);
            }
        }

        .container {
            position: relative;
            z-index: 10;
        }
    </style>
</head>

<body>
    <!-- Container for dynamically added circles -->
    <div id="animated-circles"></div>

    <div class="container mx-auto px-4 py-2">
        <div class="flex justify-between items-center py-4">
            <div class="text-3xl font-bold text-black">Nhóm 2</div>
            <div class="space-x-4">
                <a href="php/admin.php" class="hover:text-gray-600 text-black">Admin</a>
                <a href="#" class="hover:text-gray-600 text-black">Docs</a>
            </div>
        </div>
        <div class="text-center my-20">
            <h1 class="text-6xl font-bold text-black">Chat App - Dự đoán cảm xúc</h1>
            <p class="text-2xl mt-4 text-gray-700">Nhóm 2 - Kỹ thuật Robot và Trí tuệ nhân tạo K62</p>
            <button onclick="window.location.href='users.php';"
                class="mt-8 bg-black text-white font-bold py-2 px-6 rounded-full hover:bg-gray-800">Join</button>
        </div>
        <div class="text-center mt-40 mb-20">
            <div class="text-black">Backed By:</div>
            <div class="flex justify-center space-x-8 mt-4">
                <img src="php/images/image1.png" alt="Logo của Multicoin Capital" class="h-6" />
                <img src="php/images/image2.png" alt="Logo của HONGSHAN Capital" class="h-6" />
                <img src="php/images/image3.png" alt="Logo của PANTERA Capital" class="h-6" />
            </div>

            <div class="flex justify-center space-x-2 mt-20">
                <button aria-label="Slide previous" class="text-black">
                    <i class="fas fa-chevron-left"></i>
                </button>
                <button aria-label="Slide next" class="text-black">
                    <i class="fas fa-chevron-right"></i>
                </button>
            </div>
        </div>
        <div class="flex justify-between mt-4 mb-8">
            <div class="text-sm text-black">
                <a href="#" class="hover:text-gray-600">Terms</a>
                <span class="mx-2">|</span>
                <a href="#" class="hover:text-gray-600">Privacy</a>
            </div>
        </div>
    </div>

    <script>
        // Function to generate random values within a range
        function randomValue(min, max) {
            return Math.random() * (max - min) + min;
        }

        // Function to generate random color
        function randomColor() {
            const colors = [
                'rgba(144, 64, 247, 0.6)', // purple
                'rgba(33, 133, 191, 0.6)', // blue
                'rgba(255, 66, 147, 0.6)', // pink
                'rgba(213, 118, 76, 0.6)', // orange
                'rgba(255, 213, 62, 0.6)' // yellow
            ];
            return colors[Math.floor(Math.random() * colors.length)];
        }

        // Function to create random circles
        function createRandomCircles(count) {
            const container = document.getElementById('animated-circles');

            for (let i = 0; i < count; i++) {
                const circle = document.createElement('div');
                const size = randomValue(20, 50); // Random size for each circle
                const posX = randomValue(0, window.innerWidth - size); // Random X position
                const posY = randomValue(0, window.innerHeight - size); // Random Y position

                circle.classList.add('color-circle');
                circle.style.width = `${size}px`;
                circle.style.height = `${size}px`;
                circle.style.backgroundColor = randomColor(); // Set random color for each circle
                circle.style.top = `${posY}px`;
                circle.style.left = `${posX}px`;

                container.appendChild(circle);
            }
        }

        // Create 20 random circles
        createRandomCircles(10);
    </script>
</body>

</html>