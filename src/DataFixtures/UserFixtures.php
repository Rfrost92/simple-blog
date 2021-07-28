<?php

namespace App\DataFixtures;

use App\Entity\BlogPost;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{

    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        $user = new User();
        $user->setEmail('admin@admin.com');
        $user->setRoles(['ROLE_ADMIN']);

        $user->setPassword($this->passwordEncoder->encodePassword(
            $user,
            '1234'
        ));
        $manager->persist($user);

        $user2 = new User();
        $user2->setEmail('user@user.com');
        $user2->setRoles(['ROLE_USER']);

        $user2->setPassword($this->passwordEncoder->encodePassword(
            $user2,
            'qwert'
        ));
        $manager->persist($user2);
        $manager->flush();

        $blog = new BlogPost();
        $blog->setAuthor($user);
        $blog->setTitle('Best Post Ever');
        $blog->setBody('Thiis is the best post you have ever read and its body lkmsd klsdnf lksnf adsbhjk dakjn ndakjs');
        $blog->setSlug('best-post-ever');
        $blog->setUpdated(new \DateTime());
        $manager->persist($blog);

        $blog2 = new BlogPost();
        $blog2->setAuthor($user2);
        $blog2->setTitle('Very Nice Post');
        $blog2->setBody('Thiis is a very nice post by user2 and its body lkmsd klsdnf lksnf adsbhjk dakjn ndakjs');
        $blog2->setSlug('very-nice-post');
        $blog2->setUpdated(new \DateTime());
        $manager->persist($blog2);

        $manager->flush();

    }
}
