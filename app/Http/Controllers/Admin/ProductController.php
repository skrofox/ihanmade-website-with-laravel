<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('keyword');

        if ($search) {
            $products = Product::where('name', 'LIKE', '%' . $search . '%')
                ->orWhere('description', 'LIKE', '%' . $search . '%')
                ->orWhere('status', 'LIKE', '%' . $search . '%')
                ->orWhere('brand', 'LIKE', '%' . $search . '%')
                ->orderByDesc('id')
                ->paginate(10)
                ->withQueryString();
        } else {
            $products = Product::orderByDesc('id')->paginate(10);
        }

        return view("admin.page.product", compact("products"));
    }

    public function detail(string $id)
    {
        $product = Product::where("id", $id)->firstOrFail();
        $categories = Category::all();
        return view("admin.page.product.detail_product", compact("product", "categories"));
    }

    public function create()
    {
        $categories = Category::all();
        return view("admin.page.product.create_product", compact("categories"));
    }
    
    public function store(Request $request)
    {
        $request->validate([
            "name" => "required|max:150|unique:products,name",
            "description" => "",
            "brand" => "",
            "status" => "",
            "categories" => "required|array",
            "categories.*" => "exists:categories,id",
            "images.*" => "image|mimes:jpg,jpeg,png,gif,webp|max:2048",
        ]);
        
        $product = Product::create(
            [
                "name" => $request->name,
                'description' => $request->description,
                'slug' => Str::slug(Str::title($request->name)),
                'brand' => $request->brand,
                'status' => $request->status,
            ]
        );
        
        $product->categories()->sync($request->categories);

        // Xử lý hình ảnh
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $image) {
                $imageName = time() . '_' . $index . '.' . $image->getClientOriginalExtension();
                $imagePath = $image->storeAs('products', $imageName, 'public');
                
                ProductImage::create([
                    'product_id' => $product->id,
                    'url' => $imagePath,
                    'position' => $index,
                    'alt' => $request->name . ' - Hình ' . ($index + 1)
                ]);
            }
        }

        return redirect()->route('product_create')->with('success', 'Add a new product successful');
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        
        // Xóa hình ảnh trước khi xóa sản phẩm
        foreach ($product->images as $image) {
            Storage::disk('public')->delete($image->url);
            $image->delete();
        }
        
        $product->delete();

        return redirect()->route('product_index')->with(['success' => true, 'message' => 'Product deleted successfully.']);
    }
    
    public function trash()
    {
        $products = Product::onlyTrashed()->paginate(10);
        return view('admin.page.product.trash_product', compact('products'));
    }

    public function updateFields(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $product->name = $request->input('name');
        $product->description = $request->input('description');
        $product->status = $request->input('status');
        $product->brand = $request->input('brand');
        $product->save();

        if ($request->has('categories')) {
            $product->categories()->sync($request->input('categories'));
        }

        return response()->json(['success' => true]);
    }

    // Thêm hình ảnh mới cho sản phẩm
    public function addImages(Request $request, $id)
    {
        $request->validate([
            'images.*' => 'required|image|mimes:jpg,jpeg,png,gif,webp|max:2048',
        ]);

        $product = Product::findOrFail($id);
        
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $image) {
                $imageName = time() . '_' . $index . '.' . $image->getClientOriginalExtension();
                $imagePath = $image->storeAs('products', $imageName, 'public');
                
                // Lấy vị trí cao nhất hiện tại
                $maxPosition = $product->images()->max('position') ?? -1;
                
                ProductImage::create([
                    'product_id' => $product->id,
                    'url' => $imagePath,
                    'position' => $maxPosition + 1,
                    'alt' => $product->name . ' - Hình ' . ($maxPosition + 2)
                ]);
            }
        }

        return response()->json(['success' => true, 'message' => 'Hình ảnh đã được thêm thành công']);
    }

    // Xóa hình ảnh
    public function deleteImage($productId, $imageId)
    {
        $image = ProductImage::where('product_id', $productId)
                            ->where('id', $imageId)
                            ->firstOrFail();
        
        // Xóa file từ storage
        Storage::disk('public')->delete($image->url);
        
        // Xóa record từ database
        $image->delete();

        return response()->json(['success' => true, 'message' => 'Hình ảnh đã được xóa']);
    }

    // Cập nhật vị trí hình ảnh
    public function updateImagePosition(Request $request, $productId)
    {
        $request->validate([
            'image_id' => 'required|exists:product_images,id',
            'position' => 'required|integer|min:0'
        ]);

        $image = ProductImage::where('product_id', $productId)
                            ->where('id', $request->image_id)
                            ->firstOrFail();
        
        $image->position = $request->position;
        $image->save();

        return response()->json(['success' => true]);
    }

    public function search(Request $request)
    {
        $search = $request->input('keyword');
        $products = Product::where('name', 'LIKE', '%' . $search . '%')
            ->orWhere('description', 'LIKE', '%' . $search . '%')
            ->orWhere('status', 'LIKE', '%' . $search . '%')
            ->orWhere('brand', 'LIKE', '%' . $search . '%')
            ->orderByDesc('id')
            ->paginate(10)
            ->withQueryString();

        return view('admin.page.product', compact('products'));
    }
    
    public function restore($id)
    {
        $product = Product::onlyTrashed()->findOrFail($id);
        $product->restore();
        return redirect()->route('product_trash');
    }
}
