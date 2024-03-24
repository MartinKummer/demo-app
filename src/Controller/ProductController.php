<?php

namespace App\Controller;

use App\Entity\Product;
use App\OptionsResolver\ProductOptionsResolver;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use InvalidArgumentException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/api', name: 'api_', format: "json")]
class ProductController extends AbstractController
{
    #[Route('/products', name: 'product_index', methods: ['GET'])]
    public function getProducts(ProductRepository $productRepository): JsonResponse
    {
        try {
            $data = $productRepository->findAll();

            return $this->json($data, status: Response::HTTP_OK);
        } catch (Exception $e) {
            throw new BadRequestHttpException($e->getMessage());
        }
    }

    #[Route("/products/{id}", "get_product", methods: ["GET"])]
    public function getProduct(Product $data): JsonResponse
    {
        try {
            return $this->json($data, status: Response::HTTP_OK);
        } catch (Exception $e) {
            throw new BadRequestHttpException($e->getMessage());
        }
    }

    #[Route('/products', name: 'product_new', methods: ['POST'])]
    public function new(
        ProductRepository      $productRepository,
        Request                $request,
        ValidatorInterface     $validator,
        ProductOptionsResolver $productOptionsResolver
    ): Response
    {
        try {
            $requestBody = json_decode($request->getContent(), true);
            $fields = $productOptionsResolver
                ->configureName()
                ->configureDescription()
                ->configurePrice()
                ->resolve($requestBody);

            $product = new Product();
            $product->setName($fields["name"]);
            $product->setDescription($fields["description"]);
            $product->setPrice($fields["price"]);

            $errors = $validator->validate($product);
            if (count($errors) > 0) {
                throw new InvalidArgumentException((string)$errors);
            }

            $productRepository->add($product, true);

            return $this->json($product, status: Response::HTTP_CREATED);
        } catch (Exception $e) {
            throw new BadRequestHttpException($e->getMessage());
        }
    }

    #[Route("/products/{id}", "update_product", methods: ["PATCH", "PUT"])]
    public function edit(Product $product, Request $request, ProductOptionsResolver $productOptionsResolver, ValidatorInterface $validator, EntityManagerInterface $em): JsonResponse
    {
        try {
            $requestBody = json_decode($request->getContent(), true);

            $isPutMethod = $request->getMethod() === "PUT";
            $fields = $productOptionsResolver
                ->configureName($isPutMethod)
                ->configureDescription($isPutMethod)
                ->configurePrice($isPutMethod)
                ->resolve($requestBody);

            foreach ($fields as $field => $value) {
                switch ($field) {
                    case "name":
                        $product->setName($value);
                        break;
                    case "description":
                        $product->setDescription($value);
                        break;
                    case "price":
                        $product->setPrice($value);
                        break;
                }
            }
            $errors = $validator->validate($product);
            if (count($errors) > 0) {
                throw new InvalidArgumentException((string)$errors);
            }

            $em->flush();

            return $this->json($product, status: Response::HTTP_OK);
        } catch (Exception $e) {
            throw new BadRequestHttpException($e->getMessage());
        }
    }

    #[Route("/products/{id}", "delete_product", methods: ["DELETE"])]
    public function delete(Product $product, ProductRepository $productRepository): JsonResponse
    {
        try {
            $productRepository->delete($product);

            return $this->json(['message' => 'Product deleted'], status: Response::HTTP_NO_CONTENT);
        } catch (Exception $e) {
            throw new BadRequestHttpException($e->getMessage());
        }
    }

    #[Route('/search', name: 'product_search', methods: ['GET'])]
    public function search(Request $request, ProductRepository $productRepository): JsonResponse
    {
        try {
            $idQuery = (int)$request->query->get('id');
            $nameQuery = $request->query->get('name');
            $descriptionQuery = $request->query->get('description');

            $products = $productRepository->search($idQuery, $nameQuery, $descriptionQuery);

            return $this->json($products);
        } catch (Exception $e) {
            throw new BadRequestHttpException($e->getMessage());
        }
    }
}
