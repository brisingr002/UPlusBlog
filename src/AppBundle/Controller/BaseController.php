<?php
/**
 * Created by PhpStorm.
 * User: michael
 * Date: 14/06/2018
 * Time: 14:46
 */

namespace AppBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class BaseController extends Controller
{
    public function redirectToReferer() {
        return $this->redirect(
            $this->get('request')
                ->headers
                ->get('referer')
        );
    }
}