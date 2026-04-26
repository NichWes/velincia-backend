<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Order;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class InvoiceController extends Controller {
    public function generate(Order $order) {
        if ($order->user_id !== auth()->id() && auth()->user()->role !== 'admin') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        if (!in_array($order->status, [
            Order::STATUS_PAID,
            Order::STATUS_PROCESSING,
            Order::STATUS_SHIPPED,
            Order::STATUS_READY_PICKUP,
            Order::STATUS_COMPLETED,
        ])) {
            return response()->json([
                'message' => 'Invoice hanya bisa dibuat untuk order yang sudah dibayar'
            ], 422);
        }

        $invoice = $this->createInvoiceForOrder($order);

        return response()->json([
            'message' => 'Invoice generated',
            'invoice' => $invoice,
            'download_url' => url('/api/invoices/' . $invoice->id . '/download'),
        ]);
    }

    public function show(Invoice $invoice) {
        $invoice->load(['order.user', 'order.project', 'order.items']);

        if ($invoice->order->user_id !== auth()->id() && auth()->user()->role !== 'admin') {
            return response()->json([
                'message' => 'Unauthorized'
            ], 403);
        }

        return response()->json([
            'invoice' => $invoice,
            'download_url' => url('/api/invoices/' . $invoice->id . '/download'),
        ]);
    }

    public function download(Invoice $invoice) {
        $invoice->load('order');

        if ($invoice->order->user_id !== auth()->id() && auth()->user()->role !== 'admin') {
            return response()->json([
                'message' => 'Unauthorized'
            ], 403);
        }

        if (!$invoice->pdf_url || !Storage::disk('public')->exists($invoice->pdf_url)) {
            return response()->json([
                'message' => 'File invoice tidak ditemukan'
            ], 404);
        }

        return Storage::disk('public')->download(
            $invoice->pdf_url,
            $invoice->invoice_number . '.pdf'
        );
    }

    private function generateInvoiceNumber(): string {
        do {
            $number = 'INV-' . now()->format('ymd') . '-' . strtoupper(Str::random(4));
        } while (Invoice::where('invoice_number', $number)->exists());

        return $number;
    }

    public function generateForOrder(Order $order) {
        return $this->createInvoiceForOrder($order);
    }

    public function createInvoiceForOrder(Order $order): Invoice {
        $order->load(['user', 'project', 'items.material', 'items.projectItem', 'invoice']);

        if ($order->invoice) {
            return $order->invoice;
        }

        $invoiceNumber = $this->generateInvoiceNumber();

        $invoice = Invoice::create([
            'order_id' => $order->id,
            'invoice_number' => $invoiceNumber,
            'generated_at' => now(),
        ]);

        $pdf = Pdf::loadView('pdf.invoice', [
            'invoice' => $invoice,
            'order' => $order,
        ])->setPaper('a4', 'landscape');

        $fileName = 'invoices/' . $invoiceNumber . '.pdf';

        Storage::disk('public')->put($fileName, $pdf->output());

        $invoice->update([
            'pdf_url' => $fileName,
        ]);

        return $invoice->fresh();
    }

    public function byOrder(Order $order) {
        if ($order->user_id !== auth()->id() && auth()->user()->role !== 'admin') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $invoice = $order->invoice;

        if (!$invoice) {
            return response()->json([
                'message' => 'Invoice belum tersedia',
                'invoice' => null,
            ], 404);
        }

        return response()->json([
            'invoice' => $invoice,
            'download_url' => url('/api/invoices/' . $invoice->id . '/download'),
        ]);
    }
}