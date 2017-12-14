<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ImageController extends Controller
{
    /**
     * @Route("/preview/image", name="imagePreview")
     * @param Request $request
     * @return JsonResponse
     */
    public function getImageAction(Request $request): JsonResponse
    {
        $files = $request->request->get('filenameArray');
        $absPathFiles = [];

        if (sizeof($files) < 1) {
            return new JsonResponse();
        }

        foreach ($files as $filename) {
            $absPathFile = dirname($this->get('kernel')->getRootDir()) . '/web/uploads/images/products/' . $filename;
            if (file_exists($absPathFile)) {
                $absPathFiles[] = '/uploads/images/products/' . $filename;
            }
        }

        return new JsonResponse($absPathFiles);
    }
}