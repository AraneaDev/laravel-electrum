<?php

namespace AraneaDev\Electrum\App\Http\Controllers\Api;

use Illuminate\Http\Request;
use AraneaDev\Electrum\Electrum;
use App\Http\Controllers\Controller;

/**
 * Class RequestsController.
 */
class RequestsController extends Controller
{
    /** @var Electrum */
    protected $electrum;

    /**
     * RequestsController constructor.
     *
     * @param Electrum $electrum
     */
    public function __construct(Electrum $electrum)
    {
        $this->middleware(config('electrum.web_interface.middleware', ['web', 'auth']));
        $this->electrum = $electrum;
    }

    /**
     * Show all payment requests.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return response()->json($this->electrum->getRequests());
    }

    /**
     * Show a payment request.
     *
     * @param $address
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($address)
    {
        return response()->json($this->electrum->getRequest($address));
    }

    /**
     * Create a payment request.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(Request $request)
    {
        $request->validate([
            'amount'  => 'required|numeric|min:0',
            'expires' => 'required|integer|min:0',
            'memo'    => 'nullable',
        ]);

        return response()->json($this->electrum->createRequest(
            $request->get('amount'),
            $request->get('memo'),
            $request->get('expires')
        ));
    }

    /**
     * Remove a payment request.
     *
     * @param $address
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($address)
    {
        return response()->json($this->electrum->clearRequest($address));
    }

    /**
     * Remove all payment requests.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function clear()
    {
        return response()->json($this->electrum->clearRequests());
    }
}
