<?php

namespace App\Controller;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class GoogleController extends AbstractController
{
    /**
     * 
     * @Route("/connect/google", name="connect_google_start")
     */
    public function connectAction(ClientRegistry $clientRegistry)
    {
        
        // will redirect to Facebook!
        return $clientRegistry
            ->getClient('google_main') // key used in config/packages/knpu_oauth2_client.yaml
            ->redirect([
	    	'profile', 'email' // the scopes you want to access
            ]);
    }

    /**
     * After going to Facebook, you're redirected back here
     * because this is the "redirect_route" you configured
     * in config/packages/knpu_oauth2_client.yaml
     *
     * @Route("/connect/google/check", name="connect_google_check")
     */
    public function connectCheckAction(Request $request, ClientRegistry $clientRegistry)
    {
        // ** if you want to *authenticate* the user, then
        // leave this method blank and create a Guard authenticator
        // (read below)

        /** @var \KnpU\OAuth2ClientBundle\Client\Provider\FacebookClient $client */
		
        $client = $clientRegistry->getClient('google_main');

        try {
            // the exact class depends on which provider you're using
            /** @var \League\OAuth2\Client\Provider\FacebookUser $user */
            $user = $client->fetchUser();
dump($user);exit;
			        $user = $this->em->getRepository(User::class)
            ->findOneBy(['email' => $email]);

			
        $user->setSub($googleUser->getSub());
		$user->setFirstName($googleUser->getGivenName());
        $this->em->persist($user);
        $this->em->flush();
        } catch (IdentityProviderException $e) {
            // something went wrong!
            // probably you should return the reason to the user
            var_dump($e->getMessage()); die;
        }
		
    }
}