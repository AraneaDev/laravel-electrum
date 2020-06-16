<template>
    <div class="electrum">
        <div class="panel panel-default" v-if="is_loaded">
            <div class="panel-body">
                <div class="pull-right">
                    <div class="btn-group">
                        <a class="btn btn-sm btn-default btn-block dropdown-toggle" data-toggle="dropdown" href="#">
                            {{ pair }}
                            <small v-if="ticker[pair] !== undefined">({{ ticker[pair].symbol }})</small>
                            <span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-right">
                            <li v-for="currency in currencies">
                                <a @click="pair = currency;in_fiat = receive.amount * ticker[pair].last">
                                    {{ currency }}
                                    <small>({{ ticker[currency].symbol }})</small>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>

                <ul class="nav nav-tabs">
                    <li :class="{active: active === '#history'}">
                        <a data-toggle="tab" href="#history" @click="setHash('history')">History</a>
                    </li>
                    <li :class="{active: active === '#requests'}">
                        <a data-toggle="tab" href="#requests" @click="setHash('requests')">Requests</a>
                    </li>
                    <li :class="{active: active === '#receive'}">
                        <a data-toggle="tab" href="#receive" @click="setHash('receive')">Receive</a>
                    </li>
                </ul>

                <div class="tab-content">
                    <div id="history" class="tab-pane fade" :class="{'in active': active === '#history'}">
                        <div v-if="history.length">
                            <div class="table-header"></div>
                            <div class="table-responsive">
                                <table class="table table-condensed table-striped">
                                    <thead>
                                    <tr>
                                        <th class="no-wrap">
                                            <div>#</div>
                                        </th>
                                        <th class="no-wrap">
                                            <div>Date</div>
                                        </th>
                                        <th class="remaining">
                                            <div>Transaction</div>
                                        </th>
                                        <th class="no-wrap">
                                            <div>Amount</div>
                                        </th>
                                        <th class="no-wrap">
                                            <div>Balance</div>
                                        </th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr v-for="transaction in history" @click="getTransactionDetails(transaction)"
                                        class="clickable">
                                        <td class="no-wrap">
                                    <span class="glyphicon"
                                          :class="{'glyphicon-ok text-success': transaction.confirmations >= 6}">
                                    </span>
                                        </td>
                                        <td class="no-wrap" v-text="transaction.date"></td>
                                        <td class="no-remaining" v-text="transaction.txid"></td>
                                        <td class="no-wrap" v-text="transaction.value"
                                            :class="{'text-danger': !transaction.incoming}"></td>
                                        <td class="no-wrap" v-text="transaction.balance"></td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="mt-8" v-else>
                            <strong class="mt-8">No history available</strong>
                        </div>
                    </div>

                    <div id="requests" class="tab-pane fade" :class="{'in active': active === '#requests'}">
                        <div v-if="requests.length">
                            <div class="table-header"></div>
                            <div class="table-responsive">
                                <table class="table table-condensed table-striped">
                                    <thead>
                                    <tr>
                                        <th class="no-wrap">
                                            <div>Status</div>
                                        </th>
                                        <th class="no-wrap">
                                            <div>Date</div>
                                        </th>
                                        <th class="no-wrap">
                                            <div>Address</div>
                                        </th>
                                        <th class="remaining">
                                            <div>Memo</div>
                                        </th>
                                        <th class="no-wrap">
                                            <div>Amount</div>
                                        </th>
                                        <th class="no-wrap">
                                            <div>&nbsp;</div>
                                        </th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr v-for="request in requests" class="clickable" @click="getRequestDetails(request)">
                                        <td class="no-wrap" v-text="request.status"></td>
                                        <td class="no-wrap" v-text="request.date"></td>
                                        <td class="no-wrap" v-text="request.address"></td>
                                        <td class="remaining" v-text="request.memo"></td>
                                        <td class="no-wrap" v-text="request.amount_in_btc"></td>
                                        <td class="no-wrap">
                                            <button class="btn btn-xs btn-danger" @click.stop.prevent="removeRequest(request)">
                                                X
                                            </button>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="mt-8" v-else>
                            <strong class="mt-8">No requests available</strong>
                        </div>
                    </div>

                    <div id="receive" class="tab-pane fade" :class="{'in active': active === '#receive'}">
                        <div class="row mt-8">
                            <div class="col-md-9">
                                <div class="form-group">
                                    <label>Receiving address</label>
                                    <div class="input-group input-group-sm">
                                        <input type="text" v-model="receive.address" class="form-control address"
                                               disabled>
                                        <span class="input-group-btn">
                                            <button type="button" class="btn btn-default copy"
                                                    :data-clipboard-text="receive.address">Copy</button>
                                        </span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Memo</label>
                                    <input type="text" v-model="receive.memo" class="form-control input-sm"
                                           placeholder="Enter a memo here">
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Amount in BTC</label>
                                            <div class="input-group input-group-sm">
                                                <input type="text" v-model="receive.amount"
                                                       class="form-control input-sm">
                                                <span class="input-group-addon">
                                                    BTC
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Amount in {{ pair }}</label>
                                            <div class="input-group input-group-sm">
                                                <input type="text" v-model="in_fiat"
                                                       class="form-control input-sm">
                                                <span class="input-group-addon">
                                                    {{ pair }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label>Expires</label>
                                        <div class="form-group">
                                            <button type="button" class="btn btn-default btn-sm pull-right"
                                                    @click="addRequest"
                                                    :disabled="receive.amount === 0 || in_fiat === 0">
                                                <span class="glyphicon glyphicon-send"></span> Create request
                                            </button>
                                            <div class="btn-group">
                                                <a class="btn btn-sm btn-default btn-block dropdown-toggle"
                                                   data-toggle="dropdown" href="#">
                                                    {{ receive.expires ? receive.expires === 3600 ? '1 Hour' : receive.expires === 3600 * 24 ? '1 Day' : '1 Week' : 'Never'
                                                    }}
                                                    <span class="caret"></span>
                                                </a>
                                                <ul class="dropdown-menu">
                                                    <li><a @click="receive.expires = 0">Never</a></li>
                                                    <li><a @click="receive.expires = 3600">1 Hour</a></li>
                                                    <li><a @click="receive.expires = 3600 * 24">1 Day</a></li>
                                                    <li><a @click="receive.expires = 3600 * 24 * 7">1 Week</a></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="qr-code img-thumbnail img-responsive" style="margin-top:25px;">
                                    <qr-code
                                            :text="'bitcoin:' + receive.address + '?amount=' + receive.amount"></qr-code>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <div class="panel-footer">
                <div class="status pull-right">
                    Electrum {{ version }} | Synchronized: <span v-text="is_sync ? 'yes' : 'no'" :class="{
                    'text-success': is_sync,
                    'text-danger': !is_sync
                }"></span>
                </div>
                <div class="balance">
                    Balance: <span v-text="Number(Number(balance.confirmed).toFixed(8))"></span> BTC
                    <span v-if="balance.unconfirmed"> (+ <span
                            v-text="Number(Number(balance.unconfirmed).toFixed(8))"></span> BTC unconfirmed)</span>
                    <span> ({{( balance.confirmed * ticker[pair].last).toFixed(2) }} {{ pair }})</span>
                    <span> 1 BTC~{{ ticker[pair].last }} {{ pair }}</span>
                </div>
            </div>
        </div>

        <bootstrap-modal ref="tx_details" :needHeader="false" :needFooter="false" size="medium" v-if="transaction.txid">
            <div slot="body">
                <table class="table table-details table-condensed table-striped">
                    <tbody>
                    <tr>
                        <th class="no-wrap">Transaction:&nbsp;</th>
                        <td class="remaining" colspan="2">{{ transaction.txid }}</td>
                    </tr>
                    <tr>
                        <th class="no-wrap">Status:&nbsp;</th>
                        <td class="remaining">{{ transaction.confirmations }} confirmations</td>
                        <td class="qr-cell" rowspan="6">
                            <div class="qr-code img-thumbnail img-responsive">
                                <qr-code :text="transaction.txid"></qr-code>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <th class="no-wrap">Label:&nbsp;</th>
                        <td class="remaining">{{ transaction.label ? transaction.label : 'none' }}</td>
                    </tr>
                    <tr>
                        <th class="no-wrap">Block:&nbsp;</th>
                        <td class="remaining">{{ transaction.height }}</td>
                    </tr>
                    <tr>
                        <th class="no-wrap">Date:&nbsp;</th>
                        <td class="remaining">{{ transaction.date }}</td>
                    </tr>
                    <tr>
                        <th class="no-wrap">{{ transaction.incoming ? 'Received' : 'Sent' }}:&nbsp;</th>
                        <td class="remaining">
                            {{ transaction.value }}
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </bootstrap-modal>

        <bootstrap-modal ref="request_details" :needHeader="false" :needFooter="false" size="medium" v-if="request.id">
            <div slot="body">
                <table class="table table-details table-condensed table-striped">
                    <tbody>
                    <tr>
                        <th class="no-wrap">Address:&nbsp;</th>
                        <td class="remaining" colspan="2">{{ request.address }}</td>
                    </tr>
                    <tr>
                        <th class="no-wrap">Status:&nbsp;</th>
                        <td class="remaining">{{ request.status }}</td>
                        <td class="qr-cell" rowspan="6">
                            <div class="qr-code img-thumbnail img-responsive">
                                <qr-code :text="request.URI"></qr-code>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <th class="no-wrap">Memo:&nbsp;</th>
                        <td class="remaining">{{ request.memo ? request.memo : 'none' }}</td>
                    </tr>
                    <tr>
                        <th class="no-wrap">Date:&nbsp;</th>
                        <td class="remaining">{{ request.date }}</td>
                    </tr>
                    <tr>
                        <th class="no-wrap">Expires:&nbsp;</th>
                        <td class="remaining">{{ request.exp ? expires : 'never' }}</td>
                    </tr>
                    <tr>
                        <th class="no-wrap">Amount:&nbsp;</th>
                        <td class="remaining">
                            {{ request.amount_in_btc }} BTC
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </bootstrap-modal>
    </div>
