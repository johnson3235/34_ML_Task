<?php

namespace App\Http\Controllers;
use App\Models\Product;
use App\Traits\ApiTrait;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    
    public function index(Request $request)
    {
        try {
            $query = Product::query();
    
            // Filter by average_rating
            if ($request->has('filter.average_rating')) {
                $query->where('average_rating', $request->input('filter.average_rating'));
            }


                    
        if ($request->has('filter.options')) {
            $options = explode(',', $request->input('filter.options'));

            $query->whereHas('variants.options', function ($subquery) use ($options) {
                $subquery->whereIn('values', $options);
            });
        }


    
            // Filter by max_price
            if ($request->has('filter.max_price')) {
                $query->whereHas('variants', function ($subquery) use ($request) {
                    $subquery->where('price', '<=', $request->input('filter.max_price'));
                });
            }
    
            $products = $query->with(['variants.options'])->get();

            $fullProductData = [];
    
            foreach ($products as $product) {
                $fullProductData[] = $product->getFullProductData($product->id);
            }




    
            return ApiTrait::data(compact('fullProductData'), 'Products Get Successfully', 200);
        } catch (\Exception $e) {
            return ApiTrait::errorMessage(["error" => $e->getMessage()], 'Unprocessable content', 422);
        }
    }


    public function markOutOfStock($productId)
    {
        try {
          
            $product = Product::findOrFail($productId);

           
            if (!$product->is_in_stock) {
                return ApiTrait::errorMessage([], 'Product is already out of stock', 422);
            }

           
            $product->is_in_stock = false;
            $product->save();

         
            $this->notifyAdmin($product);

            return ApiTrait::data([], 'Product marked as out of stock and admin notified', 200);
        } catch (\Exception $e) {
            return ApiTrait::errorMessage(["error" => $e->getMessage()], 'Unprocessable content', 422);
        }
    }

    private function notifyAdmin(Product $product)
    {
        $adminEmail = 'admin@34ml.com'; // replace with the actual admin email

        // Check if the admin exists
        $admin = User::where('email', $adminEmail)->first();

        if ($admin) {
            // Notify admin via notification
            Notification::send($admin, new ProductOutOfStockNotification($product));
   
        }
    }
    
    

    public function get_product_byId($id)
    {
    
        try {
            $product = Product::FindOrFail($id);
            return ApiTrait::data(compact('product'));
        } catch (\Exception $e) {
            return ApiTrait::errorMessage(['ID'=> 'Invalid Id'],'Unprocessable content',200);
        }
    }




    // public function add_brand(AddBrandRequest $request)
    // {
    
    //     try {
    //         $brand = new Brand;
    //         if ($request->hasFile('image')) {
    //                 $path = Media::upload('',$request->file('image'),'brands/cars');
    //                 $brand->image = $path;
    //         }
           
    //         $brand->name = $request->name;
            
    //         if($brand->save()){
    //             return ApiTrait::successMessage('Brand Created',200);
    //         }
        
    //         return ApiTrait::errorMessage([],'Error : Brand Not Created',200);
    //     } catch (\Exception $e) {
    //         return ApiTrait::errorMessage([],'Some Thing Error',422);
    //     }
    // }


    public function delete_product($id)
    {
    
        try {
            $Product = Product::FindOrFail($id);
            Product::where('id', $id)->delete();
            return ApiTrait::successMessage('Brand Deleted Successfully',200);
        } catch (\Exception $e) {
            return ApiTrait::errorMessage(['id'=>'The Given Id Is Invalid'],'Unprocessable content',200);
        }

    }
    
    // public function upadte_brand_data(updateBrandRequest $request)
    // {

    //     try {
    //         $brand = Brand::findOrFail($request->id);


    //         if ($request->hasFile('image')) {
    //             $path = Media::upload('',$request->file('image'),'brands/cars');
    //             if($brand->image != '1680436040_.png')
    //             {
    //                 $result = Storage::disk('s3')->delete("brands/cars/".$brand->image);
    //                 if($result == true)
    //                 {
    //                     $brand->image = $path;
                        
    //                 }
    //                 else
    //                 {
    //                     return ApiTrait::errorMessage(["error"=>'Error Cannot Delete past Image'],200);
    //                 }
    //             }
    //             else
    //             {
    //                 $brand->image = $path;
    //             }          
            
    //         }
    //         $brand->name = $request->name;
    //         // $brand->image = $request->image;
    //         if($brand->save())
    //         {
    //             return ApiTrait::successMessage('Brand Updated Successfully',200);
    //         }
    //         else
    //         {
    //             return ApiTrait::errorMessage([],'Some Thing Error',200);
    //         }
    //     } catch (\Exception $e) {
    //         return ApiTrait::errorMessage([],'Some Thing Error',422);
    //     }
    
    //  }




}
