<?php

namespace App\Controller\api;

use stdClass;
use App\Entity\Jugador;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class JugadorController extends AbstractController
{
    /**
     * @Route("/api/editaUsuario", name="editaUsuario")
     */
    public function editaUsuario(ManagerRegistry $doctrine, ValidatorInterface $validator): Response
    {
        if(empty($_SESSION))
        {
            session_start();
        }
        $correo = $_SESSION['_sf2_attributes']['_security.last_username'];

        $repository = $doctrine->getRepository(Jugador::class);
        $user = $repository->findOneBy(array('email' => $correo));
        $user->setNombre($_POST["nombre"]);
        $user->setApellidos($_POST["apellidos"]);

        if(isset($_FILES['file']))
        {
            $nombre = time().rand(1,99999).$_FILES['file']['name'];
            move_uploaded_file($_FILES["file"]["tmp_name"], "bd/".$nombre);
            $imagenAntigua = $user->getImagen();
            $user->setImagen($nombre);
        }

        $errores = $validator->validate($user);
        if(count($errores)==0)
        {
            if(isset($_FILES['file'])) if($imagenAntigua!=null) unlink("bd/".$imagenAntigua);
            $entityManager = $doctrine->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
        }
        $array = [];
        foreach ($errores as &$valor) {
            $array[] = $valor->getMessage();
        }
        return new Response(json_encode($array));
    }
}