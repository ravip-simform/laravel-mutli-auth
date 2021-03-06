## Laravel 8 Multiauth

1. Install Laravel using composer command
   composer create-project laravel/laravel mauth
2. composer require laravel/ui
3. php artisan ui bootstrap
4. npm install
5. npm run dev
6. php artisan ui:auth
7. create database and configure .env
9. php artisan migrate

    => Now default login and register should work.

10. create admins table for admin authentication by using migration command: php artisan make:migration create_admins_table and copying same schema defination of user.add admin record using following command: 
```
	php artisan tinker
	$admin = new App\Models\Admin;
	$admin->name = "Admin";
	$admin->email = "admin@admin.com";
	$admin->password = Has:make("admin");
	$admin->save();
```

11. Now create model called Admin for admin login, make sure it should be authenticable.

12. For Admin Login we will create seperate guards located in given location:
    {yourRootDirectory}\config\auth.php
    php artisan make:model Admin	

13. Now create controller called AdminController using the following artisan command.
    php artisan make:controller AdminController

14. Now define routes for admin in web.php file
    {yourRootDirectory}\routes\web.php

```
    --------For Frontend User--------
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::post('/user/logout', [App\Http\Controllers\Auth\LoginController::class, 'userLogout'])->name('user.logout');
    --------For Frontend User--------

    --------For Admin--------
    Route::group(["prefix"=>"admin"],function(){
        Route::group(["middleware"=>"admin.guest"],function(){
            Route::view("/login","admin.login")->name("admin.login");
            Route::post("/login",[AdminController::class,"authenticate"])->name("admin.authenticate");
        });

        Route::group(["middleware"=>"admin.auth"],function(){
            Route::get("/dashboard",[DashboardController::class,"dashboard"])->name("admin.dashboard");
            Route::post("/logout",[AdminController::class,"logout"])->name("admin.logout");
        });
    });
    --------For Admin--------
```

15. We will create "AdminAuthenticate" middleware, where we will override "authenticate" method. 

16. We will create "AdminRedirectIfAuthenticated" middleware, where we will define, the redirection of user, if he is already logged-in.

17. We also need to register these middleware in Kernal.php file.     

18. We will create a login view for admin login form and it would be connected with a login route.    
	    
19. Now we will define a custom authenticate method in AdminController for admin login, 
    called "authenticate". This method will login admin user.
	
20. Now we will create a logout method in AdminController. And we also have to create a route 
    for admin logout.
    ```
    Route::get('/logout', [App\Http\Controllers\AdminController::class, 'logout'])->name('admin.logout');
    ```
    
21. In Middleware/AdminRedirectIfAuthenticated.php
```
	public function handle(Request $request, Closure $next)
    	{
		if (Auth::guard("admin")->check()) {
		    return redirect()->route("admin.dashboard");
		}

		return $next($request);
    	}
```

22. In Middleware/RedirectIfAuthenticated.php
```
	public function handle(Request $request, Closure $next, ...$guards)
    	{

		if (Auth::guard("web")->check()) {
		    return redirect()->route("home");
		}

		return $next($request);
   	}
```
