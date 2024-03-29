<?php

declare(strict_types=1);

namespace App\UI\Http\Rest\Response;

use App\Application\Query\Collection;
use App\Application\Query\Item;
use Symfony\Component\HttpFoundation\JsonResponse;

class OpenApi extends JsonResponse
{
    /**
     * @OA\Info(title="API Challange", version="0.1")
     *
     * @OA\Tag(name="User", description="USER role required")
     * @OA\Tag(name="Admin", description="ADMIN role required")
     * @OA\Tag(name="Auth")
     * @OA\Tag(name="System")
     */

    private function __construct($data = null, int $status = self::HTTP_OK, array $headers = [], bool $json = false)
    {
        parent::__construct($data, $status, $headers, $json);
    }

    public static function fromPayload(array $payload, int $status): self
    {
        return new self($payload, $status);
    }

    public static function empty(int $status): self
    {
        return new self(null, $status);
    }

    public static function one(Item $resource, int $status = self::HTTP_OK, array $headers = []): self
    {
        return new self(
            [
                'data' => self::model($resource)
            ],
            $status,
            $headers
        );
    }

    public static function created(string $location = null): self
    {
        return new self(
            null,
            self::HTTP_CREATED,
            ($location) ? ['location' => $location] : []
        );
    }

    public static function collection(Collection $collection, int $status = self::HTTP_OK): self
    {
        /**
         * @psalm-suppress MissingClosureParamType
         *
         * @param Item|array $data
         *
         * @return array
         */
        $transformer = function ($data): array {
            return $data instanceof Item ? self::model($data) : $data;
        };

        $resources = \array_map($transformer, $collection->data);

        return new self(
            [
                'meta' => [
                    'size' => $collection->limit,
                    'page' => $collection->page,
                    'total' => $collection->total,
                ],
                'data' => $resources,
            ],
            $status
        );
    }

    private static function model(Item $resource): array
    {
        return [
            'attributes' => $resource->resource,
        ];
    }
}
