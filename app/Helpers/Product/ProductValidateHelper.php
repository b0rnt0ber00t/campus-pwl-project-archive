<?php

namespace App\Helpers\Product;

use Illuminate\Http\Request;

/**
 * ProductValidateHelper
 */
trait ProductValidateHelper
{
  public function _hasThumbnail(Request $request): array
  {
    return $request->has('thumbnail') && !blank($request->thumbnail)
      ? $this->__thumbnailCredential($request)
      : array();
  }

  private function __thumbnailCredential(Request $request): array
  {
    $request->validate(['thumbnail' => ['required', 'image:png,jpg,jpeg']]);
    $image_name = uniqid() . '.' . $request->thumbnail->extension();
    $request->thumbnail->move(public_path('assets/img/products/'), $image_name);

    return array('thumbnail' => $image_name);
  }

  public function _hasDesc(Request $request): array
  {
    return $request->has('desc') && !blank($request->desc)
      ? $this->__descCredential($request)
      : array();
  }

  private function __descCredential(Request $request): array
  {
    $request->validate(['desc' => ['required', 'string']]);
    return $request->only('desc');
  }
}
