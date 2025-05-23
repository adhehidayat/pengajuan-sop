<?php
declare(strict_types=1);
namespace App\ApiResource;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Post;
use ApiPlatform\OpenApi\Model\Operation;
use ApiPlatform\OpenApi\Model\RequestBody;
use App\Controller\Api\PengajuanApiController;
use Symfony\Component\HttpFoundation\File\UploadedFile;

#[ApiResource(
    operations: [
        new Post(
            uriTemplate: '/pengajuan',
            inputFormats: ['multipart' => ['multipart/form-data']],
            controller: PengajuanApiController::class,
            openapi: new Operation(
                requestBody: new RequestBody(
                    content: new \ArrayObject([
                        'multipart/form-data' => [
                            'schema' => [
                                'type' => 'object',
                                'properties' => [
                                    'data' => ['type' => 'string'],
                                    'attachment' => [
                                        'type' => 'array',
                                        'items' => [
                                            'type' => 'string',
                                            'format' => 'binary'
                                        ]
                                    ]
                                ]
                            ]
                        ]
                    ])
                )
            ),
            shortName: 'PengajuanBerkas',
            deserialize: false,
            name: "Pengajuan Berkas"
        )
    ]
)]
class PengajuanBerkasDto
{
    public ?string $data = null;

    /** @var UploadedFile[] */
    public array $attachment = [];
}