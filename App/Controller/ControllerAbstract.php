<?php


namespace App\Controller;


use App\Http\Request;
use App\Http\Response;
use App\Http\ResponseBody\JSONBody;
use App\Http\ResponseBody\TextBody;

abstract class ControllerAbstract
{
    /**
     * @var \Smarty
     */
    private $smarty;

    /**
     * @var Response
     */
    protected $response;

    /**
     * @var Request
     */
    protected $request;

    /**
     * @var array
     */
    protected $shared_data = [];

    public function __construct(\Smarty $smarty, Request $request, Response $response)
    {
        $this->smarty = $smarty;
        $this->request = $request;
        $this->response = $response;
    }

    protected function render(string $template_name, array $params)
    {
        foreach ($this->shared_data as $key => &$value) {
            if (is_scalar($value)) {
                $this->smarty->assign($key, $value);
            } else {
                $this->smarty->assign_by_ref($key, $value);
            }
        }

        foreach ($params as $key => &$value) {
            if (is_scalar($value)) {
                $this->smarty->assign($key, $value);
            } else {
                $this->smarty->assign_by_ref($key, $value);
            }
        }

        $body = new TextBody($this->smarty->fetch($template_name));

        $this->response->setBody($body);

        return $this->response;

//        $this->smarty->display($template_name);
    }

    protected function redirect(string $url) {
        $this->response->redirect($url);
        return $this->response;
    }

    public function addSharedData(string $key, $value) {
        $this->shared_data[$key] = $value;
    }

    protected function getJsonResponse(array $params) {
        $body = new JSONBody($params);
        $this->response->setBody();

        $this->response->setHeaders('Content-type', 'application/json');

        return $this->response;
    }

}