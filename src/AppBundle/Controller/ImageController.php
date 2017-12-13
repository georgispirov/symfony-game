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
        ob_get_clean();
        $filename = $request->request->get('filename');
        $response = null;
        $absPathFile = dirname($this->get('kernel')->getRootDir()) . '/web/uploads/images/products/' . $filename;
        $this->get('logger')->error('hahaha', ['haha' => $absPathFile]);
        if (file_exists($absPathFile)) {
            $response = new JsonResponse(base64_encode($absPathFile));
        }
        return $response;
    }
}