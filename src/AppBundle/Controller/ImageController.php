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
        $filename = $request->request->get('filename');
        $response = null;
        $absPathFile = dirname($this->get('kernel')->getRootDir()) . '/web/uploads/images/products/' . $filename;
        if (file_exists($absPathFile)) {
            $absPathFile = '/uploads/images/products/' . $filename;
            $response = new JsonResponse($absPathFile);
        }
        return $response;
    }
}