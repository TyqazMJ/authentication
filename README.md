Login N Register Validation
1. Create Form Req Classes
- command :
php artisan make:request RegisterRequest
php artisan make:request LoginRequest

2. Add Validation Rules with Regex
(app/Http/Requests/RegisterRequest.php)
- add these codes :
public function rules(): array
{
    return [
        'name' => ['required', 'regex:/^[A-Za-z ]+$/'],
        'email' => ['required', 'email'],
        'password' => ['required', 'min:6', 'confirmed'], // 'confirmed' looks for password_confirmation field
    ];
}

(app/Http/Requests/LoginRequest.php)
- add these codes :
public function rules(): array
{
    return [
        'email' => ['required', 'email'],
        'password' => ['required'],
    ];
}

3. Update the Controllers to use these request
(app/Http/Controllers/Auth/RegisterController.php)
- import the RegisterRequest class at the top of the controller :
use App\Http\Requests\RegisterRequest;

- also need to update the register() method in order to accept RegisterRequestpublic function register(RegisterRequest : $request)
{
    // Handle registration logic
    $user = $this->create($request->validated());
    
    // Log the user in after registration
    $this->guard()->login($user);

    return redirect($this->redirectPath());
}

(app/Http/Controllers/Auth/LoginController.php)
- import the LoginRequest class at the top of the controller :
use App\Http\Requests\LoginRequest;

- update the login() method to accept the LoginRequest :
public function login(LoginRequest $request)
{
    $credentials = $request->only('email', 'password');

    if (Auth::attempt($credentials)) {
        return redirect()->intended($this->redirectTo);
    }

    return back()->withErrors([
        'email' => 'The provided credentials do not match our records.',
    ])->withInput();
}

4. Update Blade Views to Display Errors - only for register cause login got already
(register.blade.php):
<!-- Name Field -->
<input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
@error('name')
    <div class="text-danger">{{ $message }}</div>
@enderror

<!-- Email Field -->
<input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
@error('email')
    <div class="text-danger">{{ $message }}</div>
@enderror

<!-- Password Field -->
<input type="password" name="password" class="form-control" required>
@error('password')
    <div class="text-danger">{{ $message }}</div>
@enderror

Profile Page
1. Update the Database which is the users table:
php artisan make:migration add_profile_fields_to_users_table --table=users

- then add these code in (database/migrations/..._add_profile_fields_to_users_table.php):
public function up()
{
    Schema::table('users', function (Blueprint $table) {
        $table->string('nickname')->nullable();
        $table->string('avatar')->nullable();
        $table->string('phone')->nullable();
        $table->string('city')->nullable();
    });
}

public function down()
{
    Schema::table('users', function (Blueprint $table) {
        $table->dropColumn(['nickname', 'avatar', 'phone', 'city']);
    });
}

- then run this command:
php artisan migrate

2. Update the user model
(app/Models/User.php), add new fields in the $fillable array:
protected $fillable = [
    'name',
    'nickname',
    'email',
    'password',
    'avatar',
    'phone',
    'city',
];

3. Create Profile Controller & Route
php artisan make:controller ProfileController

- then add the routes in (routes/web.php):
Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/delete', [ProfileController::class, 'destroy'])->name('profile.delete');
});


4. Build the Profile Form
- Create (resources/views/profile.blade.php) with fields like these:
<form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
    @csrf

    <!-- Nickname -->
    <input type="text" name="nickname" value="{{ old('nickname', auth()->user()->nickname) }}" required>

    <!-- Email -->
    <input type="email" name="email" value="{{ old('email', auth()->user()->email) }}" required>

    <!-- Password -->
    <input type="password" name="password" placeholder="New Password (optional)">

    <!-- Phone -->
    <input type="text" name="phone" value="{{ old('phone', auth()->user()->phone) }}">

    <!-- City -->
    <input type="text" name="city" value="{{ old('city', auth()->user()->city) }}">

    <!-- Avatar -->
    <input type="file" name="avatar">

    <button type="submit">Update Profile</button>
</form>

<form method="POST" action="{{ route('profile.delete') }}">
    @csrf
    <button type="submit" onclick="return confirm('Are you sure you want to delete your account?')">Delete Account</button>
</form>


5. Add Logic in ProfileController
- in the (ProfileController.php) add these:
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

public function edit()
{
    return view('profile');
}

public function update(Request $request)
{
    $user = auth()->user();

    $data = $request->validate([
        'nickname' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email,' . $user->id,
        'password' => 'nullable|min:8',
        'phone' => 'nullable|string',
        'city' => 'nullable|string',
        'avatar' => 'nullable|image|max:2048',
    ]);

    if ($request->hasFile('avatar')) {
        $avatarPath = $request->file('avatar')->store('avatars', 'public');
        $data['avatar'] = $avatarPath;
    }

    if (!empty($data['password'])) {
        $data['password'] = Hash::make($data['password']);
    } else {
        unset($data['password']);
    }

    $user->update($data);

    return redirect()->back()->with('success', 'Profile updated successfully!');
}

public function destroy()
{
    $user = auth()->user();
    $user->delete();

    return redirect('/')->with('message', 'Account deleted');
}


6. Got error 
- it says that the Class "App\Http\Controllers\Auth\Auth" not found
- so now we need to fix it...
- i go to my (LoginController.php) add this import line:
use Illuminate\Support\Facades\Auth;

so it should look like this,
<?php

namespace App\Http\Controllers\Auth;

use App\Http\Requests\LoginRequest;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth; // ✅ THIS LINE IS MISSING


7. Show success message in (resources/views/profile.blade.php)
- since in my ProfileController@update already redirects back with a session message:
return redirect()->back()->with('success', 'Profile updated successfully!');
- now i just need to display in profile.blade.php:
@if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

8. The profile page didn't appear
- this is because i did not define the delete page properly:
Route::post('/profile/delete', [ProfileController::class, 'destroy'])->name('profile.destroy');

9. The profile picture didn't appear at the top of the page (profile.blade.php)
- so what i did is add new line after <div class="container">:
@if (auth()->user()->avatar)
    <div class="text-center mb-4">
        <img src="{{ asset('storage/' . auth()->user()->avatar) }}" alt="Avatar" class="rounded-circle shadow" width="150" height="150">
        <h4 class="mt-2">{{ auth()->user()->nickname }}</h4>
    </div>
@endif


9. The picture in the profile cannot be seen
- so what i did run command  to create the symbolic link between (public/storage) and (storage/app/public):
php artisan storage:link
