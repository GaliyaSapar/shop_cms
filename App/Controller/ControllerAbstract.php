<?php


namespace App\Controller;


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
    private $response;

    public function __construct(\Smarty $smarty, Response $response)
    {
        $this->smarty = $smarty;
        $this->response = $response;
    }

    protected function render(string $template_name, array $params)
    {
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

    protected function getJsonResponse(array $params) {
        $body = new JSONBody($params);
        $this->response->setBody();

        $this->response->setHeaders('Content-type', 'application/json');

        return $this->response;
    }

}