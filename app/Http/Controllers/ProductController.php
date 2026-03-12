<?php

namespace App\Http\Controllers;

use App\Http\Requests\Product\StoreProductRequest;
use App\Http\Requests\Product\UpdateProductRequest;
use App\Http\Requests\Product\IndexProductRequest;

use App\Models\Product;
use App\Services\ProductService;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ProductController extends Controller
{
    public function __construct(protected ProductService $productService)
    {
    }

    public function index(IndexProductRequest $request)
    {
        try {
            $products = $this->productService->getAll($request->validated());
            return $this->success('Lista de produtos carregada com sucesso.', $products);
        } catch (\Exception $e) {
            return $this->error('Erro ao carregar lista de produtos', [], 500);
        }
    }

    public function store(StoreProductRequest $request)
    {
        try {
            $product = $this->productService->create($request->validated());

            return $this->success('Produto criado com sucesso.', $product, 201);
        } catch (\Exception $e) {
            return $this->error('Erro ao criar produto', [
                'exception' => $e->getMessage()
            ], 500);
        }
    }

    public function show(int $id)
    {        
        try {
            $product = Product::findOrFail($id);
            return $this->success('Produto encontrado com sucesso.', $product);
        } catch (ModelNotFoundException $e) {
            return $this->error('Produto não encontrado.', [], 404);
        } catch (\Exception $e) {
            return $this->error('Erro ao buscar produto.', ['error' => $e->getMessage()], 500);
        }
    }

    public function update(UpdateProductRequest $request, int $id)
    {
        try {
            $product = Product::findOrFail($id);

            $updatedProduct = $this->productService->update($product, $request->validated());

            return $this->success('Produto atualizado com sucesso.', $updatedProduct);

        } catch (ModelNotFoundException $e) {
            return $this->error('Produto não encontrado para atualização.', [], 404);
        } catch (\Illuminate\Auth\Access\AuthorizationException $e) {
             return $this->error($e->getMessage(), [], 403);
        } catch (\Exception $e) {
            return $this->error('Erro ao atualizar produto.', ['error' => $e->getMessage()], 500);
        }
    }

    public function destroy(int $id)
    {
        try {
            $product = Product::findOrFail($id);
            $product->delete();

            return $this->success('Produto excluído com sucesso.', [], 200);
        } catch (ModelNotFoundException $e) {
            return $this->error('Produto não encontrado para exclusão.', [], 404);
        } catch (\Illuminate\Auth\Access\AuthorizationException $e) {
             return $this->error($e->getMessage(), [], 403);
        } catch (\Exception $e) {
            return $this->error('Erro ao excluir produto.', ['error' => $e->getMessage()], 500);
        }
    }
}