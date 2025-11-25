<?php

return [

    'accepted'             => ':attribute harus diterima.',
    'active_url'           => ':attribute bukan URL yang valid.',
    'after'                => ':attribute harus tanggal setelah :date.',
    'alpha'                => ':attribute hanya boleh berisi huruf.',
    'alpha_dash'           => ':attribute hanya boleh berisi huruf, angka, dan tanda minus.',
    'alpha_num'            => ':attribute hanya boleh berisi huruf dan angka.',
    'array'                => ':attribute harus berupa array.',
    'before'               => ':attribute harus tanggal sebelum :date.',
    'between'              => [
        'numeric' => ':attribute harus bernilai antara :min dan :max.',
        'file'    => ':attribute harus berukuran antara :min dan :max kilobytes.',
        'string'  => ':attribute harus terdiri dari :min sampai :max karakter.',
        'array'   => ':attribute harus memiliki antara :min dan :max item.',
    ],
    'confirmed'            => ':attribute tidak cocok dengan konfirmasi.',
    'date'                 => ':attribute bukan tanggal yang valid.',
    'email'                => ':attribute harus berupa alamat email yang valid.',
    'filled'               => ':attribute wajib diisi.',
    'exists'               => ':attribute yang dipilih tidak valid.',
    'image'                => ':attribute harus berupa gambar.',
    'in'                   => ':attribute yang dipilih tidak valid.',
    'integer'              => ':attribute harus berupa angka.',
    'max'                  => [
        'numeric' => ':attribute tidak boleh lebih dari :max.',
        'file'    => ':attribute tidak boleh lebih dari :max kilobytes.',
        'string'  => ':attribute tidak boleh lebih dari :max karakter.',
        'array'   => ':attribute tidak boleh lebih dari :max item.',
    ],
    'min'                  => [
        'numeric' => ':attribute minimal :min.',
        'file'    => ':attribute minimal :min kilobytes.',
        'string'  => ':attribute minimal :min karakter.',
        'array'   => ':attribute minimal memiliki :min item.',
    ],
    'not_in'               => ':attribute tidak valid.',
    'numeric'              => ':attribute harus berupa angka.',
    'required'             => ':attribute wajib diisi.',
    'required_if'          => ':attribute wajib diisi bila :other adalah :value.',
    'same'                 => ':attribute dan :other harus sama.',
    'size'                 => [
        'numeric' => ':attribute harus berukuran :size.',
        'file'    => ':attribute harus berukuran :size kilobytes.',
        'string'  => ':attribute harus berjumlah :size karakter.',
        'array'   => ':attribute harus berisi :size item.',
    ],

    'attributes' => [
        'password' => 'Password',
        'password_confirmation' => 'Konfirmasi password',
    ],

];
