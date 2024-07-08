<?php

namespace App\Http\Controllers\Api;

use App\Http\Dtos\AddToCartDto;
use App\Http\Repositories\CartRepository;
use App\Http\Requests\CartRequest;
use App\Models\Cart;
use Exception;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CartController extends Controller
{
    protected CartRepository $cartRepository;

    public function __construct()
    {
        $this->cartRepository = new CartRepository();
    }

    public function index()
    {
        $cartData = $this->cartRepository->all();
        if ($cartData) {
            return responseBuilder()->success(__('Cart Data'), $cartData);
        }
        return responseBuilder()->error(__('Something Went Wrong'));
    }

    public function getCartCount()
    {
        $count = $this->cartRepository->getCartCount();
        return responseBuilder()->success(__('Product Cart Count'), ['count' => $count]);
    }

    public function save(CartRequest $request)
    {
        try {
            $addToCartDTo = AddToCartDto::fromRequest($request);
            $cart = $this->cartRepository->save($addToCartDTo, true);
            if ($cart) {
                return responseBuilder()->success(__('Product added to cart'), $cart->toArray());
            }
            return responseBuilder()->success(__('Product not exist'));
        } catch (Exception $e) {
            return responseBuilder()->error($e->getMessage());
        }
    }

    public function update(CartRequest $request)
    {
        try {
            $carts = $this->cartRepository->update($request);
            if ($carts == false) {
                return responseBuilder()->error(__('Product is Update So cart is deleted.'));
            }
            return responseBuilder()->success(__('Cart updated successfully'), $carts);
        } catch (Exception $e) {
            return responseBuilder()->error($e->getMessage());
        }
    }

    public function delete($id)
    {
        if ($id) {
            $this->cartRepository->delete($id);
            return responseBuilder()->success(__('Product Removed From Cart'));
        } else {
            return responseBuilder()->error(__('Not Cart Found'));
        }
    }


}
