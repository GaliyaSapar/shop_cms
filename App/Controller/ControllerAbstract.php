<?php


namespace App\Controller;


abstract class ControllerAbstract
{
    /**
     * @var \Smarty
     */
    private $smarty;

    public function __construct(\Smarty $smarty)
    {
        $this->smarty = $smarty;
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

        $this->smarty->display($template_name);
    }

}