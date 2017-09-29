<?php

namespace AraneaDev\Electrum\App;

use App\Http\Controllers\Controller;

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
        return view('electrum::index');
    }
}
