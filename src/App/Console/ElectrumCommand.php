<?php

namespace AraneaDev\Electrum\App\Console;

use AraneaDev\Electrum\Electrum;
use Illuminate\Console\Command;

/**
 * Class ElectrumCommand.
 */
class ElectrumCommand extends Command
{
    /** @var Electrum */
    protected $electrum;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'electrum {method} {--address=} {--txid=} {--key=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Wrapper for Electrum';

    /**
     * ElectrumCommand constructor.
     *
     * @param  Electrum  $electrum
     */
    public function __construct(Electrum $electrum)
    {
        $this->electrum = $electrum;

        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $method = $this->argument('method');

        if (! method_exists($this, $method)) {
            $this->info(json_encode($this->electrum->sendRequest($method, $this->get_params())));
        } else {
            $this->$method();
        }
    }

    /**
     * Returns package author.
     *
     * @return void
     */
    public function author()
    {
        $this->info('Electrum for Laravel was written by Tim Schipper <info@aranea-development.nl>');
    }

    /**
     * @return array
     */
    public function get_params()
    {
        $params = [];

        if ($this->option('address')) {
            $params['address'] = $this->option('address');
        }

        if ($this->option('txid')) {
            $params['txid'] = $this->option('txid');
        }

        if ($this->option('key')) {
            $params['key'] = $this->option('key');
        }

        return $params;
    }
}
