<?php

namespace App\Providers;

use Illuminate\Http\Request;
use App\Actions\Fortify\MyLoginUser;
use App\Actions\Jetstream\DeleteUser;
use Illuminate\Support\ServiceProvider;
use Laravel\Jetstream\Jetstream;

class JetstreamServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(Request $request)
    {
        if(
            $request->getPathInfo() === "/login" 
            && $request->isMethod('post')  
            && $request->has('identification')
            && $request->has('password')
        ) {
            $user = MyLoginUser::validIdentification($request->all());
            if($user){
                $request->request->add(['email' => $user->email ]);
            }
        }

        $this->configurePermissions();

        Jetstream::deleteUsersUsing(DeleteUser::class);
    }

    /**
     * Configure the permissions that are available within the application.
     *
     * @return void
     */
    protected function configurePermissions()
    {
        Jetstream::defaultApiTokenPermissions(['read']);

        Jetstream::permissions([
            'create',
            'read',
            'update',
            'delete',
        ]);
    }
}
