<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpFoundation\Exception\SuspiciousOperationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Support\Facades\App;
use Throwable;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response as HttpResponse;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    protected function prepareException(Throwable $e)
    {
        if(App::environment('local'))
        {
            return $e;
        }

        if ($e instanceof ModelNotFoundException) {
            $e = new NotFoundHttpException($e->getMessage(), $e);
        } elseif ($e instanceof AuthorizationException) {
            $e = new AccessDeniedHttpException($e->getMessage(), $e);
        } elseif ($e instanceof TokenMismatchException) {
            $e = new HttpException(419, $e->getMessage(), $e);
        } elseif ($e instanceof SuspiciousOperationException) {
            $e = new NotFoundHttpException('Bad hostname provided.', $e);
        }

        return $e;
    }

    public function render($request, Throwable $e)
    {
        $response = parent::render($request, $e);

        /**
         * @var mixed $response 
         * */

        if (App::environment('production') && in_array($response->status(), [403, 404, 419, 500, 503]))
        {   
            return $this->returnErrorForProduction($request, $response);
        }         

        return $response;
    }


    public function returnErrorForProduction(Request $request, Response $response)
    {   
        /**
         * @var mixed $response 
         * */

        if(Auth::check())
        { 
            return $this->returnErrorForAuthenticatedUsers($response);
        }

        if (in_array($response->status(), [503, 404])) {
           
            return Inertia::render('Error', ['status' => $response->status()])
                ->toResponse($request)
                ->setStatusCode($response->status());
        }
        else if($response->status() === 419)
        {
            return back()->with('error', __('response.419_message'));
        }
        
        return back()->with('error', __('general.unkown_error'));
    }

    public function returnErrorForAuthenticatedUsers(Response $response)
    {
        /**
         * @var mixed $response 
         * */

        if ($response->status() === 403) 
        {
            return back()->with('error', __('response.403_message'));
        }
        else if ($response->status() === 404) 
        {
            return back()->with('error', __('response.404_message'));
        }
        else if($response->status() === 419)
        {
            return back()->with('error', __('response.419_message'));
        }
        else if($response->status() === 500)
        {
            return back()->with('error', __('response.500_message'));
        }
        else if($response->status() === 503)
        {
            return back()->with('error', __('response.503_message'));
        }
        return back()->with('error', __('general.unkown_error'));
    }
}
