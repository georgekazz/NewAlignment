
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Tailwind CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">

<body class="bg-gray-100 flex justify-center items-center h-screen">
    <div class="text-center">
        <button onclick="openNewWindow()" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
            Μεταβείτε στο Site
        </button>
    </div>

    <script>
        function openNewWindow() {
            window.open("{{ route('forcetreemain') }}", "_blank");
        }
    </script>
</body>
</html>
