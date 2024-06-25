<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Force-Directed Tree</title>
    <!-- Tailwind CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <style>
        body {
            position: relative;
            overflow: hidden;
        }

        .animated-bg {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: #6a0dad;
            z-index: -1;
            overflow: hidden;
        }

        .cube {
            position: absolute;
            width: 50px;
            height: 50px;
            background: rgba(255, 255, 255, 0.1);
            animation: moveCube 20s linear infinite;
        }

        .cube:nth-child(1) {
            top: 10%;
            left: 20%;
            animation-duration: 22s;
            animation-delay: -2s;
        }

        .cube:nth-child(2) {
            top: 20%;
            left: 40%;
            animation-duration: 18s;
            animation-delay: -4s;
        }

        .cube:nth-child(3) {
            top: 30%;
            left: 60%;
            animation-duration: 20s;
            animation-delay: -6s;
        }

        .cube:nth-child(4) {
            top: 40%;
            left: 80%;
            animation-duration: 25s;
            animation-delay: -8s;
        }

        .cube:nth-child(5) {
            top: 50%;
            left: 50%;
            animation-duration: 30s;
            animation-delay: -10s;
        }

        @keyframes moveCube {
            0% {
                transform: translateY(0) translateX(0);
            }

            50% {
                transform: translateY(100vh) translateX(100vw);
            }

            100% {
                transform: translateY(0) translateX(0);
            }
        }
    </style>
</head>