</template>

<script>
    import _ from 'lodash';
    import axios from 'axios';
    import moment from 'moment';
    import Clipboard from 'clipboard';
    import qrCode from 'vue-qrcode-component';
    import bootstrapModal from 'vue2-bootstrap-modal';

    export default {
        props: ['prefix', 'currency'],
        components: {
            qrCode, bootstrapModal
        },
        data() {
            return {
                /** Loaded statuses */
                address_is_loaded: false,
                history_is_loaded: false,
                requests_are_loaded: false,
                status_is_loaded: false,
                ticker_is_loaded: false,

                /** Application state */
                active: '#requests',
                clipboard: false,
                is_sync: false,
                version: null,
                timer: false,
                pair: null,
                in_fiat: 0,
                ticker: {},
                balance: {
                    confirmed: 0,
                    unconfirmed: 0
                },

                /** Raw unprocessed responses */
                raw: {
                    requests: [],
                    history: []
                },

                /** Receive model */
                receive: {
                    address: null,
                    memo: null,
                    amount: 0,
                    expires: 0
                },

                /** Request model */
                request: {
                    URI: null,
                    address: null,
                    amount: 0,
                    amount_in_btc: 0,
                    date: null,
                    exp: null,
                    id: null,
                    memo: null,
                    status: null,
                    time: 0
                },

                /** Transaction model */
                transaction: {
                    complete: false,
                    confirmations: 0,
                    date: null,
                    final: false,
                    height: 0,
                    hex: null,
                    input_addresses: [],
                    label: null,
                    output_addresses: [],
                    timestamp: 0,
                    txid: null,
                    value: 0
                }
            }
        },
        watch: {
            /** Keep the BTC input formatted and update the fiat field on change */
            'receive.amount': function (val) {
                let cleaned = String(val).replace(/[^0-9.]/g, '');
                let parts = cleaned.split('.');

                if (parts.length > 1) {
                    cleaned = parts[0] + '.' + parts[1];
                }

                if (Number(cleaned) !== 0 && Number(cleaned) < 0.00000001) {
                    cleaned = Number(0.00000001).toFixed(8);
                }

                this.receive.amount = cleaned;
                this.in_fiat = Number(Number(cleaned * this.ticker[this.pair].last).toFixed(2));
            },
            /** Keep the fiat input formatted and update the BTC field on change */
            'in_fiat': function (val) {
                let cleaned = String(val).replace(/[^0-9.]/g, '');
                let parts = cleaned.split('.');

                if (parts.length > 1) {
                    cleaned = parts[0] + '.' + parts[1];
                }

                if (Number(cleaned) !== 0 && Number(cleaned) < 0.01) {
                    cleaned = 0.01;
                }

                this.in_fiat = cleaned;
                this.receive.amount = Number(Number(cleaned / this.ticker[this.pair].last).toFixed(8));
            },
        },
        computed: {
            /** Transaction history */
            history: function () {
              return this.raw.history.transactions
            },

            /** Transaction history */
            summary: function () {
                return this.raw.history.summary
            },


            /** Loaded status */
            is_loaded: function () {
                return this.address_is_loaded && this.history_is_loaded && this.requests_are_loaded && this.status_is_loaded;
            },

            /** Expiration for current request */
            expires: function () {
                return moment.unix(this.request.time + this.request.exp).fromNow();
            },

            /** Available currencies in the ticker */
            currencies: function () {
                return Object.keys(this.ticker);
            },

            /** Processed request */
            requests: function () {
                let vm = this,
                    requests = [];

                _.each(vm.raw.requests, function (o) {
                    o.amount_in_btc = Number(Number(o.amount / 100000000).toFixed(8));
                    o.date = moment.unix(o.time).format('YYYY-MM-DD HH:mm');
                    requests.push(o);
                });

                return requests;
            }
        },
        /**
         * Set active tab on creation
         */
        created() {
            if (!window.location.hash) {
                window.location.hash = this.active;
            } else {
                this.active = window.location.hash;
            }
        },
        /**
         * Initialize clipboard and load data
         */
        mounted() {
            if (Clipboard.isSupported()) {
                this.clipboard = new Clipboard('.btn.copy');
            }

            this.pair = this.currency;

            this.getAddress();
            this.getHistory();
            this.getRequests();
            this.getStatus();

            this.timer = setInterval(() => {
                this.getStatus();
            }, 60000);
        },
        methods: {
            /**
             * Add a new payment request
             */
            addRequest() {
                let vm = this;

                axios.post('/' + vm.prefix + '/api/requests', {
                    memo: vm.receive.memo,
                    amount: vm.receive.amount,
                    expires: vm.receive.expires
                }).then((response) => {
                    vm.raw.requests.unshift(response.data);
                    vm.setHash('requests');
                    vm.getAddress();
                }).catch((error) => {
                    console.error(error);
                });
            },

            /**
             * Get an unused address
             */
            getAddress() {
                let vm = this;

                vm.address_is_loaded = false;
                axios.get('/' + vm.prefix + '/api/addresses/unused').then((response) => {
                    Object.assign(vm.receive, {
                        address: response.data,
                        memo: null,
                        amount: 0,
                        expires: 0
                    });
                    vm.address_is_loaded = true;
                }).catch((error) => {
                    console.error(error);
                });
            },

            /**
             * Get wallet history
             */
            getHistory() {
                let vm = this;

                vm.history_is_loaded = false;
                axios.get('/' + vm.prefix + '/api/history').then((response) => {
                    Object.assign(vm.raw.history, response.data);
                    vm.history_is_loaded = true;
                }).catch((error) => {
                    console.error(error);
                });
            },

            /**
             * Get request details
             *
             * @param request
             */
            getRequestDetails(request) {
                let vm = this;

                vm.request = request;
                vm.$nextTick(function () {
                    vm.$refs.request_details.open();
                });
            },

            /**
             * Get all requests
             */
            getRequests() {
                let vm = this;

                vm.requests_are_loaded = false;
                axios.get('/' + vm.prefix + '/api/requests').then((response) => {
                    Object.assign(vm.raw.requests, response.data);
                    vm.requests_are_loaded = true;
                }).catch((error) => {
                    console.error(error);
                });
            },

            /**
             * Get wallet status
             */
            getStatus() {
                let vm = this;

                axios.get('/' + vm.prefix + '/api/status').then((response) => {
                    Object.assign(vm, response.data);
                    vm.status_is_loaded = true;
                }).catch((error) => {
                    console.error(error);
                });
            },

            /**
             * Get transaction details
             *
             * @param transaction
             */
            getTransactionDetails(transaction) {
                let vm = this;

                vm.transaction = transaction;
                vm.$nextTick(function () {
                    vm.$refs.tx_details.open();
                });
            },

            /**
             * Remove request
             *
             * @param request
             */
            removeRequest(request) {
                let vm = this;

                axios.delete('/' + vm.prefix + '/api/requests/' + request.address).then((response) => {
                    if (response.data) {
                        vm.raw.requests = _.filter(vm.raw.requests, function (o) {
                            return o.id !== request.id;
                        });
                    }
                }).catch((error) => {
                    console.error(error);
                });
            },

            /**
             * Set the active tab/hash
             *
             * @param hash
             */
            setHash(hash) {
                window.location.hash = "#" + hash;
                this.active = window.location.hash;
            }
        }
    }
