<?php

namespace App\Tests\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class ProductControllerTest extends WebTestCase
{
    private ProductRepository $productRepository;
    private KernelBrowser $client;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $entityManager = self::getContainer()->get('doctrine')->getManager();
        $this->productRepository = $entityManager->getRepository(Product::class);
    }

    public function testGetProducts()
    {
        $this->client->request('GET', '/api/products');
        $this->client->getResponse();

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertResponseFormatSame("json");
    }

    public function testGetProduct(): void
    {
        $product = $this->productRepository->findOneBy([]);

        $this->client->request('GET', "/api/products/{$product->getId()}");
        $response = $this->client->getResponse();
        $result = json_decode($response->getContent(), true);

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertResponseFormatSame("json");
        $this->testProductFormat($result);
    }

    public function testCreateProduct(): void
    {
        $this->client->request(
            'POST',
            "/api/products",
            content: json_encode([
                "name" => "new Product",
                "description" => "new Description",
                "price" => 100,
            ])
        );

        $response = $this->client->getResponse();
        $result = json_decode($response->getContent(), true);

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);
        $this->testProductFormat($result);
        $this->assertSame("new Product", $result["name"]);
        $this->assertSame("new Description", $result["description"]);
        $this->assertEquals(100, $result["price"]);
    }

    private function testProductFormat(array $productsAsArray): void
    {
        $productKeys = ["id", "name", "description", "price", "createdAt", "updatedAt"];
        foreach ($productKeys as $key) {
            $this->assertArrayHasKey($key, $productsAsArray);
        }
    }

    public function testDeleteProduct(): void
    {
        $product = $this->productRepository->findOneBy([]);
        $this->client->request('DELETE', "/api/products/{$product->getId()}");

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_NO_CONTENT);
    }

    public function testPartialUpdate(): void
    {
        $product = $this->productRepository->findOneBy([]);
        $this->client->request(
            'PATCH',
            "/api/products/{$product->getId()}",
            content: json_encode(["name" => "Updated name"])
        );
        $response = $this->client->getResponse();
        $result = json_decode($response->getContent(), true);

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->testProductFormat($result);
        $this->assertSame("Updated name", $result["name"]);
    }

    public function testFullUpdate(): void
    {
        $product = $this->productRepository->findOneBy([]);

        //invalid request
        $this->client->request(
            'PUT',
            "/api/products/{$product->getId()}",
            content: json_encode(["title" => "Updated title"])
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);

        //valid request
        $this->client->request(
            'PUT',
            "/api/products/{$product->getId()}",
            content: json_encode([
                "name" => "updated Product",
                "description" => "updated Description",
                "price" => 123,
            ])
        );
        $response = $this->client->getResponse();
        $result = json_decode($response->getContent(), true);

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->testProductFormat($result);
        $this->assertSame("updated Product", $result["name"]);
        $this->assertSame("updated Description", $result["description"]);
        $this->assertEquals(123, $result["price"]);
    }
}
