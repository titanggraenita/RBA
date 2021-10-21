<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Admin') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h1 class="mx-auto font-bold font-mono text-lg mb-2">REQUEST NEW DEVICE</h1>
                        <form class="w-full max-w-sm" method="POST" action="/device/store">
                            @csrf
                            <div class="md:flex md:items-center mb-2">
                                <div class="md:w-1/3">
                                    <label class="block text-gray-500 font-bold md:text-right mb-1 md:mb-0 pr-6" for="inline-full-name">
                                        Net ID
                                    </label>
                                </div>
                                <div class="md:w-2/3">
                                    <input name="net_id" class="bg-gray-200 appearance-none border-2 border-gray-200 rounded w-full py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500" id="inline-full-name" type="text">
                                </div>
                            </div>
                            <div class="md:flex md:items-center mb-6">
                                <div class="md:w-1/3">
                                    <label class="block text-gray-500 font-bold md:text-right mb-1 md:mb-0 pr-4" for="inline-full-name">
                                        Tambah Device
                                    </label>
                                </div>
                                <div class="md:w-2/3">
                                    <input name="add_device" class="bg-gray-200 appearance-none border-2 border-gray-200 rounded w-full py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500" id="inline-full-name" type="text">
                                </div>
                            </div>
                            <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">
                                Save
                            </button>
                        </form>
                    
                    
                    <div class="container flex justify-center mx-auto">
                        <div class="flex flex-col">
                            <div class="w-full">
                                <div class="border-b border-gray-200 shadow">
                                    <table>
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th class="px-6 py-2 text-xs text-gray-500">
                                                    Nomor
                                                </th>
                                                <th class="px-6 py-2 text-xs text-gray-500">
                                                    Username
                                                </th>
                                                <th class="px-6 py-2 text-xs text-gray-500">
                                                    Status
                                                </th>
                                                <th class="px-6 py-2 text-xs text-gray-500">
                                                    Deskripsi
                                                </th>
                                                <th class="px-6 py-2 text-xs text-gray-500">
                                                    Merk
                                                </th>
                                                <th class="px-6 py-2 text-xs text-gray-500">
                                                    MAc Address
                                                </th>
                                                <th class="px-6 py-2 text-xs text-gray-500">
                                                    Tanggal Registrasi
                                                </th>
                                                <th class="px-6 py-2 text-xs text-gray-500">
                                                    Umur Registrasi
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white">
                                            <tr class="whitespace-nowrap">
                                                <td class="px-6 py-4 text-sm text-gray-500">
                                                    1
                                                </td>
                                                <td class="px-6 py-4">
                                                    <div class="text-sm text-gray-900">
                                                        Jon doe
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4">
                                                    <div class="text-sm text-gray-500">jhondoe@example.com</div>
                                                </td>
                                                <td class="px-6 py-4 text-sm text-gray-500">
                                                    2021-1-12
                                                </td>
                                                <td class="px-6 py-4 text-sm text-gray-500">
                                                    2021-1-12
                                                </td>
                                                <td class="px-6 py-4 text-sm text-gray-500">
                                                    2021-1-12
                                                </td>
                                                <td class="px-6 py-4 text-sm text-gray-500">
                                                    2021-1-12
                                                </td>
                                                <td class="px-6 py-4 text-sm text-gray-500">
                                                    2021-1-12
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div></p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>