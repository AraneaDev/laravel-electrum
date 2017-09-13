<?php

namespace AraneaDev\Electrum\App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use AraneaDev\Electrum\Electrum;

/**
 * Class StatusController.
 */
class StatusController extends Controller
{
    /** @var Electrum */
    protected $electrum;

    /**
     * StatusController constructor.
     *
     * @param Electrum $electrum
     */
    public function __construct(Electrum $electrum)
    {
        $this->middleware(config('electrum.web_interface.middleware', ['web', 'auth']));
        $this->electrum = $electrum;
    }

    /**
     * Get wallet status and ticker data
     * TODO: Make source configurable.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke()
    {
        return response()->json([
            'version' => $this->electrum->getVersion(),
            'balance' => $this->electrum->getBalance(),
            'is_sync' => $this->electrum->isSynchronized(),
            'ticker'  => json_decode(file_get_contents('https://blockchain.info/ticker')),
        ]);
    }
}
