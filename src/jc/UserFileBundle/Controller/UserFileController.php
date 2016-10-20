<?php

namespace jc\UserFileBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class UserFileController extends Controller {

    /**
     * @Route("/admin/userfile/index", name="jc_user_file_bo_index")
     */
    public function indexAction() {
        return $this->render('jcUserFileBundle::index.html.twig', array('currentPath' => ''));
    }

    /**
     * @Route("/admin/userfile/upload", name="jc_user_file_bo_upload")
     */
    public function uploadAction(Request $request) {

        $curentPath = $request->request->get('filePath');

        try {

            // Process file upload
            $file = $request->files->get('file');

            $rootPath = $this->getParameter('jc_user_file.root_path');
            $fullPath = $this->getParameter('kernel.root_dir') . '/../web' . $rootPath . $curentPath;

            // Move file into appropriate folder
            $file->move($fullPath, $file->getClientOriginalName());
        }
        catch (Exception $e) {
            $request->getSession()->getFlashBag()->add('bo-error-message', 'Erreur lors de l\'upload du fichier');
        }

        return $this->render('jcUserFileBundle::index.html.twig', array(
                'currentPath' => $curentPath
        ));
    }

    /**
     * @Route("/admin/userfile/folder", name="jc_user_file_bo_folder")
     */
    public function folderAction(Request $request) {

        $curentPath = $request->request->get('folderPath');

        try {

            // Get folder name to create
            $folderName = $request->request->get('folderName');

            $rootPath = $this->getParameter('jc_user_file.root_path');
            $fullPath = $this->getParameter('kernel.root_dir') . '/../web' . $rootPath . $curentPath . '/' . $folderName;

            // Create folder into appropriate folder
            $fs = new Filesystem();
            $fs->mkdir($fullPath);
        }
        catch (Exception $e) {
            $request->getSession()->getFlashBag()->add('bo-error-message', 'Erreur lors de la création du dossier');
        }

        return $this->render('jcUserFileBundle::index.html.twig', array('currentPath' => $curentPath));
    }

    /**
     * @Route("/admin/userfile/ajax/delete", name="jc_user_file_bo_delete")
     */
    public function deleteAction(Request $request) {

        $curentPath = $request->request->get('path');

        // Process delete only if currentPath defined (do not remove root)
        if ($curentPath) {

            // Get full path dor file/directory to remove
            $rootPath = $this->getParameter('jc_user_file.root_path');
            $fullPath = $this->getParameter('kernel.root_dir') . '/../web' . $rootPath . $curentPath;

            try {

                // Process delete operation
                $fs = new Filesystem();
                $fs->remove($fullPath);
                return new JsonResponse(1);
            }
            catch (Exception $e) {
                return new JsonResponse(0);
            }
        }
    }

    /**
     * @Route("/admin/userfile/ajax/explore", name="jc_user_file_bo_explore")
     */
    public function exploreAction(Request $request) {

        try {

            // Get full path we want to explore (display content)
            $rootPath = $this->getParameter('jc_user_file.root_path');
            $curentPath = $request->request->get('path');
            $fullPath = $this->getParameter('kernel.root_dir') . '/../web' . $rootPath . $curentPath;

            // Use Finder to get all directories and files existing in current path (not including sub directories)
            $finder = Finder::create();
            $finder->in($fullPath)->depth(0)->sortByType();

            return $this->render('jcUserFileBundle::explorer.html.twig', array(
                    'rootPath' => $rootPath,
                    'currentPath' => $curentPath,
                    'fileList' => $finder
            ));
        }
        catch (Exception $e) {
            $request->getSession()->getFlashBag()->add('bo-error-message', 'Erreur lors de l\'exploration du dossier courant');
        }
    }
}
