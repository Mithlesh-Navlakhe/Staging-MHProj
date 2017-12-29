<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Auth;

class Permision
{
    protected $router;
    protected $permision_list = [
        'Admin' => ['logout', 'index'],
        'Supervisor' => ['logout', 'index'],
        'Lead' => ['logout', 'index'],
        'Developer' => ['logout', 'index'],
        'QA_Engineer' => ['logout', 'index'],
        'HR_Manager' => ['logout', 'index']
    ];

    public function __construct(Router $router)
    {
        $this->router = $router;
    }

    public function handle($request,  Closure $next)
    {
        if( Auth::guest() ) {
            return redirect('/login');
        };

        $_SESSION['user_type'] = Auth::user()['original']['employe'];
        $action_name = explode('@', $this->router->getRoutes()->match($request)->getActionName());

        return $next($request);

        if(Auth::check() == false ) {
           return redirect('/login');
        } elseif (in_array($action_name[1], $this->permision_list[Auth::user()['original']['employe']])) {
            return $next($request);
        } else {
            return redirect()->back();
        }
    }
}