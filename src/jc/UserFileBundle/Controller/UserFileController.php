<?php

namespace jc\UserFileBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\JsonResponse;

class UserFileController extends Controller {

    public function indexAction() {
        return $this->render('jcUserFileBundle::index.html.twig', array('currentPath' => ''));
    }

    public function uploadAction() {

        $curentPath = $this->getRequest()->request->get('filePath');

        try {

            // Process file upload
            $file = $this->getRequest()->files->get('file');

            $rootPath = $this->container->getParameter('jc_user_file.root_path');
            $fullPath = $this->container->getParameter('kernel.root_dir') . '/../web' . $rootPath . $curentPath;

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

    public function folderAction() {

        $curentPath = $this->getRequest()->request->get('folderPath');

        try {

            // Get folder name to create
            $folderName = $this->getRequest()->request->get('folderName');

            $rootPath = $this->container->getParameter('jc_user_file.root_path');
            $fullPath = $this->container->getParameter('kernel.root_dir') . '/../web' . $rootPath . $curentPath . '/' . $folderName;

            // Create folder into appropriate folder
            $fs = new Filesystem();
            $fs->mkdir($fullPath);
        }
        catch (Exception $e) {
            $request->getSession()->getFlashBag()->add('bo-error-message', 'Erreur lors de la création du dossier');
        }

        return $this->render('jcUserFileBundle::index.html.twig', array('currentPath' => $curentPath));
    }

    public function deleteAction() {

        $curentPath = $this->getRequest()->request->get('path');

        // Process delete only if currentPath defined (do not remove root)
        if ($curentPath) {

            // Get full path dor file/directory to remove
            $rootPath = $this->container->getParameter('jc_user_file.root_path');
            $fullPath = $this->container->getParameter('kernel.root_dir') . '/../web' . $rootPath . $curentPath;

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

    public function exploreAction() {

        try {

            // Get full path we want to explore (display content)
            $rootPath = $this->container->getParameter('jc_user_file.root_path');
            $curentPath = $this->getRequest()->request->get('path');
            $fullPath = $this->container->getParameter('kernel.root_dir') . '/../web' . $rootPath . $curentPath;

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
