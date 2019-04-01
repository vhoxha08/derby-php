<?php
namespace App\Controllers;

use Phalcon\Flash\Session as Flash;
use Phalcon\Http\Response;
use Phalcon\Logger;
use Phalcon\Mvc\Controller;
use Phalcon\Config;
use Phalcon\Mvc\View\Engine\Volt;
use Phalcon\Mvc\View\Simple;

/**
 * Class BaseController
 * @author Adeyemi Olaoye <yemi@cottacush.com>
 * @package App\Controllers
 * @property Response $response
 * @property Config $config
 * @property Simple $view
 * @property Volt $volt
 * @property Flash $flash
 * @property Logger\Adapter\File $logger
 */
abstract class BaseController extends Controller
{
    /**
     * Check if payload is empty
     * @author Tega Oghenekohwo <tega@cottacush.com>
     * @return bool
     */
    public function isPayloadEmpty()
    {
        $postData = $this->getPayload();
        return empty((array)$postData);
    }

    /**
     * Get payload of current request
     * @author Tega Oghenekohwo <tega@cottacush.com>
     * @return array|bool|\stdClass
     */
    public function getPayload()
    {
        return $this->request->getJsonRawBody();
    }
}
