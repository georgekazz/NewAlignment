<!DOCTYPE html>
<html lang="el">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Register - Modern</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"
        crossorigin="anonymous" />
    <link rel="icon" href="./img/favicon-alignment.png">

</head>

<body
    class="bg-gradient-to-br from-purple-700 via-indigo-700 to-blue-700 min-h-screen flex items-center justify-center p-6">
    <a href="{{ url('/') }}" class="absolute top-6 left-6 text-white text-3xl hover:text-gray-300 transition">
        <i class="fas fa-arrow-left"></i>
    </a>

    <div
        class="bg-white bg-opacity-90 backdrop-blur-md rounded-3xl shadow-xl max-w-4xl w-full flex flex-col md:flex-row overflow-hidden">
        <div class="w-full md:w-1/2 p-10 flex flex-col justify-center">
            <h1
                class="text-4xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-purple-600 to-indigo-600 mb-8 text-center">
                Register
            </h1>

            <form class="space-y-6" action="{{ route('register') }}" method="POST">
                @csrf
                <div>
                    <label for="name" class="block text-gray-700 font-semibold mb-2 flex items-center gap-2">
                        <i class="fas fa-user text-indigo-600"></i> Name
                    </label>
                    <input type="text" id="name" name="name" required
                        class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                        placeholder="Your full name" />
                </div>

                <div>
                    <label for="username" class="block text-gray-700 font-semibold mb-2 flex items-center gap-2">
                        <i class="fas fa-user-circle text-indigo-600"></i> Username
                    </label>
                    <input type="text" id="username" name="username" required
                        class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                        placeholder="Choose a username" />
                </div>

                <div>
                    <label for="password" class="block text-gray-700 font-semibold mb-2 flex items-center gap-2">
                        <i class="fas fa-lock text-indigo-600"></i> Password
                    </label>
                    <input type="password" id="password" name="password" required minlength="8"
                        class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                        placeholder="At least 8 characters" />
                </div>

                <div>
                    <label for="password_confirmation"
                        class="block text-gray-700 font-semibold mb-2 flex items-center gap-2">
                        <i class="fas fa-key text-indigo-600"></i> Repeat Password
                    </label>
                    <input type="password" id="password_confirmation" name="password_confirmation" required
                        class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                        placeholder="Repeat your password" />
                </div>

                <button type="submit"
                    class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 rounded-lg shadow-lg transition">
                    Register
                </button>
            </form>
        </div>

        <div class="hidden md:block md:w-1/2">
            <img src="https://img.freepik.com/free-vector/sign-page-abstract-concept-illustration_335657-2242.jpg?w=900&t=st=1718194432~exp=1718195032~hmac=0ff985bee12e5c2a01bebaef0987945b131f0a6f74bb4ac209f656c107bd6a48"
                alt="Εγγραφή" class="object-cover w-full h-full rounded-r-3xl" />
        </div>
    </div>
</body>

</html>