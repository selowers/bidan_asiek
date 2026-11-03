<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductAccess;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class VideoController extends Controller
{
    public function show(Product $product): View
    {
        $access = $this->activeAccess($product);

        return view('store.video', compact('product', 'access'));
    }

    public function stream(Product $product): StreamedResponse
    {
        $this->activeAccess($product);
        abort_unless(Storage::disk('local')->exists($product->video_path), 404);
        $stream = Storage::disk('local')->readStream($product->video_path);

        return response()->stream(function () use ($stream): void {
            fpassthru($stream);
            fclose($stream);
        }, 200, ['Content-Type' => 'video/mp4', 'Content-Disposition' => 'inline']);
    }

    private function activeAccess(Product $product): ProductAccess
    {
        return auth()->user()->productAccesses()->where('product_id', $product->id)->where('status', 'active')->where('expired_at', '>', now())->firstOrFail();
    }
}