<body class="bg-gray-100 flex flex-col h-screen">
    <div class="animated-bg">
        <div class="cube"></div>
        <div class="cube"></div>
        <div class="cube"></div>
        <div class="cube"></div>
        <div class="cube"></div>
        <div class="cube"></div>
        <div class="cube"></div>
    </div>
    <div class="container mx-auto py-8 flex-grow">
        <h1 class="text-3xl font-bold text-center text-white mb-8">Welcome to Force-Directed Tree</h1>
        <div class="overflow-x-auto">
            <table class="table-auto bg-white shadow-md rounded-lg mx-auto">
                <thead class="bg-gray-200">
                    <tr>
                        <th class="px-4 py-2">ID</th>
                        <th class="px-4 py-2">File Name</th>
                        <th class="px-4 py-2">Created At</th>
                        <th class="px-4 py-2">Status</th>
                        <th class="px-4 py-2">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach ($files as $file)
                        <tr>
                            <td class="px-4 py-2">{{ $file->id }}</td>
                            <td class="px-4 py-2">{{ $file->filename }}</td>
                            <td class="px-4 py-2">{{ $file->created_at }}</td>
                            <td class="px-4 py-2 text-center">
                                <!-- Εικονίδιο Status (κύκλος) -->
                                <span
                                    class="inline-block w-4 h-4 rounded-full {{ $file->status ? 'bg-green-500' : 'bg-red-500' }}"></span>
                            </td>
                            <td class="px-4 py-2 flex items-center justify-center">
                                <!-- Κουμπί Get -->
                                <button id="openParametersModal{{ $file->id }}" title="Get this file"
                                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Get</button>
                                <!-- Κουμπί Run -->
                                <button id="runChart{{ $file->id }}" title="Run this file"
                                    class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">Run</button>
                                <!-- Κουμπί Delete -->
                                <button id="deleteFile{{ $file->id }}" title="Delete this file"
                                    class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded ml-2">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </td>

                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="flex justify-center mt-4">
                <button id="openModal" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Upload
                </button>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div id="uploadModal" class="fixed inset-0 bg-gray-800 bg-opacity-50 flex justify-center items-center hidden">
        <div class="bg-white rounded-lg shadow-lg p-8 max-w-md mx-auto">
            <h2 class="text-2xl font-bold mb-4">Upload TTL File</h2>
            <form id="uploadForm">
                <input type="file" id="ttlFile" name="ttlFile" accept=".ttl" class="mb-4">
                <div id="uploadMessage" class="mb-4"></div>
                <div class="flex justify-end">
                    <button type="button" id="closeModal"
                        class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded mr-2">
                        Cancel
                    </button>
                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Upload
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Νέο Modal με πεδία παραμέτρων και κείμενο μηνυμάτων -->
    <div id="parametersModal" class="fixed inset-0 bg-gray-800 bg-opacity-50 flex justify-center items-center hidden">
        <div class="bg-white rounded-lg shadow-lg p-8 w-full max-w-3xl mx-auto">
            <h2 class="text-2xl font-bold mb-4">Enter Parameters</h2>
            <form id="parametersForm">
                <div class="mb-6">
                    <label for="param1" class="block text-sm font-medium text-gray-700">Namespace</label>
                    <input type="text" id="param1" name="param1"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                </div>
                <div class="mb-6">
                    <label for="param2" class="block text-sm font-medium text-gray-700">Predicate</label>
                    <input type="text" id="param2" name="param2"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                </div>
                <div id="parametersMessage" class="mb-4 p-4 border border-gray-300 rounded-md hidden"></div>
                <div class="flex justify-end">
                    <button type="button" id="closeParametersModal"
                        class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded mr-2">
                        Cancel
                    </button>
                    <button type="button" id="submitParameters"
                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Submit
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Footer -->
    <footer class="py-4 bg-gray-200 text-center">
        <img src="https://okfn.gr/wp-content/uploads/2023/11/okfn-newlogo-gr-1.svg" class="mx-auto mb-2"
            alt="Footer Image" width="170">
        <p class="text-gray-600">© 2024 Georgios Christoforos Kazlaris. All rights reserved. Developed with Open
            Knowledge Foundation Greece.</p>
    </footer>

    <!-- Script -->
    <script>
        document.getElementById('openModal').addEventListener('click', function () {
            document.getElementById('uploadModal').classList.remove('hidden');
        });

        document.getElementById('closeModal').addEventListener('click', function () {
            document.getElementById('uploadModal').classList.add('hidden');
            document.getElementById('uploadMessage').innerHTML = '';
        });

        document.getElementById('uploadForm').addEventListener('submit', function (e) {
            e.preventDefault();

            var formData = new FormData();
            var fileInput = document.getElementById('ttlFile');

            if (fileInput.files.length === 0) {
                document.getElementById('uploadMessage').innerHTML = '<p class="text-red-500">You must select a file</p>';
                return;
            }

            formData.append('ttlFile', fileInput.files[0]);

            var xhr = new XMLHttpRequest();
            xhr.open('POST', '{{ route("upload") }}', true);
            xhr.setRequestHeader('X-CSRF-TOKEN', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));

            xhr.onload = function () {
                if (xhr.status === 200) {
                    var response = xhr.response;
                    if (response.includes('File uploaded successfully!')) {
                        document.getElementById('uploadMessage').innerHTML = '<p class="text-green-500">File uploaded successfully!</p>';
                        setTimeout(function () {
                            document.getElementById('uploadModal').classList.add('hidden');
                            document.getElementById('uploadMessage').innerHTML = '';
                        }, 2000);
                    } else {
                        document.getElementById('uploadMessage').innerHTML = '<p class="text-red-500">File upload failed.</p>';
                    }
                } else {
                    document.getElementById('uploadMessage').innerHTML = '<p class="text-red-500">An error occurred!</p>';
                }
            };

            xhr.send(formData);
        });

        // Event listeners for Get and Run buttons
        @foreach ($files as $file)
            document.getElementById('openParametersModal{{ $file->id }}').addEventListener('click', function () {
                document.getElementById('parametersModal').classList.remove('hidden');
            });

            document.getElementById('runChart{{ $file->id }}').addEventListener('click', function () {
                alert('Open chart for file ID {{ $file->id }}');
                // Add your logic to open the chart here
            });
        @endforeach

        document.getElementById('closeParametersModal').addEventListener('click', function () {
            document.getElementById('parametersModal').classList.add('hidden');
            clearParametersForm();
            document.getElementById('parametersMessage').classList.add('hidden');
        });

        document.getElementById('submitParameters').addEventListener('click', function () {
            var param1 = document.getElementById('param1').value.trim();
            var param2 = document.getElementById('param2').value.trim();
            var parametersMessage = document.getElementById('parametersMessage');

            if (param1 !== '' && param2 !== '') {
                parametersMessage.innerHTML = '<p class="text-green-500">Parameters submitted successfully!</p>';
                parametersMessage.classList.remove('hidden');

                setTimeout(function () {
                    document.getElementById('parametersModal').classList.add('hidden');
                    clearParametersForm();
                    parametersMessage.classList.add('hidden');
                }, 2000);
            } else {
                parametersMessage.innerHTML = '<p class="text-red-500">Please fill in both parameters.</p>';
                parametersMessage.classList.remove('hidden');
            }
        });

        function clearParametersForm() {
            document.getElementById('param1').value = '';
            document.getElementById('param2').value = '';
        }
    </script>


    <script>
        document.addEventListener('DOMContentLoaded', function () {
            @foreach ($files as $file)
                document.getElementById('deleteFile{{ $file->id }}').addEventListener('click', function () {
                    if (confirm('Are you sure you want to delete this file?')) {
                        deleteFile({{ $file->id }});
                    }
                });
            @endforeach

            function deleteFile(fileId) {
                var xhr = new XMLHttpRequest();
                xhr.open('DELETE', '/file/delete/' + fileId, true);
                xhr.setRequestHeader('X-CSRF-TOKEN', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));

                xhr.onload = function () {
                    if (xhr.status === 200) {
                        var response = JSON.parse(xhr.responseText);
                        if (response.status === 'success') {
                            alert('File deleted successfully!');
                            window.location.reload();
                        } else {
                            alert('Failed to delete file. Please try again later.');
                        }
                    } else {
                        alert('Error: ' + xhr.status);
                    }
                };

                xhr.send();
            }
        });
    </script>


</body>

</html>