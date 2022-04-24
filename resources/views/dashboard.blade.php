<?php $i = 1; $isFull = false; ?>
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <img src="{{asset('img/Logo_PENS.png')}}" class="object-scale-down mx-auto w-24 h-24">
                    <center>
                        <h1 class="mx-auto font-bold font-mono text-lg">EEPIS MOBILE ACCESS</h1>
                        <p>Welcome {{Auth::user()->email}}</p>
                        <p>Please Register Your Device and Let's BYOD</p>
                        <p>Your IP Address: {{$_SERVER['REMOTE_ADDR']}}</p>
                        <h1 class="mx-auto font-bold font-mono text-lg mb-6">Anda mempunyai sisa kuota perangkat sebanyak <?php
                        $maxDevice = 3;
                        echo $maxDevice - $deviceCount;
                        if (($maxDevice - $deviceCount) < 1) {
                            $isFull = true;
                            echo "<script>alert('Jumlah Device Maksimal Tercapai')</script>";
                        } ?> </h1>
                        <form class="w-full max-w-sm" method="POST" action="/device/store">
                            @csrf
                            <div class="md:flex md:items-center mb-2">
                                <div class="md:w-1/3">
                                    <label class="block text-gray-500 font-bold md:text-right mb-1 md:mb-0 pr-4" for="inline-full-name">
                                        MAC Address
                                    </label>
                                </div>
                                <div class="md:w-2/3">
                                    <input name="mac" class="bg-gray-200 appearance-none border-2 border-gray-200 rounded w-full py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500" id="inline-full-name" type="text" value="{{$MAC}}" disabled>
                                </div>
                            </div>
                            <div class="md:flex md:items-center mb-2">
                                <div class="md:w-1/3">
                                    <label class="block text-gray-500 font-bold md:text-right mb-1 md:mb-0 pr-4" for="inline-password">
                                        Device
                                    </label>
                                </div>
                                <div class="md:w-2/3">
                                    <select name="device" class="bg-gray-200 appearance-none border-2 border-gray-200 rounded w-full py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500" id="inline-password">
                                        <option>Choose Your Device</option>
                                        <option value="Smartphone">Smartphone / Tablet</option>
                                        <option value="Laptop">Notebook / Laptop</option>
                                        <option value="Desktop">Desktop PC</option>
                                    </select>
                                </div>
                            </div>
                            <div class="md:flex md:items-center mb-2">
                                <div class="md:w-1/3">
                                    <label class="block text-gray-500 font-bold md:text-right mb-1 md:mb-0 pr-4" for="inline-password">
                                        OS Type
                                    </label>
                                </div>
                                <div class="md:w-2/3">
                                    <select name="deviceOS" class="bg-gray-200 appearance-none border-2 border-gray-200 rounded w-full py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500" id="inline-password">
                                        <option>Choose Your Device OS</option>
                                        <option value="Windows">Windows</option>
                                        <option value="Linux">Linux</option>
                                        <option value="Mac OS">Mac OS</option>
                                        <option value="Mobile Device">Mobile Device</option>
                                    </select>
                                </div>
                            </div>
                            <div class="md:flex md:items-center mb-6">
                                <div class="md:w-1/3">
                                    <label class="block text-gray-500 font-bold md:text-right mb-1 md:mb-0 pr-4" for="inline-full-name">
                                        Vendor
                                    </label>
                                </div>
                                <div class="md:w-2/3">
                                    <input name="vendor" class="bg-gray-200 appearance-none border-2 border-gray-200 rounded w-full py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500" id="inline-full-name" type="text">
                                </div>
                            </div>
                            <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline mb-6" type="submit" <?=($isFull) ? "disabled" : ""?>>
                                Save
                            </button><br>
                        </form>
                    </center>

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
                                                    MAC Address
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
                                            @foreach($user_devices as $user_device)
                                            <tr class="whitespace-nowrap">
                                                <td class="px-6 py-4 text-sm text-gray-500">
                                                    <?= $i ?>
                                                </td>
                                                <td class="px-6 py-4">
                                                    <div class="text-sm text-gray-900">
                                                        <p>{{auth()->user()['name']}}</p>
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4">
                                                    <div class="text-sm text-gray-500">{{$user_device->status}}</div>
                                                </td>
                                                <td class="px-6 py-4 text-sm text-gray-500">
                                                    {{$user_device->deskripsi}}
                                                </td>
                                                <td class="px-6 py-4 text-sm text-gray-500">
                                                    {{$user_device->merk}}
                                                </td>
                                                <td class="px-6 py-4 text-sm text-gray-500">
                                                    {{$user_device->mac_address}}
                                                </td>
                                                <td class="px-6 py-4 text-sm text-gray-500">
                                                    {{$user_device->tgl_register}}
                                                </td>
                                                <td class="px-6 py-4 text-sm text-gray-500">
                                                    <?php /*$date = date('yyyy-mm-dd');
                                                        echo $date;
                                                        $diff_date = $date - $user_device->tgl_register;
                                                        echo $diff_date. " days";*/
                                                        //$date_diff($date1, $date2);
                                                        $date1 = date('Y-m-d H:i:s');
                                                        $date1 = new DateTime($date1);
                                                        $date2 = new DateTime($user_device->tgl_register);
                                                        $interval = date_diff($date1, $date2);
                                                        echo $interval->format("%d hari")
                                                    ?>
                                                </td>
                                            </tr>
                                        </tbody>
                                        <?php $i++; ?>
                                        @endforeach
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    </p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
