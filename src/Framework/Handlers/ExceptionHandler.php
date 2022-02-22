<?php

namespace Shiblati\Framework\Handlers;

use Shiblati\Framework\Container;

/**
 * Class ExceptionHandler
 */
class ExceptionHandler
{
    /** @var mixed $log */
    protected mixed $log;

    /** @var mixed $view */
    protected mixed $view;

    /** @var mixed $router */
    protected mixed $router;

    const HTTP_NOTFOUND = 404;

    /**
     * ExceptionHandler constructor.
     *
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->log = $container['log'];
        $this->view = $container['view'];
        $router = $container['router'];

        $router->respond(function () use ($router) {
            $router->onHttpError(function ($code, $router) {
                if (!getenv('APP_DEBUG')) {
                    switch ($code) {
                        case self::HTTP_NOTFOUND:
                            $router->response()->body($this->view->render('error/404.twig'));
                            break;
                        default:
                            $router->response()->body($this->view->render('error/500.twig'));
                    }
                } else {
                    $router->response()->body($router->response()->status());
                }
            });
        });

        set_exception_handler([$this, 'handle']);
    }

    /**
     * Handle exception.
     *
     * @param $exception
     * @return void
     */
    public function handle($exception): void
    {
        $this->log->error($exception->getMessage());
        echo $this->view->render('error/500.twig', [
            'debug' => getenv('APP_DEBUG'),
            'error' => $exception->getMessage(),
            'trace' => ltrim($exception->getPrevious()->getTraceAsString())
        ]);
    }
}