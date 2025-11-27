<?php

namespace App\Controller;

use App\Entity\Usuario;
use App\form\RegistroUsuarioFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

final class UsuarioController extends AbstractController
{

    #[Route('/registro', name: 'registro')]
    public function regitrarUsuario(Request $request, UserPasswordHasherInterface $user_password_hasher, EntityManagerInterface $entity_manager): Response
    {
        $user = new Usuario();
        $form = $this->createForm(RegistroUsuarioFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $senha = $form->get('senha')->getData();

            $user->setPassword($user_password_hasher->hashPassword($user, $senha));

            $user->setRoles(['ROLES_USER']);

            $entity_manager->persist($user);
            $entity_manager->flush();

            return $this->redirectToRoute('login');
        }

        return $this->render('registroUsuario/registro_usuario.html.twig', [
            'titulo' => 'Novo Usuário',
            'registrationForm' => $form,
        ]);
    }
}
