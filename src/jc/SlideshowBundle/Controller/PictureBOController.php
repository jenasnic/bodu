<?php

namespace jc\SlideshowBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use jc\SlideshowBundle\Entity\Picture;
use jc\SlideshowBundle\Entity\Slideshow;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class PictureBOController extends Controller {

    /**
     * @Route("/admin/slideshow/picture/upload", name="jc_slideshow_bo_picture_upload")
     */
    public function uploadPictureAction(Request $request) {

        $entityManager = $this->getDoctrine()->getManager();

        try {

            // Create new picture
            $picture = new Picture();

            // Set slideshow + rank
            $slideshowId = $request->request->get('slideshowId');
            $slideshow = $entityManager->getRepository('jcSlideshowBundle:Slideshow')->find($slideshowId);
            $picture->setSlideshow($slideshow);
            $picture->setRank($entityManager->getRepository('jcSlideshowBundle:Picture')->getMaxRankForSlideshow($slideshow) + 1);

            // Process file upload
            $file = $request->files->get('file');

            $relativeFolderPath = $this->getParameter('jc_slideshow.root_path') . '/slideshow_' . $slideshowId;
            $absoluteFolderPath = $this->getParameter('kernel.root_dir') . '/../web' . $relativeFolderPath;

            // Create folder if necessary
            if (!is_dir($absoluteFolderPath))
                mkdir($absoluteFolderPath);

            // For picture's name => use file name without extension
            $originalName = $file->getClientOriginalName();
            $dotIndex = strrpos($originalName, '.');
            $name = substr($originalName, 0, $dotIndex);
            $extension = substr($originalName, $dotIndex + 1);

            // For picture's file => use unique name and move file into appropriate folder
            $uniqueFileName = $this->getUniqueFileNameForPicture($absoluteFolderPath, $extension);
            $file->move($absoluteFolderPath, $uniqueFileName);

            $picture->setName($name);
            $picture->setPictureUrl($relativeFolderPath . '/' . $uniqueFileName);

            // Save new picture
            $entityManager->persist($picture);
            $entityManager->flush();

            return new JsonResponse(array('success' => true, 'id' => $picture->getId(), 'name' => $picture->getName(),
                    'rank' => $picture->getRank(), 'url' => $picture->getPictureUrl()));
        }
        catch (Exception $e) {
            return new JsonResponse(array('success' => false, 'message' => 'Erreur lors de la création de l\'image'));
        }
    }

    /**
     * @Route("/admin/slideshow/picture/delete", name="jc_slideshow_bo_picture_delete")
     */
    public function deletePictureAction(Request $request) {

        $pictureId = $request->request->get('pictureId');

        if ($pictureId > 0) {

            try {

                $entityManager = $this->getDoctrine()->getManager();
                $pictureToDelete = $entityManager->getRepository('jcSlideshowBundle:Picture')->find($pictureId);

                // If picture found => delete it
                if ($pictureToDelete != null) {

                    $pictureFileToDelete = $this->getParameter('kernel.root_dir') . '/../web' . $pictureToDelete->getPictureUrl();

                    $entityManager->remove($pictureToDelete);
                    $entityManager->flush();

                    // Delete main picture file if exist
                    if (file_exists($pictureFileToDelete))
                        unlink($pictureFileToDelete);

                    return new JsonResponse(array('success' => true));
                }

                return new JsonResponse(array('success' => false, 'message' => 'Image non trouvée'));
            }
            catch (Exception $e) {
                return new JsonResponse(array('success' => false, 'message' => 'Erreur lors de la suppression de l\'image'));
            }
        }
    }

    private function getUniqueFileNameForPicture($slideshowPath, $extension) {

        $i = 1;
        do {
            $fileName = 'picture_' . sprintf("%'.03d", $i++) . '.' . $extension;
        }
        while (file_exists($slideshowPath . '/' . $fileName));

        return $fileName;
    }
}
