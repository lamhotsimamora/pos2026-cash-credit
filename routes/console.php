<?php

use App\Models\Category;
use App\Models\Customers;
use App\Models\Freeze;
use App\Models\Products;
use App\Models\Profile;
use App\Models\Satuans;
use App\Models\Settings;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

// Artisan::command('inspire', function () {
//     $this->comment(Inspiring::quote());
// })->purpose('Display an inspiring quote');

Artisan::command('seed', function () {
    $password = md5('Qwezxc123');
    DB::insert('insert into admins (username, password) values (?, ?)', ['admin', $password]);

    Satuans::create([
        'satuan' => 'pcs'
    ]);

    Profile::create([
        'name' => 'Toko Terang Bintang',
        'address'=>"Jln Lintas Sumatera Bangko",
        'hp' =>'081399453242',
        'email'=>'pdrtechnology93@gmail.com'
    ]); 

    Customers::create([
        'name' => 'Lamhot Simamora',
        'phone' => '081399453242',
        'address' => 'Sidodadi'
    ]);

    Settings::create([
        'ppn' => '0'
    ]);

    Freeze::create([
        'is_frozen' => false
    ]);
})->purpose('');

