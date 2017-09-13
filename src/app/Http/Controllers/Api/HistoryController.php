<?php

namespace AraneaDev\Electrum\App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use AraneaDev\Electrum\Electrum;

/**
 * Class HistoryController.
 */
class HistoryController extends Controller
{
    /** @var Electrum */
    protected $electrum;

    /**
     * HistoryController constructor.
     *
     * @param Electrum $electrum
     */
    public function __construct(Electrum $electrum)
    {
        $this->electrum = $electrum;
    }

    /**
     * Get the wallet history.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        return response()->json($this->electrum->getHistory());
    }

    /**
     * Get the history for a specific address.
     *
     * @param $address
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($address)
    {
        return response()->json($this->electrum->getAddressHistory($address));
    }

    /**
     * Get transaction details.
     *
     * @param $txid
     *
     * @return object
     */
    public function details($txid)
    {
        return response()->json($this->electrum->getTransaction($txid));
    }
}
