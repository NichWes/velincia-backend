<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $invoice->invoice_number }}</title>
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
            color: #111;
        }

        .page {
            width: 100%;
        }

        .header {
            width: 100%;
            margin-bottom: 20px;
        }

        .left-header {
            width: 55%;
            float: left;
        }

        .right-header {
            width: 42%;
            float: right;
        }

        .store-title {
            font-size: 24px;
            font-weight: bold;
            line-height: 1.1;
        }

        .invoice-title {
            font-size: 22px;
            font-weight: bold;
            text-align: center;
            margin-bottom: 8px;
        }

        .info-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 11px;
        }

        .info-table td {
            border: 1px solid #111;
            padding: 5px;
            vertical-align: top;
        }

        .clear {
            clear: both;
        }

        .customer {
            border-top: 2px solid #111;
            border-bottom: 1px solid #111;
            padding: 5px 0;
            margin-bottom: 18px;
        }

        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 8px;
        }

        .items-table th,
        .items-table td {
            border: 1px solid #111;
            padding: 5px;
        }

        .items-table th {
            text-align: center;
            font-weight: bold;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .bottom {
            width: 100%;
            margin-top: 4px;
        }

        .note-box {
            width: 62%;
            height: 48px;
            border: 1px solid #111;
            float: left;
            padding: 6px;
        }

        .summary {
            width: 31%;
            float: right;
        }

        .summary-table {
            width: 100%;
            border-collapse: collapse;
        }

        .summary-table td {
            border: 1px solid #111;
            padding: 5px;
        }

        .summary-total {
            font-size: 18px;
            font-weight: bold;
        }

        .signature {
            width: 58%;
            float: left;
            margin-top: 22px;
        }

        .signature-col {
            width: 45%;
            float: left;
            text-align: center;
        }

        .signature-line {
            margin-top: 45px;
            border-top: 1px solid #111;
            padding-top: 4px;
        }

        .bank-box {
            width: 31%;
            float: right;
            border: 2px solid #111;
            padding: 8px;
            margin-top: 10px;
            font-size: 14px;
            font-weight: bold;
        }

        .footer-note {
            position: fixed;
            bottom: 10px;
            left: 0;
            right: 0;
            font-style: italic;
            font-size: 11px;
        }

        .page-number {
            float: right;
            font-style: normal;
        }
    </style>
</head>
<body>
@php
    $subtotal = (float) $order->subtotal;
    $shipping = (float) $order->shipping_fee;
    $total = (float) $order->total_amount;
@endphp

<div class="page">
    <div class="header">
        <div class="left-header">
            <div class="store-title">
                TOKO VELINCIA HPL<br>
                CIBITUNG
            </div>
            <div style="margin-top: 8px;">
                Komplek Ruko Casa Gardenia, Jl. Selang Bulak Blok R1 No.12 dan No.14,<br>
                Kel. Wanasari, Kec. Cibitung<br>
                Telp. 0821-2463-6876.<br>
                Kab. Bekasi Jawa Barat 17520<br>
                Indonesia
            </div>
        </div>

        <div class="right-header">
            <div class="invoice-title">Invoice</div>
            <table class="info-table">
                <tr>
                    <td>
                        Tanggal<br>
                        <strong>{{ optional($invoice->generated_at)->format('d M Y') }}</strong>
                    </td>
                    <td>
                        Syarat Pembayaran<br>
                        <strong>{{ $order->payment_type ? strtoupper($order->payment_type) : 'C.O.D' }}</strong>
                    </td>
                    <td>
                        Nomor<br>
                        <strong>{{ $invoice->invoice_number }}</strong>
                    </td>
                </tr>
            </table>
            <div class="customer">
                Kepada : <strong>{{ $order->user->name ?? '-' }}</strong>
            </div>
        </div>

        <div class="clear"></div>
    </div>

    <table class="items-table">
        <thead>
            <tr>
                <th style="width: 12%;">Kode Barang</th>
                <th style="width: 40%;">Nama Barang</th>
                <th style="width: 8%;">Jml</th>
                <th style="width: 8%;"></th>
                <th style="width: 14%;">@Harga</th>
                <th style="width: 8%;">Diskon</th>
                <th style="width: 14%;">Total Harga</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->items as $item)
                <tr>
                    <td>{{ $item->material_id ?? $item->id }}</td>
                    <td>{{ $item->name_snapshot }}</td>
                    <td class="text-right">{{ $item->qty }}</td>
                    <td>{{ $item->material->unit ?? '' }}</td>
                    <td class="text-right">{{ number_format((float) $item->unit_price, 0, ',', '.') }}</td>
                    <td class="text-right">0</td>
                    <td class="text-right">{{ number_format((float) $item->line_total, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="bottom">
        <div class="note-box">
            Keterangan : {{ $order->project->title ?? '-' }}
        </div>

        <div class="summary">
            <table class="summary-table">
                <tr>
                    <td>Sub Total</td>
                    <td class="text-right">{{ number_format($subtotal, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td>Ongkir</td>
                    <td class="text-right">{{ number_format($shipping, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td class="summary-total">Total</td>
                    <td class="summary-total text-right">{{ number_format($total, 0, ',', '.') }}</td>
                </tr>
            </table>
        </div>

        <div class="clear"></div>

        <table style="width:100%; margin-top:20px;">
            <tr>
                <!-- Signature -->
                <td style="width:65%; vertical-align:top;">
                    <table style="width:100%;">
                        <tr>
                            <td style="text-align:center;">
                                Disiapkan Oleh
                                <div style="margin-top:50px; border-top:1px solid #111;">Tgl</div>
                            </td>
                            <td style="text-align:center;">
                                Disetujui Oleh
                                <div style="margin-top:50px; border-top:1px solid #111;">Tgl.</div>
                            </td>
                        </tr>
                    </table>
                </td>

                <!-- Bank Box -->
                <td style="width:35%; vertical-align:top;">
                    <div style="
                        border:2px solid #111;
                        padding:10px;
                        font-size:14px;
                        font-weight:bold;
                        text-align:left;
                    ">
                        Transfer BCA<br>
                        7391722360<br>
                        A.n. Nicholas Westley
                    </div>
                </td>
            </tr>
        </table>

        <div class="clear"></div>
    </div>

    <div class="footer-note">
        Maksimal penukaran barang 3 hari setelah pembelian dengan syarat barang belum dibuka dan membawa bukti invoice
    </div>
</div>
</body>
</html>