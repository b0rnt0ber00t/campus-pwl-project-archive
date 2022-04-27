<?php

namespace App\Http\Controllers\Product;

use App\Helpers\Product\ProductStoreHelper;
use App\Helpers\Product\ProductUpdateHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Product\ProductRequest;
use App\Models\Product\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    // helper
    use ProductStoreHelper, ProductUpdateHelper;

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:product.index')->only('index');
        $this->middleware('permission:product.create')->only('create', 'store');
        $this->middleware('permission:product.show')->only('show');
        $this->middleware('permission:product.edit')->only('edit', 'update');
        $this->middleware('permission:product.destroy')->only('destroy');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $products = Product::when($request->name, function ($query, $name) {
            return $query->where('name', 'like', '%' . $name . '%');
        })->paginate(10);

        return view('product.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('product.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProductRequest $request)
    {
        return Product::create($this->_store($request))
            ? redirect()->route('product.index')->with('success', 'Product Berhasil Ditambahkan')
            : redirect()->route('product.index')->with('failed', 'Product Gagal Ditambahkan');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        return view('product.edit', compact('product'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ProductRequest $request, Product $product)
    {
        $this->_hasUpdateThumbnail($request, $product);

        return $product->update($this->_store($request))
            ? redirect()->route('product.index')->with('success', 'Product Berhasil Diperbarui')
            : redirect()->route('product.index')->with('failed', 'Product Gagal Diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        return $product->delete()
            ? redirect()->route('product.index')->with('success', 'Product Berhasil Dihapus')
            : redirect()->route('product.index')->with('failed', 'Product Gagal Dihapus');
    }
}
