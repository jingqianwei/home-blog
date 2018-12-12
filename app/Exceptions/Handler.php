<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

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
     * Report or log an exception.
     *
     * @param  \Exception $exception
     * @return void
     * @throws Exception
     */
    public function report(Exception $exception)
    {
        if(empty(env('APP_DEBUG')) && $exception->getMessage() && $exception->getCode() != -1) {
            if ('cli' !== PHP_SAPI) {
                ob_start();
                dump(\Request::server());
                $raw = ob_get_contents();
                ob_end_clean();

                \Mail::raw('', function ($m) use ($exception, $raw) {
                    $exceptionHandler = new \Symfony\Component\Debug\ExceptionHandler();
                    $content = $exceptionHandler->getHtml($exception);

                    $m->setBody($content . $raw, 'text/html');

                    if (config('app.name')) {
                        $errName = config('app.name') . '_' . config('app.env');
                    } else {
                        $errName = config('app.env');
                    }
                    $m->subject('System Error--->' . $errName);
                    $m->to('able.yang@etocrm.com'); // 发送人
                    $m->cc('xin.li@etocrm.com'); //抄送人
                });
            }
        }

        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        return parent::render($request, $exception);
    }
}
