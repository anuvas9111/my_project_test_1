<?php



namespace App\DataFixtures;
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
        $user->setEmail("admin@mail.ru");
        $user->setRoles(['ROLE_ADMIN']);
        $user->setPassword($this->passwordEncoder->encodePassword($user, '123123123'));
        $manager->persist($user);
        $manager->flush();

        $user = new User();
        $user->setEmail("user@mail.ru");
        $user->setRoles(['ROLE_USER']);
        $user->setPassword($this->passwordEncoder->encodePassword($user, '321321321'));
        $manager->persist($user);
        $manager->flush();


    }
}