</script>

<style lang="scss">
    .electrum {
        font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
        font-size: 13px;
        a {
            cursor: pointer;
        }
        .mt-8 {
            margin-top: 8px;
        }
        .tab-pane {
            position: relative;
        }
        .table-header {
            height: 35px;
            border-bottom: 2px solid #ddd;
        }
        .table-responsive {
            max-height: 400px;
            overflow-y: auto;
        }
        td, .address {
            font-family: "Inconsolata", "Fira Mono", "Source Code Pro", Monaco, Consolas, "Lucida Console", monospace;
        }
        .table {
            margin-bottom: 0;
            border-top: none;
            &.table-details {
                tbody, th, td {
                    border: 1px solid #ddd;
                }
                th, td {
                    padding-left:5px;
                }
            }
            th, tr:first-of-type, tr:first-of-type td {
                border-top: none;
            }
            th {
                height: 0;
                margin: 0;
                padding: 0;
                border-bottom: none;
                div {
                    position: absolute;
                    padding: 9px 20px 9px 30px;
                    top: 0;
                    margin-left: -25px;
                    line-height: normal;
                }
            }
            .btn {
                font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
                font-size: 10px;
                font-weight: bold;
            }
            .no-wrap {
                white-space: nowrap;
                vertical-align: middle;
            }
            .remaining {
                width: 99%;
                max-width: 0;
                overflow: hidden;
                text-overflow: ellipsis;
                white-space: nowrap;
                vertical-align: middle;
            }
        }
        .clickable {
            cursor: pointer;
        }
        .form-horizontal .control-label.text-left {
            text-align: left;
        }
        .qr-cell {
            min-width:160px;
            padding:5px 5px 0 5px;
        }
        .qr-code {
            canvas {
                visibility: hidden;
            }
            img {
                width: 100%;
            }
        }
    }
</style>