<?php

namespace AraneaDev\Electrum\App\Http\Controllers;

use App\Http\Controllers\Controller;
use AraneaDev\Electrum\Electrum;

/**
 * Class ElectrumController.
 */
class IndexController extends Controller
{
    /**
     * ElectrumController constructor.
     */
    public function __construct()
    {
        $this->middleware(config('electrum.web_interface.middleware', ['web', 'auth']));
    }

    /**
     * Show the Electrum interface.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function __invoke()
    {
//        $electrum = new Electrum(123);
//        $electrum->createRequest(0.1,'hoi');
        return view('electrum::index');
    }
}
