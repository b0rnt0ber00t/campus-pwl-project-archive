<?php

namespace App\Helpers\Product;

use App\Models\Product\Product;
use Illuminate\Http\Request;

/**
 * ProductUpdateHelper
 */
trait ProductUpdateHelper
{
  public function _hasUpdateThumbnail(Request $request, Product $product): void
  {
    $request->has('thumbnail') && !blank($request->thumbnail)
      ? $this->__thumbnailDelete($product)
      : false;
  }

  private function __thumbnailDelete(Product $product): void
  {
    $product->thumbnail != 'thumbnail.png'
      ? unlink(public_path('assets/img/products/') . $product->thumbnail)
      : false;
  }
}
