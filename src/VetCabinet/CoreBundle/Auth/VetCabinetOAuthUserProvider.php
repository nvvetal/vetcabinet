<?php
namespace VetCabinet\CoreBundle\Auth;

use Symfony\Component\HttpFoundation\Response;
use HWI\Bundle\OAuthBundle\Security\Core\User\OAuthUserProvider;
use HWI\Bundle\OAuthBundle\OAuth\Response\UserResponseInterface;
use Symfony\Component\Security\Core\User\UserInterface;


class VetCabinetOAuthUserProvider extends OAuthUserProvider
{
    protected $entityManager;
    protected $container;

    public function __construct($entityManager, $container)
    {
        $this->entityManager    = $entityManager;
        $this->container        = $container;
    }

    public function loadUserByOAuthUserResponse(UserResponseInterface $response)
    {
        $attr = $response->getResponse();
        switch($response->getResourceOwner()->getName()) {
            case 'facebook':
                //var_dump($attr);
                //exit;
                /*
                $facebook = $this->container->get("radiooo.helper.facebook");
                $facebook->setAccessToken($response->getAccessToken());
                if(!$user = $this->entityManager->getRepository('RadioooCoreBundle:User')->findOneByFacebookId($attr['id'])) {
                    if(isset($attr['verified']) && $attr['verified'] && isset($attr['email']) && ($user = $this->entityManager->getRepository('RadioooCoreBundle:User')->findOneByEmail($attr['email']))) {
                        $user->setFacebookId($attr['id']);
                    }else{
                        $user = new User();
                        $user->setFacebookId($attr['id']);
                        $user->setPassword('');
                        $this->setUserFacebookUsername($user, $attr['username']);
                        $this->setUserFacebookCountry($user);
                        $user->setCreatedTime(time());
                        $user->setActive("y");
                        $user->setEmail($attr['email']);
                        if(isset($attr['last_name']))$user->setLastName($attr['last_name']);
                        if(isset($attr['first_name']))$user->setFirstName($attr['first_name']);
                        $this->setUserFacebookBirthday($attr['birthday'], $user);
                        $this->entityManager->persist($user);
                        $this->entityManager->flush();
                        $this->setUserFacebookAvatar($user);
                    }
                }
                */
                break;
        }
        $this->entityManager->flush();
        if (null === $user) {
            throw new AccountNotLinkedException('User unknown exception');
        }

        return $user;
    }

    public function refreshUser(UserInterface $user)
    {
        return parent::refreshUser($user);
    }

    private function setUserFacebookUsername(&$userEntity, $username)
    {
        /*
        $uName  = $username;
        while(true){
            $otherUser = $this->entityManager->getRepository('RadioooCoreBundle:User')->findOneByUsername($username);
            if(is_null($otherUser)){
                $userEntity->setUserName($username);
                break;
            }else{
                $username = $uName.mt_rand(1,100000);
            }
        }
        */
        return true;
    }

    //Facebook countries http://en.wikipedia.org/wiki/ISO_3166-1
    private function setUserFacebookCountry(&$userEntity)
    {

        $facebook = $this->container->get("radiooo.helper.facebook");
        try {
            $fbCountryName  = $facebook->getUserCountry();
            $country        = $facebook->getCountryByFacebookCountryName($fbCountryName);
            if(is_null($country)) throw new \Exception('Cannot find country by FB country name '.$fbCountryName);
        }catch(\Exception $e)
        {
            $this->container->get('radiooo.logger')->write('[type facebook][error '.$e->getMessage().']', 'error_country_detect');
            $country = $this->entityManager->getRepository('RadioooCoreBundle:Country')->findOneByName('France');
        }
        $userEntity->setCountry($country);

        return true;
    }

    private function setUserFacebookAvatar(&$userEntity)
    {
        $facebook   = $this->container->get("radiooo.helper.facebook");
        $data       = $facebook->getUserAvatarPicUrlData();
        if(is_null($data)) return false;
        $manager        = $this->container->get('radiooo.storagebundle.manager.media');
        $provider       = $this->container->get('radiooo.storagebundle.provider.user_file_image');
        $context        = 'userprofile';
        $manager->setContext($context);
        $manager->setProvider($provider);
        $manager->saveByData($userEntity, $data, false);
        return true;
    }



    /**
     * @param $facebookBirthday string DD/MM/YYYY
     * @param $userEntity User
     * @return bool
     * @comment
     */
    private function setUserFacebookBirthday($facebookBirthday, User $userEntity)
    {
        $date = \DateTime::createFromFormat('d/m/Y', $facebookBirthday);
        $userEntity->setBirthday($date);
        return true;
    }

}