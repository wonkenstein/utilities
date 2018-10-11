php artisan tinker
$user = new User;
$user->username = 'admin';
$user->name = 'admin';
$user->password = Hash::make('password');
$user->save();

