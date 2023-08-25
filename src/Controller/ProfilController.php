<?php

namespace App\Controller;

use App\Core\Icons;
use App\Core\Notification;
use App\Core\NotificationColor;
use App\Form\AccountModificationFormType;
use App\Form\AccountPasswordModificationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class ProfilController extends AbstractController
{
    #[Route('/profil', name: 'route_profil')]
    public function profil(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $userPasswordHasher): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $user = $this->getUser();

        // --------Section form user account details--------
        $formDetails = $this->createForm(AccountModificationFormType::class, $user);
        $formDetails->handleRequest($request);

        if ($formDetails->isSubmitted() && $formDetails->isValid()) {

            $entityManager->flush();
            
            $this->addFlash("user", new Notification("Success", "Your user informations were successfully updated", NotificationColor::SUCCESS, Icons::THUMB_UP));
            return $this->redirectToRoute('route_profil');
        }

        // --------Section form password modification--------
        $formPassword = $this->createForm(AccountPasswordModificationFormType::class);
        $formPassword->handleRequest($request);
        if ($formPassword->isSubmitted() && $formPassword->isValid()) {

            // *IMPORTANT: Current password != new password
            if ($formPassword->get('currentPassword')->getData() != $formPassword->get('newPassword')->getData()) {

                // *IMPORTANT: Current password is valid
                if ($userPasswordHasher->isPasswordValid($user, $formPassword->get('currentPassword')->getData())) {
                    // encode the plain password
                    $user->setPassword(
                        $userPasswordHasher->hashPassword(
                            $user,
                            $formPassword->get('newPassword')->getData()
                        )
                    );
                    $entityManager->flush();
                    $this->addFlash("user", new Notification("Success", "Your password have been successfully updated", NotificationColor::SUCCESS, Icons::THUMB_UP));

                    return $this->redirectToRoute('route_profil');
                } else {
                    $this->addFlash("user", new Notification("Error", "Error, your old password must match current password", NotificationColor::DANGER, Icons::WARNING));
                }
            } else {
                $this->addFlash("user", new Notification("Error", "Your new password must be different from your current password", NotificationColor::DANGER, Icons::WARNING));
            }
        }

        return $this->render('profil/profil.html.twig', [
            'accountModificationFormType' => $formDetails->createView(),
            'accountPasswordModificationFormType' => $formPassword,
            'email' => $user->getEmail()
        ]);
    }

    #[Route('/login', name: 'route_login')]
    public function login(Request $request, AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('route_profil');
        }
        $error = $authenticationUtils->getLastAuthenticationError();
        $notification = null;

        if ($error != null && $error->getMessageKey() === 'Invalid credentials.') {
            $message = "Wrong combination of identifier and password, try again.";
            $notification = new Notification('error', $message, NotificationColor::WARNING);
        }

        $lastUsername = $authenticationUtils->getLastUsername();
        return $this->render('profil/login.html.twig', [
            'last_username' => $lastUsername,
            'notification' => $notification
        ]);
    }

    #[Route('/logout', name: 'route_logout')]
    public function logout()
    {
        throw new \Exception("Dont forget to activate logout in security.yaml");
    }
}
