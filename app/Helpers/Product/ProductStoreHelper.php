<?php

namespace App\Helpers\Product;

use Illuminate\Http\Request;

/**
 * ProductStoreHelper
 */
trait ProductStoreHelper
{
  use ProductValidateHelper;

  public function _store(Request $request): array
  {
    return $this->__merge($request);
  }

  private function __merge(Request $request): array
  {
    $store = array();

    foreach ($this->__request($request) as $product) {
      $store = array_merge($store, $product);
    }

    return $store;
  }

  private function __request(Request $request): array
  {
    return array(
      $request->validated(),
      $this->_hasThumbnail($request),
      $this->_hasDesc($request)
    );
  }
}
