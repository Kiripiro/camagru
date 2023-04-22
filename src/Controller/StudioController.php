<?php

class StudioController extends BaseController
{
    public function StudioView()
    {
        $session = new Session();
        $user = $session->get('user');
        if ($user == null) {
            $this->redirect("/login");
        }
        $this->addParam("user", $user);
        $this->addParam("title", "Studio");
        $this->addParam("description", "Ajouter une image");
        $this->addParam('session', $session);
        $this->addParam("posts", $session->get('posts'));
        $this->addParam("success_message", $session->get('success_message'));
        $this->addParam("error_message", $session->get('error_message'));
        $this->addParam("navbar", "View/Navbar/navbar.php");
        $this->view("studio");
    }
    public function StudioPreview($imageDimensions, $filters)
    {
        $session = new Session();
        $user = $session->get('user');
        if ($user == null) {
            $this->redirect("/login");
        }

        if (isset($_FILES["image"]) && isset($imageDimensions) && isset($filters)) {
            if ($_FILES['image']['error'] !== UPLOAD_ERR_OK) {
                http_response_code(400);
                echo json_encode(array("error" => "File upload error: " . $_FILES['image']['error']));
                exit;
            }

            $imageDimensions = json_decode($imageDimensions, true);
            if (!$imageDimensions || !isset($imageDimensions["width"]) || !isset($imageDimensions["height"])) {
                http_response_code(400);
                echo json_encode(array("error" => "Invalid image dimensions"));
                exit;
            }

            if (!$filters || !is_string($filters)) {
                http_response_code(400);
                echo json_encode(array("error" => "Invalid filters"));
                exit;
            }

            $tmpFilePath = $_FILES['image']['tmp_name'];
            $fileContent = file_get_contents($tmpFilePath);
            $id = hash_file('sha256', $tmpFilePath);
            $path = "Media/posts/" . $id . ".png";
            file_put_contents($path, $fileContent);
            $image = imagecreatefrompng($path);
            if (!$image) {
                http_response_code(400);
                echo json_encode(array("error" => "Error creating image resource"));
                exit;
            }
            $filters = json_decode($filters);

            foreach ($filters as $filter) {
                $filterName = $filter->name;
                $filterImagePath = __DIR__ . "/../Media/filtres/" . $filterName;
                $filterImageContent = file_get_contents($filterImagePath);
                $filterImage = imagecreatefromstring($filterImageContent);

                if (!$filterImage) {
                    http_response_code(400);
                    echo json_encode(array("error" => "Error creating filter image resource"));
                    imagedestroy($image);
                    exit;
                }

                imagesavealpha($filterImage, true);
                imagealphablending($filterImage, false);

                $filterWidth = imagesx($filterImage);
                $filterHeight = imagesy($filterImage);

                $widthRatio = $filter->width / $filterWidth;
                $heightRatio = $filter->height / $filterHeight;
                $newFilterWidth = intval($filterWidth * $widthRatio);
                $newFilterHeight = intval($filterHeight * $heightRatio);

                $resizedFilterImage = imagecreatetruecolor($newFilterWidth, $newFilterHeight);
                imagealphablending($resizedFilterImage, false);
                imagesavealpha($resizedFilterImage, true);
                imagecopyresampled($resizedFilterImage, $filterImage, 0, 0, 0, 0, $newFilterWidth, $newFilterHeight, $filterWidth, $filterHeight);

                if (!imagecopy($image, $resizedFilterImage, (int) $filter->x, (int) $filter->y, 0, 0, $newFilterWidth, $newFilterHeight)) {
                    http_response_code(400);
                    echo json_encode(array("error" => "Error copying filter image"));
                    imagedestroy($image);
                    imagedestroy($filterImage);
                    imagedestroy($resizedFilterImage);
                    exit;
                }

                imagedestroy($filterImage);
                imagedestroy($resizedFilterImage);
                $session->set('latest_image_path', $path);
                $session->set('latest_image_id', $id);
            }

            imagepng($image, $path);
            $response = array(
                "id" => $id,
                "path" => $path
            );

            header('Content-Type: application/json');
            echo json_encode($response);
        }
    }

    public function StudioUploadPost($description)
    {
        $session = new Session();
        $user = $session->get('user');
        if ($user == null) {
            $this->redirect("/login");
        }
        $latestImageId = $session->get('latest_image_id');
        if ($this->StudioManager->postExists($latestImageId)) {
            throw new PostExistsException();
        }
        $post = $this->StudioManager->addPost($latestImageId, $user->getId(), $description);
        if ($post) {
            $session->addArray('posts', array($post->getPath()));
            $session->set('success_message', "Votre image a bien été publiée !");
        } else {
            $session->set('error_message', "Une erreur est survenue lors de la publication de votre image.");
        }
        $this->redirect("/studio");
    }

    public function StudioDeletePost($pictureID, $token)
    {
        if (empty($token || empty($pictureID))) {
            $this->redirect("/studio");
        }
        $session = new Session();
        $user = $session->get('user');
        if ($user == null) {
            $this->redirect("/login");
        }
        $post = $this->StudioManager->getUsersPost($user->getId(), $pictureID);
        if ($post) {
            $this->StudioManager->deletePost($pictureID);
            $session->removeFromArray('posts', $post->getPath());
            unlink("Media/posts/" . $post->getPath() . ".png");
            $response = array(
                "success" => true
            );

            header('Content-Type: application/json');
            echo json_encode($response);
        } else {
            $response = array(
                "success" => false
            );

            header('Content-Type: application/json');
            echo json_encode($response);
        }
    }
}