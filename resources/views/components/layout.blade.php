<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Google Drive Clone</title>
    @Vite('resources/css/app.css','resources/js/app.js')
</head>

<body class="flex h-screen bg-zinc-900 text-white">

    <div class="w-64 p-4 bg-zinc-900">
        <div class="flex items-center mb-6">
            <img src="https://www.gstatic.com/images/branding/product/1x/drive_2020q4_48dp.png" alt="Drive Logo"
                class="w-10 h-10 mr-2">
            <span class="text-xl font-semibold">Drive Clone</span>
        </div>
        <ul class="space-y-2">
            <button class="w-1/2 px-4 py-2 text-left text-white bg-blue-600 rounded-xl hover:bg-blue-700"> +
                New</button>
            <li class="hover:bg-gray-700 rounded">
                <a href="{{ route('file.index') }}" class="block px-4 py-2">Home</a>
            </li>

            <li class="hover:bg-gray-700 rounded">
                <a href="#" class="block px-4 py-2">Recent</a>
            </li>
            <li class="hover:bg-gray-700 rounded">
                <a href="{{ route('starred') }}" class="block px-4 py-2">
                    Starred
                </a>
            </li>

            <li class="hover:bg-gray-700 rounded">
                <a href="#" class="block px-4 py-2">Trash</a>
            </li>
            <li class="cursor-default block px-4 py-2">
                Storage
            </li>
        </ul>
    </div>
    <div class="flex-grow bg-zinc-900">
        <div class="flex items-center p-4 bg-zinc-900">
            <input name="search" id="search" type="text" placeholder=" &telrec; Search in Drive"
                class="flex-grow px-4 py-2 text-white rounded-3xl bg-zinc-800">
        </div>
        <div class="space-x-2 flex items-center mb-4 mx-4">
            <!-- Type Filtering -->
            <div class="relative inline-block text-left">
                <button id="menu-button"
                    class="px-4 py-2 text-white bg-black border-solid border border-white rounded-xl hover:bg-zinc-700"
                    aria-expanded="false" aria-haspopup="true">
                    Type &#9207;
                </button>
                <div class="absolute z-10 mt-2 w-56 origin-top-right rounded-md bg-black shadow-lg ring-1 ring-black ring-opacity-5 hidden"
                    role="menu" aria-orientation="vertical" aria-labelledby="menu-button">
                    <div class="py-1" role="none">
                        <!-- Menu Items -->
                        <button
                            class="filter-button px-4 py-2 w-full text-left text-sm hover:bg-zinc-700 flex items-center space-x-2"
                            name="image" role="menuitem">

                            <span>Images</span>
                        </button>
                        <button
                            class="filter-button px-4 py-2 w-full text-left text-sm hover:bg-zinc-700 flex items-center space-x-2"
                            name="video" role="menuitem">

                            <span>Videos</span>
                        </button>
                        <button
                            class="filter-button px-4 py-2 w-full text-left text-sm hover:bg-zinc-700 flex items-center space-x-2"
                            name="audio" role="menuitem">

                            <span>Audios</span>
                        </button>
                        <button
                            class="filter-button px-4 py-2 w-full text-left text-sm hover:bg-zinc-700 flex items-center space-x-2"
                            name="pdf" role="menuitem">

                            <span>PDFs</span>
                        </button>
                        <button
                            class="filter-button px-4 py-2 w-full text-left text-sm hover:bg-zinc-700 flex items-center space-x-2"
                            name="text" role="menuitem">

                            <span>Text</span>
                        </button>
                        <button
                            class="filter-button px-4 py-2 w-full text-left text-sm hover:bg-zinc-700 flex items-center space-x-2"
                            name="archive" role="menuitem">

                            <span>Archives</span>
                        </button>
                    </div>
                </div>
            </div>
            <!-- Modified by filter -->
            <div class="relative inline-block text-left">
                <button id="menu-button"
                    class="px-4 py-2 text-white bg-black border-solid border border-white rounded-xl hover:bg-zinc-700"
                    aria-expanded="false" aria-haspopup="true">
                    Modified &#9207;
                </button>
                <div class="absolute z-10 mt-2 w-56 origin-top-right rounded-md bg-black shadow-lg ring-1 ring-black ring-opacity-5 hidden"
                    role="menu" aria-orientation="vertical" aria-labelledby="menu-button">
                    <div class="py-1" role="none">
                        <!-- Menu Items -->
                        <button
                            class="filter-button px-4 py-2 w-full text-left text-sm hover:bg-zinc-700 flex items-center space-x-2"
                            name="image" role="menuitem">

                            <span>Images</span>
                        </button>
                        <button
                            class="filter-button px-4 py-2 w-full text-left text-sm hover:bg-zinc-700 flex items-center space-x-2"
                            name="video" role="menuitem">

                            <span>Videos</span>
                        </button>
                        <button
                            class="filter-button px-4 py-2 w-full text-left text-sm hover:bg-zinc-700 flex items-center space-x-2"
                            name="audio" role="menuitem">

                            <span>Audios</span>
                        </button>
                        <button
                            class="filter-button px-4 py-2 w-full text-left text-sm hover:bg-zinc-700 flex items-center space-x-2"
                            name="pdf" role="menuitem">

                            <span>PDFs</span>
                        </button>
                        <button
                            class="filter-button px-4 py-2 w-full text-left text-sm hover:bg-zinc-700 flex items-center space-x-2"
                            name="text" role="menuitem">

                            <span>Text</span>
                        </button>
                        <button
                            class="filter-button px-4 py-2 w-full text-left text-sm hover:bg-zinc-700 flex items-center space-x-2"
                            name="archive" role="menuitem">

                            <span>Archives</span>
                        </button>
                    </div>
                </div>
            </div>

        </div>
        {{$slot}}
    </div>
</body>

</html>