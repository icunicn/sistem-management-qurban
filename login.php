<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Qurban Management System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="login.css">
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">
    <div class="bg-white p-8 rounded-lg shadow-md w-96">
        <div class="text-center mb-8">
            <i class="fas fa-mosque text-4xl text-green-600 mb-4"></i>
            <h1 class="text-2xl font-bold text-gray-800">Qurban Management System</h1>
        </div>
        <form action="" method="POST">
            <div class="mb-4">
                <div class="relative">
                    <input type="text" id="username" name="username" 
                        class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500"
                        placeholder="Enter your username" required>
                    <i class="fas fa-user absolute left-3 top-3 text-gray-400"></i>
                </div>
            </div>
            <div class="mb-6">
                <div class="relative">
                    <input type="password" id="password" name="password" 
                        class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500"
                        placeholder="Enter your password" required>
                    <i class="fas fa-lock absolute left-3 top-3 text-gray-400"></i>
                </div>
            </div>
            <button type="submit" 
                class="w-full bg-green-600 text-white py-2 px-4 rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition-colors flex items-center justify-center">
                <i class="fas fa-sign-in-alt mr-2"></i>
                Sign In
            </button>
        </form>
        <div class="mt-6 text-center">
            <p class="text-sm text-gray-600">
                <i class="fas fa-info-circle mr-1"></i>
                Contact administrator for access
            </p>
        </div>
    </div>
</body>
</html>
