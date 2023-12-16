<?php

namespace App\Http\Controllers;
use App\Models\Product;
use App\Traits\ApiTrait;
use app\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use App\Notifications\ProductOutOfStockNotification;
use Illuminate\Support\Facades\Mail;

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

    
            // Filter by max_price
            if ($request->has('filter.max_price')) {
                $query->whereHas('variants', function ($subquery) use ($request) {
                    $subquery->where('price', '<=', $request->input('filter.max_price'));
                });
            }
    
            $products = $query->with(['variants'])->get();

            $fullProductData = [];
    
            foreach ($products as $product) {
                $fullProductData[] = $product->getFullProductData($product->id);
            }

            if ($request->has('filter.options')) {
                $options = explode(',', $request->input('filter.options'));
                $filterFunction = function ($product) use ($options) {
                    return count(array_intersect($options, $product['all_options'])) > 0;
                };

                $fullProductData = array_filter($fullProductData, $filterFunction);   
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
        $adminEmail = 'admin@34ml.com';
    
        $admin = \App\Models\User::where('email', $adminEmail)->first();

        if ($admin) {
            // Notify admin via notification
            Notification::send($admin, new ProductOutOfStockNotification($product));
        }
    }
    

    // public function get_product_byId($id)
    // {
    
    //     try {
    //         $product = Product::FindOrFail($id);
    //         return ApiTrait::data(compact('product'));
    //     } catch (\Exception $e) {
    //         return ApiTrait::errorMessage(['ID'=> 'Invalid Id'],'Unprocessable content',200);
    //     }
    // }







    



}
