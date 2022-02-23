<?php

namespace Shiblati\Framework;

use Shiblati\Framework\Models\Session;

/**
 * Abstract base controller class.
 */
abstract class Controller
{
    /** @var mixed $log */
    protected mixed $log;

    /** @var mixed $db */
    protected mixed $db;

    /** @var mixed $router */
    protected mixed $router;

    /** @var mixed $view */
    protected mixed $view;

    /** @var array $data */
    public array $data = [];

    /** @var Session $blog */
    protected Session $session;

    /**
     * Abstract Controller constructor.
     *
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->log = $container['log'];
        $this->db = $container['db'];
        $this->router = $container['router'];
        $this->view = $container['view'];
        $this->session = $container['session'];

        $this->data['title'] = env("APP_NAME");

        $this->time();
    }

    /**
     * Set data to pass to the view.
     *
     * @param array $data
     * @return self
     */
    protected function setViewData(array $data = []): Controller
    {
        if (is_array($data)) {
            foreach ($data as $key => $val) {
                if ($key === 'title' && $val !== '') {
                    $val = $val . ' | ' . $this->data['title'];
                }
                $this->data[$key] = $val;
            }
        }

        return $this;
    }

    /**
     * Renders templates with view data.
     *
     * @param string $template
     * @return mixed
     */
    protected function render(string $template): mixed
    {
        $template = $this->getTemplate($template);

        return $this->view->render($template, $this->data);
    }

    /**
     * Get template.
     *
     * @param string $template
     * @return string
     */
    private function getTemplate(string $template): string
    {
        if (!str_contains($template, '.twig')) {
            return $template.'.twig';
        }

        return $template;
    }

    /**
     * Check for session expiry.
     *
     * @return void
     */
    protected function time() {
        $session = $this->session->get();
        if ($session && isset($session['id'])) {
            $time = time() - strtotime($session['created_at']);

            if ($time > Session::TTL) {
                $this->session->destroy();
                $session = null;
            }
        }
    }
}
